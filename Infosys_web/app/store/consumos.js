Ext.define('Infosys_web.store.consumos', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Producto',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            //create: preurl + 'facturas/save', 
            read: preurl + 'productosfact/getAllconsumos',
            //update: preurl + 'facturas/update'
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