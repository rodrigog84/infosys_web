Ext.define('Infosys_web.store.transportista', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.transportista',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            create: preurl + 'transportistas/save', 
            read: preurl + 'transportistas/getAll',
            update: preurl + 'transportistas/update'
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