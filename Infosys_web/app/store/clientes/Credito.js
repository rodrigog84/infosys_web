Ext.define('Infosys_web.store.clientes.Credito', {
    extend: 'Ext.data.Store',
    fields: ['id', 'nombre'],
    data : [
       	{"id":"1", "nombre":"INNOMINADO"},
       	{"id":"2", "nombre":"NOMINADO"},
	 	{"id":"3", "nombre":"NORMAL"},
	 	{"id":"4", "nombre":"RELACIONADO"},
	 	{"id":"5", "nombre":"LARGO PLAZO"}
    ]
});