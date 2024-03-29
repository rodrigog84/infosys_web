Ext.define('Infosys_web.view.clientes.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.clientesprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Clientes',
    store: 'Clientes',
    height: 500,
    viewConfig: {
        forceFit: true,
        style: 'background-color: ##00FF00;'
    },
    columns: [{
        header: "Id",
        width: 390,
        dataIndex: 'idctacte',
        hidden:true
        
    },{
        header: "Id",
        width: 390,
        dataIndex: 'id',
        hidden:true
        
    },{
        header: "Razon Social",
        width: 390,
        dataIndex: 'nombres'
        
    },{
        header: "Rut",
        flex: 1,
        dataIndex: 'rut',
        align: 'right'
    },{
        header: "Direccion",
         width: 390,
        dataIndex: 'direccion'
    },{
        header: "Giro",
        flex: 1,
        dataIndex: 'giro'
    },{
        header: "Ciudad",
        flex: 1,
        dataIndex: 'nombre_ciudad'
    },{
        header: "Comuna",
        flex: 1,
        dataIndex: 'nombre_comuna',
        hidden: true
    },{
        header: "Telefono",
        flex: 1,
        dataIndex: 'fono',
         hidden: true
    },{
        header: "E-Mail",
        flex: 1,
        dataIndex: 'e_mail',
         hidden: true
    },{
        header: "Descuento %",
        flex: 1,
        dataIndex: 'descuento',
         hidden: true
    },{
        header: "Vendedor",
        flex: 1,
        dataIndex: 'nombre_vendedor',
         hidden: true
    },{
        header: "Condicion Pago",
        flex: 1,
        dataIndex: 'nom_id_pago',
        hidden: true
    },{
        header: "Dias Mora Permitido",
        flex: 1,
        dataIndex: 'dias_mora_permitido',
        hidden: true
    },{
        header: "Acuerdo",
        width: 130,
        dataIndex: 'tipo_acuerdo',
        renderer: function(value){
            if (value == 0) {
                return 'Sin Acuerdo';
            }
            if (value == 1) {
                return 'Persona Natural';
            }
            if (value == 2) {
            return 'Representante';   
            }
           
        },
        hidden: true
    },{
        header: "Cupo Disponible",
        flex: 1,
        dataIndex: 'cupo_disponible',
        hidden: true
    },{
        header: "Impuesto Adicional",
        flex: 1,
        dataIndex: 'imp_adicional',
        hidden: true
    },{
        header: "Estado",
        flex: 1,
        dataIndex: 'estado',
        renderer: function(value){
            if (value == 1) {
                return 'VIGENTE';
            }
            if (value == 2) {
             //return '<img src="http://localhost:999/rutaimg.jpg" />'
               return 'INACTIVO';   
            }
            if (value == 3) {
             //return '<img src="http://localhost:999/rutaimg.jpg" />'
               return 'BLOQUEADO';   
            }
            if (value == 4) {
             //return '<img src="http://localhost:999/rutaimg.jpg" />'
               return 'PROTESTO VIGENTE';   
            }
            if (value == 5) {
             //return '<img src="http://localhost:999/rutaimg.jpg" />'
               return 'AUTORIZADO';   
            }
        }
    },{
            header: "Ver Cartola",
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver Cartola',
                handler: function(grid, rowIndex, colIndex) {

                    var rec = grid.getStore().getAt(rowIndex);
                    console.log(rec);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('clientesprincipal');
                    vista.fireEvent('verCartola',rec,2)
                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    if(record.get('idctacte') == ""){
                        return false;
                    }else{
                        return true;
                    }
                }                 
            }]
    }
   
    ],
    
    initComponent: function() {
        var me = this

        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'textfield',
                //anchor: '80%',
                fieldLabel: 'Usuario',
                name: 'Id_usuario',
                itemId: 'IdUsuario',
                hidden: true
                //hidden: true
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'validar',
                text : 'Agregar'
            },{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'editarclientes',
                text : 'Editar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'eliminarclientes',
                text : 'Eliminar'
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelclientes'
            },{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-exel',
                text: 'EXCEL Seguros',
                action:'exportarexcelseguros'
            },'->',{
                xtype: 'button',
                iconCls : 'icon-search',
                text: 'Acuero de Pago',
                action:'acuerdodepago'
            },{
                xtype: 'button',
                iconCls : 'icon-search',
                text: 'Contactos',
                action:'despliegacontactosclientes'
            },{
                xtype: 'button',
                iconCls : 'icon-search',
                text: 'Sucursales',
                action:'despliegasucursales'
            },{
                xtype: 'combo',
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'clientes.Selector'
            },{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarclientes',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarclientes',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Clientes',
            displayInfo: true
        }];
        
        this.callParent(arguments);
         this.on('render', this.loadStore, this);
    },
    loadStore: function() {
        this.getStore().load();
    }      
});
