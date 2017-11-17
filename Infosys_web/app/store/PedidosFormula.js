Ext.define('Infosys_web.store.PedidosFormula', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Formulas',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
         actionMethods:  {
            read: 'POST'
         },
        api: {
            //create: preurl + 'pedidos/save', 
            read: preurl + 'pedidos/formulas',
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