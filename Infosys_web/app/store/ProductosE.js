Ext.define('Infosys_web.store.ProductosE', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Producto',
    autoLoad: true,
    pageSize: 10,
    
    proxy: {
        type: 'ajax',

        api: {
            create: preurl + 'productosfact/save', 
            read: preurl + 'productosfact/getAllExiste',
            update: preurl + 'productosfact/update'
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