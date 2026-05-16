<?php

namespace WizballEsy\LibreNmsDevicePhoto\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoListService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoOrderService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoPermissionService;
use WizballEsy\LibreNmsDevicePhoto\Services\PhotoSettingsService;

class ActionController extends Controller
{
    public function __construct(
        private readonly PhotoListService $photos,
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

        if ($action !== 'save_order') {
            return $this->redirect($deviceId, 'unknown_action');
        }

        return $this->saveOrder($request, $deviceId);
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