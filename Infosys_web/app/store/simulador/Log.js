Ext.define('Infosys_web.store.simulador.Log', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.simulador.Log',
    autoLoad: false,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        actionMethods: { read: 'GET' },
        url: preurl + 'simulador_intereses/getLogSimulaciones',
        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'total',
            successProperty: 'success'
        }
    }
});
