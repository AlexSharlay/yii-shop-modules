<?php

/**
 * Set all application aliases.
 */

Yii::setAlias('common',     dirname(__DIR__));
Yii::setAlias('console',    dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('frontend',   dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend',    dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('mobile',     dirname(dirname(__DIR__)) . '/mobile');
Yii::setAlias('statics',    dirname(dirname(__DIR__)) . '/statics');
Yii::setAlias('root',       dirname(dirname(__DIR__)));

Yii::setAlias('theme',          dirname(dirname(__DIR__)) . '/frontend/themes/shop/assets');
Yii::setAlias('themeBackend',   dirname(dirname(__DIR__)) . '/backend/themes/shop/assets');
Yii::setAlias('themeMobile',    dirname(dirname(__DIR__)) . '/mobile/themes/shop/assets');