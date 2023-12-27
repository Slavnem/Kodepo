<?php
// proje numarası almak
$get_projectnum = isset($_GET[CODES_CODE_DETAIL_SHORT]) ? (htmlspecialchars($_GET[CODES_CODE_DETAIL_SHORT])) : null;

// seçili proje numarasına göre listeleme
switch((int)$get_projectnum > 0) {
    // belirli bir proje numarası var
    // sadece o projenin kodlarını gösterme
    case 1:
        echo "belirli proje num";
    break;
    // belirli proje numarası yok
    // genel projeleri listeleme
    default:
        echo "genel proje";
    break;
}
?>