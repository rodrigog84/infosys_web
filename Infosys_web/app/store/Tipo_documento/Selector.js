Ext.define('Infosys_web.store.Tipo_documento.Selector', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"101", "nombre":"FACTURA ELECTRONICA"},
        {"id":"21", "nombre":"FACTURA COMPRA"},
        {"id":"2", "nombre":"BOLETA"},
        {"id":"103", "nombre":"GUIA DESPACHO"},
        {"id":"105", "nombre":"FACTURA EXENTA"},    
    ]
});