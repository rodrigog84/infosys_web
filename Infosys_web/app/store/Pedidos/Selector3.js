Ext.define('Infosys_web.store.Pedidos.Selector3', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"TODOS"},
        {"id":"2", "nombre":"PRODUCCION"},
        {"id":"3", "nombre":"DESPACHADA"}       
    ]
});