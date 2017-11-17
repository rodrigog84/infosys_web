Ext.define('Infosys_web.model.valida', {
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
        {name: 'pedido', decimalPrecision:3},
        {name: 'stock', decimalPrecision:3},
        {name: 'dcto'},
        {name: 'descuento'},      
        
        ]
});