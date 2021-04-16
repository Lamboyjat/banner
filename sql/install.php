<?php

$sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'multi_banner` (
    `id_multi_banner` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `color` VARCHAR(7) NOT NULL,
    `background_color` VARCHAR(7) NOT NULL,
    `content` TEXT NOT NULL,
    `start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` DATE NOT NULL DEFAULT 0,
    `active` TINYINT(1) NOT NULL DEFAULT 0,
    `priority` INT(3) NOT NULL DEFAULT 1,

    PRIMARY KEY (`id_multi_banner`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

if (Db::getInstance()->execute($sql) == false) {
    return false;
}