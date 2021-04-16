<?php

$sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'multi_banner`';

if (Db::getInstance()->execute($sql) == false) {
    return false;
}