Ext.define('Infosys_web.model.transportistas.Item', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'rut'},
        {name: 'nombre'},
        {name: 'ciudad'},        
        {name: 'camion'},
        {name: 'carro'},
        {name: 'fono'}
    ]
});