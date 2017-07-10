Ext.define('Infosys_web.store.facturapedidos', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.pedidos.Item',
    autoLoad: true,
    //pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            //create: preurl + 'pedidos/save', 
            read: preurl + 'pedidos/pedidosdetalle',
            //update: preurl + 'pedidos/update'
            //destroy: 'php/deletaContacto.php'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success',
        },
        writer: {
            type: 'json',
            writeAllFields: true,
            encode: true,
            root: 'data'
        }
    }
});