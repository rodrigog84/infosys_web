Ext.define('Infosys_web.model.cuentacorriente.Saldodocumentos', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'cuentacontable'},
        {name: 'documento'},
    	{name: 'fecha', type: 'date', dateFormat: 'Y-m-d'},
    	{name: 'fechavencimiento', type: 'date', dateFormat: 'Y-m-d'},
    	{name: 'saldoporvencer'},
    	{name: 'saldovencido'},
    	{name: 'dias'},
    	{name: 'saldodocto'}
    ]
});