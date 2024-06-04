INSERT INTO `accesos` (`codigo`, `reg_estado`, `descripcion`) VALUES ('vyf_cert_digital', 1, 'ventas y facturacion->facturacion electronica->carga certificado digital');
CREATE TABLE `param_fe` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(30) NULL DEFAULT NULL,
	`valor` VARCHAR(100) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `param_fe` (`nombre`) VALUES ('cert_password');

CREATE TABLE `caf` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`tipo_caf` INT(11) NULL DEFAULT '0',
	`fd` INT(11) NULL DEFAULT '0',
	`fh` INT(11) NULL DEFAULT '0',
	`archivo` VARCHAR(50) NULL DEFAULT NULL,
	`caf_content` TEXT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


CREATE TABLE `folios_caf` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`folio` INT(11) NULL DEFAULT '0',
	`idcaf` INT(11) NOT NULL DEFAULT '0',
	`estado` ENUM('P','T','O') NOT NULL DEFAULT 'P' COMMENT 'P: pendiente (está libre para ocupar). T: Tomado (existe una factura en el momento que está generando con ese folio). O: Ocupado (Ya se usó el folio)',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `param_fe` (`nombre`, `valor`) VALUES ('rut_empresa', '76369594-8');
INSERT INTO `tipo_documento` (`id`, `descripcion`, `correlativo`) VALUES (101, 'FACTURA ELECTRONICA', 0);

INSERT INTO `param_fe` (`nombre`) VALUES ('cert_password_encrypt');


CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rut` int(11) DEFAULT '0',
  `dv` char(1) DEFAULT '0',
  `razon_social` varchar(100) DEFAULT '',
  `giro` varchar(100) DEFAULT '',
  `cod_actividad` int(11) DEFAULT '0',
  `dir_origen` varchar(100) DEFAULT '',
  `comuna_origen` varchar(100) DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `empresa` (`id`, `rut`, `dv`, `razon_social`, `giro`, `cod_actividad`, `dir_origen`, `comuna_origen`, `created_at`, `updated_at`) VALUES
	(1, 76369594, '8', 'SERVICIOS INTEGRALES INFOSYS SPA', 'Insumos de Computacion', 726000, '4 Poniente 0280', 'Talca', '2016-01-12 17:02:14', '2016-01-12 17:02:14');


ALTER TABLE `folios_caf`
	ADD COLUMN `dte` TEXT NOT NULL DEFAULT '' AFTER `estado`;
ALTER TABLE `folios_caf`
	ADD COLUMN `idfactura` INT NOT NULL AFTER `dte`;
ALTER TABLE `folios_caf`
	ADD COLUMN `archivo_dte` VARCHAR(50) NOT NULL AFTER `dte`;
ALTER TABLE `folios_caf`
	ADD COLUMN `path_dte` VARCHAR(50) NOT NULL AFTER `dte`;		

ALTER TABLE `folios_caf`
	ADD COLUMN `pdf` VARCHAR(50) NOT NULL AFTER `archivo_dte`;

/******************** FIN PRIMERA SUBIDA **************************/	

ALTER TABLE `empresa`
	ADD COLUMN `fec_resolucion` DATE NULL DEFAULT NULL AFTER `comuna_origen`;
ALTER TABLE `empresa`
	ADD COLUMN `nro_resolucion` INT NULL DEFAULT NULL AFTER `fec_resolucion`;
ALTER TABLE `empresa`
	ADD COLUMN `logo` VARCHAR(50) NULL DEFAULT NULL AFTER `nro_resolucion`;

ALTER TABLE `folios_caf`
	ADD COLUMN `pdf_cedible` VARCHAR(50) NOT NULL AFTER `pdf`;	
ALTER TABLE `folios_caf`
	ADD COLUMN `trackid` VARCHAR(30) NOT NULL AFTER `pdf_cedible`;		

CREATE TABLE `tipo_caf` (
	`id` INT(11) NOT NULL,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
;


INSERT INTO `tipo_caf` (`id`, `nombre`) VALUES (33, 'Factura Electrónica');
INSERT INTO `tipo_caf` (`id`, `nombre`) VALUES (34, 'Factura No Afecta Electrónica');
INSERT INTO `tipo_caf` (`id`, `nombre`) VALUES (56, 'Nota de Débito Electrónica');
INSERT INTO `tipo_caf` (`id`, `nombre`) VALUES (61, 'Nota de Crédito Electrónica');


/**************************************************/

CREATE TABLE `dte_proveedores` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idproveedor` INT(11) NULL DEFAULT '0',
	`dte` TEXT NOT NULL,
	`path_dte` VARCHAR(50) NOT NULL,
	`archivo_dte` VARCHAR(50) NOT NULL,
	`envios_recibos` TEXT NOT NULL,
	`recepcion_dte` TEXT NOT NULL,
	`resultado_dte` TEXT NOT NULL,
	`arch_env_rec` VARCHAR(50) NOT NULL,
	`arch_rec_dte` VARCHAR(50) NOT NULL,
	`arch_res_dte` VARCHAR(50) NOT NULL,
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
AUTO_INCREMENT=1
;


/*******************************************************/
ALTER TABLE `dte_proveedores`
	ADD COLUMN `fecha_documento` DATE NOT NULL AFTER `arch_res_dte`;

/**********************************************************/

INSERT INTO `tipo_documento` (`id`, `descripcion`, `correlativo`) VALUES (102, 'NOTAS DE CREDITO ELECTRONICA', 1);

/******************************************************************/

INSERT INTO `correlativos` (`id`, `nombre`, `correlativo`) VALUES (19, 'FACTURA EXENTA', 0);
ALTER TABLE `factura_clientes`
	ADD COLUMN `forma` INT(11) NOT NULL AFTER `estado`;
INSERT INTO `tipo_documento` (`id`, `descripcion`) VALUES (19, 'FACTURA EXENTA');	

CREATE TABLE `detalle_factura_glosa` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`glosa` VARCHAR(300) NOT NULL,
	`id_factura` INT(11) NOT NULL,
	`id_guia` INT(11) NOT NULL,
	`num_guia` TINYINT(10) NOT NULL,
	`neto` INT(10) NOT NULL,
	`iva` INT(10) NOT NULL,
	`total` INT(10) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
/***********************************************************************************/
INSERT INTO `tipo_documento` (`id`, `descripcion`) VALUES (103, 'FACTURA EXENTA ELECTRONICA');

/*************************************************************************************/

INSERT INTO `param_fe` (`nombre`, `valor`) VALUES ('envio_sii', 'manual');



/******************************************************************************************/
CREATE TABLE `contribuyentes_autorizados_1` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`rut` INT(11) NULL DEFAULT NULL,
	`dv` CHAR(1) NULL DEFAULT NULL,
	`razon_social` VARCHAR(250) NULL DEFAULT NULL,
	`nro_resolucion` INT(11) NULL DEFAULT NULL,
	`fec_resolucion` DATE NULL DEFAULT NULL,
	`mail` VARCHAR(100) NULL DEFAULT NULL,
	`url` VARCHAR(250) NULL DEFAULT NULL,
	`fecha` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;



CREATE TABLE `contribuyentes_autorizados_2` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`rut` INT(11) NULL DEFAULT NULL,
	`dv` CHAR(1) NULL DEFAULT NULL,
	`razon_social` VARCHAR(250) NULL DEFAULT NULL,
	`nro_resolucion` INT(11) NULL DEFAULT NULL,
	`fec_resolucion` DATE NULL DEFAULT NULL,
	`mail` VARCHAR(100) NULL DEFAULT NULL,
	`url` VARCHAR(250) NULL DEFAULT NULL,
	`fecha` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


CREATE TABLE `log_cargas_bases_contribuyentes` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre_archivo` VARCHAR(100) NULL DEFAULT NULL,
	`ruta` VARCHAR(50) NULL DEFAULT NULL,
	`fecha_carga` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
;


INSERT INTO `param_fe` (`nombre`, `valor`) VALUES ('tabla_contribuyentes', 'contribuyentes_autorizados_2');

CREATE TABLE `contribuyentes_autorizados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`rut` VARCHAR(20) NOT NULL DEFAULT '0',
	`razon_social` VARCHAR(500) NULL DEFAULT NULL,
	`nro_resolucion` VARCHAR(50) NULL DEFAULT NULL,
	`fec_resolucion` VARCHAR(50) NULL DEFAULT NULL,
	`mail` VARCHAR(100) NULL DEFAULT NULL,
	`url` VARCHAR(250) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



#post_max_size 30


/********************************************************/

CREATE TABLE `log_libros` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`mes` INT(11) NULL DEFAULT NULL,
	`anno` INT(11) NULL DEFAULT NULL,
	`tipo_libro` ENUM('COMPRA','VENTA') NULL DEFAULT NULL,
	`archivo` VARCHAR(50) NULL DEFAULT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


/**************************************************************/
CREATE TABLE `email_fe` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`email_contacto` VARCHAR(50) NOT NULL DEFAULT '0',
	`pass_contacto` VARCHAR(50) NOT NULL DEFAULT '0',
	`tserver_contacto` ENUM('smtp','imap') NOT NULL,
	`port_contacto` INT(11) NOT NULL DEFAULT '0',
	`host_contacto` VARCHAR(250) NOT NULL DEFAULT '0',
	`email_intercambio` VARCHAR(50) NOT NULL DEFAULT '0',
	`pass_intercambio` VARCHAR(50) NOT NULL DEFAULT '0',
	`tserver_intercambio` ENUM('smtp','imap') NOT NULL,
	`port_intercambio` INT(11) NOT NULL DEFAULT '0',
	`host_intercambio` VARCHAR(250) NOT NULL DEFAULT '0',
	`created_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
 /******************************************************************/
 INSERT INTO `tipo_documento` (`id`, `descripcion`, `correlativo`) VALUES (104, 'NOTA DE DEBITO ELECTRONICA', 0);
 INSERT INTO `tipo_documento` (`id`, `descripcion`, `correlativo`) VALUES (16, 'NOTAS DE DEBITO', 0);


 /************************************* DESDE AQUI ******************************************/

 INSERT INTO `tipo_caf` (`id`, `nombre`) VALUES (52, 'Guia de Despacho Electrónica');
INSERT INTO `tipo_documento` (`id`, `descripcion`) VALUES (105, 'GUIA DE DESPACHO ELECTRONICA');


/*****************************************************************************************************/

INSERT INTO `tipo_documento` (`id`, `descripcion`, `correlativo`) VALUES ('120', 'BOLETA ELECTRONICA', '0');
INSERT INTO `infosys_web`.`tipo_caf` (`id`, `nombre`) VALUES ('39', 'Boleta Electrónica');



/*****************************************************************************************/

ALTER TABLE `empresa`
	CHANGE COLUMN `giro` `giro` TEXT NULL DEFAULT '' AFTER `razon_social`;

ALTER TABLE `empresa`
	ADD COLUMN `texto_fono` VARCHAR(100) NULL DEFAULT '' AFTER `comuna_origen`;

UPDATE `empresa` SET `dir_origen`='CASA MATRIZ :AVENIDA LAS RASTRAS N.1218' WHERE  `id`=1;
UPDATE `empresa` SET `texto_fono`='Fono: 712245932 - 712245933 - Fono Fax: 712247717 Casilla 466' WHERE  `id`=1;
ALTER TABLE `empresa`
	ADD COLUMN `texto_sucursales` TEXT NULL DEFAULT '' AFTER `texto_fono`;
UPDATE `empresa` SET `dir_origen`='Casa Matriz:  Avenida Las Rastras N° 1218' WHERE  `id`=1;


UPDATE `empresa` SET `texto_sucursales`='Sucursales: Avenida Las Rastras No 948 - Talca\r\nKM. 4 Ruta T-804 Ralicura - Rio Bueno' WHERE  `id`=1;


/*****************************************************************/
CREATE TABLE `consumo_folios` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`fecha` DATE NULL DEFAULT NULL,
	`cant_folios` INT(11) NULL DEFAULT NULL,
	`folio_desde` INT(11) NULL DEFAULT NULL,
	`folio_hasta` INT(11) NULL DEFAULT NULL,
	`path_consumo_folios` VARCHAR(50) NULL DEFAULT NULL,
	`archivo_consumo_folios` VARCHAR(50) NULL DEFAULT NULL,
	`xml` TEXT NULL,
	`trackid` VARCHAR(30) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;


ALTER TABLE `empresa`
	ADD COLUMN `fec_inicio_boleta` DATE NULL DEFAULT NULL AFTER `logo`;

UPDATE `empresa` SET `fec_inicio_boleta`='2020-09-01' WHERE  `id`=1;
ALTER TABLE `folios_caf`
	ADD COLUMN `id_consumo_folios` INT(11) NOT NULL AFTER `idfactura`;


/***********************************************/

ALTER TABLE `observacion_facturas`
	ADD COLUMN `destino` VARCHAR(50) NOT NULL AFTER `fono`;


/**************************************************/

ALTER TABLE `factura_clientes`
	ADD COLUMN `num_pedido` VARCHAR(50) NOT NULL AFTER `orden_compra`;



/******************************************************/

ALTER TABLE `clientes`
	ADD COLUMN `dias_mora_permitido` INT NOT NULL DEFAULT 0 AFTER `fecha_acuerdo`;

/*******************************************************************/
ALTER TABLE `factura_clientes`
	ADD COLUMN `impuesto` INT(20) NOT NULL AFTER `iva`;

/*******************************************************************/

ALTER TABLE `detalle_factura_cliente`
	ADD COLUMN `id_guia` INT(11) NOT NULL AFTER `id_notacredito`,
	ADD COLUMN `num_guia` INT(11) NOT NULL AFTER `id_guia`;


/**********************************************************************/

ALTER TABLE `detalle_factura_glosa`
	CHANGE COLUMN `glosa` `glosa` VARCHAR(500) NOT NULL COLLATE 'latin1_swedish_ci' AFTER `id_producto`;


/*****************************************************************************/


CREATE TABLE `movimiento_cuenta_corriente_parcial` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`numcomprobante` INT(11) NULL DEFAULT NULL,
	`tipo` ENUM('INGRESO','EGRESO','REGULARIZACION') NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`proceso` ENUM('CANCELACION','DEPOSITO','OTRO') NOT NULL COLLATE 'latin1_swedish_ci',
	`finalizado` TINYINT(4) NOT NULL DEFAULT '0',
	`glosa` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`fecha` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



CREATE TABLE `detalle_mov_cuenta_corriente_parcial` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idmov` INT(11) NOT NULL DEFAULT '0',
	`idcuenta` INT(11) NOT NULL DEFAULT '0',
	`tipodocumento` INT(11) NOT NULL DEFAULT '0',
	`documento` INT(11) NOT NULL DEFAULT '0',
	`docpago` INT(11) NOT NULL DEFAULT '0',
	`glosa` VARCHAR(250) NOT NULL DEFAULT '0' COLLATE 'latin1_swedish_ci',
	`debe` BIGINT(20) NOT NULL DEFAULT '0',
	`haber` BIGINT(20) NOT NULL DEFAULT '0',
	`saldo` BIGINT(20) NOT NULL DEFAULT '0',
	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;



/********************************************************************************************************/


ALTER TABLE `movimiento_cuenta_corriente_parcial`
	ADD COLUMN `idctacte` INT(11) NOT NULL DEFAULT '0' AFTER `id`;

	
/***************************************************************************/
INSERT INTO `tipo_documento` (`id`, `descripcion`, `correlativo`) VALUES ('106', 'LIQUIDACION FACTURA', '0');
INSERT INTO `correlativos` (`id`, `nombre`, `correlativo`, `hasta`, `fecha_venc`) VALUES ('106', 'LIQUIDACION FACTURA', '0', '50000', '2025-12-31');



/*******************************************************************************/

CREATE TABLE `param_cc` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(30) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`valor` VARCHAR(100) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



INSERT INTO `infosys_web`.`param_cc` (`nombre`) VALUES ('tasa_interes');
INSERT INTO `infosys_web`.`param_cc` (`nombre`) VALUES ('dias_cobro');



/***********************************************************************/

INSERT INTO `infosys_web`.`accesos` (`codigo`, `reg_estado`, `descripcion`) VALUES ('cc_tg_autorizacion', '1', 'cuentas corrientes->tablas generales->clave_autorizacion');
INSERT INTO `infosys_web`.`rol_acceso` (`id_rol`, `id_acceso`) VALUES ('1', '108');

CREATE TABLE `clave_acceso_dinamica` (
	`clave` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`fec_genera` DATETIME NULL DEFAULT NULL,
	`fec_caducidad` DATETIME NULL DEFAULT NULL,
	`usuario` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci'
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


/***************************************************************************/


CREATE TABLE `tipo_gasto` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(250) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`activo` TINYINT(4) NULL DEFAULT '1',
	`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `tipo_gasto` (`nombre`, `activo`, `created_at`) VALUES ('COMISION COMERCIALIZACION DE GANADO', 1, '2023-03-29 14:51:06');
INSERT INTO `tipo_gasto` (`nombre`, `activo`, `created_at`) VALUES ('DIFERENCIA DE PRECIO', 1, '2023-03-29 14:51:13');
INSERT INTO `tipo_gasto` (`nombre`, `activo`, `created_at`) VALUES ('RECUPERACION DE GASTO DE FLETE', 1, '2023-03-29 14:51:23');
INSERT INTO `tipo_gasto` (`nombre`, `activo`, `created_at`) VALUES ('VENTA DE MATERIA PRIMA', 1, '2023-03-29 14:51:29');
INSERT INTO `tipo_gasto` (`nombre`, `activo`, `created_at`) VALUES ('INGRESOS FINANCIEROS POR MAYOR PLAZO', 1, '2023-03-29 14:51:39');
INSERT INTO `tipo_gasto` (`nombre`, `activo`, `created_at`) VALUES ('EXAMEN DE BRUCELOSIS', 1, '2023-03-29 14:51:46');



ALTER TABLE `factura_clientes`
	ADD COLUMN `idtipogasto` INT(11) NOT NULL AFTER `id_cond_venta`;

ALTER TABLE `cartola_cuenta_corriente`
	ADD COLUMN `factintereses` TINYINT NULL DEFAULT '0' AFTER `fecha`;



INSERT INTO `infosys_web`.`roles` (`id`) VALUES ('108');
UPDATE `infosys_web`.`roles` SET `nombre`='AUTORIZACION', `descripcion`='AUTORIZACION', `reg_estado`='1' WHERE  `id`=108;
		delete
		FROM 	rol_acceso
		WHERE id_acceso = 108


INSERT INTO `infosys_web`.`rol_acceso` (`id_rol`, `id_acceso`) VALUES ('108', '108');
INSERT INTO `infosys_web`.`usuario_rol` (`id_usuario`, `id_rol`) VALUES ('2', '108');
INSERT INTO `infosys_web`.`usuario_rol` (`id_usuario`, `id_rol`) VALUES ('18', '108');	


/********************************************************************************/

ALTER TABLE `cartola_cuenta_corriente`
	ADD INDEX `idctacte` (`idctacte`);

ALTER TABLE `cuenta_corriente`
	ADD INDEX `idcliente` (`idcliente`);

ALTER TABLE `detalle_mov_cuenta_corriente`
	ADD INDEX `idmovimiento` (`idmovimiento`);



/******************************************************************/
CREATE TABLE `lista_id_detalle_cta_cte` (
	`id` INT(11) NULL DEFAULT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


CREATE TABLE `lista_id_cartola_cta_cte` (
	`id` INT(11) NULL DEFAULT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

sp_quita_duplicados_cta_cte



/*******************************************************************/

ALTER TABLE `factura_clientes`
	ADD COLUMN `porccomisionganado` FLOAT NOT NULL DEFAULT 0 AFTER `descuento`,
	ADD COLUMN `porccostomayorplazo` FLOAT NOT NULL DEFAULT 0 AFTER `porccomisionganado`,
	ADD COLUMN `porcotroscargos` FLOAT NOT NULL DEFAULT 0 AFTER `porccostomayorplazo`,
	ADD COLUMN `comisionganado` INT(20) NOT NULL AFTER `porcotroscargos`,
	ADD COLUMN `costomayorplazo` INT(20) NOT NULL AFTER `comisionganado`,
	ADD COLUMN `otroscargos` INT(20) NOT NULL AFTER `costomayorplazo`;

	

/*******************************************************************************/


ALTER TABLE `productos`
	ADD COLUMN `requiere_receta` ENUM('SI','NO') NOT NULL DEFAULT 'NO' AFTER `imagen`;


/**********************************************************************************/

CREATE TABLE `pedidos_estados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `pedidos_estados` (`nombre`) VALUES ('Pedido Ingresado');
INSERT INTO `pedidos_estados` (`nombre`) VALUES ('Pedido Requiere Producción');
INSERT INTO `pedidos_estados` (`nombre`) VALUES ('Solicitud Producción Realizada');
INSERT INTO `pedidos_estados` (`nombre`) VALUES ('Termino Producción Realizada');


ALTER TABLE `pedidos`
	ADD COLUMN `tipopedido` ENUM('I','E') NOT NULL DEFAULT 'I' COMMENT 'I: Interno, E: Externo' AFTER `num_guia`;

ALTER TABLE `pedidos`
	ADD COLUMN `idestadopedido` INT NOT NULL DEFAULT '1' AFTER `tipopedido`;


CREATE TABLE `pedidos_log_estados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpedido` INT(11) NOT NULL,
	`idestado` INT(11) NOT NULL,
	`fecha` DATETIME NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


/*********************************************************************************************/


INSERT INTO `infosys_web2`.`pedidos_estados` (`id`, `nombre`) VALUES ('5', 'Pedido Sin Stock');
UPDATE `infosys_web2`.`pedidos_estados` SET `nombre`='Pedido con Stock Disponible' WHERE  `id`=5;

INSERT INTO `infosys_web2`.`pedidos_estados` (`nombre`) VALUES ('Cliente Pendiente Autorización');





/***********************************************************************************************/
INSERT INTO `accesos` (`id`, `codigo`, `reg_estado`, `descripcion`) VALUES (109, 'pro_md_autoriza', 1, 'Produccion -> Movimiento Diario -> Genera Pedidos');
INSERT INTO `infosys_web2`.`rol_acceso` (`id_rol`, `id_acceso`) VALUES ('1', '109');



/*****************************************************************************************************/


INSERT INTO `infosys_web2`.`pedidos_estados` (`id`, `nombre`) VALUES ('7', 'Pedido por Evaluar');

CREATE TABLE `pedidos_detalle_estados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

INSERT INTO `infosys_web2`.`pedidos_detalle_estados` (`nombre`) VALUES ('Producto Ingresado');
INSERT INTO `infosys_web2`.`pedidos_detalle_estados` (`nombre`) VALUES ('Producto Requiere Producción');
INSERT INTO `infosys_web2`.`pedidos_detalle_estados` (`nombre`) VALUES ('Solicitud Producción Realizada');
INSERT INTO `infosys_web2`.`pedidos_detalle_estados` (`nombre`) VALUES ('Termino Producción Realizada');
INSERT INTO `infosys_web2`.`pedidos_detalle_estados` (`nombre`) VALUES ('Pedido con Stock Disponible');


ALTER TABLE `pedidos_detalle`
	ADD COLUMN `idestadoproducto` INT(10) NOT NULL DEFAULT '0' AFTER `secuencia`;

CREATE TABLE `pedidos_detalle_log_estados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idproductodetalle` INT(11) NOT NULL,
	`idestado` INT(11) NOT NULL,
	`fecha` DATETIME NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



ALTER TABLE `pedidos_detalle`
	ADD COLUMN `id_formula` INT(11) NOT NULL AFTER `id_bodega`;

ALTER TABLE `formula_pedido`
	ADD COLUMN `id_detalle_pedido` INT(11) NOT NULL AFTER `id_pedido`;


/****************************************************************************************************/


INSERT INTO `accesos` (`id`, `codigo`, `reg_estado`, `descripcion`) VALUES (110, 'pro_md_reproprod', 1, 'Produccion -> Movimiento Diario -> Registro Produccion Productos');
INSERT INTO `infosys_web2`.`rol_acceso` (`id_rol`, `id_acceso`) VALUES ('1', '110');



/***********************************************************************************************************/

CREATE TABLE `produccion_detalle_pedidos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_produccion` INT(11) NOT NULL DEFAULT '0',
	`id_cliente` INT(11) NOT NULL,
	`id_pedido` INT(11) NOT NULL,
	`id_detalle_pedido` INT(11) NOT NULL,
	`nom_producto` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci',
	`id_producto` INT(11) NOT NULL,
	`cantidad` DECIMAL(20,2) NOT NULL,
	`cantidad_prod` DECIMAL(10,2) NOT NULL,
	`cant_real` DECIMAL(10,2) NOT NULL,
	`valor_prod` DECIMAL(10,2) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


INSERT INTO `pedidos_estados` (`id`, `nombre`) VALUES ('8', 'Pedido Finalizado');

UPDATE `pedidos_detalle_estados` SET `nombre`='Producto con Stock Disponible' WHERE  `id`=5;


/************************************************************************************************************/

ALTER TABLE `pedidos_detalle`
	ADD INDEX `id_pedido` (`id_pedido`);

	

/********************************************************************************************************/
ALTER TABLE `pedidos_detalle`
	ADD COLUMN `requierereceta` TINYINT NOT NULL DEFAULT 0 AFTER `idestadoproducto`,
	ADD COLUMN `subereceta` TINYINT NOT NULL DEFAULT 0 AFTER `requierereceta`;


	
	/************************************************************************************************/

	ALTER TABLE `pedidos_detalle`
	ADD COLUMN `nomarchivoreceta` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `subereceta`;

	ALTER TABLE `pedidos_detalle`
	ADD COLUMN `nomarchivorecetareal` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `nomarchivoreceta`;

	ALTER TABLE `pedidos_detalle`
	CHANGE COLUMN `nomarchivoreceta` `nomarchivoreceta` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci' AFTER `subereceta`,
	CHANGE COLUMN `nomarchivorecetareal` `nomarchivorecetareal` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci' AFTER `nomarchivoreceta`;



	/**************************************************************************************************/

	ALTER TABLE `pedidos`
	ADD COLUMN `ordencompra` VARCHAR(50) NOT NULL DEFAULT '1' AFTER `idestadopedido`;


	/***************************************************************************************************/

	ALTER TABLE `pedidos`
	CHANGE COLUMN `iva` `iva` INT(10) NOT NULL AFTER `neto`,
	ADD COLUMN `ubicacion` VARCHAR(250) NOT NULL AFTER `ordencompra`;
	


/********************************************************************************************************/


ALTER TABLE `produccion`
	ADD COLUMN `ciclos` VARCHAR(10) NOT NULL AFTER `lote`;

/**********************************************************************************************************/

ALTER TABLE `pedidos_detalle`
	ADD COLUMN `cantidad_solicitada` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `nroreceta`;


/**********************************************************************************************************/

ALTER TABLE `pedidos`
	ADD COLUMN `subeoc` TINYINT NOT NULL DEFAULT 0 AFTER `ordencompra`;


	ALTER TABLE `pedidos`
	ADD COLUMN `nomarchivooc` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `subeoc`;

	ALTER TABLE `pedidos`
	ADD COLUMN `nomarchivoocreal` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `nomarchivooc`;

	ALTER TABLE `pedidos`
	CHANGE COLUMN `nomarchivooc` `nomarchivooc` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci' AFTER `subeoc`,
	CHANGE COLUMN `nomarchivoocreal` `nomarchivoocreal` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci' AFTER `nomarchivooc`;



/***********************************************************************************************************/

ALTER TABLE `produccion_detalle_pedidos`
	ADD COLUMN `id_formula` INT(11) NOT NULL AFTER `id_pedido`;

	
/**********************************************************************************************************/

INSERT INTO `correlativos` (`id`, `nombre`, `correlativo`, `hasta`) VALUES (109, 'ORDEN DE COMPRA', 1, 20000);

ALTER TABLE `pedidos`
	ADD COLUMN `ordencompraint` VARCHAR(50) NOT NULL AFTER `nomarchivoocreal`;

ALTER TABLE `pedidos`
	ADD COLUMN `nomarchivoocint` VARCHAR(100) NOT NULL AFTER `ordencompraint`;


/**************************************************************************************************************/

ALTER TABLE `vendedores`
	ADD COLUMN `siglavendedor` VARCHAR(5) NOT NULL DEFAULT '' AFTER `estado`;


UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='VG' WHERE  `id`=1;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='VE' WHERE  `id`=2;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='JP' WHERE  `id`=3;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='BO' WHERE  `id`=4;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='FB' WHERE  `id`=6;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='MR' WHERE  `id`=7;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='JS' WHERE  `id`=8;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='MS' WHERE  `id`=9;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='MC' WHERE  `id`=10;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='AG' WHERE  `id`=11;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='EC' WHERE  `id`=12;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='FP' WHERE  `id`=13;
UPDATE `infosys_web`.`vendedores` SET `siglavendedor`='KL' WHERE  `id`=14;



/*******************************************************************************************************/

CREATE TABLE `pedidos_guias` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpedido` INT(11) NULL DEFAULT NULL,
	`idguia` INT(11) NULL DEFAULT NULL,
	`created_at` DATE NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;


