<?php

namespace App\Support;

use Illuminate\Support\Str;

class AdminModules
{
    public static function roleOptions(): array
    {
        return [
            1 => 'Administrador/comercial',
            2 => 'Logistica',
            3 => 'Contabilidad',
        ];
    }

    public static function roleLabel($role): string
    {
        return self::roleOptions()[(int) $role] ?? 'Sin rol';
    }

    public static function moduleCatalog(): array
    {
        return [
            'home' => [
                'label' => 'Home',
                'description' => 'Slider y contenido principal.',
                'icon' => 'fas fa-home',
            ],
            'empresa' => [
                'label' => 'Empresa',
                'description' => 'Contenido institucional.',
                'icon' => 'fas fa-city',
            ],
            'productos' => [
                'label' => 'Productos',
                'description' => 'Categorias, productos, colores y catalogo.',
                'icon' => 'fas fa-boxes',
            ],
            'donde_comprar' => [
                'label' => 'Donde comprar',
                'description' => 'Locales y distribuidores.',
                'icon' => 'fas fa-map-marker-alt',
            ],
            'novedades' => [
                'label' => 'Novedades',
                'description' => 'Categorias y publicaciones.',
                'icon' => 'fas fa-newspaper',
            ],
            'zona_privada' => [
                'label' => 'Zona privada',
                'description' => 'Carrito y clientes.',
                'icon' => 'fas fa-user-lock',
            ],
            'logistica' => [
                'label' => 'Logistica',
                'description' => 'Pedidos, armado y stock.',
                'icon' => 'fas fa-truck',
            ],
            'contabilidad' => [
                'label' => 'Contabilidad',
                'description' => 'Pedidos a facturar, facturado, facturas y Excel.',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
            'estadisticas' => [
                'label' => 'Estadisticas',
                'description' => 'Ventas, clientes, graficos y exportaciones.',
                'icon' => 'fas fa-chart-line',
            ],
            'contacto' => [
                'label' => 'Contacto',
                'description' => 'Datos de contacto, redes y logos.',
                'icon' => 'fas fa-address-book',
            ],
            'usuarios' => [
                'label' => 'Usuarios',
                'description' => 'Alta, edicion y permisos internos.',
                'icon' => 'fas fa-users-cog',
            ],
            'metadatos' => [
                'label' => 'Metadatos',
                'description' => 'SEO por seccion.',
                'icon' => 'fas fa-tags',
            ],
            'newsletter' => [
                'label' => 'Newsletter',
                'description' => 'Suscriptores.',
                'icon' => 'fas fa-envelope-open-text',
            ],
        ];
    }

    public static function roleModules(): array
    {
        return [
            1 => array_keys(self::moduleCatalog()),
            2 => ['logistica'],
            3 => ['contabilidad', 'estadisticas'],
        ];
    }

    public static function modulesForRole($role): array
    {
        $catalog = self::moduleCatalog();
        $keys = self::roleModules()[(int) $role] ?? [];

        return array_intersect_key($catalog, array_flip($keys));
    }

    public static function modulesForUser($user): array
    {
        return $user ? self::modulesForRole($user->role) : [];
    }

    public static function userCanModule($user, string $module): bool
    {
        return array_key_exists($module, self::modulesForUser($user));
    }

    public static function routeAllowed($user, string $path, ?string $routeName): bool
    {
        if (!$user) {
            return false;
        }

        $role = (int) $user->role;

        if ($role === 1) {
            return true;
        }

        if ($path === 'adm' || in_array($routeName, ['home', 'adm.dashboard.data'], true)) {
            return true;
        }

        if ($role === 2) {
            return in_array($routeName, self::logisticaRoutes(), true);
        }

        if ($role === 3) {
            return Str::startsWith($path, 'adm/contabilidad')
                || in_array($routeName, self::contabilidadRoutes(), true);
        }

        return false;
    }

    private static function logisticaRoutes(): array
    {
        return [
            'pedido',
            'adm.updateAddProduct.pedido',
            'pedido_bulto',
            'pedido_delete',
            'pedido_put',
            'pedido_put2',
            'pedido_putAprobado',
            'pedido_post',
            'adm.update.pedido',
            'adm.pedido.eliminar',
            'export.stock',
            'export.stockexcel',
        ];
    }

    private static function contabilidadRoutes(): array
    {
        return [
            'adm.facturado',
            'adm.facturas',
            'adm.contabilidad.pedidos',
            'adm.contabilidad.pedidoAll',
            'pedidoAll',
            'pedidoexcel',
            'estat.calcular',
            'estat.ventas',
            'export.productosVendidos',
            'export.clientesVentas',
            'export.stock',
            'export.stockexcel',
            'estat.grafventas',
            'estat.clientes',
        ];
    }
}
