<?php
// dosya adres
define("FILE_LOCATION_AJAX", "include/ajax/ajax-libs-jquery-3-6-4-jquery-min.js");
// eklenecek dosyaların özelliklerini belirten değişkenler
define("FILE_IMPORT", "file-import");
define("PROJECT_NAME", "project-name");
define("PROJECT_COMMENT", "project-comment");
define("FILE_TEMP_NAME", "file-temp-name");
define("FILE_TYPE", "file-type");
define("FILE_SUBMIT", "file-submit");
define("FILE_RESET", "file-reset");
define("FILE_DELETE", "file-delete");
define("FILE_SUBMITED", "file-submited");
define("FILE_STORE_CODE_UPLOAD", "store/code/upload/");
define("FILE_LINK_CODE_UPLOAD", "http://192.168.1.109:7600/store/code/upload/");
define("FILE_SUBMIT_CREATE_PROJECT", "file-submit-create-project");

// BYTE
define("FILE_MAX_SIZE_AUTO", 10485760); // 10 MB
define("FILE_MAX_SIZE_RANK_1", 26214400); // 25 MB
define("FILE_MAX_SIZE_RANK_2", 41943040); // 40 MB
define("FILE_MAX_SIZE_RANK_3", 52428800); // 50 MB
define("FILE_MAX_SIZE_RANK_4", 73400320); // 70 MB
define("FILE_MAX_SIZE_RANK_5", 104857600); // 100 MB
define("FILE_MAX_SIZE_RANK_6", 157286400); // 150 MB
define("FILE_MAX_SIZE_RANK_7", 209715200); // 200 MB

// BACKUP LIMIT
define("FILE_MAX_BACKUP_AUTO", 2); // 10 MB
define("FILE_MAX_BACKUP_1", 3); // 25 MB
define("FILE_MAX_BACKUP_2", 4); // 40 MB
define("FILE_MAX_BACKUP_3", 5); // 50 MB
define("FILE_MAX_BACKUP_4", 6); // 70 MB
define("FILE_MAX_BACKUP_5", 8); // 100 MB
define("FILE_MAX_BACKUP_6", 10); // 150 MB
define("FILE_MAX_BACKUP_7", 12); // 200 MB

// Dosyalara ilişkin verileri için sınıf
class StaticFile {
    public static $ARRAY_FILEMAX_RANK = [
        FILE_MAX_SIZE_AUTO,
        FILE_MAX_SIZE_RANK_1,
        FILE_MAX_SIZE_RANK_2,
        FILE_MAX_SIZE_RANK_3,
        FILE_MAX_SIZE_RANK_4,
        FILE_MAX_SIZE_RANK_5,
        FILE_MAX_SIZE_RANK_6,
        FILE_MAX_SIZE_RANK_7
    ];

    public static $ARRAY_FILEMAX_BACKUP = [
        FILE_MAX_BACKUP_AUTO,
        FILE_MAX_BACKUP_1,
        FILE_MAX_BACKUP_2,
        FILE_MAX_BACKUP_3,
        FILE_MAX_BACKUP_4,
        FILE_MAX_BACKUP_5,
        FILE_MAX_BACKUP_6,
        FILE_MAX_BACKUP_7
    ];
}
?>