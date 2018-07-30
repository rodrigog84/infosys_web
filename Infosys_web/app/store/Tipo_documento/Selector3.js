Ext.define('Infosys_web.store.Tipo_documento.Selector3', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"FACTURA"},
        {"id":"101", "nombre":"FACTURA ELECTRONICA"},
     	{"id":"106", "nombre":"FACTURA DE EXPORTACION"}, 
     	{"id":"107", "nombre":"FACTURA DE IMPORTACION"}, 
     	{"id":"108", "nombre":"FACTURA ACTIVO FIJO"}          
    ]
});