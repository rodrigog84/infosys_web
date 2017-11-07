Ext.define('Infosys_web.store.FormulasPedidos', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Formulas',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            //create: preurl + 'formulas/save', 
            read: preurl + 'formula/getAll2',
            //update: preurl + 'formulas/update'
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