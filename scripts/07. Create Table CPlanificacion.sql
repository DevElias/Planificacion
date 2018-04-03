
CREATE TABLE cplanificacion (
   id INT(10) NOT NULL AUTO_INCREMENT,
   id_ano INT(10) NOT NULL COMMENT 'Id Ano',   
   id_pais INT(10) DEFAULT NULL COMMENT 'Id pais',
   id_sede INT(10) DEFAULT NULL COMMENT 'Id Sede',
   edit_plan_enero INT(1) DEFAULT 1 COMMENT 'Edit plan Enero',
   edit_real_enero INT(1) DEFAULT 1 COMMENT 'Edit real Enero',
   edit_plan_febrero INT(1) DEFAULT 1 COMMENT 'Edit plan Febrero',
   edit_real_febrero INT(1) DEFAULT 1 COMMENT 'Edit real Febrero',
   edit_plan_marzo INT(1) DEFAULT 1 COMMENT 'Edit plan Marzo',
   edit_real_marzo INT(1) DEFAULT 1 COMMENT 'Edit real Marzo',
   edit_plan_abril INT(1) DEFAULT 1 COMMENT 'Edit plan Abril',
   edit_real_abril INT(1) DEFAULT 1 COMMENT 'Edit real Abril',
   edit_plan_mayo INT(1) DEFAULT 1 COMMENT 'Edit plan Mayo',
   edit_real_mayo INT(1) DEFAULT 1 COMMENT 'Edit real Mayo',
   edit_plan_junio INT(1) DEFAULT 1 COMMENT 'Edit plan Junio',
   edit_real_junio INT(1) DEFAULT 1 COMMENT 'Edit real Junio',
   edit_plan_julio INT(1) DEFAULT 1 COMMENT 'Edit plan Julio',
   edit_real_julio INT(1) DEFAULT 1 COMMENT 'Edit real Julio',
   edit_plan_agosto INT(1) DEFAULT 1 COMMENT 'Edit plan Agosto',
   edit_real_agosto INT(1) DEFAULT 1 COMMENT 'Edit real Agosto',
   edit_plan_septiembre INT(1) DEFAULT 1 COMMENT 'Edit plan Septiembre',
   edit_real_septiembre INT(1) DEFAULT 1 COMMENT 'Edit real Septiembre',
   edit_plan_octubre INT(1) DEFAULT 1 COMMENT 'Edit plan Octubre',
   edit_real_octubre INT(1) DEFAULT 1 COMMENT 'Edit real Octubre',
   edit_plan_noviembre INT(1) DEFAULT 1 COMMENT 'Edit plan Noviembre',
   edit_real_noviembre INT(1) DEFAULT 1 COMMENT 'Edit real Noviembre',
   edit_plan_diciembre INT(1) DEFAULT 1 COMMENT 'Edit plan Diciembre',
   edit_real_diciembre INT(1) DEFAULT 1 COMMENT 'Edit real Diciembre',
   id_creator INT(10) UNSIGNED NOT NULL COMMENT 'Id creator',
   id_updater INT(10) UNSIGNED NOT NULL COMMENT 'Id updater',
   date_insert DATETIME NOT NULL COMMENT 'Date insert',
   date_update DATETIME DEFAULT NULL COMMENT 'last date update',
   situation INT(1) DEFAULT 1 COMMENT 'Activo ou Inactivo',
   deleted INT(1) DEFAULT 0 COMMENT 'Deleted',
  PRIMARY KEY (id),
  FOREIGN KEY (id_ano) REFERENCES ano(id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table of Create planificacion';