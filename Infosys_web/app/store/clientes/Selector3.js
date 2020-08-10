Ext.define('Infosys_web.store.clientes.Selector3', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"Nombre", "nombre":"Nombre"},
        {"id":"Rut", "nombre":"Rut"},
        {"id":"Numero", "nombre":"Numero"},
        {"id":"Producto", "nombre":"Producto"},
        {"id":"Todos", "nombre":"Todos"}           
    ]
});