Ext.define('Infosys_web.store.formula.Items', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.formulas.Item',
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'root'
        }
    }
});