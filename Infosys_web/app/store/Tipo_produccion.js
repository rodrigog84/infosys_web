Ext.define('Infosys_web.store.Tipo_produccion', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"PRODUCCION"},
        {"id":"2", "nombre":"CONSUMOS"}    
    ]
});