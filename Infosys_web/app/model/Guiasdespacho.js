
Ext.define('Infosys_web.model.Guiasdespacho', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'id_factura'},
        {name: 'id_cliente'},
        {name: 'id_producto'},
        {name: 'nombre_cliente'},
        {name: 'rut_cliente'},        
        {name: 'orden_compra'},
        {name: 'num_factura'},
        {name: 'id_vendedor'},
        {name: 'nom_vendedor'},
        {name: 'fecha_factura', type:'date',dateFormat:"Y-m-d"},
        {name: 'fecha_venc', type:'date',dateFormat:"Y-m-d"},
        {name: 'sub_total'},
        {name: 'descuento'},
        {name: 'neto'},
        {name: 'iva'},
        {name: 'totalfactura'},
        {name: 'cantidad'},
        {name: 'id_guia'},
        {name: 'id_guia'},
        {name: 'glosa'},
        {name: 'kilos'},
        {name: 'nombre_producto'},
        {name: 'num_guia'},
        {name: 'precios'},
        {name: 'rut_cliente'},
        {name: 'total'},
        {name: 'codigo'},
        {name: 'tipoguia'}
               
               
    ]
});