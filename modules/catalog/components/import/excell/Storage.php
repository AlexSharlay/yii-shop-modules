<?php
namespace common\modules\catalog\components\import\excell;

class Storage {

    private static $instance = null;

    private static $id_parent_main = null;
    private static $id_parent_model = null;
    private static $id_kit = 1;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getMain() {
        return self::$id_parent_main;
    }

    public function getModel() {
        return self::$id_parent_model;
    }

    public function getKit() {
        return self::$id_kit;
    }


    public function setMain($id) {
        self::$id_parent_main = $id;
    }

    public function setModel($id) {
        self::$id_parent_model = $id;
    }

    public function incKit($id) {
        self::$id_kit = self::$id_kit++;
    }


    public function clear() {
        self::$id_parent_main  = null;
        self::$id_parent_model = null;
        self::$id_kit = 1;
    }

    public function getNow() {
        if (self::$id_parent_model !== null) {
            return self::$id_parent_model;
        } else {
            return self::$id_parent_main;
        }
    }

    private function __clone() {}

    private function __construct() {}

}