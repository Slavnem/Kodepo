<?php
define("PROJECT_ONLY_ACTIVE", 0);
define("PROJECT_ONLY_BACKUP", 1);
define("PROJECT_ACTIVE_BACKUP", 2);

// Kullanıcı adını URL'den al
$get_username = isset($_GET[USERS_USER_DETAIL_SHORT]) ? (htmlspecialchars($_GET[USERS_USER_DETAIL_SHORT])) : null;

// kullanıcı adına bağlı olarak bilgileri gösterme
switch((string)$get_username != null) {
    // kullanıcı adı var
    case 1:
        // proje numarası almak
        $get_projectpage = isset($_GET[USERS_USER_PROJECT_PAGE_NUM]) ? (htmlspecialchars($_GET[USERS_USER_PROJECT_PAGE_NUM])) : null;

        // sorgu için yeni sınıf objesi oluşturma
        $toolkit_user = new ToolkitUser($databaseconn);

        // kullanıcının verilerini fetch etmek
        $ARRAY_USER_DATA = $toolkit_user->limitwithorderby(
            ConfigTable::$table_a,
            [ConfigTable::$column_table_a_1], // username
            [$get_username], // query username
            [
                ConfigTable::$column_table_a_0, // user id
                ConfigTable::$column_table_a_1, // username
                ConfigTable::$column_table_a_4, // first name
                ConfigTable::$column_table_a_5, // last name
                ConfigTable::$column_table_a_6, // rank
                ConfigTable::$column_table_a_7 // register
            ],
            MAX_USER_LIST_LIMIT,
            0,
            [
                ConfigTable::$column_table_a_6, // rank
                ConfigTable::$column_table_a_7 // register
            ],
            [
                "DESC", // rank
                "ASC" // register
            ],
            1 // WHERE
        );

        // eğer kullanıcı bulunamadıysa boş dönsün
        switch(count($ARRAY_USER_DATA) < 1) {
            // kullanıcı bulunamadı
            case 1:
                return false;
            // dizideki ilk sırayı kullanıcı verisi olarak ayarla
            default:
                $ARRAY_USER_DATA = $ARRAY_USER_DATA[0];
            break;
        }        

        // otomatik olarak sadece aktif kodları arasın
        $STATUS_PROJECT_ACTIVE = PROJECT_ONLY_ACTIVE; // active
        
        // kullanıcıya ait projeleri sayma
        // fakat eğer aranan kullanıcı adı oturumdaki mevcut kullanıcıya ait ise
        // aktif ve yedekli kodları gösterebilsin
        // oturumdaki kullanıcı değilse, sadece aktifleri göstersin
        switch(SessionManager::get(SessionManager::$session_var_id) == $ARRAY_USER_DATA[ConfigTable::$column_table_a_0]) {
            // aranan kullanıcı, oturum sahibi
            case 1:
                $STATUS_PROJECT_ACTIVE = PROJECT_ACTIVE_BACKUP; // active - backup

                $TOTAL_COUNT_USER_PROJECTS = $toolkit_user->countmultiquery(
                    ConfigTable::$table_b, // table
                    "*", // column
                    [ // query column
                        ConfigTable::$column_table_b_1, // owner id
                    ],
                    [ // query data
                        $ARRAY_USER_DATA[ConfigTable::$column_table_a_0], // user id
                    ]
                );
                break;
            // oturum sahibi değil, normal kullanıcı
            default:
                $STATUS_PROJECT_ACTIVE = PROJECT_ONLY_ACTIVE; // active

                $TOTAL_COUNT_USER_PROJECTS = $toolkit_user->countmultiquery(
                    ConfigTable::$table_b, // table
                    "*", // column
                    [ // query column
                        ConfigTable::$column_table_b_1, // owner id
                        ConfigTable::$column_table_b_12 // backup status
                    ],
                    [ // query data
                        $ARRAY_USER_DATA[ConfigTable::$column_table_a_0], // user id
                        ConfigTable::$enum_table_b_12_0 // active
                    ]
                );
            break;
        }

        $USER_LISTED_MAX_PROJECT_PAGE = 0; // en yüksek listelenmiş sayfa numarası
        $USER_PROJECTS_PAGE_LIMIT = (int)($TOTAL_COUNT_USER_PROJECTS / MAX_USER_PROJECTS_LIST_LIMIT);
        $USER_PROJECTS_OFFSET = (int)MIN_OFFSET * MAX_USER_PROJECTS_LIST_LIMIT;

        // sayfa numarasına göre başlangıç konumu almak
        switch($get_projectpage >= 0 && $get_projectpage <= $USER_PROJECTS_PAGE_LIMIT) {
            // sayfa numarası kullanımı normal
            case 1:
                $USER_PROJECTS_OFFSET = (int)$get_projectpage * MAX_USER_PROJECTS_LIST_LIMIT;
                break;
            // kullanım dışı sayfa numarası
            default:
                // sayfa numarası başlangıç numarası standart en düşük
                // başlangıç numarasından daha düşük olamaz
                // eğer en düşük sayfa sayısından düşük değilse en büyük sayfa sayısına ayarla
                switch($get_projectpage < 0) {
                    // MIN
                    case 1:
                        $get_projectpage = (int)MIN_OFFSET;
                        $USER_PROJECTS_OFFSET = (int)MIN_OFFSET * MAX_USER_PROJECTS_LIST_LIMIT;
                        break;
                    // MAX
                    default:
                        $get_projectpage = (int)$USER_PROJECTS_PAGE_LIMIT;
                        $USER_PROJECTS_OFFSET = (int)$USER_PROJECTS_PAGE_LIMIT * MAX_USER_PROJECTS_LIST_LIMIT;
                    break;
                }
            break;
        }

        // kullanıcıya ait projeleri fetch etme
        // fakat eğer aranan kullanıcı adı oturumdaki mevcut kullanıcıya ait ise
        // aktif ve yedekli kodları gösterebilsin
        // oturumdaki kullanıcı değilse, sadece aktifleri göstersin
        switch($STATUS_PROJECT_ACTIVE) {
            // aranan kullanıcı, oturum sahibi
            case PROJECT_ACTIVE_BACKUP:
                $ARRAY_USER_PROJECTS_DATA = $toolkit_user->limitwithorderby(
                    ConfigTable::$table_b,
                    [
                        ConfigTable::$column_table_b_1, // project owner id
                    ],
                    [
                        $ARRAY_USER_DATA[ConfigTable::$column_table_a_0], // query project owner id
                    ],
                    ["*"],
                    MAX_USER_PROJECTS_LIST_LIMIT,
                    (int)$USER_PROJECTS_OFFSET,
                    [
                        ConfigTable::$column_table_b_10, // time
                        ConfigTable::$column_table_b_4, // like
                        ConfigTable::$column_table_b_5, // dislike
                        ConfigTable::$column_table_b_7, // download count
                        ConfigTable::$column_table_b_6 // secure
                    ],
                    [
                        "DESC", // time
                        "DESC", // like
                        "ASC", // dislike
                        "DESC", // download count
                        "DESC" // secure
                    ],
                    1 // WHERE
                );
                break;
            // oturum sahibi değil, normal kullanıcı
            // aktif projeler sadece
            case PROJECT_ONLY_ACTIVE:
                $ARRAY_USER_PROJECTS_DATA = $toolkit_user->limitwithorderby(
                    ConfigTable::$table_b,
                    [
                        ConfigTable::$column_table_b_1, // project owner id
                        ConfigTable::$column_table_b_12 // project backup status
                    ],
                    [
                        $ARRAY_USER_DATA[ConfigTable::$column_table_a_0], // query project owner id
                        ConfigTable::$enum_table_b_12_0 // active
                    ],
                    ["*"],
                    MAX_USER_PROJECTS_LIST_LIMIT,
                    (int)$USER_PROJECTS_OFFSET,
                    [
                        ConfigTable::$column_table_b_10, // time
                        ConfigTable::$column_table_b_4, // like
                        ConfigTable::$column_table_b_5, // dislike
                        ConfigTable::$column_table_b_7, // download count
                        ConfigTable::$column_table_b_6 // secure
                    ],
                    [
                        "DESC", // time
                        "DESC", // like
                        "ASC", // dislike
                        "DESC", // download count
                        "DESC" // secure
                    ],
                    1 // WHERE
                );
            break;
            // yedekli projeler sadece
            case PROJECT_ONLY_BACKUP:
                $ARRAY_USER_PROJECTS_DATA = $toolkit_user->limitwithorderby(
                    ConfigTable::$table_b,
                    [
                        ConfigTable::$column_table_b_1, // project owner id
                        ConfigTable::$column_table_b_12 // project backup status
                    ],
                    [
                        $ARRAY_USER_DATA[ConfigTable::$column_table_a_0], // query project owner id
                        ConfigTable::$enum_table_b_12_1 // backup
                    ],
                    ["*"],
                    MAX_USER_PROJECTS_LIST_LIMIT,
                    (int)$USER_PROJECTS_OFFSET,
                    [
                        ConfigTable::$column_table_b_10, // time
                        ConfigTable::$column_table_b_4, // like
                        ConfigTable::$column_table_b_5, // dislike
                        ConfigTable::$column_table_b_7, // download count
                        ConfigTable::$column_table_b_6 // secure
                    ],
                    [
                        "DESC", // time
                        "DESC", // like
                        "ASC", // dislike
                        "DESC", // download count
                        "DESC" // secure
                    ],
                    1 // WHERE
                );
            break;
        }

        // kullanıcı verisini çıktı verme
        // ek durumlar için döngü oluşturuldu
        switch(count($ARRAY_USER_DATA) > 0) {
            // kullanıcı verisi var
            case 1:
                // rastgele arkaplan resmi getirmek
                $autoimportglobal = new AutoImportGlobal($databaseconn);

                $custom_art_design = $autoimportglobal->getSysData(AutoImportGlobal::$var_sysdata_custom_art_design);
                $custom_art_design_url = $custom_art_design;

                $tempuser_userid = $ARRAY_USER_DATA[ConfigTable::$column_table_a_0];
                $tempuser_username = $ARRAY_USER_DATA[ConfigTable::$column_table_a_1];
                $tempuser_user_firstname = $ARRAY_USER_DATA[ConfigTable::$column_table_a_4];
                $tempuser_user_lastname = $ARRAY_USER_DATA[ConfigTable::$column_table_a_5];
                $tempuser_user_rank = $ARRAY_USER_DATA[ConfigTable::$column_table_a_6];
                $tempuser_user_register = $ARRAY_USER_DATA[ConfigTable::$column_table_a_7];

                // element svg
                $tempuser_svg_rank = $SVG_ARRAY_RANK[$tempuser_user_rank];
                $tempuser_svg_register = StaticSvg::$SVG_calendar_true;

                $tempuser_user_style_background = "
                background: linear-gradient(to bottom, rgba(0,0,0,0.75), rgba(0,0,0,0.9)), url($custom_art_design_url) repeat center";

                // html çıktısı vermek
                echo "<div class='users-user-selected' style='$tempuser_user_style_background'>
                        <div class='users-user-selected-info-textarea'>
                            <h2 class='user-selected-info-title'>$tempuser_username</h2>
                        </div>
                        <div class='users-user-selected-info-textarea user-selected-info-names'>
                            <p class='user-selected-info-firstname'>$tempuser_user_firstname</p>
                            <p class='user-selected-info-lastname'>$tempuser_user_lastname</p>
                        </div>
                        <div class='users-user-selected-info-textarea user-selected-info-others'>
                            <span class='user-selected-info-rank'>$tempuser_svg_rank $tempuser_user_rank</span>
                            <span class='user-selected-info-register'>$tempuser_svg_register $tempuser_user_register</span>
                        </div>
                    </div>";
            break;
            // kullanıcı verisi yok
            default:
                return false;
        }

        // kullanıcının projeleri varsa oluştursun
        switch(count($ARRAY_USER_PROJECTS_DATA) > 0) {
            // kullanıcıya ait projeler var
            case 1:
                ////////////////////////////////////////////////////////
                // projelerin toplam kaç sayfa olduğunu çıktı vermek
                ////////////////////////////////////////////////////////
                
                // ana div proje sayfalarını çıktı vermek için
                echo "<div class='users-user-projects-page-area'>";

                // şu anki bulunan sayfanın devamındaki sayfalarla birlikte
                // son syfa limitine kadar sayfa numaralarını çıktı vermek

                // numaralararın tutulacağı div
                echo "<div class='users-user-project-page-list-area'>";

                // tek sayfadan fazla sayfa var ise sayfa numaraları çıktısını verme
                switch($USER_PROJECTS_PAGE_LIMIT > 0) {
                    case 1:
                        // eğer bir önceki sayfa başlangıç değilse, 1 önceki sayfayı eklesin
                        // eğer bir önceki sayfa başlangıç sayfayı 1 yapsın
                        // eğer tek sayfa ise, zaten diğer sayfayı eklemiyecek
                        $get_projectpage == 0 ? ($min_countpage = 0) : ($min_countpage = (int)$get_projectpage - 1);

                        // eğer kullanıcının proje sayfasının sonunda ise
                        // ilk sayfaya gitmeyi sağlayan buton eklesin
                        switch(($get_projectpage) == ($USER_PROJECTS_PAGE_LIMIT)) {
                            case 1:
                                $tempuser_project_page_num = MIN_OFFSET;
                                $tempuser_project_page_cleaned_url = preg_replace('/([&?]' . USERS_USER_PROJECT_PAGE_NUM . '=)\d+/', '$1' . $tempuser_project_page_num, $_SERVER['REQUEST_URI']);
                                $tempuser_project_page_new_url = $tempuser_project_page_cleaned_url . "&" . USERS_USER_PROJECT_PAGE_NUM ."=" . $tempuser_project_page_num;
                                
                                echo "<button type='button' class='users-user-projects-page-btn' onclick='window.location.href = \"$tempuser_project_page_new_url\"'>$tempuser_project_page_num</button>";
                            break;
                        }
                        
                        // sayfa numarası ekleme sınırına göre sayfa numaraları ekleme
                        for($countpage = $min_countpage, $countlimited = 0;
                        ($countlimited < MAX_USER_PROJECT_PAGE_LIST_LIMIT) && ($countpage <= $USER_PROJECTS_PAGE_LIMIT);
                        $countpage++, $countlimited++)
                        {
                            // ana url'e tekrar tekrar ekleme yapmaması için
                            // kısaltma adresindeki değerini başta temizliyoruz
                            // sonra olması gerekeni ekliyoruz
                            $tempuser_project_page_cleaned_url = preg_replace('/([&?]' . USERS_USER_PROJECT_PAGE_NUM . '=)\d+/', '$1' . $countpage, $_SERVER['REQUEST_URI']);
                            $tempuser_project_page_new_url = $tempuser_project_page_cleaned_url . "&" . USERS_USER_PROJECT_PAGE_NUM ."=" . $countpage;
                        
                            // şuanki sayaç sayfa numarasına eşit ise butona ek özellik eklesin
                            switch($countpage == $get_projectpage) {
                                case 0:
                                    echo "<button type='button' class='users-user-projects-page-btn' onclick='window.location.href = \"$tempuser_project_page_new_url\"'>$countpage</button>";
                                    break;
                                case 1:
                                    echo "<button type='button' class='users-user-projects-page-btn user-projects-page-btn-selected' disabled>$countpage</button>";
                                    break;
                            }

                            // en yüksek listelenmiş sayfa numarasını artan sayfa liste numarası ile eşitle
                            $USER_LISTED_MAX_PROJECT_PAGE = $countpage;
                        }

                        // eğer kullanıcının proje sayfaları sayfa liste sınırından küçük ise
                        // son sayfaya gidebilmesi için ek buton eklesin
                        switch(($USER_LISTED_MAX_PROJECT_PAGE) < ($USER_PROJECTS_PAGE_LIMIT)) {
                            case 1:
                                $tempuser_project_page_num = $USER_PROJECTS_PAGE_LIMIT;
                                $tempuser_project_page_cleaned_url = preg_replace('/([&?]' . USERS_USER_PROJECT_PAGE_NUM . '=)\d+/', '$1' . $tempuser_project_page_num, $_SERVER['REQUEST_URI']);
                                $tempuser_project_page_new_url = $tempuser_project_page_cleaned_url . "&" . USERS_USER_PROJECT_PAGE_NUM ."=" . $tempuser_project_page_num;
                                
                                echo "<button type='button' class='users-user-projects-page-btn' onclick='window.location.href = \"$tempuser_project_page_new_url\"'>$tempuser_project_page_num</button>";
                            break;
                        }
                    break;
                }

                // numaraların tutulacağı div sonu

                // projelerin sayfa çıktısını veren div'in sonu
                echo "</div>";

                ////////////////////////////////////////////////////////
                // Projeleri çıktı vermek
                ////////////////////////////////////////////////////////

                // element svg
                $tempuserproject_svg_like = StaticSvg::$SVG_like;
                $tempuserproject_svg_dislike = StaticSvg::$SVG_dislike;
                $tempuserproject_svg_downloadcount = StaticSvg::$SVG_download_true;
                $tempuserproject_svg_size = StaticSvg::$SVG_code_file;
                $tempuserproject_svg_link = StaticSvg::$SVG_link;
                $tempuserproject_svg_time = StaticSvg::$SVG_calendar_true;
                $tempuserproject_svg_update = StaticSvg::$SVG_update;

                // element title
                $tempuserproject_title_like = LanguageSupport::getlang(LanguageSupport::$lang_key__text_like);
                $tempuserproject_title_dislike = LanguageSupport::getlang(LanguageSupport::$lang_key__text_dislike);
                $tempuserproject_title_download_counter = LanguageSupport::getlang(LanguageSupport::$lang_key__text_download_counter);
                $tempuserproject_title_time = LanguageSupport::getlang(LanguageSupport::$lang_key__text_created);
                $tempuserproject_title_update = LanguageSupport::getlang(LanguageSupport::$lang_key__text_updated);

                // projeler için ana div oluşturma
                echo "<div class='users-user-selected-projects-area'>";

                // kullanıcı projelerini çıktı verme
                // ek durumlar için döngü oluşturuldu
                foreach($ARRAY_USER_PROJECTS_DATA as $indexUserProject => $UserProject) {
                    // proje bilgilerini almak
                    $tempuserproject_projectid = $UserProject[ConfigTable::$column_table_b_0];
                    $tempuserproject_project_name = $UserProject[ConfigTable::$column_table_b_2];
                    $tempuserproject_project_comment = $UserProject[ConfigTable::$column_table_b_3];
                    $tempuserproject_project_like = $UserProject[ConfigTable::$column_table_b_4];
                    $tempuserproject_project_dislike = $UserProject[ConfigTable::$column_table_b_5];
                    $tempuserproject_project_secure = $UserProject[ConfigTable::$column_table_b_6];
                    $tempuserproject_project_downloadcount = $UserProject[ConfigTable::$column_table_b_7];
                    $tempuserproject_project_size = AutoImportGlobal::writeFileSize($UserProject[ConfigTable::$column_table_b_8], AutoImportGlobal::$imod_write_filetype);
                    $tempuserproject_project_link = $UserProject[ConfigTable::$column_table_b_9];
                    $tempuserproject_project_time = $UserProject[ConfigTable::$column_table_b_10];
                    $tempuserproject_project_update = $UserProject[ConfigTable::$column_table_b_11];
                    $tempuserproject_project_status = null;

                    // proje aktifse aktifler için ek bir css sınıfı
                    // değilse de yedekler için ek bir css sınıfı
                    switch($UserProject[ConfigTable::$column_table_b_12]) {
                        case ConfigTable::$enum_table_b_12_0: // active
                            $tempuserproject_project_status = "project-status-active";
                        break;
                        case ConfigTable::$enum_table_b_12_1: // backup
                            $tempuser_project_status_text = LanguageSupport::getlang(LanguageSupport::$lang_key__text_backup);
                            $tempuserproject_project_name = $tempuserproject_project_name . "&nbsp;&nbsp;[" .$tempuser_project_status_text. "]";
                            $tempuserproject_project_status = "project-status-backup";
                        break;
                    }

                    $tempuserproject_project_url = "/" . PAGE_CODES . "?" . USERS_USER_PROJECTID_SHORT . "=$tempuserproject_projectid";
                
                    // html çıktısı vermek
                    echo "<div class='users-user-selected-projects-area-project $tempuserproject_project_status'>
                        <div class='users-user-selected-projects-area-project-info-textarea user-project-titles'>
                            <p class='user-project-detail-title'>$tempuserproject_project_name</p>
                            <p class='user-project-detail-comment'>$tempuserproject_project_comment</p>
                        </div>
                        <div class='users-user-selected-projects-area-project-info-textarea'>
                            <div class='user-project-detail-likes'>
                                <p class='user-project-detail-like' title='$tempuserproject_title_like'>$tempuserproject_svg_like $tempuserproject_project_like</p>
                                <p class='user-project-detail-dislike' title='$tempuserproject_title_dislike'>$tempuserproject_svg_dislike $tempuserproject_project_dislike</p>
                            </div>
                            <div class='user-project-detail-download-size'>
                                <p class='user-project-detail-downloadcount' title='$tempuserproject_title_download_counter'>$tempuserproject_svg_downloadcount $tempuserproject_project_downloadcount</p>
                                <p class='user-project-detail-size'>$tempuserproject_svg_size $tempuserproject_project_size</p>
                            </div>
                        </div>
                        <div class='users-user-selected-projects-area-project-info-textarea'>
                            <p class='user-project-detail-time' title='$tempuserproject_title_time'>$tempuserproject_svg_time $tempuserproject_project_time</p>
                            <p class='user-project-detail-update' title='$tempuserproject_title_update'>$tempuserproject_svg_update $tempuserproject_project_update</p>
                        </div>
                        <div class='users-user-selected-projects-area-project-info-textarea'>
                            <a class='user-project-detail-url' href='$tempuserproject_project_url'>$tempuserproject_svg_link $tempuserproject_project_name</a>
                        </div>
                    </div>";
                }

                // projeler için ana div'i sonlandırma
                echo "</div>";
            break;
        }
    break;
}
?>