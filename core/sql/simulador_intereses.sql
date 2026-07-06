-- ============================================================
-- Simulador de Intereses - Script de registro de acceso
-- Ejecutar en la base de datos infosys_web
-- ============================================================

-- 1. Registrar el módulo en la tabla de accesos
INSERT INTO `accesos` (`codigo`, `reg_estado`, `descripcion`)
VALUES ('cc_rep_simuladorintereses', 1, 'Cuentas Corrientes -> Reportes -> Simulador de Intereses');

-- 2. Asignar el acceso a TODOS los roles existentes (ajustar según necesidad)
--    Si solo se quiere asignar a roles específicos, filtrar por id_rol
INSERT INTO `rol_acceso` (`id_rol`, `id_acceso`)
SELECT r.id, a.id
FROM roles r
CROSS JOIN accesos a
WHERE a.codigo = 'cc_rep_simuladorintereses'
  AND NOT EXISTS (
      SELECT 1 FROM rol_acceso ra
      WHERE ra.id_rol = r.id AND ra.id_acceso = a.id
  );
