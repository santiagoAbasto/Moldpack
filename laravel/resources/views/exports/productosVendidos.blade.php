<table>
    <thead>
        <tr>
            <th>Código Producto</th>
            <th>Nombre Producto</th>
            <th>Precio Producto</th>
            <th>Cantidad Vendida</th>
            <th>Cantidad Solicitada</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productosVendidos as $codigoProducto => $producto)
            <tr>
                <td>{{ $codigoProducto }}</td>
                <td>{{ @$producto['nombre_producto'] }}</td>
                <td>{{ @number_format($producto['precio_producto'], 2) }}</td>
                <td>{{ @$producto['cantidad_vendida'] }}</td>
                <td>{{ @$producto['cantidad_solicitada'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
