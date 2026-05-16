<?php

namespace WizballEsy\LibreNmsDevicePhoto\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoLinkService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoListService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoOrderService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPermissionService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoSettingsService;

class ActionController extends Controller
{
    public function __construct(
        private readonly PhotoListService $photos,
        private readonly PhotoLinkService $links,
        private readonly PhotoOrderService $order,
        private readonly PhotoPermissionService $permissions,
        private readonly PhotoSettingsService $settings,
    ) {
    }

    public function handle(Request $request)
    {
        $user = auth()->user();

        if (! $user || ! $user->can('global-read')) {
            abort(403, 'Forbidden');
        }

        $action = (string) $request->input('action', '');
        $deviceId = (int) $request->input('device_id', 0);

        return match ($action) {
            'save_order' => $this->saveOrder($request, $deviceId),
            'remove_link' => $this->removeLink($request, $deviceId),
            'remove_outgoing_link' => $this->removeOutgoingLink($request, $deviceId),
            default => $this->redirect($deviceId, 'unknown_action'),
        };
    }

    private function saveOrder(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'reorder_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $orderJson = (string) $request->input('order_json', '[]');
        $decoded = json_decode($orderJson, true);

        if (! is_array($decoded)) {
            return $this->redirect($deviceId, 'invalid_order');
        }

        $safeShortName = $this->photos->safeDevicePrefix($deviceId);
        $existing = $this->photos->listFilenamesForDevice($deviceId);
        $existingLookup = array_flip($existing);

        $newOrder = [];

        foreach ($decoded as $filename) {
            $filename = basename((string) $filename);

            if (isset($existingLookup[$filename])) {
                $newOrder[] = $filename;
            }
        }

        foreach ($existing as $filename) {
            if (! in_array($filename, $newOrder, true)) {
                $newOrder[] = $filename;
            }
        }

        $this->order->save($safeShortName, $newOrder);

        return $this->redirect($deviceId, 'order_updated');
    }

    private function removeLink(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $ownerDeviceId = (int) $request->input('owner_device_id', 0);

        $removeLinkPattern = '/^device-' . preg_quote((string) $ownerDeviceId, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if ($ownerDeviceId < 1 || ! preg_match($removeLinkPattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        $this->links->remove($deviceId, $ownerDeviceId, $filename);

        return $this->redirectAfterAction($request, $deviceId, 'link_removed');
    }

    private function removeOutgoingLink(Request $request, int $deviceId)
    {
        if ($deviceId < 1) {
            return $this->redirect($deviceId, 'device_not_found');
        }

        $settings = $this->settings->settings();

        if (! $this->permissions->userCanAction(auth()->user(), $settings, 'delete_roles')) {
            return $this->redirect($deviceId, 'permission_denied');
        }

        $filename = basename((string) $request->input('filename', ''));
        $targetDeviceId = (int) $request->input('target_device_id', 0);
        $safeShortName = $this->photos->safeDevicePrefix($deviceId);

        $pattern = '/^' . preg_quote($safeShortName, '/') . '-[0-9]{1,3}\.(jpg|jpeg|png|webp)$/i';

        if (! preg_match($pattern, $filename)) {
            return $this->redirect($deviceId, 'invalid_filename');
        }

        if ($targetDeviceId < 1 || ! Device::find($targetDeviceId)) {
            return $this->redirect($deviceId, 'invalid_target_device');
        }

        $this->links->remove($targetDeviceId, $deviceId, $filename);

        return $this->redirectAfterAction($request, $deviceId, 'link_removed');
    }

    private function redirectAfterAction(Request $request, int $deviceId, ?string $status = null)
    {
        $returnTo = (string) $request->input('return_to', '');

        if ($returnTo === 'overview') {
            $query = [];

            if ($status !== null) {
                $query['status'] = $status;
            }

            return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : ''));
        }

        return $this->redirect($deviceId, $status);
    }

    private function redirect(int $deviceId, ?string $status = null)
    {
        $query = [];

        if ($deviceId > 0) {
            $query['device_id'] = $deviceId;
        }

        if ($status !== null) {
            $query['status'] = $status;
        }

        return redirect(url('plugin/device-photo') . ($query ? '?' . http_build_query($query) : ''));
    }
}