Ext.define('Infosys_web.store.Clientefinal', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Clientefinal',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
        actionMethods:  {
            read: 'POST'
         },
        api: {
            create: preurl + 'clientefinal/save', 
            read: preurl + 'clientefinal/getAll',
            update: preurl + 'clientefinal/update'
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