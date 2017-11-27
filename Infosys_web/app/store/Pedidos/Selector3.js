Ext.define('Infosys_web.store.Pedidos.Selector3', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"TODOS"},
        {"id":"2", "nombre":"EN PRODUCCION"},
        {"id":"3", "nombre":"PRODUCCION TERMINADA"},
        {"id":"4", "nombre":"PENDIENTES"}       
    ]
});