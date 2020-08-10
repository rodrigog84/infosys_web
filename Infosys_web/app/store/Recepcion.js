Ext.define('Infosys_web.store.Recepcion', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"101", "nombre":"FACTURA ELECTRONICA"},
        {"id":"105", "nombre":"GUIA DESPACHO"},
     	{"id":"2", "nombre":"BOLETA"}           
    ]
});