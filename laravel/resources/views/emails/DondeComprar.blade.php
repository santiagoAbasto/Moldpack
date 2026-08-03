<table class="table" >
    <th>Datos revendedor</th>
    @isset($dataRequest['razon'])        
        <tr><td>Razon social</td><td>{{$dataRequest['razon']}}</td></tr>
    @endisset
    @isset($dataRequest['nombre'])        
        <tr><td>Nombre y apellido</td><td>{{$dataRequest['nombre']}}</td></tr>
    @endisset
    @isset($dataRequest['cargo'])        
        <tr><td>Cargo</td><td>{{$dataRequest['cargo']}}</td></tr>
    @endisset
    @isset($dataRequest['telefono'])        
        <tr><td>Telefono</td><td>{{$dataRequest['telefono']}}</td></tr>
    @endisset
    @isset($dataRequest['localiad'])        
        <tr><td>Localiad</td><td>{{$dataRequest['localiad']}}</td></tr>
    @endisset
    @isset($dataRequest['provincia'])        
        <tr><td>Provincia</td><td>{{$dataRequest['provincia']}}</td></tr>
    @endisset
    @isset($dataRequest['rubro'])        
        <tr><td>Rubro de su negocio</td><td>{{$dataRequest['rubro']}}</td></tr>
    @endisset
    @isset($dataRequest['cantidadClientes'])        
        <tr><td>Cantidad de clientes</td><td>{{$dataRequest['cantidadClientes']}}</td></tr>
    @endisset
    @isset($dataRequest['ubicacion'])
        <tr><td>Ubicación de los clientes</td><td>{{$dataRequest['ubicacion']}}</td></tr>
    @endisset
    @isset($dataRequest['cantidadVendedoresInternos'])
        <tr><td>Cantidad de vendedores internos</td><td>{{$dataRequest['cantidadVendedoresInternos']}}</td></tr>
    @endisset
    @isset($dataRequest['cantidadVendedoresExternos'])
        <tr><td>Cantidad de vendedores externos</td><td>{{$dataRequest['cantidadVendedoresExternos']}}</td></tr>
    @endisset
    @isset($dataRequest['marca'])
        <tr><td>Marcas que comercializa</td><td>{{$dataRequest['marca']}}</td></tr>
    @endisset
    @isset($dataRequest['mensaje'])
        <tr><td>Mensaje</td><td>{{$dataRequest['mensaje']}}</td></tr>
    @endisset    
</table>
