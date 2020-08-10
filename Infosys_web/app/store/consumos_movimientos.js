Ext.define('Infosys_web.store.consumos_movimientos', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Consumo_diario',
    autoLoad: true,
    //pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            //create: preurl + 'facturas/save', 
            read: preurl + 'detalle_movimiento/getAll2',
            //update: preurl + 'facturas/update'
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