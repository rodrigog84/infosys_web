Ext.define('Infosys_web.store.Tipoenvases', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Tipoenvase',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
        actionMethods:  {
            read: 'POST'
         },
        api: {
            create: preurl + 'tipoenvases/save', 
            read: preurl + 'tipoenvases/getAll',
            update: preurl + 'tipoenvases/update'
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