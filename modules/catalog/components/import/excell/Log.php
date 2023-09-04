<?php
namespace common\modules\catalog\components\import\excell;

class Log {

    private static $instance = null;

    private static $messages = [];

    private static $issetError = 0;

    /**
     * @return Log
     */
    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add($message,$type = '') {
        self::$messages[] = $message;
        if ($type == 'error') self::$issetError = 1;
    }

    public function getAll() {
        return self::$messages;
    }

    public function getIssetError() {
        return self::$issetError;
    }

    public function setIssetError($n) {
        self::$issetError = $n;
    }

    private function __clone() {}

    private function __construct() {}

}