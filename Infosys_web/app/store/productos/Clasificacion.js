Ext.define('Infosys_web.store.productos.Clasificacion', {
    extend: 'Ext.data.Store',
	fields: ['id', 'nombre'],
    data : [
        {"id":"1", "nombre":"Materia Prima"},
        {"id":"2", "nombre":"Producto Terminado"},
        {"id":"4", "nombre":"Producto Fabricado"},
        {"id":"3", "nombre":"Todos"}
           
    ]
});