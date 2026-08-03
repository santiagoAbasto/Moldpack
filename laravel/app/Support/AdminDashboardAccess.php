<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminDashboardAccess
{
    public const SALES = 'sales';
    public const WEB_TRAFFIC = 'web_traffic';

    private const STORAGE_PATH = 'admin-dashboard-access.json';

    public static function options(): array
    {
        return [
            self::WEB_TRAFFIC => 'Grafico de trafico web',
            self::SALES => 'Metricas de ventas y pedidos',
        ];
    }

    public static function dashboardTypeFor(?User $user): string
    {
        if (!$user) {
            return self::WEB_TRAFFIC;
        }

        if (self::isPrimarySalesUser($user)) {
            return self::SALES;
        }

        $config = self::readConfig();
        $type = $config['users'][(string) $user->id] ?? self::WEB_TRAFFIC;

        return array_key_exists($type, self::options()) ? $type : self::WEB_TRAFFIC;
    }

    public static function setDashboardTypeFor(User $user, string $type): void
    {
        $type = array_key_exists($type, self::options()) ? $type : self::WEB_TRAFFIC;
        $config = self::readConfig();
        $config['users'][(string) $user->id] = self::isPrimarySalesUser($user) ? self::SALES : $type;
        $config['updated_at'] = now()->toIso8601String();

        Storage::put(self::STORAGE_PATH, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public static function canManage(?User $user): bool
    {
        return $user ? self::isPrimarySalesUser($user) : false;
    }

    public static function isPrimarySalesUser(User $user): bool
    {
        return strtolower((string) $user->username) === 'pmathey'
            || strtolower((string) $user->email) === 'pmathey@moldpack.com.ar';
    }

    private static function readConfig(): array
    {
        if (!Storage::exists(self::STORAGE_PATH)) {
            return ['users' => []];
        }

        $data = json_decode((string) Storage::get(self::STORAGE_PATH), true);

        if (!is_array($data)) {
            return ['users' => []];
        }

        if (!isset($data['users']) || !is_array($data['users'])) {
            $data['users'] = [];
        }

        return $data;
    }
}
