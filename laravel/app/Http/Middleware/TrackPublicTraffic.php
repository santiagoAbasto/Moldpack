<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Throwable;

class TrackPublicTraffic
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $this->recordVisit($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request, $response): bool
    {
        if (!$request->isMethod('GET') || $request->ajax() || $request->expectsJson()) {
            return false;
        }

        if (method_exists($response, 'getStatusCode') && $response->getStatusCode() >= 400) {
            return false;
        }

        return !$request->is(
            'adm*',
            'afip*',
            'login',
            'logout',
            'register',
            'password/*',
            'forgot-password',
            'storage/*',
            'img/*',
            'css/*',
            'js/*',
            'vendor/*',
            'slick/*'
        );
    }

    private function recordVisit(Request $request): void
    {
        try {
            $path = storage_path('app/analytics/web-traffic.json');
            $dir = dirname($path);

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $handle = fopen($path, 'c+');

            if (!$handle) {
                return;
            }

            flock($handle, LOCK_EX);
            $contents = stream_get_contents($handle);
            $data = json_decode($contents ?: '{}', true);
            $data = is_array($data) ? $data : [];

            $dayKey = now()->toDateString();
            $section = $this->sectionFor($request);

            $data[$dayKey] = $data[$dayKey] ?? ['visitas' => 0, 'secciones' => []];
            $data[$dayKey]['visitas'] = (int) ($data[$dayKey]['visitas'] ?? 0) + 1;
            $data[$dayKey]['secciones'][$section] = (int) ($data[$dayKey]['secciones'][$section] ?? 0) + 1;

            ksort($data);
            $data = array_slice($data, -45, null, true);

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($data, JSON_PRETTY_PRINT));
            fflush($handle);
            flock($handle, LOCK_UN);
            fclose($handle);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function sectionFor(Request $request): string
    {
        $routeName = (string) optional($request->route())->getName();
        $path = trim($request->path(), '/');

        if ($routeName === 'buscar' || $path === 'buscar') {
            return 'buscar';
        }

        if (strpos($routeName, 'page.product') === 0 || strpos($routeName, 'page.categorias') === 0 || $path === 'catalogo') {
            return 'catalogo';
        }

        if (strpos($routeName, 'page.novedad') === 0) {
            return 'novedades';
        }

        if ($routeName === 'page.dondeComprar') {
            return 'donde_comprar';
        }

        if ($routeName === 'page.contacto') {
            return 'contacto';
        }

        if ($routeName === 'page.registro') {
            return 'registro';
        }

        if ($path === '' || $routeName === 'page.inicio') {
            return 'inicio';
        }

        return 'otras';
    }
}
