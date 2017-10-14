Ext.define('Infosys_web.model.Correlativo', {
    extend: 'Ext.data.Model',
    fields: [
    	{name: 'id'},
    	{name: 'nombre'},
    	{name: 'correlativo'},
    	{name: 'hasta'},
    	{name: 'fecha_venc', dateFormat:"Y-m-d"}
    ]
});