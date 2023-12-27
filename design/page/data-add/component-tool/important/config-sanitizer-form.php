<?php
define("STR_MIN_LENGTH", 3);

class FormSanitizer {
    protected static $characterClearName = array(
        '/','!','?','`','$','#','½','¾','{','}','[',']'
        ,'*','_','|','=','(',')','+','£','>','<','^','%','"','é'
        ,";",',','¨','~',':','\'');

    public static function sanitizeName($data) {
        // karakterleri temizle
        return str_replace(self::$characterClearName, '', $data);
    }

    public static function checkLength($data) {
        // metin uzunluğu kontrol
        return strlen($data) > STR_MIN_LENGTH ? (true) : (false);
    }
}
?>
