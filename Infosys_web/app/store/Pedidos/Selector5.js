Ext.define('Infosys_web.store.Pedidos.Selector5', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"TODOS"},
        {"id":"2", "nombre":"PENDIENTE AUTORIZACION CLIENTE"},
        {"id":"3", "nombre":"PENDIENTE PRODUCCION"},
        {"id":"4", "nombre":"EN PRODUCCION"},
        {"id":"5", "nombre":"PEDIDO PARCIALMENTE TERMINADO"},   
        {"id":"6", "nombre":"PEDIDO TERMINADO"},    
    ]
});