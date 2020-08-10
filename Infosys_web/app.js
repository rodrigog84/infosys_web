Ext.util.Format.thousandSeparator = '.';
//variables globales

//var gbl_site = 'http://angus.agricultorestalca.cl/Infosys_web/';
var gbl_site = 'http://localhost/Infosys_web/Infosys_web/';

//var gbl_site = 'http://angus.agricultorestalca.cl/Infosys_web/';
var preurl = gbl_site + 'core/index.php/';
var preurl_img = gbl_site + 'core/archivos/';
var preurl_js = gbl_site + 'Infosys_web/';


var config_iva = 19;
var rol =  "";


Ext.application({

   controllers: ["General", "Clientes", "Proveedores", "Productos", 
                "Ventas", "Facturacion", "Inventario", "Tipo_movimientos",
                "Correlativos", "Ordencompra", "Cuentas_centralizacion", 
                  "Existencias", "Bitacora", "Tipo_movimientos_inventario",
                  "Preventa", "Pago_caja","CuentasCorrientes","Cotizacion",
                  "Recaudacion", "Notacredito","Caja", "Guiasdespacho",
                  "Comisiones","Notadebito","Tipodocumentocompras", "Precios",
                  "Facturaglosa", "Facturaganado","Pedidos","ExistenciasClientes",
                  "FacturaCompra", "Formulas", "Produccion", "Emailautorizados",
                  "Consumos","Transportistas","Pedidos2"],
	   
    views: [
        'Infosys_web.view.WLogin',
        'Infosys_web.view.Login',
        'Infosys_web.view.usuarios.WinAddEditUsuarios',
        'Infosys_web.view.usuarios.AdminUsuarios',
        'Infosys_web.view.roles.AdminRoles',
        'Infosys_web.view.roles.WinAddEditRoles',
        'Infosys_web.view.CambioPass',
        'Infosys_web.view.movimiento_diario_inventario.BuscarProductos'
    ],

    name: 'Infosys_web',
	launch: function() {
        _myAppGlobal = this;

       Ext.create('Infosys_web.view.Login');
        
    }
});