Ext.define('Infosys_web.store.guiasdespacho.Selector', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"GENERAL", "nombre":"PENDIENTES"},
        {"id":"LIBRO GUIAS", "nombre":"LIBRO GUIAS"}
           
    ]
});