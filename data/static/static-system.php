<?php
// veritabanı tablolarını
require_once("include/database/config-database-table.php");

// otomatik olarak değişkenlere NULL vermek
$data_system_logo = NULL;

// sisteme ait otomatik biglileri alacağımız sınıf
class AutoSystem {
    // gizli değişkenler
    private $databaseConnection;

    // veritabanı yapı
    public function __construct($databaseConnection) {
        // veritabanı bağlantısı
        $this->databaseConnection = $databaseConnection;
    }

    // sadece seçileni getiren fonksiyon
    public function getSelected($array_querycolumn = [], $array_value = [], int $selecttype = 0, string $table = null) {
        // seçim türü istenenin dışında ise hata koduyla dönsün
        // amaç ya tüm tabloları seçmek ya da istenen sütunları
        switch($selecttype) {
            case 0:
                // hepsini seç
                break;
            case 1:
                // sadece istenenler seçileceği için dizileri kontrol etmeliyiz
                if(empty($array_querycolumn) || empty($array_value) || count($array_querycolumn) != count($array_value)) {
                    return -98;
                }
                break;
            default:
                // hatalı değer
                return -99;
        }

        // tablo girilmemişse kendimiz belirliyoruz
        if($table == null) {
            $table = ConfigTable::$table_d;
        }

        // girilen sütun tablonun içinde mevcut mu kontrol
        ConfigStaticTable::initialize();
        
        // otomatik olarak false, yanlış başlasın
        // sonuç bulunursa true, doğru olsun
        $control = false;

        // eğer hepsi seçilsin istenmişse sadece tablo kontrolü
        // istenilen sütunlar yazılmışsa hem tablo hem sütun kontrolü

        switch($selecttype) {
            // sadece tablo kontrolü
            case 0:
                foreach(ConfigStaticTable::$ARRAY_TABLE as $indextable => $ArrayTable) {
                    // ilk tablo araması
                    if($ArrayTable == $table) {
                        $control = true;
                        break;
                    }
        
                    // tablo bulunamadı hala
                    $control = -1;
                }
                break;
            // hem tablo hem sütun kontrolü
            case 1:
                // sadece istenenler seçileceği için dizileri kontrol etmeliyiz
                if(empty($array_querycolumn) || empty($array_value) || count($array_querycolumn) != count($array_value)) {
                    return -98;
                }

                foreach(ConfigStaticTable::$ARRAY_TABLE as $indextable => $ArrayTable) {
                    // ilk tablo araması
                    if($ArrayTable == $table) {
                        // tek tek diziye girilmiş olan sütunları kontrol etme
                        foreach($array_querycolumn as $index_querycolumn => $QueryColumn) {
                            // eğer herhangi bir sütun o tabloda yoksa false dönsün
                            foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indextable] as $ArrayColumn) {
                                // dizi olan sütunları kontrol
                                if(($ArrayColumn == $QueryColumn)) {
                                    // sütun bulundu
                                    $temp_val = true;
                                    break;
                                }
                                else {
                                    // sütun bulunmadı devam
                                    $temp_val = false;
                                    continue;
                                }
                            }
                        }

                        // tablo bulundu
                        break;
                    }
                    else {
                        // tablo bulunamadı hala
                        $control = -1;
                        continue;
                    }
                }
                break;
            default:
                // hatalı değer
                return -99;
        }


        // eğer tablo ya da sütun bulunamadıysa
        // hata koduna göre döndürsün
        if($control != true) {
            return $control;
        }

        // seçim türüne göre sorgu ayarlaması
        switch($selecttype) {
            // hepsini seç
            case 0:
                // tablo ve sütun bulundu
                $sqlSelected = "SELECT * FROM ". $table;

                // Sorgu bağlantısı
                $stmtSelected = $this->databaseConnection->prepare($sqlSelected);
                break;
            // sadece istenenleri seç
            case 1:
                // tablo ve sütun bulundu
                $sqlSelected = "SELECT * FROM ". $table
                ." WHERE 1 > 0";

                // sorgu parametreleri
                $sqlParam = "";

                // girilen sorgu sütunu kadar dönsün
                foreach($array_querycolumn as $index_querycolumn => $QueryColumn) {
                    $sqlSelected = $sqlSelected . " AND ". $QueryColumn ." = ?";
                    $sqlParam = $sqlParam ."s";
                }

                // Sorgu bağlantısı
                $stmtSelected = $this->databaseConnection->prepare($sqlSelected);

                // Sorgu parametreleri
                $stmtSelected->bind_param("$sqlParam", ...$array_value);
                break;
            default:
                // hatalı değer
                return -99;
        }

        // Sorguyu çalıştırma
        $stmtSelected->execute();

        // Sorgu sonucu
        $resultSelected = $stmtSelected->get_result();

        // bulunan sonuçlar aktarılacak olan bir dizi oluştur
        $arrayFetchSelected = [];

        // verileri diziye aktar
        while($fetchSelected = $resultSelected->fetch_assoc()) {
            $arrayFetchSelected[] = $fetchSelected;
        }

        // veriler içine aktarılan diziyi döndür
        return $arrayFetchSelected;
    }
}