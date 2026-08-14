<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\FacturasRelacion;
use App\Models\CarritoContenido;
use Http;
use Illuminate\Support\Facades\Log;


include_once dirname(__FILE__) . '/afipsdk/src/Afip.php';



//include "app\afipsdk\src\Afip.php";
//use App\afipsdk\src\Afip;
use Barryvdh\DomPDF\Facade\Pdf;

use stdClass;

class FacturacionController extends Controller
{

  public $afip_ws;

  public function __construct()
  {
    $this->middleware('auth');
    ini_set('soap.wsdl_cache_enabled', 0);
    ini_set('soap.wsdl_cache_ttl', 0);
    $this->afip_ws = $this->afipOptions();
  }

  private function afipOptions()
  {
    $folder = $this->afipResourceFolder();

    return [
      'CUIT' => $this->afipCuit($folder),
      'res_folder' => $folder,
      'ta_folder' => $folder,
      'passphrase' => env('AFIP_PASSPHRASE', ''),
      'production' => $this->afipProduction()
    ];
  }

  private function afipProduction(): bool
  {
    $production = env('AFIP_PRODUCTION', null);

    if ($production === null || $production === '') {
      return true;
    }

    return filter_var($production, FILTER_VALIDATE_BOOLEAN);
  }

  private function afipCuit(string $folder): int
  {
    $cuit = intval(env('AFIP_CUIT', 0));

    if ($cuit > 0) {
      return $cuit;
    }

    $certPath = $folder . 'cert';

    if (!function_exists('openssl_x509_parse') || !is_readable($certPath)) {
      return 0;
    }

    $cert = @file_get_contents($certPath);
    $parsed = $cert ? @openssl_x509_parse($cert) : false;

    if (!is_array($parsed) || !isset($parsed['subject']) || !is_array($parsed['subject'])) {
      return 0;
    }

    $serialNumber = $parsed['subject']['serialNumber'] ?? $parsed['subject']['serialnumber'] ?? '';

    if (preg_match('/CUIT\s+(\d{11})/', $serialNumber, $matches)) {
      return intval($matches[1]);
    }

    return 0;
  }

  private function afipResourceFolder(): string
  {
    $controllerDir = dirname(__FILE__);
    $defaultFolder = $controllerDir . '/afipsdk/src/Afip_res';
    $folder = env('AFIP_SDK_FOLDER', $defaultFolder);

    if (!is_string($folder) || trim($folder) === '') {
      $folder = $defaultFolder;
    }

    $folder = trim($folder, " \t\n\r\0\x0B\"'");
    $folder = str_replace('\\', DIRECTORY_SEPARATOR, $folder);

    if (!$this->isAbsolutePath($folder)) {
      $folder = $controllerDir . DIRECTORY_SEPARATOR . $folder;
    }

    $folder = rtrim($folder, '/\\') . DIRECTORY_SEPARATOR;
    $fallback = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $defaultFolder), '/\\') . DIRECTORY_SEPARATOR;

    if ((!file_exists($folder . 'cert') || !file_exists($folder . 'key')) && file_exists($fallback . 'cert') && file_exists($fallback . 'key')) {
      Log::warning('AFIP_SDK_FOLDER no contiene cert/key. Usando carpeta default del SDK.', [
        'configured_folder' => $folder,
        'fallback_folder' => $fallback,
      ]);

      return $fallback;
    }

    return $folder;
  }

  private function isAbsolutePath(string $path): bool
  {
    return substr($path, 0, 1) === DIRECTORY_SEPARATOR || preg_match('/^[A-Za-z]:[\/\\\\]/', $path) === 1;
  }

  private function savePdf(string $filename, string $contents): void
  {
    $directories = [public_path('pdf')];
    $httpdocs = base_path('../httpdocs');

    if (is_dir($httpdocs)) {
      $directories[] = $httpdocs . DIRECTORY_SEPARATOR . 'pdf';
    }

    foreach (array_unique($directories) as $directory) {
      if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
      }

      file_put_contents($directory . DIRECTORY_SEPARATOR . $filename, $contents);
    }
  }

  //
  public function cobrosstore(Request $request)
  {

    $comp_rels = $request->comp_id;

    $pagos = $request->pagos;

    $cobrosId = [];


    foreach ($request->pagos as $pago) {
      $cobro = new \App\Models\Cobros;
      $cobro->client_id = $request->client_id;
      $cobro->amount =  $pago['importe'];


      $cobro->type = $pago['forma_pago']['value'];

      if (isset($pago['banco'])) {
        $cobro->bank = $pago['banco']['label'];
      }
      if (isset($pago['detail'])) {
        $cobro->detail = $pago['detail'];
      }
      if (isset($pago['due_date'])) {
        $cobro->due_date = $pago['due_date'];
      }
      if (isset($pago['emision_date'])) {
        $cobro->emision_date = $pago['emision_date'];
      }
      if (isset($pago['nro_cheque'])) {
        $cobro->nro_cheque = $pago['nro_cheque'];
      }

      $cobro->created_at = $pago['ingreso'];

      $cobro->save();

      $cobrosId[] = $cobro->id;
    }

    $recibo = new \App\Models\Recibo;

    $recibo->client_id = $request->client_id;
    $totalPagos = \App\Models\Cobros::whereIn('id', $cobrosId)->sum('amount');
    $recibo->importe = $totalPagos;

    $recibo->save();


    if ($comp_rels) {

      sort($comp_rels);

      $comprobantes = \App\Models\AfipComprobantes::whereIn('id', $comp_rels)->get(['ImpTotal', 'id', 'numeroComprobante']);

      foreach ($comprobantes as $comprobante) {

        echo $comprobante->numeroComprobante . ' - ' . $comprobante->ImpTotal . ' <br>';

        $i =  $comprobante->ImpTotal - $comprobante->cobrado;
        $saldo = 0;

        foreach ($pagos as $key => $pago) {

          // if($pago['forma_pago']['value'] == "R"){

          //   dd($pago);

          // }

          $i = $i - $pago['importe'];

          $importeRel = 0; // $comprobante->ImpTotal - $pago['importe'];

          if ($i < 0) {

            $importeRel =  $pagos[$key]['importe'] - abs($i);
          } else {

            $importeRel = $pagos[$key]['importe'];
          }

          echo "PAGO de " . $importeRel . ' key ' . $key . ' - ' . $i . ' cobroID: ' . $cobrosId[$key] . '<br>';

          $cobro_rel = new \App\Models\CobroRelacion;
          $cobro_rel->cobro_id = $cobrosId[$key];
          $cobro_rel->afip_comprobante_id = $comprobante->id;
          $cobro_rel->monto = $importeRel;

          $cobro_rel->save();

          if ($i <= 0) {

            $i = abs($i);

            $pagos[$key]['importe'] = $i;


            continue 2;
          } else {

            unset($pagos[$key]);
          }
        }
      }
    }

    if (isset($i) && $i > 0) {

      //echo "<br> Generar Nota de Credito por $ ".$i;
      $recibo->saldo = $i;
      $recibo->save();
    }

    return ['status' => 'success'];
    /*
truncate cobro_relacions;
truncate cobros;
 */
  }
  public function show($id)
  {

    //dd($id);

    $fac = \App\Models\AfipComprobantes::find($id);

    $compResp = array(
      "cuit" => 20205213962,
      "codigoTipoComprobante" => 1,
      "numeroPuntoVenta" => 3,
      "numeroComprobante" => 24,
      "fechaEmision" => "2022-01-24",
      "CAE" => 72045984899276,
      "fechaVencimientoCAE" => "2022-02-03"
    );
    $object = (object) $compResp;

    // $data = [
    //   'ImpTotal' => 333,
    //   'ImpTotConc' => 0,
    //   'ImpNeto' => 0,
    //   'ImpOpEx' => 0,
    //   'ImpTrib' => 0,
    //   'ImpIVA' => 0,
    //   'FchServDesde' => '',
    //   'FchServHasta' => '',
    //   'FchVtoPago' => '',
    //   'MonId' => 'PES',
    //   'condicionVenta' => 'contado'
    // ];

    $facturador = $this->CUIT(20205213962);

    return view('facturacion.facdesign', [
      'facturador' => $facturador,
      'taxdata' => $this->CUIT($fac->DocNro),
      'fac_letter' => 'A',
      'compResp' => $object,
      // 'data' => $data,
      'fac' => $fac
    ]);
  }

  public function ValidateCUITCUIL($cuit)
  {

    $array_dig2 = array(20, 23, 24, 27, 30, 33, 34);

    $cuit = str_replace('-', '', $cuit);

    $cuit = str_replace(' ', '', $cuit);

    // validar que el cuit tenga 11 digitos
    if (strlen($cuit) != 11) {

      return ['status' => 'error', 'message' => 'CUIT/CUIL invalido', 'cuit' => $cuit];
    }


    if (in_array($cuit[0] . $cuit[1], $array_dig2)) { //si los 2 primeros digistos son validos

      $coeficiente = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);

      $cuit2 = str_replace('-', '', $cuit);
      $cuit = str_split($cuit2);
      $verificador = array_pop($cuit);

      $sum = 0;

      for ($i = 0; $i < 10; $i++) {

        $sum += ($cuit[$i] * $coeficiente[$i]);
        $resultado = $sum % 11;
        $resultado = 11 - $resultado;
        //saco el digito verificador
        $veri_nro = intval($verificador);
      }


      if ($resultado === 11) {
        $resultado = 0;
      } else if ($resultado === 10) {
        $resultado = 9;
      }


      if ($veri_nro <> $resultado) {
        //cuit invalido

        return ['status' => 'error', 'message' => 'CUIT/CUIL invalido', 'cuit' => $cuit];
      } else {
        //cuit correcto
        return ['status' => 'success'];
      }
    } else {
      return ['status' => 'error', 'message' => 'CUIT/CUIL invalido', 'cuit' => $cuit];
    }
  }

  public function CUIT($cuit)
  {
    error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
    ini_set('display_errors', config('app.debug') ? 1 : 0);

    $valid = $this->ValidateCUITCUIL($cuit);


    $afip = new \Afip($this->afip_ws);

    if ($valid['status'] !== 'error') {


      $tax_id = $afip->RegisterScopeThirteen->GetTaxpayerDetails($cuit);

      if ($tax_id) {

        return $tax_id;
      } else {


        return ['status' => 'error', 'message' => 'No hay datos en AFIP de este contribuyente.', 'cuit' => $cuit];
      }
    } else {

      return ['status' => 'error', 'message' => 'CUIT/CUIL invalido', 'cuit' => $cuit];
    }
  }


  public function listafip()
  {

    $data = \App\Models\AfipComprobantes::whereNotNull('numeroComprobante')->orderBy('created_at', 'DESC')->get();

    return ['data' => $data];
  }

  public function getPdvAndCTypes()
  {


    error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
    ini_set('display_errors', config('app.debug') ? 1 : 0);

    // generamos credenciales para utilizar el webservice


    $afip = new \Afip($this->afip_ws);

    $puntosdeventa = $afip->ElectronicBilling->GetSalesPoints();

    $tiposdefactura = $afip->ElectronicBilling->GetVoucherTypes();

    return [$puntosdeventa, $tiposdefactura];
  }
  public function facturadoEstado(Request $request)
  {
    $FacturasRelacion = FacturasRelacion::findOrFail($request->id);
    $FacturasRelacion->estado = "PAGADO";
    $FacturasRelacion->save();
    $response['msj'] = "Estado modificado";
    return $response;
  }

  public function nota(Request $request, $id)
  {
    $nota = $request->nota;

    //VALIDAR USUARIO
    $idPedido = $id;
    $pedido = Pedido::findOrFail($idPedido);
    $numFactura = $request->factura;
    $factura = $pedido->obtenerRelacionados->where('numeroFactura', '=', $numFactura)->first();
    //PARA FACTURA B
    $tipoDeFactura = 6;
    $CbteTipo = 3;
    if ($factura->factura == "A") {
      $tipoDeFactura = 1;
      $CbteTipo = 8;
    }
    $usuario_id = $pedido->usuario_id;
    $cliente = Cliente::find($usuario_id);
    if (!$cliente) {
      return redirect()->back()->withErrors(['cliente' => 'El pedido no tiene un cliente asociado.']);
    }
    $cuit = $cliente->cuit;
    $factura = null;
    $descripcion = $request->descripcion;

    //CARRITO IVA
    // $carrito = CarritoContenido::first();    
    // $iva = $carrito->iva;
    // $iva = $iva / 100;
    // $iva = $iva + 1;


    //$total = round($total,2);
    $total = $request->cantidad;
    $dni = $cliente->cuit;
    /*
    $respuesta = Http::get('https://aio-api.d10.osole.com.ar/?cuit='.$dni);
    $respuesta = $respuesta->json();
    if($respuesta){
      $contribuyente = $respuesta['tipoContribuyente'];      
    } 
*/


    error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
    ini_set('display_errors', config('app.debug') ? 1 : 0);
    $facturado = 0;
    // generamos credenciales para utilizar el webservice
    $afip =  new \Afip($this->afip_ws);

    $docnro = $this->afip_ws['CUIT'];

    // chequear tipo de impuesto segun el dni ( necesita tener habilitado el scope 13 afip en webservices )
    $taxdata = $this->CUIT($docnro);
    $facturador = $this->CUIT($this->afip_ws['CUIT']);

    $CbteTipo = 3;
    if ($nota == "debito") {
      $CbteTipo = 2;
    }
    $dni = 0;
    $idIva = 5;
    $doctipo = 99;

    if ($request->facturar == "A") {
      $doctipo = 80;
      $dni = $cliente->cuit;
      //$idIva = 3;
    }
    /*      $dni = 0;
      $idIva = 5;
      $doctipo = 99;
      if($contribuyente != "RESPONSABLE INSCRIPTO"){
        $doctipo = 80;
        $dni = $cliente->cuit;        
        $idIva = 3;
      }   
*/
    $last_voucher = $afip->ElectronicBilling->GetLastVoucher(6, $CbteTipo);

    $new_factura = $last_voucher + 1;
    $carrito = CarritoContenido::first();
    $iva = $carrito->iva;
    $iva = $iva / 100;
    $iva = $iva + 1;

    $total = round($total, 2);

    $totalIva = $total * $iva;
    $totalIva = round($totalIva, 2);
    $ImpTotal = $totalIva;
    $ImpTotConc = 0;
    $ImpNeto = round($total, 2);
    $ImpOpEx = 0;
    $ImpTrib = 0;
    $ImpIVA = $ImpTotal - $ImpNeto;

    $data = array(
      'CantReg'   => 1,  // Cantidad de comprobantes a registrar
      'PtoVta'   => 6,  // Punto de venta
      'CbteTipo'   => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
      'Concepto'   => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
      'DocTipo'   => $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
      'DocNro'   => $dni,  // Número de documento del comprador (0 consumidor final)
      'CbteDesde'   => $new_factura,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
      'CbteHasta'   => $new_factura,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
      'CbteFch'   => intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
      'ImpTotal'   => $ImpTotal, // Importe total del comprobante
      'ImpTotConc'   => 0,   // Importe neto no gravado
      'ImpNeto'   => $ImpNeto, // Importe neto gravado
      'ImpOpEx'   => 0,   // Importe exento de IVA
      'ImpIVA'   => $ImpIVA,  //Importe total de IVA
      'ImpTrib'   => 0,   //Importe total de tributos
      'MonId'   => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
      'MonCotiz'   => 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
      'CbtesAsoc' => array(
        array(
          'Tipo' => $CbteTipo,
          'PtoVta' => 6,
          'Nro' => $numFactura,
        )
      ),
      'Iva'     => array( // (Opcional) Alícuotas asociadas al comprobante
        array(
          'Id'     => $idIva,
          'BaseImp'   => $ImpNeto,
          'Importe'   => $ImpIVA
        )
      ),
    );

    $res = $afip->ElectronicBilling->CreateVoucher($data);

    $res['CAE']; //CAE asignado el comprobante
    $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)
    $fecha = date('d/m/Y');
    $data = compact('res', 'cliente', 'nota', 'fecha', 'cuit', 'new_factura', 'numFactura', 'ImpIVA', 'ImpNeto', 'total', 'descripcion', 'CbteTipo');
    $cuit = "20-20521396-2";
    ///PDF FACTURA
    $pdf = Pdf::loadView('pdf.nota', $data);
    $pdf->render();
    $output = $pdf->output();
    $this->savePdf($nota . $res['CAE'] . ".pdf", $output);

    //FACTURAS RELACIONADAS
    //PARA LO FACTURADO
    $FacturasRelacion = new FacturasRelacion();
    $FacturasRelacion->pedido_id = $pedido->id;
    $FacturasRelacion->relacion_id = $res['CAE'];
    $FacturasRelacion->factura = $nota;
    $FacturasRelacion->pedido = $numFactura;
    $FacturasRelacion->numeroFactura = $new_factura;
    $FacturasRelacion->total = $total;
    $FacturasRelacion->save();
    return redirect()->route('adm.facturado')->with('success', 'Nota realizada.');
  }

  public function index(Request $request)
  {
    ini_set('soap.wsdl_cache_enabled', 0);
    ini_set('soap.wsdl_cache_ttl', 0);
    //VALIDAR USUARIO
    $request->validate([
      'idPedido' => 'required|integer|exists:pedidos,id',
    ]);

    $idPedido = $request->idPedido;
    $pedido = Pedido::findOrFail($idPedido);
    $pedidoPedido = json_decode($pedido->pedido, true);
    if (!is_array($pedidoPedido)) {
      $pedidoPedido = [];
    }

    $pedidoTotal = 0;
    $usuario_id = $pedido->usuario_id;
    $cliente = Cliente::find($usuario_id);
    if (!$cliente) {
      return redirect()->back()->withErrors(['cliente' => 'El pedido no tiene un cliente asociado.']);
    }
    $cuit = $cliente->cuit;
    $descuento = 1;
    $pendiente = 0;
    $res = 0;
    $num_factura = 0;
    $fecha = date('d/m/Y');
    if ($cliente->descuento != 0 || $cliente->descuento != null) {
      $descuento = 100 - $cliente->descuento;
      $descuento = $descuento / 100;
    }
    $factura = null;

    //CONTACTO
    $contacto = Contacto::first();

    //CARRITO IVA
    $carrito = CarritoContenido::first();
    $iva = $carrito->iva;
    $iva = $iva / 100;
    $iva = $iva + 1;

    $productos = array();
    $productosNegro = array();

    $productosPendiente = array();
    $productosCompleto = array();

    $total = 0;
    $totalNegro = 0;
    $totalPendiente = 0;
    $totalCompleto = 0;
    $estado = "FACTURADO";
    $facturado = 0;
    $facturadoNegro = 0;
    $pedidoIndexPorId = [];
    foreach ($pedidoPedido as $index => $productoPedido) {
      if (!is_array($productoPedido)) {
        continue;
      }

      $pedidoIndexPorId[(string)($productoPedido['idPedido'] ?? $index)] = $index;
    }

    $presentaciones = $request->input('presentacion', []);
    $codigos = $request->input('codigo', []);
    $nombres = $request->input('nombre', []);
    $precios = $request->input('precio', []);
    $cantidades = $request->input('cantidad', []);
    $cantidadesA = $request->input('cantidadF', []);
    $cantidadesX = $request->input('cantidadN', []);
    $idItems = $request->input('idItem', []);
    $cantidadesOriginalesCliente = $request->input('cantidad_original_cliente', []);
    $logisticaModificada = $request->input('logistica_modificada', []);

    $contador = count($presentaciones);
    for ($i = 0; $i < count($presentaciones); $i++) {
      $contador--;
      $codigo = trim((string)($codigos[$i] ?? ''));
      $nombre = (string)($nombres[$i] ?? '');
      $precio = floatval($precios[$i] ?? 0);
      $cantidadPreparada = intval($cantidades[$i] ?? 0);
      $cantidadFacturadaA = intval($cantidadesA[$i] ?? 0);
      $cantidadFacturadaX = intval($cantidadesX[$i] ?? 0);
      $esEnvio = $codigo === '' || $codigo === '0';

      $pedidoTotal += $precio * $cantidadPreparada;

      $pedidoIndex = null;
      $idItem = $idItems[$i] ?? null;
      if ($idItem !== null && $idItem !== '') {
        $pedidoIndex = $pedidoIndexPorId[(string)$idItem] ?? null;
      }

      if ($pedidoIndex === null && !$esEnvio) {
        foreach ($pedidoPedido as $index => $productoPedido) {
          if (isset($productoPedido['codigo']) && trim((string)$productoPedido['codigo']) === $codigo) {
            $pedidoIndex = $index;
            break;
          }
        }
      }

      $esProductoPedido = !$esEnvio && $pedidoIndex !== null && isset($pedidoPedido[$pedidoIndex]);
      $cantidadOriginalCliente = intval($cantidadesOriginalesCliente[$i] ?? ($esProductoPedido ? ($pedidoPedido[$pedidoIndex]['cantidad_original_cliente'] ?? $cantidadPreparada) : $cantidadPreparada));
      $cantidadFueModificadaPorLogistica = intval($logisticaModificada[$i] ?? ($esProductoPedido ? ($pedidoPedido[$pedidoIndex]['cantidad_modificada_logistica'] ?? 0) : 0)) === 1;
      $cantidadBasePendiente = $cantidadFueModificadaPorLogistica ? $cantidadOriginalCliente : $cantidadPreparada;
      $cantidadPendiente = $cantidadBasePendiente - $cantidadFacturadaA - $cantidadFacturadaX;

      if ($esProductoPedido) {
        $pedidoPedido[$pedidoIndex]['precio'] = $precio;
        $pedidoPedido[$pedidoIndex]['precio_congelado'] = $precio;
        $pedidoPedido[$pedidoIndex]['cantidad'] = $cantidadPreparada;
        $pedidoPedido[$pedidoIndex]['cantidad_original_cliente'] = $cantidadOriginalCliente;
        $pedidoPedido[$pedidoIndex]['cantidad_preparada_logistica'] = $cantidadPreparada;

        $pedidoPedido[$pedidoIndex]['logistica_facturacion_snapshot'] = [
          'fecha' => now('America/Argentina/Buenos_Aires')->format('Y-m-d H:i:s'),
          'cantidad_original_cliente' => $cantidadOriginalCliente,
          'cantidad_preparada_logistica' => $cantidadPreparada,
          'cantidad_logistica_modificada' => $cantidadFueModificadaPorLogistica ? 1 : 0,
          'cantidad_a_previa' => intval($pedidoPedido[$pedidoIndex]['cantidadF'] ?? 0),
          'cantidad_x_previa' => intval($pedidoPedido[$pedidoIndex]['cantidadN'] ?? 0),
          'cantidad_pendiente_previa' => intval($pedidoPedido[$pedidoIndex]['cantidadP'] ?? 0),
          'cantidad_a_enviada' => $cantidadFacturadaA,
          'cantidad_x_enviada' => $cantidadFacturadaX,
          'cantidad_pendiente_calculada' => $cantidadPendiente,
        ];
      }
	  
      
      ///NOTA DE PEDIDO COMPLETA
      $producto = new stdClass();
      $producto->nombre = $nombre;
      $producto->precio = $precio;
      $producto->cantidad = $cantidadFacturadaA + $cantidadFacturadaX;
      $totalCompleto += $producto->precio * $producto->cantidad;
      array_push($productosCompleto, $producto);
      ///SEPARAR LO FACTURADO / LO NEGRO Y LO PENDIENTE
      if ($cantidadFacturadaA > 0) {
        $producto = new stdClass();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->cantidad = $cantidadFacturadaA;
        $total += $producto->precio * $producto->cantidad;
        if ($esProductoPedido) {
          $pedidoPedido[$pedidoIndex]['cantidadF'] = $cantidadFacturadaA;
        }
        array_push($productos, $producto);
        $facturado = 1;
      }
      if ($cantidadFacturadaX > 0) {
        $producto = new stdClass();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->cantidad = $cantidadFacturadaX;
        $totalNegro += $producto->precio * $producto->cantidad;
        if ($esProductoPedido) {
          $pedidoPedido[$pedidoIndex]['cantidadN'] = $cantidadFacturadaX;
        }
        array_push($productosNegro, $producto);
        $facturadoNegro = 1;
      }

      if ($esProductoPedido) {
      if ($cantidadPendiente > 0) {
        $pendiente = 1;

        $producto = new stdClass();
        $producto->nombre = $nombre;
        $producto->cantidad = $cantidadPendiente;
        array_push($productosPendiente, $producto);
      }
      $pedidoPedido[$pedidoIndex]['cantidadP'] = $cantidadPendiente;
      }
    }

    if ($pendiente == 1) {
      $estado = "FACTURADO PENDIENTES";
    }

    $pedido->pedido = json_encode($pedidoPedido);

    /// TOTAL CON DESCUENTO Y + IVA
    $total = round($total, 2);
    if (isset($request->descuento)) {
      $total = $total * $descuento;
    }
    $totalIva = $total;
    $totalIva = round($total, 2);

    /// TOTAL CON DESCUENTO EN NEGRO
    $totalNegro = round($totalNegro, 2);
    if (isset($request->descuento)) {
      $totalNegro = $totalNegro * $descuento;
    }

    $dni = $cliente->cuit;

    /*$respuesta = Http::get('https://aio-api.d10.osole.com.ar/?cuit='.$dni);
    $respuesta = $respuesta->json();
    
    $contribuyente = "MOTOTRIBUTISTA";   
    if($respuesta['status'] != 'error' ){
      $contribuyente = $respuesta['tipoContribuyente'];      
    }
    $afip = new \Afip($this->afip_ws);
    //dd($afip->ElectronicBilling->GetDocumentTypes());
    */
    $dni = $cliente->cuit;
    $doctipo = 96;
    $tipoFactura = "B";
    $idIva = 5;
    $CbteTipo = 6;
    $totalIva = $total * $iva;
    $totalIva = round($totalIva, 2);
    $idIva = 5;
    if ($request->factura == "A") {
      $doctipo = 80;
      $dni = $cliente->cuit;
      $tipoFactura = "A";

      $CbteTipo = 1;
    }

    //dd($CbteTipo." ".$dni." ".$doctipo);
    // servicio de afip sin items = "wsfe" 
    //
    // servicio de afip con items = "wsmtxca" usar ExtendedElectronicBilling
    // 
    // mostrar errores ( debug )
    error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
    ini_set('display_errors', config('app.debug') ? 1 : 0);
    // $afip = new \Afip(array('CUIT' => 20205213962));
    // dd($afip);
    // generamos credenciales para utilizar el webservice
    if ($facturado == 1) {

      $afip =  new \Afip($this->afip_ws);


      $docnro = 20205213962;

      // chequear tipo de impuesto segun el dni ( necesita tener habilitado el scope 13 afip en webservices )
      $taxdata = $this->CUIT($docnro);
      $facturador = $this->CUIT(20205213962);
      //dd($taxdata);

      //$puntosdeventa = $afip->ElectronicBilling->GetSalesPoints();

      //dd($puntosdeventa);
      //ultima factura emitida
      $last_voucher = $afip->ElectronicBilling->GetLastVoucher(6, $CbteTipo);

      $num_factura = $last_voucher + 1;

      //FACTURA A
      //
      $ImpTotal = round($totalIva, 2);
      $ImpTotConc = 0;
      $ImpNeto = round($total, 2);
      $ImpOpEx = 0;
      $ImpTrib = 0;
      $ImpIVA = $ImpTotal - $ImpNeto;

      //dd($doctipo." ".$dni);
      $data = array(
        'CantReg'   => 1,  // Cantidad de comprobantes a registrar
        'PtoVta'   => 6,  // Punto de venta
        'CbteTipo'   => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
        'Concepto'   => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
        'DocTipo'   => $doctipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
        'DocNro'   => $dni,  // Número de documento del comprador (0 consumidor final)
        'CbteDesde'   => $num_factura,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
        'CbteHasta'   => $num_factura,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
        'CbteFch'   => intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
        'ImpTotal'   => $totalIva, // Importe total del comprobante
        'ImpTotConc'   => 0,   // Importe neto no gravado
        'ImpNeto'   => $ImpNeto, // Importe neto gravado
        'ImpOpEx'   => 0,   // Importe exento de IVA
        'ImpIVA'   => $ImpIVA,  //Importe total de IVA
        'ImpTrib'   => 0,   //Importe total de tributos
        'MonId'   => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
        'MonCotiz'   => 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
        'Iva'     => array( // (Opcional) Alícuotas asociadas al comprobante
          array(
            'Id'     => $idIva, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
            'BaseImp'   => $ImpNeto, // Base imponible
            'Importe'   => $ImpIVA // Importe 
          )
        ),
      );


      $res = $afip->ElectronicBilling->CreateVoucher($data);

      $res['CAE']; //CAE asignado el comprobante
      $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)


      //FACTURAS RELACIONADAS
      //PARA LO FACTURADO
      $FacturasRelacion = new FacturasRelacion();
      $FacturasRelacion->pedido_id = $pedido->id;
      $FacturasRelacion->relacion_id = $res['CAE'];
      $FacturasRelacion->factura = "A";
      $FacturasRelacion->numeroFactura = $num_factura;
      $FacturasRelacion->pedido = json_encode($productos);
      $FacturasRelacion->total = $total;
      $FacturasRelacion->save();
      $facturaId = $FacturasRelacion->id;
      $data = compact('res', 'cliente', 'productos', 'tipoFactura', 'fecha', 'cuit', 'num_factura', 'ImpIVA', 'ImpNeto', 'descuento', 'pedido', 'carrito', 'contacto', 'facturaId');
      $cuit = "20-20521396-2";
      ///PDF FACTURA
      $pdf = Pdf::loadView('pdf.factura', $data);
      $pdf->render();
      $output = $pdf->output();
      $this->savePdf($res['CAE'] . ".pdf", $output);
    }

    $nPedido = $pedido->id;
    if ($facturadoNegro == 1) {
      //FACTURAS RELACIONADAS
      if ($res == 0) {
        $res = [];
        $res['CAE'] = $pedido->id;
      }
      //PARA LO QUE VA EN NEGRO
      $FacturasRelacion = new FacturasRelacion();
      $FacturasRelacion->pedido_id = $pedido->id;
      $FacturasRelacion->relacion_id = "N" . $res['CAE'];
      $FacturasRelacion->factura = "N";
      $FacturasRelacion->total = $totalNegro;
      $FacturasRelacion->pedido = json_encode($productosNegro);
      $FacturasRelacion->save();
      $facturaId = $FacturasRelacion->id;
      ///PDF FACTURA EN NEGRO 

      $data = compact('nPedido', 'cliente', 'productosNegro', 'fecha', 'cuit', 'descuento', 'pedido', 'contacto', 'facturaId', 'res');
      $pdf = Pdf::loadView('pdf.facturaN', $data);
      $pdf->render();
      $output = $pdf->output();
      $this->savePdf("N" . $res['CAE'] . ".pdf", $output);
    }
    if ($res == 0) {
      $res = [];
      $res['CAE'] = $pedido->id;
    }
    if ($pedido->estado == "APROBADO") {

      //PARA EL TOTAL DEL PEDIDO
      $FacturasRelacion = new FacturasRelacion();
      $FacturasRelacion->pedido_id = $pedido->id;
      $FacturasRelacion->relacion_id = "T" . $res['CAE'];
      $FacturasRelacion->factura = "T";
      $FacturasRelacion->pedido = json_encode($productosCompleto);
      $FacturasRelacion->total = $totalCompleto;
      $FacturasRelacion->save();
      $facturaId = "";
      if ($num_factura != 0) {
        $facturaId = $num_factura;
      }
      //FACTURAS RELACIONADAS
      ///PDF NOTA DE PEDIDO COMPLETA
      $data = compact('nPedido', 'cliente', 'productosCompleto', 'fecha', 'cuit', 'descuento', 'pedido', 'contacto', 'pedido', 'facturaId');
      $pdf = Pdf::loadView('pdf.facturaCompleta', $data);
      $pdf->render();
      $output = $pdf->output();
      $this->savePdf("T" . $res['CAE'] . ".pdf", $output);
    }

    if ($pendiente == 1) {
      //PARA EL TOTAL DEL PEDIDO
      $FacturasRelacion = new FacturasRelacion();
      $FacturasRelacion->pedido_id = $pedido->id;
      $FacturasRelacion->relacion_id = "PENDIENTE" . $res['CAE'];
      $FacturasRelacion->factura = "P";
      $FacturasRelacion->pedido = json_encode($productosCompleto);
      $FacturasRelacion->total = $totalCompleto;
      $FacturasRelacion->save();
      $facturaId = $FacturasRelacion->id;
      //FACTURAS RELACIONADAS
      ///PDF NOTA DE PEDIDO COMPLETA
      $data = compact('nPedido', 'cliente', 'productosPendiente', 'fecha', 'cuit', 'descuento', 'pedido', 'contacto', 'pedido', 'facturaId');
      $pdf = Pdf::loadView('pdf.facturaPendiente', $data);
      $pdf->render();
      $output = $pdf->output();
      $this->savePdf("PENDIENTE" . $res['CAE'] . ".pdf", $output);
    }

    $pedido->estado = $estado;
    $pedido->facturaTotal = $totalIva;
    $pedido->total = $pedidoTotal;
    if (isset($FacturasRelacion)) {
      if (isset($request->descuento)) {
          $descuentoremito = $FacturasRelacion->total * $descuento;
      } else {
          $descuentoremito = 0;
      }
      $FacturasRelacion->descuento = $descuentoremito;
      $FacturasRelacion->subtotal = $totalCompleto-$descuentoremito;
      $FacturasRelacion->save();
    }
    $pedido->save();
    if (isset($pdf)) {
      $pdf->stream();
    }
    return redirect()->route('adm.facturado');
    // tipos de alicuotas ( iva )    
    // borre todos los insultos generados al gobierno por profesionalismo
    //$alicuotas = $afip->ExtendedElectronicBilling->GetAliquotTypes();

    // si no tiene una lista de alicuotas, por lo que segun el manual de AFIP, se debe
    // utilizar el tipo de alicuota que corresponda a la moneda que se esta facturando
    // en este caso, el tipo de alicuota corresponde a la moneda argentina ( $ )
    //

    /*
        array:6 [▼
  0 => {#204 ▼
    +"codigo": 3
    +"descripcion": "0%"
  }
  1 => {#200 ▼
    +"codigo": 4
    +"descripcion": "10.5%"
  }
  2 => {#199 ▼
    +"codigo": 5
    +"descripcion": "21%"
  }
  3 => {#198 ▼
    +"codigo": 6
    +"descripcion": "27%"
  }
  4 => {#197 ▼
    +"codigo": 8
    +"descripcion": "5%"
  }
  5 => {#196 ▼
    +"codigo": 9
    +"descripcion": "2.5%"
  }
]
*/
    //$puntosdeventa = $afip->ExtendedElectronicBilling->GetPointsOfSale();

    /* 
         no me devolvio nada, talvez porque mi cuit no tenga puntos de venta.
    */

    // tipo de comprobantes habilitados a generar.
    //$tiposdefactura = $afip->ExtendedElectronicBilling->GetVoucherTypes();



    $tipofac = 1;

    // consultar cotización 
    //$usd = $afip->ExtendedElectronicBilling->GetCurrencyExchangeRate(1, "USD");
    // aca no enteindo el resultado es 2.232 al 20/11/21

    // chequear ultimo comprobante generado por punto de venta y tipo de comprobante.
    $last_voucher = $afip->ElectronicBilling->GetLastVoucher(1, $tipofac);

    // sumamos uno para el siguiente comprobante.
    $valfac = $last_voucher + 1;



    // generamos los datos a utilizar en el comprobante.
    $data = array(
      'numeroComprobante' => $valfac,
      'numeroPuntoVenta'  => 1,
      'CantReg'           => 1, // Cantidad de comprobantes a registrar
      'PtoVta'            => 6, // Punto de venta
      'CbteTipo'          => $tipofac, // Tipo de comprobante (ver tipos disponibles) 
      'Concepto'          => 1, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
      'DocTipo'           => 80, // Tipo de documento del comprador (ver tipos disponibles)
      'DocNro'            => $docnro, // Numero de documento del comprador
      'CbteDesde'         => $valfac, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
      'CbteHasta'         => $valfac, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
      'CbteFch'           => intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
      'ImpTotal'          => 500, // Importe total del comprobante
      'ImpTotConc'        => 500, // Importe neto no gravado
      'ImpNeto'           => 0, // Importe neto gravado
      'ImpOpEx'           => 0, // Importe exento de IVA
      'ImpIVA'            => 0, //Importe total de IVA
      'ImpTrib'           => 0, //Importe total de tributos
      'FchServDesde'      => NULL, // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
      'FchServHasta'      => NULL, // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
      'FchVtoPago'        => NULL, // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
      'MonId'             => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
      'MonCotiz'          => 1, // Cotización de la moneda usada (1 para pesos argentinos)  
    );

    //autorizamos el comprobante
    $fac = $afip->ElectronicBilling->AuthorizeVoucher($data);

    //letra del comprobante
    $fac_letter = $fac->resultado;

    //datos recibidos por afip del comprobante ('CAE', 'CAEFchVto', 'Observaciones', 'Errores')...
    $fac = $fac->comprobanteResponse;

    //datos extendidos del comprobante.
    $voucher_info = $afip->ExtendedElectronicBilling->GetVoucherInfo($fac->numeroComprobante, $fac->numeroPuntoVenta, $fac->codigoTipoComprobante);

    // generacion del pdf
    return view('facturacion.factura', compact(['fac', 'fac_letter', 'voucher_info', 'taxdata', 'facturador']));
  }


  public function create(Request $request)
  {

    // mostrar errores ( debug )
    error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
    ini_set('display_errors', config('app.debug') ? 1 : 0);


    // generamos credenciales para utilizar el webservice

    $afip =  new \Afip($this->afip_ws);

    //$afip = new \Afip($this->afip_ws);

    $DocNro = $request->idPersona;
    $cuitData = $this->CUIT($DocNro);

    $CbteTipo = $request->ctype['Id'];
    $PtoVta = $request->pdv['codigo'];


    $items = $request->items;

    $alicuotas = [];
    $ImpIVA = 0;
    $ImpNeto = 0;
    foreach ($request->ivas as $key => $value) {

      if ($value <= 0) {
        continue;
      }

      $ImpIVA += $value;

      switch ($key) {

        case 4:

          $alicuotas[] =
            [
              'Id' => $key,
              'BaseImp' => $value / 10.5 * 100,
              'Importe' => $value,
            ];

          $ImpNeto += $value / 10.5 * 100;

          break;
        case 5:

          $alicuotas[] =
            [
              'Id' => $key,
              'BaseImp' => $value / 21 * 100,
              'Importe' => $value,
            ];

          $ImpNeto += $value / 21 * 100;

          break;
        case 6:

          $alicuotas[] =
            [
              'Id' => $key,
              'BaseImp' => $value / 27 * 100,
              'Importe' => $value,
            ];

          $ImpNeto += $value / 27 * 100;

          break;
        case 7:

          $alicuotas[] =
            [
              'Id' => $key,
              'BaseImp' => $value / 0.05 * 100,
              'Importe' => $value,
            ];

          $ImpNeto += $value / 10.5 * 100;

          break;

        default:

          break;
      }
    }

    $ImpTotConc =  $request->totalesAfip['ImpTotConc'];
    $ImpOpEx =  $request->totalesAfip['ImpOpEx'];

    $ImpNeto = round($ImpNeto, 2);
    $ImpTrib =  0; //7.8;

    $ImpTotal = $ImpTotConc + $ImpNeto + $ImpIVA + $ImpOpEx + $ImpTrib;


    /*
    Tipos de documentos
    00, C I CAPITAL FEDERAL
    01, C I BUENOS AIRES
    02, C I CATAMARCA
    03, C I CORDOBA
    04, C I CORRIENTES
    05, C I ENTRE RIOS
    06, C I JUJUY
    07, C I MENDOZA
    08, C I LA RIOJA
    09, C I SALTA
    10, C I SAN JUAN
    11, C I SAN LUIS
    12, C I SANTA FE
    13, C I SGO DEL ESTERO
    14, C I TUCUMAN
    16, C I CHACO
    17, C I CHUBUT
    18, C I FORMOSA
    19, C I MISIONES
    20, C I NEUQUEN
    21, C I LA PAMPA
    22, C I RIO NEGRO
    23, C I SANTA CRUZ
    24, C I TIERRA DEL FUEGO
    80, C U I T
    86, C U I L
    87, C D I
    89, LIBRETA DE ENROLAMIENTO
    90, LIBRETA CIVICA
    91, C I EXTRANJERA
    92, EN TRAMITE
    93, ACTA DE NACIMIENTO
    94, PASAPORTE
    95, C I BS AS R N P
    96, DOC NACIONAL DE IDENTIDAD
    99, SIN IDENTIFICAR / VENTA GLOBAL DIARIA
    */

    $tipoClave = $cuitData->tipoClave;

    switch ($tipoClave) {
      case 'CUIT':
        $DocTipo = 80;
        break;
      case 'CUIL':
        $DocTipo = 86;
        break;
      case 'CDI':
        $DocTipo = 87;
        break;
      case 'LIBRETA DE ENROLAMIENTO':
        $DocTipo = 89;
        break;
      case 'LIBRETA CIVICA':
        $DocTipo = 90;
        break;
      case 'C I EXTRANJERA':
        $DocTipo = 91;
        break;
      case 'EN TRAMITE':
        $DocTipo = 92;
        break;
      case 'ACTA DE NACIMIENTO':
        $DocTipo = 93;
        break;
      case 'PASAPORTE':
        $DocTipo = 94;
        break;
      case 'C I BS AS R N P':
        $DocTipo = 95;
        break;
      case 'DOC NACIONAL DE IDENTIDAD':
        $DocTipo = 96;
        break;
      case 'SIN IDENTIFICAR / VENTA GLOBAL DIARIA':
        $DocTipo = 99;
        break;
      default:
        $DocTipo = 99;
        break;
    }

    $data = array(
      'CantReg'   => 1,  // Cantidad de comprobantes a registrar
      'PtoVta'   => $PtoVta,  // Punto de venta
      'CbteTipo'   => $CbteTipo,  // Tipo de comprobante (ver tipos disponibles) 
      'Concepto'   => 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
      'DocTipo'   => $DocTipo, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
      'DocNro'   => $DocNro,  // Número de documento del comprador (0 consumidor final)
      'CbteFch'   => intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
      'ImpTotal'   => $ImpTotal, // Importe total del comprobante
      'ImpTotConc'   => $ImpTotConc,   // Importe neto no gravado
      'ImpNeto'   => $ImpNeto, // Importe neto gravado
      'ImpOpEx'   => $ImpOpEx,   // Importe exento de IVA
      'ImpIVA'   => $ImpIVA,  //Importe total de IVA
      'ImpTrib'   => $ImpTrib,   //Importe total de tributos
      'MonId'   => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
      'MonCotiz'   => 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
      'Iva'     => $alicuotas,
      // 'Tributos' 	=> array( // (Opcional) Tributos asociados al comprobante
      //   array(
      //     'Id' 		=> 99, // Id del tipo de tributo (1 para IVA) (ver tipos disponibles) 
      //     'Desc' 	=> 'Ingresos Brutos', // Descripcion tributo
      //     'BaseImp' 	=> $ImpNeto, // Base imponible
      //     'Alic' 	=> 5.2, // Alícuota
      //     'Importe' 	=> 7.8 // Importe 
      //   )
      // ),
    );



    $res = $afip->ElectronicBilling->CreateNextVoucher($data);

    $cae        = $res['CAE']; //CAE asignado el comprobante
    $caeFchVto  = $res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)

    // consultar comprobante
    $infoVoucher = $afip->ElectronicBilling->GetVoucherInfo($res['voucher_number'], $PtoVta, $CbteTipo);


    $afip_comprobante = new \App\Models\AfipComprobantes($data);
    $afip_comprobante->save();

    $afip_comprobante->condicionVenta = implode(',', $request->cdv);

    $afip_comprobante->client_id = $request->client_id;
    $afip_comprobante->bultos  = $request->bultos;
    $afip_comprobante->numeroComprobante = $request->pdv['descripcion'] . '-' . STR_PAD($res['voucher_number'], 8, "0", STR_PAD_LEFT);
    $afip_comprobante->save();


    foreach ($request->items as $item) {

      $to_items = [
        'unidadesMtx' => 0,
        'codigoMtx' => 0,
        'codigo' => 0,
        'descripcion' => 0,
        'cantidad' => 0,
        'codigoUnidadMedida' => 0,
        'precioUnitario' => 0,
        'importeBonificacion' => 0,
        'codigoCondicionIVA' => 0,
        'importeIVA' => 0,
        'importeItem' => 0,
      ];


      if (isset($item['type']) && $item['type'] == 'separator') {
        $afip_comprobante_item = new \App\Models\AfipComprobanteItems($to_items);
        $afip_comprobante_item->codigo = 'separator';
        $afip_comprobante_item->afip_comprobante_id = $afip_comprobante->id;
        $afip_comprobante_item->save();
        continue;
      } else {

        $bonif = 0;

        $bonif = ($item['precio'] * $item['cantidad'] - 0) / 100;

        $to_items = [
          'unidadesMtx' => 1,
          'codigoMtx' => 1,
          'codigo' =>  $item['product_code'],
          'descripcion' => $item['product_name'],
          'cantidad' => $item['cantidad'],
          'codigoUnidadMedida' => 1,
          'precioUnitario' => round($item['precio'], 2),
          'importeBonificacion' => round($bonif, 2),
          'codigoCondicionIVA' => $item['iva'],
          'importeIVA' => round(($item['precio'] * $item['cantidad'] - $bonif) * 0.21, 2),
          'importeItem' => round(($item['precio'] * $item['cantidad'] - $bonif) * (1 + 0.21), 2),
        ];
      }

      $afip_comprobante_item = new \App\Models\AfipComprobanteItems($to_items);
      $afip_comprobante_item->afip_comprobante_id = $afip_comprobante->id;
      $afip_comprobante_item->save();
    }




    $afip_comprobante->cae = $res['CAE'];
    $afip_comprobante->fechaVencimientoCAE = $res['CAEFchVto'];
    $afip_comprobante->save();

    return ['status' => 'success'];
  }

  public function alicuotasventas($y, $m)
  {

    $comprobantes_ventas = \App\Models\AfipComprobantes::where('created_at', '>=', $y . '-' . $m . '-01 00:00:00')
      ->where('created_at', '<=', $y . '-' . $m . '-31 23:59:59')
      ->get();

    return ['data' => $comprobantes_ventas];
  }

  public function ivaventas($y, $m)
  {

    $iv_lineas = ""; //[];

    // $iv_lineas[] = [
    //   'fecha' => '20220302',
    //   'tipo' => '003',
    //   'pdv' => '00001',
    //   'numero_factura' => '00000000000000001814',
    //   'numero_hasta' => '00000000000000001814',
    //   'tipo_comprador' => '80',
    //   'nombre_comprador' => 'TALLERES GRAFICOS DEL NORTE SR',
    //   'importe_total' => '000000000493680',
    //   'importe_conceptos' => '000000000000000',
    //   'percep_no_categor' => '000000000000000',
    //   'importe_exento' => '000000000000000',
    //   'importe_nacional' => '000000000000000',
    //   'importe_iibb' => '000000000000000',
    //   'importe_municip' => '000000000000000',
    //   'importe_interno' => '000000000000000',
    //   'moneda' => 'PES',
    //   'tipo_cambio' => '0001000000',
    //   'cant_alicuotas' => '1',
    //   'cod_oper' => ' ',
    //   'otros_tributos' => '000000000000000',
    //   'pago_vence' => '20220430'
    // ];

    // get AfipComprobantes where created_at in year $y and month $m
    $comprobantes_ventas = \App\Models\AfipComprobantes::where('created_at', '>=', $y . '-' . $m . '-01 00:00:00')
      ->where('created_at', '<=', $y . '-' . $m . '-31 23:59:59')
      ->get();


    header('Content-Disposition: attachment; filename="AFIP_VENTAS_' . $y . '-' . $m . '.txt"');

    foreach ($comprobantes_ventas as $comprobante_venta) {
      echo $comprobante_venta->lineTxt;
      // print break line
      echo "\r\n";

      /*[
          'fecha' => $comprobante_venta->fecha,
          'tipo' => '003',
          'pdv' => '00001',
          'numero_factura' => $comprobante_venta->numero,
          'numero_hasta' => $comprobante_venta->numero,
          'tipo_comprador' => '80',
          'nombre_comprador' => $comprobante_venta->afip_comprobante_cliente->afip_comprobante_cliente_persona->nombre,
          'importe_total' => $total_importe_item,
          'importe_conceptos' => '000000000000000',
          'percep_no_categor' => '000000000000000',
          'importe_exento' => '000000000000000',
          'importe_nacional' => '000000000000000',
          'importe_iibb' => '000000000000000',
          'importe_municip' => '000000000000000',
          'importe_interno' => '000000000000000',
          'moneda' => 'PES',
          'tipo_cambio' => '0001000000',
          'cant_alicuotas' => '1',
          'cod_oper' => ' ',
          'otros_tributos' => '000000000000000',
          'pago_vence' => $comprobante_venta->fechaVencimientoCAE
        ];*/
    }

    //return  $iv_lineas;
  }



  public function ivacompras($y, $m)
  {

    $iv_lineas = ""; //[];

    $comprobantes_ventas = \App\Models\AfipCompras::where('created_at', '>=', $y . '-' . $m . '-01 00:00:00')
      ->where('created_at', '<=', $y . '-' . $m . '-31 23:59:59')
      ->get();


    // header('Content-Disposition: attachment; filename="AFIP_COMPRAS_'.$y.'-'.$m.'.txt"');

    foreach ($comprobantes_ventas as $comprobante_venta) {
      echo $comprobante_venta->lineTxt;
      // print break line
      echo "\r\n";
    }
  }

  public function cobros()
  {

    $data = \App\Models\Cobros::all();

    $data->each->append(['client_name']);

    return ['status' => 'success', 'data' => $data];
  }



  public function afipcompras2(Request $request)
  {

    $data = \App\Models\AfipCompras::with('cliente')->get();

    return ['status' => 'success', 'data' => $data];
  }


  public function afipcompras(Request $request)
  {

    $ownerData = [
      'docu_comprador' => '20205213962',
      'nombre_comprador' => 'MOLDPACK',
      'tipo_comprador' => '',
    ];
    //dd($request->all());
    // json decode the request
    //dd($request->all());

    $provider = \App\Models\Proveedores::find($request->client_id);

    $item = new \App\Models\AfipCompras();

    // check if request has file
    if ($request->hasFile('file')) {
      $file = $request->file('file');
      $filename = $file->getClientOriginalName();
      $file->move(public_path('/uploads'), $filename);
      $item->file = $filename;
    }

    $item->client_id = $provider->id;

    $item->fecha = $request->date1;
    $item->tipo = $request->ctype;

    $item->pdv = $request->pdv;
    $item->numero_factura = $request->nfac;

    $item->despacho_importacion = $request->despacho_importacion;

    $item->tipo_comprador = $provider->Categoria_Impositiva;
    $item->docu_comprador = $provider->Documento;
    $item->nombre_comprador = $provider->Razon_Social;


    $item->importe_total = $request->total;
    $item->importe_conceptos = $request->conceptos;
    $item->importe_exento = $request->exento;
    $item->importe_percep = $request->percepiva;
    $item->importe_nacional = $request->nacionales;
    $item->importe_iibb = $request->iibb;

    $item->importe_municip = $request->municipales;
    $item->importe_interno = $request->internos;

    $item->tipo_cambio = $request->moneda;
    $item->cant_alicuotas = $request->alicuotas;
    $item->cod_oper = $request->cod_oper;
    $item->cred_fiscal = $request->cred_fiscal;

    $item->otros_tributos = $request->otros_tributos;

    $item->cuit_emisor = $request->cuit_emisor;
    $item->denom_emisor = $request->razon_emisor;

    $item->iva_comision = $request->iva_comision;

    //dd($item);

    $item->save();

    return ['status' => 'success', 'data' => $item];

    /* "ctype" => "{"descripcion":"Factura B"}"
  "nfac" => ""003-00000045""
  "noc" => ""133""
  "credmypyme" => "["mypyme"]"
  "date1" => ""2022-04-20T03:00:00.000Z""
  "date2" => ""2022-04-26T03:00:00.000Z""
  "venc1" => ""2022-04-29T03:00:00.000Z""
  "venc2" => ""2022-04-22T03:00:00.000Z""
  "descglobal" => "10"
  "grav21" => "15"
  "grav27" => "12"
  "grav105" => "11"
  "exento" => "44"
  "nograv" => "22"
  "grav5" => "33"
  "grav205" => "5"
  "iva" => "71.52"
  "iibb" => "0"
  "percepiva" => "12"
  "nacionales" => "1"
  "internos" => "555"
  "otros" => "44"
  "provideer" => "{"key":134,"name":"FA.TU.AR. SRL"}" ************
  "municipales" => "33"
  "observaciones" => ""probandooo ""
  "ingreso" => ""2022-05-06""
  "multiples_iibbs" => "[{"iibb":44,"prov":0},{"iibb":333,"prov":2}]"
  */

    //$item->tipo = $prov->ctype;



    // $total = 0;
    // $conceptos = 0;

    //   if($request->importe_total){
    //     $total = $request->importe_total;
    //   }

    //   $item = new \App\Models\AfipCompras;

    //   $item->fecha = $request->date1;
    //   $item->tipo = $request->ctype['codigo'];
    //   $item->pdv = $request->pdv['codigo'];
    //   $item->numero_factura = $request->nfac;
    //   $item->numero_hasta = $request->nfac;
    //   $item->tipo_comprador = $provider->Categoria_Impositiva;
    //   $item->nombre_comprador = $provider->Razon_Social;
    //   $item->importe_total = $total;
    //   $item->importe_conceptos = $conceptos;
    //   $item->percep_no_categor = $request->percep_no_categor;
    //   $item->importe_exento = $request->exento;
    //   $item->importe_nacional = $request->nacionales;
    //   $item->importe_iibb = $request->iibb;
    //   $item->importe_municip = $request->municipales;
    //   $item->importe_interno = $request->internos;
    //   $item->moneda = 'PES';
    //   $item->tipo_cambio = '0001000000';
    //   $item->cant_alicuotas = '1';
    //   $item->cod_oper = ' ';
    //   $item->cred_fiscal = $request->cred_fiscal;
    //   $item->otros_tributos = $request->otros;
    //   $item->cuit_emisor = $provider->CUIT;
    //   $item->denom_emisor = $provider->Razon_Social;
    //   $item->iva_comision = $request->iva_comision;

  }
}
