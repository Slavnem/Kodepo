<?php
// ön tanımlama
define("DEF_PROJECT_NONE", 0);
define("DEF_PROJECT_ONLY_BACKUP", 1);
define("DEF_PROJECT_ONLY_ACTIVE", 2);
define("DEF_PROJECT_ACTIVE_BACKUP", 3);
define("DEF_BACKUP_NOT_AVAILABLE", 0);
define("DEF_BACKUP_AVAILABLE", 1);
define("LOCATION_DATA_ADD_NAVIGATION_FORM_CSS", "design/style/css/page/data-add/page-data-add-navigation.css");

// giriş yapılmış bazı sayfalar için otomatik tutulmuş dosya
require_once("design/page/auto/auto-import-global.php");

// veri ekleme de sayfalar arası geçiş için basit bir kontrol adlandırıcısı
$name_menu_data_add_go = "menu-data-add-go";
$name_menu_data_add_create_new_project = "menu-data-add-create-new-project";
$name_menu_data_add_create_folder = "menu-data-add-create-folder";
$name_menu_data_add_add_file = "menu-data-add-add-file";
$name_menu_data_add_delete = "menu-data-add-delete";
$name_menu_data_add_recover_backup = "menu-data-add-recover-backup";
$name_menu_data_add_new_backup = "menu-data-add-new-backup";

// diziye ait  içindeki ayraçlar
$public_name_button_name = "button_name";
$public_name_button_value = "button_value";
$public_name_button_svg = "button_svg";
$public_name_button_text = "button_text";

// veri gelip gelmediğini kontrol etmek
// kullanıcı proje sayısı
$countproject = $AutoImportGlobal->getCount(
    ConfigTable::$table_b,
    ConfigTable::$column_table_b_1,
    [ConfigTable::$column_table_b_1],
    [$AutoImportGlobal->getSession(AutoImportGlobal::$var_session_id)]
);

$array_column_querybackup = [
    ConfigTable::$column_table_b_1,
    ConfigTable::$column_table_b_12
];

$array_data_querybackup = [
    $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_id),
    ConfigTable::$enum_table_b_12_1 // yedekte olmayan projeler
];

$countproject_backup = $AutoImportGlobal->getCount(
    ConfigTable::$table_b,
    ConfigTable::$column_table_b_1,
    $array_column_querybackup,
    $array_data_querybackup
);

// aktif olan projeleri bulmak için tüm projelerden
// yedekteki projelerin sayısını çıkarıyoruz
$countproject_active = $countproject - $countproject_backup;

// menü dizileri
// hiçbir proje yok
$array_name_project_none = [
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_create_new_project,
        $public_name_button_svg => StaticSvg::$SVG_add_to_database,
        $public_name_button_text => $AutoImportGlobal->getLangData(11)
    ]
];

// yedek var ama aktif proje yok
$array_name_project_backup = [
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_delete,
        $public_name_button_svg => StaticSvg::$SVG_delete,
        $public_name_button_text => $AutoImportGlobal->getLangData(14)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_recover_backup,
        $public_name_button_svg => StaticSvg::$SVG_database_backup,
        $public_name_button_text => $AutoImportGlobal->getLangData(15)
    ]
];

// aktif proje var, yedek yok vs vs...
$array_name_project_active = [
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_create_new_project,
        $public_name_button_svg => StaticSvg::$SVG_add_to_database,
        $public_name_button_text => $AutoImportGlobal->getLangData(11)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_create_folder,
        $public_name_button_svg => StaticSvg::$SVG_folder_add,
        $public_name_button_text => $AutoImportGlobal->getLangData(12)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_add_file,
        $public_name_button_svg => StaticSvg::$SVG_file_add,
        $public_name_button_text => $AutoImportGlobal->getLangData(13)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_delete,
        $public_name_button_svg => StaticSvg::$SVG_delete,
        $public_name_button_text => $AutoImportGlobal->getLangData(14)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_new_backup,
        $public_name_button_svg => StaticSvg::$SVG_save_old_style,
        $public_name_button_text => $AutoImportGlobal->getLangData(16)
    ]
];

// aktif ve yedek var
$array_name_project_active_backup = [
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_create_new_project,
        $public_name_button_svg => StaticSvg::$SVG_add_to_database,
        $public_name_button_text => $AutoImportGlobal->getLangData(11)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_create_folder,
        $public_name_button_svg => StaticSvg::$SVG_folder_add,
        $public_name_button_text => $AutoImportGlobal->getLangData(12)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_add_file,
        $public_name_button_svg => StaticSvg::$SVG_file_add,
        $public_name_button_text => $AutoImportGlobal->getLangData(13)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_delete,
        $public_name_button_svg => StaticSvg::$SVG_delete,
        $public_name_button_text => $AutoImportGlobal->getLangData(14)
    ],
    [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_recover_backup,
        $public_name_button_svg => StaticSvg::$SVG_database_backup,
        $public_name_button_text => $AutoImportGlobal->getLangData(15)
    ]
];

// eğer yedek oluşturma sınırı aşılmamışsa
// kullanıcının yedekleme sayısı en fazla yedeklenme sayısından az ise
if($countproject_backup < $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_maxbackup)) {
    $array_name_project_active_backup[] = [
        $public_name_button_name => $name_menu_data_add_go,
        $public_name_button_value => $name_menu_data_add_new_backup,
        $public_name_button_svg => StaticSvg::$SVG_save_old_style,
        $public_name_button_text => $AutoImportGlobal->getLangData(16)
    ];
}
?>
<!-- DATA ADD NAVIGATION -->
<section id="data-add-navigation" class="data-add-navigation">
    <!-- DATA ADD NAVIGTION FORM CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo LOCATION_DATA_ADD_NAVIGATION_FORM_CSS; ?>"/>
    <!-- DATA ADD NAVIGATION FORM -->
    <form method="POST" name="<?php echo $name_menu_data_add_go; ?>"
        id="menu-data-add-form" class ="menu-data-add-form">
    <?php
        // eğer giriş yapılmışsa
        switch(isset($_SESSION[SessionManager::$session_var_id]) && $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_id)) {
            case 1:
                $tempCreateType = null;

                // projelere göre seçim ayarlaması yapma
                // hiç proje yok
                if($countproject < 1) {
                    $tempCreateType = DEF_PROJECT_NONE;
                }
                // proje var ama sadece yedek
                else if($countproject_backup > 0 && $countproject_active < 1) { 
                    $tempCreateType = DEF_PROJECT_ONLY_BACKUP;
                }
                // proje var ama sadece aktif
                else if($countproject_backup < 1 && $countproject_active > 0) {
                    $tempCreateType = DEF_PROJECT_ONLY_ACTIVE;
                }
                // hem aktif hem yedek proje var
                else if($countproject_active > 0 && $countproject_backup > 0) {
                    $tempCreateType = DEF_PROJECT_ACTIVE_BACKUP;
                }
                // bilinmeyen durum
                else {
                    return false;
                }

                // div oluştur listelemek ve daha rahat stil verebilmek için
                echo "<div id='form-data-add-button-area' class='form-data-add-button-area'>";

                // proje yoksa ve normal durum yani proje varsa
                switch($tempCreateType) {
                    // hiç proje yok
                    case DEF_PROJECT_NONE:
                        // döngü ile buton ekleme
                        foreach($array_name_project_none as $indexProjectNone => $ProjectNone) {
                            $tempButtonName = $ProjectNone[$public_name_button_name];
                            $tempButtonValue = $ProjectNone[$public_name_button_value];
                            $tempButtonSvg = $ProjectNone[$public_name_button_svg];
                            $tempButtonText = $ProjectNone[$public_name_button_text];

                            echo
                            "<button name='$tempButtonName' value='$tempButtonValue'>
                                $tempButtonSvg
                                <span>$tempButtonText</span>
                            </button>";
                        }
                    break;
                    // sadece yedek
                    case DEF_PROJECT_ONLY_BACKUP:
                        // döngü ile buton ekleme
                        foreach($array_name_project_backup as $indexProjectBackup => $ProjectBackup) {
                            $tempButtonName = $ProjectBackup[$public_name_button_name];
                            $tempButtonValue = $ProjectBackup[$public_name_button_value];
                            $tempButtonSvg = $ProjectBackup[$public_name_button_svg];
                            $tempButtonText = $ProjectBackup[$public_name_button_text];

                            echo
                            "<button name='$tempButtonName' value='$tempButtonValue'>
                                $tempButtonSvg
                                <span>$tempButtonText</span>
                            </button>";
                        }
                    break;
                    // sadece aktif
                    case DEF_PROJECT_ONLY_ACTIVE:
                        // döngü ile buton ekleme
                        foreach($array_name_project_active as $indexProjectActive => $ProjectActive) {
                            $tempButtonName = $ProjectActive[$public_name_button_name];
                            $tempButtonValue = $ProjectActive[$public_name_button_value];
                            $tempButtonSvg = $ProjectActive[$public_name_button_svg];
                            $tempButtonText = $ProjectActive[$public_name_button_text];

                            echo
                            "<button name='$tempButtonName' value='$tempButtonValue'>
                                $tempButtonSvg
                                <span>$tempButtonText</span>
                            </button>";
                        }
                    break;
                    // aktif ve yedek
                    case DEF_PROJECT_ACTIVE_BACKUP:
                        // döngü ile buton ekleme
                        foreach($array_name_project_active_backup as $indexProjectActiveBackup => $ProjectActiveBackup) {
                            $tempButtonName = $ProjectActiveBackup[$public_name_button_name];
                            $tempButtonValue = $ProjectActiveBackup[$public_name_button_value];
                            $tempButtonSvg = $ProjectActiveBackup[$public_name_button_svg];
                            $tempButtonText = $ProjectActiveBackup[$public_name_button_text];

                            echo
                            "<button name='$tempButtonName' value='$tempButtonValue'>
                                $tempButtonSvg
                                <span>$tempButtonText</span>
                            </button>";
                        }
                    break;
                }

                // div sonlandır
                echo "</div>";
            break;
            // oturumu temizle
            default:
                return $AutoImportGlobal->falseMeanCleanSession(1);
        }
    ?>
    </form>
</section>
<?php
// tıklanana göre işlem yapmak ama ilk önce kontrol
switch($_SERVER["REQUEST_METHOD"] == "POST") {
    // form var, post
    case 1:
        // POST butonların ortak name değerini al
        $action = isset($_POST[$name_menu_data_add_go]) ? $_POST[$name_menu_data_add_go] : null;
        
        switch($action) {
            // proje oluştur
            case $name_menu_data_add_create_new_project:
                switch($tempCreateType) {
                    case DEF_PROJECT_NONE: // hiç proje yok
                    case DEF_PROJECT_ONLY_ACTIVE: // sadece aktif
                    case DEF_PROJECT_ACTIVE_BACKUP: // aktif ve yedek
                        include("design/page/data-add/data-add-create-project.php");
                    break;
                }
            break;
            // klasör oluştur
            case $name_menu_data_add_create_folder:
                switch($tempCreateType) {
                    // aktif proje varsa klasör oluştursun
                    case DEF_PROJECT_ONLY_ACTIVE: // sadece aktif
                    case DEF_PROJECT_ACTIVE_BACKUP: // aktif ve yedek
                        include("design/page/data-add/data-add-create-folder.php");
                    break;
                }
            break;
            // dosya ekle
            case $name_menu_data_add_add_file:
                switch($tempCreateType) {
                    // aktif proje varsa dosya eklesin
                    case DEF_PROJECT_ONLY_ACTIVE: // sadece aktif
                    case DEF_PROJECT_ACTIVE_BACKUP: // aktif ve yedek
                        include("design/page/data-add/data-add-files.php");
                    break;
                }
            break;
            // sil
            case $name_menu_data_add_delete:
                switch($tempCreateType) {
                    // aktif ya da yedek proje varsa silebilsin
                    case DEF_PROJECT_ONLY_BACKUP: // sadece yedek
                    case DEF_PROJECT_ONLY_ACTIVE: // sadece aktif
                    case DEF_PROJECT_ACTIVE_BACKUP: // yedek ve aktif
                        include("design/page/data-add/data-add-delete.php");
                    break;
                }
            break;
            // yedekten kurtar
            case $name_menu_data_add_recover_backup:
                // belirli koşullar varsa olsun
                switch($tempCreateType) {
                    case DEF_PROJECT_ONLY_BACKUP: // sadece yedek
                    case DEF_PROJECT_ACTIVE_BACKUP: // yedek ve aktif
                        include("design/page/data-add/data-add-recover-backup.php");
                    break;
                }
            break;
            // yedekle
            case $name_menu_data_add_new_backup:
                switch($tempCreateType) {
                    // yedek oluşturabilmek için aktif proje olmak zorunda
                    case DEF_PROJECT_ONLY_ACTIVE: // sadece aktif
                    case DEF_PROJECT_ACTIVE_BACKUP: // yedek ve aktif
                        // yeni yedek oluşturabilme imkanı varsa
                        switch($countproject_backup < $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_maxbackup)) {
                            case DEF_BACKUP_AVAILABLE: // yedek oluşturma müsait
                                include("design/page/data-add/data-add-new-backup.php");
                            break;
                        }
                    break;
                }
            break;
        }

        // dosya için oluşturulmuş html alan sonu
        echo "</section>";
    break;
}
?>