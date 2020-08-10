
Ext.define('Infosys_web.model.Consumo_diario', {
    extend: 'Ext.data.Model',
    fields: [
    	{name: 'id', type: 'auto'},
        {name: 'id_producto'},
        {name: 'id_existencia'},
        {name: 'id_tipom'},
        {name: 'id_tipomd'},
        {name: 'id_bodegaent'},
        {name: 'cantidad', decimalPrecision:4},
        {name: 'cantidad_real', decimalPrecision:4},
        {name: 'id_bodega'},
        {name: 'precio', decimalPrecision:3},
        {name: 'valor_compra', decimalPrecision:3},
        {name: 'valor', decimalPrecision:3},
        {name: 'codigo'},
        {name: 'nom_producto'},
        {name: 'lote'},
        {name: 'fecha', type:'date',dateFormat:"Y-m-d"},
        {name: 'fecha_vencimiento', type:'date',dateFormat:"Y-m-d"}
    ]
});