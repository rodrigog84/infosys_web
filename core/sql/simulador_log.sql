-- ============================================================
-- Simulador de Intereses — Tabla de log de simulaciones
-- Ejecutar en la base de datos infosys_web
-- ============================================================

CREATE TABLE IF NOT EXISTS `simulador_log` (
    `id`                   INT(11)        NOT NULL AUTO_INCREMENT,
    `rut`                  VARCHAR(20)    NOT NULL,
    `nombre_cliente`       VARCHAR(200)   NOT NULL DEFAULT '',
    `fecha_simulacion`     DATE           NOT NULL,
    `tasa_interes`         DECIMAL(8,4)   NOT NULL DEFAULT 0,
    `dias_cobro`           INT(4)         NOT NULL DEFAULT 0,
    `total_saldo`          DECIMAL(15,0)  NOT NULL DEFAULT 0,
    `total_interes_neto`   DECIMAL(15,0)  NOT NULL DEFAULT 0,
    `total_interes_con_iva`DECIMAL(15,0)  NOT NULL DEFAULT 0,
    `total_pagar`          DECIMAL(15,0)  NOT NULL DEFAULT 0,
    `ids_documentos`       TEXT           NOT NULL,
    `tipo_exportacion`     VARCHAR(10)    NOT NULL DEFAULT 'PDF',
    `fecha_ejecucion`      DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id_usuario`           INT(11)        DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_rut`   (`rut`),
    KEY `idx_fecha` (`fecha_ejecucion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
