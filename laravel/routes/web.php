<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\test; // Asegúrate de que esta clase exista
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth', 'role:1'])->group(function () {
  Route::post('install-storage', function () {
    abort_unless(app()->environment('local'), 404);

    \Artisan::call('clear-compiled');
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('optimize:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');

    return "listo";
  })->name('admin.install-storage');

  Route::post('testmail', function () {
    abort_unless(app()->environment('local'), 404);

    Mail::to(config('mail.from.address'))->send(new test());

    return 'Correo enviado';
  })->name('admin.testmail');
});

Route::group(['prefix' => '/', 'middleware' => 'track.public.traffic'], function () {
	Route::get('pedidoexcel', 'PedidosController@pedidoexcel')->middleware(['auth', 'admin.module'])->name('pedidoexcel');
	Route::get('contruccion',  'PageController@construccion');
  Route::get('',  'PageController@index')->name('page.inicio');
  Route::get('afip/{cuit}',  'FacturacionController@ValidateCUITCUIL')->middleware(['auth', 'admin.module'])->name('page.afip.validate');
Route::get('/empresa',  'PageController@empresa')->name('page.empresa');
Route::get('/calidad',  'PageController@calidad')->name('page.calidad');
Route::get('/productos',  'PageController@productosCategorias')->name('page.productosCategorias');
Route::get('/categorias/{id}',  'PageController@categorias')->name('page.categorias');
Route::get('/productos/{id}',  'PageController@productos')->name('page.productos');
Route::get('/producto/{id}',  'PageController@producto')->name('page.producto');
Route::get('/videos',  'PageController@videos')->name('page.videos');
Route::get('/novedades',  'PageController@novedades')->name('page.novedades');
Route::get('/novedades/{id}',  'PageController@novedad')->name('page.novedad');
Route::get('/donde_comprar',  'PageController@dondeComprar')->name('page.dondeComprar');
Route::post('/donde_comprar',  'PageController@dondeComprarPost')->name('page.search.mapa');
Route::get('/catalogo',  'PageController@catalogo')->name('page.catalogo');
Route::get('/catalogoview',  'PageController@catalogoView')->name('page.catalogo.view');
Route::get('/contacto',  'PageController@contactos')->name('page.contacto');
Route::post('/contacto', 'PageController@contactosEnviar')->name('page.contacto.post');

Route::match(['get', 'post'], '/buscar', 'PageController@buscar')->name('buscar');

Route::get('/registro', 'PageController@registro')->name('page.registro');
Route::post('/registro', 'PageController@registropost')->name('page.nuevoclienteform');

Route::post('/newsletter', 'PageController@subscribirse')->name('subscribirse');
Route::post('/guardars', 'PageController@registropost')->name('page.nuevocliente');

///RESET PASSWORD
Route::get('/forgot-password', 'PageController@password')->name('password');
Route::post('/forgot-password', 'PageController@passwordpost')->name('passwordpost');

Route::get('loginCliente', function () {
    if (\Illuminate\Support\Facades\Auth::guard('cliente')->check()) {
        return redirect()->route('page.pedido');
    }

    return redirect()->route('page.inicio')->withErrors([
        'sesion' => 'La sesion expiro. Inicie sesion nuevamente.',
    ]);
});
Route::post('loginCliente','LoginClienteController@login')->name('login.clientes');
    Route::post('registro_cliente_post','LoginClienteController@registro_cliente_post')->name('registro_cliente_post');
    Route::get('registro_cliente','LoginClienteController@registro_cliente')->name('registro_cliente');
});

Route::match(['get', 'post'], 'salir','LoginClienteController@salir')->name('salir');

Route::middleware(['auth.cliente', 'admin.no-store'])->group(function(){  
  Route::get('carrito','ZonaPrivadaController@carrito')->name('carrito');
  Route::get('pedido','ZonaPrivadaController@pedido')->name('page.pedido');
  Route::get('/pedido/categorias/{id}',  'ZonaPrivadaController@categoriasPedido')->name('page.categoriasPedido');
  Route::get('/pedido/producto/{id}',  'ZonaPrivadaController@productoPedido')->name('page.productoPedido');

  Route::post('carrito','ZonaPrivadaController@carrito_post')->name('carrito_post');
  Route::get('carrito/abandonado','ZonaPrivadaController@obtenerCarritoAbandonado')->name('carrito.abandonado.obtener');
  Route::post('carrito/guardar-abandonado','ZonaPrivadaController@guardarCarritoAbandonado')->name('carrito.abandonado.guardar');
  Route::get('lista_de_precio','ZonaPrivadaController@lista')->name('descargas_zp');
  Route::post('carrito_enviar','ZonaPrivadaController@carrito_enviar')->name('carrito_enviar');

  Route::get('historico_de_pedidos/{id}','ZonaPrivadaController@historico')->name('historico');
  Route::post('/enviarpedido', 'ZonaPrivadaController@carrito_post')->name('enviarpedido');

  Route::get('facturas/','ZonaPrivadaController@facturas')->name('zp.factura');

  Route::post('/comprar', 'ZonaPrivadaController@comprar')->name('page.comprar');  
  Route::match(['get', 'post'], '/pedido/busqueda', 'ZonaPrivadaController@buscar')->name('page.buscarPedido');
  Route::post('recomprar','ZonaPrivadaController@recomprar')->name('recomprar'); 
});

Auth::routes();

Route::redirect('/home', '/adm');

Route::get('/adm', 'adm\AdmController@index')->middleware(['auth', 'admin.module', 'admin.no-store'])->name('home');
Route::get('/adm/dashboard-data', 'adm\AdmController@data')->middleware(['auth', 'admin.module', 'admin.no-store'])->name('adm.dashboard.data');

Route::group(['middleware' => ['auth', 'admin.module', 'admin.no-store']], function() {    
    Route::get('/adm/sliders/{seccion}', 'adm\SliderController@index')->name('slider');
    Route::get('/adm/sliders/create/{seccion}', 'adm\SliderController@create')->name('nuevoslider');
    Route::post('/adm/sliders/create/{seccion}','adm\SliderController@store')->name('crearslider');
    Route::get('/adm/sliders/edit/{seccion}/{id}','adm\SliderController@edit')->name('editslider');
    Route::put('/adm/sliders/update/{seccion}/{id}', 'adm\SliderController@update')->name('updateslider');
    Route::get('/adm/sliders/eliminar/{id}', 'adm\SliderController@destroy')->name('eliminarslider');

    //LOCALIZACION
    Route::get('/adm/location/{seccion}', 'adm\ServiceController@index')->name('service');
    Route::get('/adm/location/create/{seccion}', 'adm\ServiceController@create')->name('nuevoservice');
    Route::post('/adm/location/create/{seccion}','adm\ServiceController@store')->name('crearservice');
    Route::get('/adm/location/edit/{seccion}/{id}','adm\ServiceController@edit')->name('editservice');
    Route::put('/adm/location/update/{seccion}/{id}', 'adm\ServiceController@update')->name('updateservice');
    Route::get('/adm/location/eliminar/{id}', 'adm\ServiceController@destroy')->name('eliminarservice');
    //Empresa
    Route::get('/adm/servicios/edit/{id}','adm\EmpresaController@edit')->name('editarempresa');
    Route::put('/adm/servicios/update/{id}', 'adm\EmpresaController@update')->name('updateempresa'); 
      // novedadCategoria
      Route::get('adm/novedadCategoria', 'adm\NovedadCategoriaController@index')->name('novedadCategoria');
      Route::get('adm/novedadCategoria/create', 'adm\NovedadCategoriaController@create')->name('nuevonovedadCategoria');
      Route::post('adm/novedadCategoria/create', 'adm\NovedadCategoriaController@store')->name('crearnovedadCategoria');
      Route::get('adm/novedadCategoria/edit/{id}', 'adm\NovedadCategoriaController@edit')->name('editarnovedadCategoria');
      Route::put('adm/novedadCategoria/update/{id}', 'adm\NovedadCategoriaController@update')->name('updatenovedadCategoria');
      Route::get('adm/novedadCategoria/eliminar/{id}', 'adm\NovedadCategoriaController@destroy')->name('eliminarnovedadCategoria');
  
      // novedad
      Route::get('adm/novedad', 'adm\NovedadController@index')->name('novedad');
      Route::get('adm/novedad/create', 'adm\NovedadController@create')->name('nuevonovedad');
      Route::post('adm/novedad/create', 'adm\NovedadController@store')->name('crearnovedad');
      Route::get('adm/novedad/edit/{id}', 'adm\NovedadController@edit')->name('editarnovedad');
      Route::put('adm/novedad/update/{id}', 'adm\NovedadController@update')->name('updatenovedad');
      Route::get('adm/novedad/eliminar/{id}', 'adm\NovedadController@destroy')->name('eliminarnovedad');
      Route::put('/adm/deescargas/imgnovedad/', 'adm\NovedadController@imgnovedad')->name('imgnovedad');
     // video
    Route::get('adm/video', 'adm\VideoController@index')->name('video');
    Route::get('adm/video/create', 'adm\VideoController@create')->name('nuevovideo');
    Route::post('adm/video/create', 'adm\VideoController@store')->name('crearvideo');
    Route::get('adm/video/edit/{id}', 'adm\VideoController@edit')->name('editarvideo');
    Route::put('adm/video/update/{id}', 'adm\VideoController@update')->name('updatevideo');
    Route::get('adm/video/eliminar/{id}', 'adm\VideoController@destroy')->name('eliminarvideo');
     //Calidad
    Route::get('/adm/calidades/edit/{id}','adm\CalidadController@edit')->name('editarcalidad');
    Route::put('/adm/calidades/update/{id}', 'adm\CalidadController@update')->name('updatecalidad');
    //Descargas
    Route::get('/adm/deescargas/', 'adm\DescargasController@index')->name('deescarga');
    Route::get('/adm/deescargas/create/', 'adm\DescargasController@create')->name('nuevodescarga');
    Route::put('/adm/deescargas/imgdescarga/', 'adm\DescargasController@imgdescarga')->name('imgdescarga');
    Route::post('/adm/deescargas/create/','adm\DescargasController@store')->name('creardescarga');
    Route::get('/adm/deescargas/edit/{id}','adm\DescargasController@edit')->name('editardescarga');
    Route::put('/adm/deescargas/update/{id}', 'adm\DescargasController@update')->name('updatedescarga');
    Route::get('/adm/deescargas/eliminar/{id}', 'adm\DescargasController@destroy')->name('eliminardescarga');

    //Inicio
    Route::get('/adm/inicio/edit/{id}','adm\InicioController@edit')->name('editarinicio');
    Route::put('/adm/inicio/update/{id}', 'adm\InicioController@update')->name('updateinicio');
    Route::get('/borrarimginicio/{id}/{img}', 'adm\InicioController@borrarproducto')->name('borrarimagenInicio');

    //Contacto
    Route::get('adm/contacto', 'adm\ContactosController@index')->name('contacto');
    Route::get('/adm/contacto/edit/{id}','adm\ContactosController@edit')->name('editarcontacto');
    Route::put('/adm/contacto/update/{id}', 'adm\ContactosController@update')->name('updatecontacto');
    //Logos
    Route::get('adm/logos', 'adm\LogosController@index')->name('logos');
    Route::get('/adm/logos/edit/{id}','adm\LogosController@edit')->name('editarlogos');
    Route::put('/adm/logos/update/{id}', 'adm\LogosController@update')->name('updatelogos');

    //Redes
    Route::get('adm/redes', 'adm\RedesController@index')->name('redes');
    Route::get('/adm/redes/edit/{id}','adm\RedesController@edit')->name('editarredes');
    Route::put('/adm/redes/update/{id}', 'adm\RedesController@update')->name('updateredes');
    //Productos //Categorias //Subcategorias//colores
    Route::get('/adm/Categorias/', 'adm\CategoriasController@index')->name('Categorias');
    Route::get('/adm/Categorias/create/', 'adm\CategoriasController@create')->name('nuevoCategoria');
    Route::post('/adm/Categorias/create/','adm\CategoriasController@store')->name('crearCategoria');
    Route::get('/adm/Categorias/edit/{id}','adm\CategoriasController@edit')->name('editarCategoria');
    Route::put('/adm/Categorias/update/{id}', 'adm\CategoriasController@update')->name('updateCategoria');
    Route::get('/adm/Categorias/eliminar/{id}', 'adm\CategoriasController@destroy')->name('eliminarCategoria');
    Route::get('/borrarCategorias/{id}/{img}', 'adm\CategoriasController@borrarCategoria')->name('borrarimagenCategoria');
    Route::put('/adm/deescargas/imgcategorias/', 'adm\CategoriasController@imgcategorias')->name('imgcategorias');

    Route::get('/adm/familiaProductos/', 'adm\FamiliaProductosController@index')->name('familiaProductos');
    Route::get('/adm/familiaProductos/create/', 'adm\FamiliaProductosController@create')->name('nuevofamiliaProducto');
    Route::post('/adm/familiaProductos/create/','adm\FamiliaProductosController@store')->name('crearfamiliaProducto');
    Route::get('/adm/familiaProductos/edit/{id}','adm\FamiliaProductosController@edit')->name('editarfamiliaProducto');
    Route::put('/adm/familiaProductos/update/{id}', 'adm\FamiliaProductosController@update')->name('updatefamiliaProducto');
    Route::get('/adm/familiaProductos/eliminar/{id}', 'adm\FamiliaProductosController@destroy')->name('eliminarfamiliaProducto');
    Route::get('/borrarfamiliaProductos/{id}/{img}', 'adm\FamiliaProductosController@borrarfamiliaProducto')->name('borrarimagenfamiliaProducto');

    Route::get('/adm/productos/', 'adm\ProductosController@index')->name('Productos');
    Route::get('/adm/productos/create/', 'adm\ProductosController@create')->name('nuevoproducto');
    Route::post('/adm/productos/create/','adm\ProductosController@store')->name('crearproducto');
    Route::get('/adm/productos/edit/{id}','adm\ProductosController@edit')->name('editarproducto');
    Route::put('/adm/productos/update/{id}', 'adm\ProductosController@update')->name('updateproducto');
    Route::get('/adm/productos/eliminar/{id}', 'adm\ProductosController@destroy')->name('eliminarproducto');
    Route::get('/borrarproductos/{id}/{img}', 'adm\ProductosController@borrarproducto')->name('borrarimagen');
    
    Route::get('/precios', 'adm\ProductosController@precios')->name('precios');
    Route::post('/precios', 'adm\ProductosController@updateprecio')->name('updateprecio');

    Route::get('/adm/colors/', 'adm\ColorsController@index')->name('Colors');
    Route::get('/adm/colors/create/', 'adm\ColorsController@create')->name('nuevocolor');
    Route::post('/adm/colors/create/','adm\ColorsController@store')->name('crearcolor');
    Route::get('/adm/colors/edit/{id}','adm\ColorsController@edit')->name('editarcolor');
    Route::put('/adm/colors/update/{id}', 'adm\ColorsController@update')->name('updatecolor');
    Route::get('/adm/colors/eliminar/{id}', 'adm\ColorsController@destroy')->name('eliminarcolor');
    Route::get('/borrarcolors/{id}/{img}', 'adm\ColorsController@borrarcolor')->name('borrarimagenColor');

    ///CARGA EXCEL
    Route::post('/productos_import_excel', 'adm\ProductosController@productos_import_excel')->name('productos_import_excel');
    Route::post('/soporte_import_excel', 'adm\ServiceController@service_import_excel')->name('soporte_import_excel');
    Route::post('/comprar_import_excel', 'adm\ServiceController@comprar_import_excel')->name('comprar_import_excel');

    //user
    Route::get('/adm/usuarios/', 'adm\UserController@index')->name('usuarios');
    Route::get('/adm/usuarios/create/', 'adm\UserController@create')->name('nuevousuarios');
    Route::post('/adm/usuarios/create/','adm\UserController@store')->name('crearusuarios');
    Route::get('/adm/usuarios/edit/{id}','adm\UserController@edit')->name('editarusuarios');
    Route::put('/adm/usuarios/update/{id}', 'adm\UserController@update')->name('updateusuarios');
    Route::get('/adm/usuarios/eliminar/{id}', 'adm\UserController@destroy')->name('eliminarusuarios');
    
    //METADATOS
    Route::get('/adm/metadatos/', 'adm\MetadatoController@index')->name('metadatos');
    Route::get('/adm/metadatos/edit/{id}','adm\MetadatoController@edit')->name('editarmetadatos');
    Route::put('/adm/metadatos/update/{id}', 'adm\MetadatoController@update')->name('updatemetadatos');

    //Subcriptores
    Route::get('Subcriptores', 'adm\SubcriptoresController@verSubcriptores')->name('Subcriptores.view');
    Route::get('home/subscriptores/crearMail','adm\SubcriptoresController@create')->name('subcriptores.create');
    Route::post('home/subscriptores/enviarMail','adm\SubcriptoresController@store')->name('subcriptores.store');
    Route::get('Subcriptores/edit/{id}', 'adm\SubcriptoresController@edit')->name('subscriptores.editar');
    Route::put('Subcriptores/update/{id}', 'adm\SubcriptoresController@update')->name('updateSubcriptores');

  //ZONA PRIVADA
  Route::group(['prefix' => 'adm/zona_privada'], function () {
    Route::get('/carrito', 'CarritoController@carrito')->name('carrito_zp');
    Route::post('/carrito', 'CarritoController@carrito_post')->name('carrito_zp_post');
    Route::put('/carrito_put/{id}', 'CarritoController@carrito_put')->name('carrito.editar');
    Route::get('/carrito_delte/{id}', 'CarritoController@carrito_delete')->name('carrito_zp_delete');

    Route::get('/clientescontenido', 'ClientesController@cliente')->name('clientes.view');
    Route::post('/clienteBusqueda', 'ClientesController@clienteBusqueda')->name('clienteBusqueda.view');
    Route::get('/clientes_delete/{id}', 'ClientesController@clientes_delete')->name('eliminarcliente');        
    Route::get('/clientes_put/{id}', 'ClientesController@update')->name('editarcliente');
    Route::put('/clientes_put/{id}', 'ClientesController@clientes_put')->name('put.cliente');
    Route::post('/clientes_password_key_check', 'ClientesController@verificarClavePassword')->name('clientes.password_key_check');
    Route::post('/clientes_password_view/{id}', 'ClientesController@verPasswordCliente')->name('clientes.password_view');
    Route::get('/crear', 'ClientesController@create')->name('cliente.create');
    Route::post('/guardars', 'ClientesController@clientes_post')->name('nuevocliente');
    Route::get('crearMail','ClientesController@email')->name('clientes.create.email');
    Route::post('enviarMail','ClientesController@enviar')->name('clientes.email'); 
    
    Route::get('/clientes_export_excel', 'ClientesController@clientes_export_excel')->name('clientes_export_excel');
  });
  
  Route::get('/facturado', 'PedidosController@facturado')->name('adm.facturado');
  Route::get('/allfacturas', 'PedidosController@facturas')->name('adm.facturas');

  Route::group(['prefix' => 'adm/logistica'], function () {
    Route::get('/pedido', 'PedidosController@pedido')->name('pedido');
	  Route::post('/updateAddProduct', 'PedidosController@updateAddProduct')->name('adm.updateAddProduct.pedido');
Route::get('/pedidoAll', 'PedidosController@pedidoAll')->name('pedidoAll');
	  Route::post('/pedido_bulto', 'PedidosController@pedido_bulto')->name('pedido_bulto');
    Route::delete('/pedido_delete/{id}', 'PedidosController@pedido_delete')->name('pedido_delete');        
    Route::post('/pedido_put', 'PedidosController@pedido_put')->name('pedido_put');
    Route::post('/pedido_put2', 'PedidosController@pedido_put2')->name('pedido_put2');
	  Route::post('/pedido_putAprobado', 'PedidosController@pedido_putAprobado')->name('pedido_putAprobado');
    Route::post('/pedido', 'PedidosController@pedido_post')->name('pedido_post');
    Route::post('/update', 'PedidosController@update')->name('adm.update.pedido');
    Route::post('/eliminar', 'PedidosController@eliminar')->name('adm.pedido.eliminar');
  });

  Route::group(['prefix' => 'adm/contabilidad'], function () {
    Route::get('/pedido', 'ContabilidadController@pedido')->name('adm.facturacion');
    Route::get('/pedidos-a-facturar', 'ContabilidadController@pedido')->name('adm.contabilidad.pedidos');
    Route::get('/todos-los-pedidos', 'PedidosController@pedidoAll')->name('adm.contabilidad.pedidoAll');
    Route::post('/facturar', 'FacturacionController@index')->name('adm.facturacion.post');
    Route::post('/nota/{pedido}', 'FacturacionController@nota')->name('adm.facturacion.nota.post');
    Route::delete('/pedido_delete/{id}', 'ContabilidadController@pedido_delete')->name('adm.facturacion_delete');        
    Route::post('/pedido_put', 'ContabilidadController@pedido_put')->name('adm.facturacion_put');
    Route::post('/pedido', 'ContabilidadController@pedido_post')->name('adm.facturacion_post');
    Route::post('/update', 'ContabilidadController@update')->name('adm.facturacion.update');
    Route::post('/facturadoEstado', 'FacturacionController@facturadoEstado')->name('adm.facturado.estado');
  });
	Route::post('/estatcalcular', 'PageController@calcularSubtotales')->name('estat.calcular');
	Route::get('/estatventas', 'EstadisticaController@ventas')->name('estat.ventas');
	//Route::get('/estatcalcular', 'EstadisticaController@calcularSubtotales')->name('estat.calcular');
	Route::get('/export-productos-vendidos', 'EstadisticaController@exportarProductosVendidos')->name('export.productosVendidos');
	Route::get('/exportar-clientes', 'EstadisticaController@exportarClientes')->name('export.clientesVentas');
	Route::get('/estatstock', 'EstadisticaController@stock')->name('export.stock');
	Route::get('/export-stock', 'EstadisticaController@export')->name('export.stockexcel');
	Route::get('/estatgrafventas', 'EstadisticaController@grafventas')->name('estat.grafventas');
	Route::get('/estatclientes', 'EstadisticaController@clientes')->name('estat.clientes');
});
