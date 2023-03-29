Ext.define('Infosys_web.store.Tipo_gasto', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Tip_gasto',
    autoLoad: true,
    //pageSize: 14,
    
    proxy: {
        type: 'ajax',
         actionMethods:  {
            read: 'POST'
         },
        api: {
            create: preurl + 'tipo_gasto/save', 
            read: preurl + 'tipo_gasto/getAll',
            update: preurl + 'tipo_gasto/update'
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