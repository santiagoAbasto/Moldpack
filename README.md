# Moldpack

Sistema web de Moldpack para sitio publico, zona de clientes, administracion interna, pedidos, facturacion, logistica, contabilidad y catalogo de productos.

> Repositorio publico del codigo fuente. No incluye `.env`, certificados AFIP, tokens, archivos generados, storage, base de datos, vendor ni node_modules.

## Arquitectura

- **Backend:** Laravel/PHP.
- **Frontend publico:** Blade, CSS/JS tradicional, catalogo, buscador, productos, zona de cliente y carrito.
- **Panel administrativo:** Blade con assets compilados para dashboard administrativo.
- **Base de datos:** MySQL/MariaDB.
- **Facturacion:** Integracion AFIP mediante SDK local ubicado en `laravel/app/Http/Controllers/afipsdk`.
- **PDFs:** Generacion con DomPDF para comprobantes y documentos internos.
- **Assets:** Compilacion con Laravel Mix/Webpack.

## Estructura principal

```text
laravel/
  app/
    Http/Controllers/        Controladores publicos, admin, clientes, AFIP y pedidos
    Http/Middleware/         Seguridad de modulos, sesiones y cache
    Models/                  Modelos Eloquent
    Support/                 Helpers de permisos y dashboard admin
  config/                    Configuracion Laravel
  database/                  Migraciones y seeders historicos
  public/                    Entrada publica y assets compilados
  resources/
    views/                   Vistas Blade publicas, admin, zona privada y PDFs
    js/                      Entrypoints JS
    sass/                    Estilos fuente
  routes/
    web.php                  Rutas publicas, admin y zona de clientes
```

## Correcciones y mejoras recientes

- Correccion de rutas y consultas en administracion para reducir inconsistencias entre Logistica, Contabilidad, Facturacion y Zona Privada.
- Buscadores administrativos en modulos clave: pedidos/logistica, contabilidad, facturado, clientes y productos.
- Mejoras de seguridad en rutas administrativas con middleware por modulo.
- Correccion de busqueda publica para evitar errores `419 Page Expired`.
- Correccion de cierre de sesion de clientes y manejo de sesiones expiradas.
- Refuerzo de cache/no-store para evitar que Chrome muestre paginas privadas luego de cerrar sesion.
- Correccion de `GET /loginCliente` para evitar `405 Method Not Allowed` al refrescar o volver desde historial.
- Dashboard administrativo con metricas operativas y vistas diferenciadas por usuario/permisos.
- Mejoras visuales del login, sidebar, favicon y panel administrativo.
- Ajustes de Facturacion para guardar PDFs en la carpeta publica correcta.
- Normalizacion de ruta AFIP para Linux cuando el `.env` trae rutas con barras de Windows.
- AFIP usa produccion por defecto si `AFIP_PRODUCTION` no esta definido y puede leer el CUIT desde el certificado cuando `AFIP_CUIT` falta.

## Configuracion local

1. Entrar a la app:

```bash
cd laravel
```

2. Instalar dependencias PHP:

```bash
composer install
```

3. Instalar dependencias frontend:

```bash
npm install
```

4. Crear `.env` local a partir de `.env.example` y configurar base de datos:

```bash
cp .env.example .env
php artisan key:generate
```

5. Compilar assets:

```bash
npm run dev
```

6. Levantar servidor local:

```bash
php artisan serve --port=8001
```

## Variables importantes

Estas variables se configuran en `.env` y no deben subirse al repositorio:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.moldpack.com.ar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

CLIENT_PASSWORD_VIEW_KEY_HASH=

AFIP_PRODUCTION=true
AFIP_CUIT=30714394874
AFIP_SDK_FOLDER=/var/www/vhosts/moldpack.com.ar/moldpack_laravel/app/Http/Controllers/afipsdk/src/Afip_res/
AFIP_PASSPHRASE=
```

## Archivos que no van al repositorio

- `.env` y variantes.
- Certificados, keys y tokens AFIP.
- `storage/`, PDFs generados, logs y cache.
- `vendor/` y `node_modules/`.
- Dumps SQL, backups, datos MySQL locales y archivos comprimidos.
- Informes internos de auditoria o despliegue.

## Produccion

El despliegue de produccion debe realizarse de forma controlada, sin pisar `.env`, `storage`, `vendor`, `node_modules`, `.git`, base de datos ni certificados AFIP.

Los scripts locales de deploy/importacion no forman parte del repositorio publico porque contienen rutas operativas del entorno.

## Notas de seguridad

- No publicar credenciales reales.
- No regenerar `APP_KEY` en produccion salvo emergencia controlada.
- No reemplazar `storage` ni `.env` de produccion desde local.
- AFIP requiere certificados y autorizaciones correctas en el entorno correspondiente.
