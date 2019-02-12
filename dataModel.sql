#-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;

USE ideasac;

#-- tabla provisoria. mensaje no tiene usuario asociado
DROP TABLE IF EXISTS `mensaje` CASCADE;
CREATE TABLE `mensaje` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `msj_is_tweet` VARCHAR(6) DEFAULT NULL, # 'true' or 'false'
  `msj_tweet_id` BIGINT UNSIGNED DEFAULT NULL, #
  `msj_tweet_username` VARCHAR( 100 ) DEFAULT NULL ,
  `msj_nombre` VARCHAR(100) DEFAULT NULL,
  `msj_email` VARCHAR(100) DEFAULT NULL,
  `msj_txt` VARCHAR(200) DEFAULT NULL,
  `msj_nfavor` INTEGER DEFAULT '0',
  `msj_ncontra` INTEGER DEFAULT '0',
  `msj_date` TIMESTAMP,  # fecha de ingreso
  `usuario_id` INTEGER DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS `usuario` CASCADE;
CREATE TABLE `usuario` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `u_twitter_auth` VARCHAR(6) DEFAULT NULL, # autentificado desde twitter, 'true' or 'false'
  `u_twitter_username` VARCHAR( 255 ) DEFAULT NULL ,
  `u_nombre` VARCHAR(100) DEFAULT NULL,
  `u_email` VARCHAR(100) DEFAULT NULL,
  `u_hpass` VARCHAR(50) DEFAULT NULL,
  `u_fecha_reg` TIMESTAMP,  # fecha de registro
  `u_tz_offset` VARCHAR(10), # timezone offset in minutes
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS `voto` CASCADE;
CREATE TABLE `voto` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `usuario_id` INTEGER NOT NULL,
  `mensaje_id` INTEGER NOT NULL,
  `v_type` VARCHAR( 10 ) DEFAULT NULL ,
  `v_date` TIMESTAMP,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS `tag` CASCADE;
CREATE TABLE `tag` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `tag` VARCHAR( 50 ) DEFAULT NULL ,
  `count` INTEGER DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- DROP TABLE IF EXISTS `mencion` CASCADE;
-- CREATE TABLE `mencion` (
--   `id` INTEGER NOT NULL AUTO_INCREMENT DEFAULT NULL,
--   `tag_id` INTEGER NOT NULL,
--   `mensaje_id` INTEGER NOT NULL,
--   PRIMARY KEY (`id`)
-- )ENGINE=InnoDB;


ALTER TABLE `mensaje` ADD FOREIGN KEY (usuario_id) REFERENCES `usuario` (`id`);
ALTER TABLE `voto` ADD FOREIGN KEY (usuario_id) REFERENCES `usuario` (`id`);
ALTER TABLE `voto` ADD FOREIGN KEY (mensaje_id) REFERENCES `mensaje` (`id`);
-- ALTER TABLE `mencion` ADD FOREIGN KEY (mensaje_id) REFERENCES `mensaje` (`id`);
-- ALTER TABLE `mencion` ADD FOREIGN KEY (tag_id) REFERENCES `tag` (`id`);

# crear un par de usuarios
INSERT INTO `usuario` (`id`, `u_twitter_auth`, `u_nombre`, `u_email`, `u_fecha_reg`) VALUES
(1, 'false','Juan', 'juan@mail.com','2012-03-13 00:00:00');

INSERT INTO `usuario` (`id`, `u_twitter_auth`, `u_nombre`, `u_email`, `u_fecha_reg`) VALUES
(2, 'false','Maria', 'maria@mail.com','2012-03-13 00:00:00');

INSERT INTO `usuario` (`id`, `u_twitter_auth`, `u_nombre`, `u_email`, `u_fecha_reg`, `u_hpass`) VALUES
(3, 'false','Pedro', 'pedro@mail.com','2012-03-13 00:00:00', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684'); # this is the sha1 for 'pass'

# crear mensajes
INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(1, 'Juan', 'juan@mail.com','Hagamos una constitución escalable, que pueda evolucionar y no ponga trabas a los cambios que el pais necesita', '2012-03-13 00:00:00', '12', '6');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(2, 'Camila', 'juana@mail.com','Educacion gratuita y de calidad debe estar garantizada ', '2012-07-12 00:00:00', '120','15');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(3, 'Sebastian', 'juana@mail.com','Estado más laico! Menos influencia política de la iglesia ', '2012-07-31 00:00:00', '3','45');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(4, 'Andrea', 'juana@mail.com','Rol del estado debe partir por garantias minimas en salud, educacion y seguridad ', '2012-07-31 00:00:00', '123','30');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(5, 'Pedro', 'juana@mail.com','Yo cambiaria el lema \"por la razon o la fuerza\", no corresponde a sociedad progresista ', '2012-07-31 00:00:00', '13','1');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(6, 'Daniel', 'mail@mail.com','Lorem ipsum dolor sit amet, consectetur adipiscing elit http://es.wikipedia.org/wiki/Asamblea_constituyente', '2012-07-31 00:00:00', '34','57');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(7, 'Mario', 'mail@mail.com','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris eget leo nunc, nec tempus mi? Curabitur id nisl mi, ut vulputate urna.', '2012-07-31 00:00:00', '21','1');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(8, 'Paz', 'mail@mail.com','Lorem ipsum dolor sit amet! Mauris eget leo nunc, nec tempus mi?', '2012-07-31 00:00:00', '56','12');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(9, 'Javier', 'mail@mail.com','Lorem ipsum dolor sit amet', '2012-07-31 00:00:00', '46','1');

INSERT INTO `mensaje` (`id`, `msj_nombre`, `msj_email`, `msj_txt`, `msj_date`, `msj_nfavor`, `msj_ncontra`) VALUES
(10, 'Maria', 'mail@mail.com','Libertad de expresión e ideológica deberían ser valores constitucionales', '2012-07-31 00:00:00', '85','0');
