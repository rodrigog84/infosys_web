
Ext.define('Infosys_web.model.Cotizacion', {
    extend: 'Ext.data.Model',
    fields: [
    	{name: 'id'},
        {name: 'id_producto'},
        {name: 'id_descuento'},
        {name: 'nombre'},
        {name: 'num_cotiza'},
    	{name: 'codigo'},
    	{name: 'precio_base'},
        {name: 'total'},
        {name: 'neto'},
        {name: 'totaliva'},
        {name: 'iva'},
        {name: 'dcto'},
        {name: 'descuento'},
    	{name: 'descripcion'},
    	{name: 'requisitos'},
    	{name: 'cantidad'},
        {name: 'descuentoprct'},
        {name: 'img'}
    ]
});