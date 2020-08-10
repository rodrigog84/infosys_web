Ext.define('Infosys_web.store.ProductosDetalle', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Productos.Item',
    autoLoad: true,
    //pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            //create: preurl + 'productos/save', 
            read: preurl + 'compras/getAllCompras',
            //update: preurl + 'productos/update'
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