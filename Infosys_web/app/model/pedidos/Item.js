Ext.define('Infosys_web.model.pedidos.Item', {
    extend: 'Ext.data.Model',
    fields: [
      	{name: 'id'},
        {name: 'id_pedido'},
        {name: 'num_pedido'},
        {name: 'nombre'},
        {name: 'id_producto'},
        {name: 'id_bodega'},
        {name: 'id_descuento'},
        {name: 'nom_producto'},
        {name: 'fecha', type:'date',dateFormat:"Y-m-d"},
        {name: 'precio', decimalPrecision:3},
        {name: 'cantidad', decimalPrecision:3},
        {name: 'dcto'},
        {name: 'descuento'},
        {name: 'dcto'},
        {name: 'neto', decimalPrecision:3},
        {name: 'iva'},
        {name: 'total'}
        
        ]
});