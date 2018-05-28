Ext.define('Infosys_web.store.emailautorizados', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Emailautorizados',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
        actionMethods:  {
            read: 'POST'
         },
        api: {
            create: preurl + 'emailautorizados/save', 
            read: preurl + 'emailautorizados/getAll',
            update: preurl + 'emailautorizados/update'
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