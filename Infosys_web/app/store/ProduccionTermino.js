Ext.define('Infosys_web.store.ProduccionTermino', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Produccion.Item',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
         actionMethods:  {
            read: 'POST'
         },
        api: {
            //create: preurl + 'pedidos/save', 
            read: preurl + 'produccion/producciontermino',
            //update: preurl + 'pedidos/update'
            //destroy: 'php/deletaContacto.php'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
        writer: {
            type: 'json',
            writeAllFields: true,
            encode: true,
            root: 'data'
        }
    }
});