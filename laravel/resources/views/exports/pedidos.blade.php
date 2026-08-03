<table>
    <thead>
    <tr>
		<th>Pedido</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Razon Social</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Total</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Mensaje</th>
        
    </tr>
    </thead>
    <tbody>
     @forelse($pedidos as $pedido)
     <tr>
		 <td>{{$pedido->pedido_id}}</td>
         <td>{{$pedido->nombre}}</td>
         <td>{{$pedido->apellido}}</td>
         <td>{{$pedido->razonSocial}}</td>
         <td>{{$pedido->fecha}}</td>
         <td>{{$pedido->estado}}</td>
         <td>{{$pedido->total}}</td>
         <td>{{$pedido->productonombre}}</td>
         <td>{{$pedido->productoprecio}}</td>
         <td>{{$pedido->productocantidad}}</td>
         <td>{{$pedido->mensaje}}</td>
     </tr>
     @empty
     @endforelse
    </tbody>
</table>