
Ext.define('Infosys_web.model.Orden_compra', {
    extend: 'Ext.data.Model',
    fields: [
    	{name: 'id'},
        {name: 'num_orden'},
        {name: 'nombre'},
        {name: 'stock'},
        {name: 'nombre_contacto'},
        {name: 'ciudad'},
        {name: 'comuna'},
        {name: 'direccion'},
        {name: 'id_proveedor'},
        {name: 'rut'},
        {name: 'telefono_contacto'},
    	{name: 'mail_contacto'},
    	{name: 'cumplida'},
        {name: 'semicumplida'},
        {name: 'emitida'},
        {name: 'giro'},
        {name: 'valor'},
        {name: 'direccion'},
        {name: 'empresa'},
        {name: 'descuento'},
        {name: 'pretotal'},
        {name: 'lote'},
        {name: 'iva'},
        {name: 'neto'},
        {name: 'afecto'},
        {name: 'total'},
        {name: 'transportista'},
        {name: 'observacion'},
        {name: 'fecha', type:'date',dateFormat:"Y-m-d"},
        {name: 'fecha_vencimiento', type:'date',dateFormat:"Y-m-d"},
        {name: 'val_real'}      

    ]
});