Ext.define('Infosys_web.store.Tipo_documento.Selector', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"101", "nombre":"FACTURA ELECTRONICA"},
        {"id":"107", "nombre":"FACTURA COMPRA"},
        {"id":"2", "nombre":"BOLETA"},
        {"id":"120", "nombre":"BOLETA ELECTRONICA"},
        {"id":"105", "nombre":"GUIA DESPACHO"},
        {"id":"103", "nombre":"FACTURA EXENTA ELECTRONICA"},    
    ]
});