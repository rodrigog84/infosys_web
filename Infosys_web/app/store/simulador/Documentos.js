Ext.define('Infosys_web.store.simulador.Documentos', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.simulador.Documento',
    autoLoad: false,

    proxy: {
        type: 'ajax',
        actionMethods: {
            read: 'POST'
        },
        url: preurl + 'simulador_intereses/getDocumentosImpagos',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
