Ext.define('Infosys_web.store.Tipo_documentos.Selector', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"FACTURA"},
        {"id":"2", "nombre":"BOLETA"},
     	{"id":"3", "nombre":"GUIA DESPACHO"}           
    ]
});