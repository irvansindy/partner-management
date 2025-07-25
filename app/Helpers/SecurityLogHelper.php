<?php

namespace App\Helpers;

use Jenssegers\Agent\Agent;

class SecurityLogHelper
{
    public static function createLogData(string $type, array $extra = []): array
    {
        $request = request();
        $agent = new Agent();
        $geo = geoip($request->ip());

        return [
            'type'        => $type,
            'ip'          => $request->ip(),
            'country'     => $geo->country ?? null,
            'city'        => $geo->city ?? null,
            'state'       => $geo->state_name ?? null,
            'timezone'    => $geo->timezone ?? null,
            'lat'         => $geo->lat ?? null,
            'lon'         => $geo->lon ?? null,
            'user_agent'  => [
                'raw'       => $request->userAgent(),
                'browser'   => $agent->browser() . ' ' . $agent->version($agent->browser()),
                'os'        => $agent->platform() . ' ' . $agent->version($agent->platform()),
                'device'    => $agent->device() ?: 'Unknown',
                'is_mobile' => $agent->isMobile(),
                'is_tablet' => $agent->isTablet(),
                'is_robot'  => $agent->isRobot(),
            ],
            'url'         => $request->fullUrl(),
            'extra'       => $extra,
            'time'        => now()->toDateTimeString(),
        ];
    }
}
