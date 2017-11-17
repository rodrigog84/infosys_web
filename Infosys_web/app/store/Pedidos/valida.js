Ext.define('Infosys_web.store.Pedidos.valida', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.pedidos.valida',
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'root'
        }
    }
});