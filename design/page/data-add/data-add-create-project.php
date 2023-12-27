<?php
// Genel bir sorun yok
define("FILE_LOCATION_TOOL_NAV_CREATE_PROJECT_STYLE", "design/style/css/page/data-add/component-tool/page-tool-navigation-create-project.css");

$id_form_create_project_title_area = "create-new-project-title-area";
$id_form_create_project_title = "create-new-project-title";
$name_form_create_project = "form-create-new-project";
$name_form_create_project_input_area = "create-new-project-input-area";
$name_form_create_project_process_area = "create-new-project-process-area";
$name_form_create_project_name = "create-new-project-name";
$name_form_create_project_description = "create-new-project-description";

// eğer oturum girişi yoksa otomatik oturum temizleyecek
$AutoImportGlobal->falseMeanCleanSession(0);

// rastgele seçilmiş arkaplan
$randomBackgroundUrl = $AutoImportGlobal->getSysData(AutoImportGlobal::$var_sysdata_background);

// sonuç mesajı isimlendirme
$name_result_msg_title = "result-title";
$name_result_msg_text = "result-text";
$name_result_msg_info = "result-info";
$name_result_msg_type = "result-type";

// işlem sonucu mesajları
$resultMessage = [
    [ // success
        $name_result_msg_title => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__title_success_created_project),
        $name_result_msg_text => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__msg_success_created_project),
        $name_result_msg_info => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__success),
        $name_result_msg_type => 1
    ],
    [ // error
        $name_result_msg_title => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__title_error__not_created_project),
        $name_result_msg_text => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__msg_error__not_created_project),
        $name_result_msg_info => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__error),
        $name_result_msg_type => 2
    ],
    [ // warning
        $name_result_msg_title => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__title_warning_create_project),
        $name_result_msg_text => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__msg_warning_create_project),
        $name_result_msg_info => $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__warning),
        $name_result_msg_type => 3
    ]
];
?>
<!-- CREATE PROJECT CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo FILE_LOCATION_TOOL_NAV_CREATE_PROJECT_STYLE; ?>"/>
<!-- CREATE PROJECT -->
<section id="create-new-project-area" class="create-new-project-area"
    style="background: linear-gradient(to top, rgba(0,0,0,0.25), rgba(0,0,0,0.5)), url('<?php echo $randomBackgroundUrl; ?>');">
    <div id="create-new-project-center" id="create-new-project-center">
        <form method="POST" name="<?php echo $name_form_create_project; ?>" id="<?php echo $name_form_create_project; ?>" class="<?php echo $name_form_create_project; ?>">
            <div id="<?php echo $id_form_create_project_title_area; ?>" class="<?php echo $id_form_create_project_title_area; ?>">
                <h2 id="<?php echo $id_form_create_project_title; ?>" class="<?php echo $id_form_create_project_title; ?>">
                    <?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__create_new_project); ?>
                </h2>
            </div>
            <div name="<?php echo $name_form_create_project_input_area; ?>" id="<?php echo $name_form_create_project_input_area; ?>" class="<?php echo $name_form_create_project_input_area; ?>">
                <input type="text" name="<?php echo $name_form_create_project_name; ?>" required
                placeholder=" <?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_project_create_name); ?>"/>
                <textarea rows="3" cols="30" name="<?php echo $name_form_create_project_description; ?>" required
                placeholder="<?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_project_create_description); ?>"></textarea>
            </div>
            <div name="<?php echo $name_form_create_project_process_area; ?>" id="<?php echo $name_form_create_project_process_area; ?>" class="<?php echo $name_form_create_project_process_area; ?>">
                <button type="submit" name="<?php echo FILE_SUBMIT_CREATE_PROJECT; ?>"
                title="<?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__form_send); ?>">
                    <?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__form_send); ?>
                </button>
                <button type="reset" name="<?php echo FILE_RESET; ?>" title="<?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__form_clear); ?>">
                    <?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__form_clear); ?>
                </button>
            </div>
        </form>
    </div>
</section>
<!-- MESSAGE JS -->
<script nonce src="../../../component/tool/message/message-system.js"></script>
<!-- AJAX -->
<script nonce src="<?php echo FILE_LOCATION_AJAX; ?>"></script>
<script nonce>
    $(document).ready(function() {
        $("#<?php echo $name_form_create_project; ?>").submit(function(e) {
            e.preventDefault();

            // submit
            const btnSubmit = document.querySelector("[name='<?php echo FILE_SUBMIT_CREATE_PROJECT; ?>']");

            // values
            const projectName = document.querySelector("[name='<?php echo $name_form_create_project_name; ?>']").value;
            const projectDescription = document.querySelector("[name='<?php echo $name_form_create_project_description; ?>']").value;

            // name & description REQUIRED!
            if(projectName.length < 1 || projectDescription.length < 1) {
                return false;
            }

            $.ajax({
                url: 'http://192.168.1.103:8080/design/page/data-add/component-tool/tool-navigation-create-project.php',
                method: 'post',
                data: {
                    "<?php echo $name_form_create_project_name; ?>": projectName,
                    "<?php echo $name_form_create_project_description; ?>": projectDescription
                },
                dataType: "json",
                success: function(data) {                    
                    // remove submit
                    btnSubmit.remove();

                    // sorgu sonucu
                    switch(data) {
                        // başarılı
                        case true:
                            MessageType(
                                '<?php echo $resultMessage[0][$name_result_msg_title]; ?>',
                                '<?php echo $resultMessage[0][$name_result_msg_text]; ?>',
                                '<?php echo $resultMessage[0][$name_result_msg_info]; ?>',
                                <?php echo $resultMessage[0][$name_result_msg_type]; ?>
                            );
                        break;
                        // başarısız
                        case false:
                            MessageType(
                                '<?php echo $resultMessage[1][$name_result_msg_title]; ?>',
                                '<?php echo $resultMessage[1][$name_result_msg_text]; ?>',
                                '<?php echo $resultMessage[1][$name_result_msg_info]; ?>',
                                <?php echo $resultMessage[1][$name_result_msg_type]; ?>
                            );
                        break;
                        // uyarı
                        default:
                            MessageType(
                                '<?php echo $resultMessage[2][$name_result_msg_title]; ?>',
                                '<?php echo $resultMessage[2][$name_result_msg_text]; ?>',
                                '<?php echo $resultMessage[2][$name_result_msg_info]; ?>',
                                <?php echo $resultMessage[2][$name_result_msg_type]; ?>
                            );
                        break;
                    }

                    // reload page
                    setTimeout(function() {
                        window.location.href = "";
                    }, 2500);
                },
                error: function() {
                    // remove submit
                    btnSubmit.remove();

                    // reload page
                    setTimeout(function() {
                        window.location.href = "";
                    }, 1800);
                }
            });
        });
    });
</script>