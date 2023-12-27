<?php
class ToolkitUser {
// gizli değişkenler
  private $databaseConnection;

  // veritabanı yapı
  public function __construct($databaseConnection) {
    // veritabanı bağlantısı
    $this->databaseConnection = $databaseConnection;
  }

  // sadece sayaç
  public function countonly(string $table, string $column) {
    // tablonun doğru olup olmadığını kontrol etmek
    $TEMP_CHECK = false;

    foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $TTable) {
        // tablo bulundu
        if($table == $TTable) {
            // tüm sütunlar
            if($column == "*") {
                $TEMP_CHECK = true;
                break;
            }
            else {
                // sütun kontrol
                foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $TColumn) {
                    // sütun bulundu
                    if($column == $TColumn) {
                        $TEMP_CHECK = true;
                        break;
                    }
                }
            }
        }
    }

    // eğer değerler bulunmamışsa döndür
    if(!$TEMP_CHECK) {
        return false;
    }

    // değerler uygun, sorgu oluştur
    $sqlCountAll = "SELECT COUNT($column) FROM $table";

    // sorguyu hazırlamak
    $stmtCountAll = $this->databaseConnection->prepare($sqlCountAll);

    // sorguyu çalıştıırp sonucu almak
    $stmtCountAll->execute();
    $resultCountAll = $stmtCountAll->get_result();

    return $resultCountAll->fetch_assoc()["COUNT($column)"];
  }

  // belirlenen sıradakileri getirtme sadece
  public function countlimited(string $table, string $column, int $limit, int $offset) {
    // belirli limit veya başlangıç konumu değeri girilmemiş
    if($limit < 1 || $offset < 0) {
        return -1;
    }

    // tablonun doğru olup olmadığını kontrol etmek
    $TEMP_CHECK = false;

    foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $TTable) {
        // tablo bulundu
        if($table == $TTable) {
            // tüm sütunlar
            if($column == "*") {
                $TEMP_CHECK = true;
                break;
            }
            else {
                // sütun kontrol
                foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $TColumn) {
                    // sütun bulundu
                    if($column == $TColumn) {
                        $TEMP_CHECK = true;
                        break;
                    }
                }
            }
        }
    }

    // eğer değerler bulunmamışsa döndür
    if(!$TEMP_CHECK) {
        return false;
    }

    // değerler uygun, sorgu oluştur
    $sqlCountAll = "SELECT COUNT($column) FROM $table
    LIMIT $limit OFFSET $offset";

    // sorguyu hazırlamak
    $stmtCountAll = $this->databaseConnection->prepare($sqlCountAll);

    // sorguyu çalıştıırp sonucu almak
    $stmtCountAll->execute();
    $resultCountAll = $stmtCountAll->get_result();

    return $resultCountAll->fetch_assoc()["COUNT($column)"];
  }

  // belirli bir liste sıralaması ile
  public function limitwithorderby(
    string $table,
    $querycolumn = [], $querydata = [],
    $columnselect = [],
    int $limit, int $offset,
    $listarray = [], $descarray = [],
    int $argwhere = 0
  )
  {
    // belirli limit veya başlangıç konumu değeri girilmemiş
    if($limit < 1 || $offset < 0) {
        return -1;
    }

    // eğer sıralamada sıralama miktarı sütun sayısından fazla ise
    if(count($descarray) > count($listarray)) {
        return -2;
    }

    // sorgu sütunu ve sorgu değeri eşit boyutta değilse hata döndür
    if(count($querycolumn) != count($querydata)) {
        return -5;
    }
    
    // tablonun doğru olup olmadığını kontrol etmek
    $TEMP_CHECK = false;
    
    foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $TTable) {
        // tablo bulundu
        if($table === $TTable) {
            // tüm sütunlar
            if($columnselect[0] === "*") {
                $TEMP_CHECK = true;
                break;
            }
            else {
                // sütunların sayısını tutan geçici sayaç
                $TEMP_COUNT_COLUMNS = 0;

                // sütun kontrol
                foreach($columnselect as $indexColumnSelect => $ColumnSelected) {
                    foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $TColumn) {
                        // sütun bulundu
                        if($ColumnSelected === $TColumn) {
                            $TEMP_COUNT_COLUMNS += 1;
                            break;
                        }
                    }
                }

                // seçilen sütun sayısı kontrolu sonucu
                // bulunan sayı eğer seçilmek istenen sütun sayısına eşit değilse
                // hata döndür
                if($TEMP_COUNT_COLUMNS != count($columnselect)) {
                    return -4;
                }
            }

            // kaç sütun bulunduğunu tespit etmek için sayaç
            $COUNT_LIST_ARRAY = 0;
                
            // sıralama sütunları bu tabloda var mı kontrol
            foreach($listarray as $indexList => $List) {
                // sütunu tablo içinde kontrol
                foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $TColumn) {
                    if($List == $TColumn) {
                        $COUNT_LIST_ARRAY += 1;
                        break;
                    }
                }
            }

            // eğer bulunan sütun miktarı girilenle eşit değilse hata dönsün
            if(count($listarray) != $COUNT_LIST_ARRAY) {
                return -3;
            }

            // sorgulanacak sütunlar bu tabloda varmı kontrol
            $COUNT_LIST_ARRAY_NEW = 0;
                
            // sıralama sütunları bu tabloda var mı kontrol
            foreach($querycolumn as $indexcol => $Colm) {
                // sütunu tablo içinde kontrol
                foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $TColumn) {
                    if($Colm == $TColumn) {
                        $COUNT_LIST_ARRAY_NEW += 1;
                        break;
                    }
                }
            }

            // eğer bulunan sütun miktarı girilenle eşit değilse hata dönsün
            if(count($querycolumn) != $COUNT_LIST_ARRAY_NEW) {
                return -6;
            }

            // kontrol başarılı
            $TEMP_CHECK = true;
        }
    }
    
    // eğer değerler bulunmamışsa döndür
    if($TEMP_CHECK != true) {
        return false;
    }

    // seçilmek istenen sütunları ekliyoruz
    $temp_selectedall = "";
    foreach($columnselect as $indexColumnSelect => $ColumnSelected) {
        $temp_selectedall = $temp_selectedall . "$ColumnSelected";

        // eğer sayaç bitmemişse sonuna virgül eklesin
        if($indexColumnSelect < (count($columnselect) - 1)) {
            $temp_selectedall = $temp_selectedall . ", ";
        }
    }
    
    // where sorguları
    $temp_querycolm = "";
    foreach($querycolumn as $indexcol => $Colm) {
        $temp_data = $querydata[$indexcol]; // sorgulanacak değer

        // like kullanımımı yoksa where mi?
        switch($argwhere) {
            // LIKE
            case 0:
                $temp_querycolm = $temp_querycolm . " AND $Colm LIKE \"$temp_data%\""; // ek bir sorgu kontrolü
                break;
            // WHERE
            default:
                $temp_querycolm = $temp_querycolm . " AND $Colm = \"$temp_data\""; // ek bir sorgu kontrolü
                break;
        }
    }

    // değerler uygun, sorgu oluştur
    $sqlCountAllOrderBy = "SELECT $temp_selectedall FROM $table WHERE 1 > 0 $temp_querycolm ORDER BY ";

    // sütunların büyüklük küçüklük seçimi
    foreach($listarray as $indexList => $List) {
        // eğer büyük (desc) girilmişse ona göre değilse başka
        if($descarray[$indexList] == "DESC" || $descarray[$indexList] == "desc") {
            $sqlCountAllOrderBy = $sqlCountAllOrderBy . "$List DESC";
        }
        else {
            $sqlCountAllOrderBy = $sqlCountAllOrderBy . "$List ASC";
        }

        // sonu virgüllü olmaması için kontrol
        if($indexList < (count($listarray) - 1)) {
            $sqlCountAllOrderBy = $sqlCountAllOrderBy . ", ";
        }
    }

    // limit ve offset ekleme
    $sqlCountAllOrderBy = $sqlCountAllOrderBy . " LIMIT $limit OFFSET $offset";
    
    // sorguyu hazırlamak
    $stmtCountAllOrderBy = $this->databaseConnection->prepare($sqlCountAllOrderBy);
    
    // sorguyu çalıştıırp sonucu almak
    $stmtCountAllOrderBy->execute();
    $resultCountAllOrderBy = $stmtCountAllOrderBy->get_result();
    $array_data_fetched = []; // veriler
    
    while($fetchCountAllOrderBy = $resultCountAllOrderBy->fetch_assoc()) {
        $array_data_fetched[] = $fetchCountAllOrderBy;
    }

    // verilerin aktarıldığı diziyi döndürme
    return $array_data_fetched;
  }

  // kullanıcıya ait projeleri sayma tek sorgu kontrol hakkı
  public function countsinglequery(
    string $table = null, string $column = null,
    $querycolumn, $querydata)
    {
        // belirli kullanıcı id'si girilmediği için
        // veya tablo ya da sütun girilmediğinden
        // -1 döndürsün ki hatalı olduğu anlaşılsın 
        if($table == null || $column == null) {
            return -1;
        }

        // sorgu sütunu veya değeri girilmediğinden
        // 0 döndür
        if($querycolumn == null || $querydata == null) {
            return 0;
        }

        // NOT: 08-12-2023 21:29
        /* Eğer girilen table, column, querycolumn
        metinleri eğer tablo ve sütunların sıralandığı hiçbir
        dizi ile uyuşmuyorsa işlemi iptal ettirme yapılabilir
        ve bu sayede istenmeyen güvenlik açıkları önlenmiş olur
        */
        ConfigStaticTable::initialize();

        // geçici kontrol değeri otomatik false
        $temp_val = false;

        // Girilen tablo değeri doğru mu kontrol
        foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $ArrayTable) {
            // tablo uyuşmadı sonraki tura geç
            if($ArrayTable == $table) {
                foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $ArrayColumn) {
                    // sütun bulunamadı devam et
                    if(($ArrayColumn == $column || $column == "*") && $ArrayColumn == $querycolumn) {
                         // sütun bulundu
                        $temp_val = true;
                        break;
                    }
                }
            }
        }

        // eğer doğru dönmemişse hatayı döndür
        if(!$temp_val) {
            return $temp_val;
        }

        // tablo ve sütun doğru, işleme devam
        // Sorguyu oluşturma
        // kullanıcı tablo ve sütunu girilmiş ve
        // kullanıcı id'si eşleşenleri say
        $sqlCount = "SELECT COUNT(" .$column .")"
        ." FROM " .$table
        ." WHERE " .$querycolumn ." = ?";

        // Sorgu bağlantısı
        $stmtCount = $this->databaseConnection->prepare($sqlCount);

        // Sorgu argümanları
        $stmtCount->bind_param("s", $querydata);

        // Sorguyu çalıştır
        $stmtCount->execute();

        // Sorgu sonucunu al
        $resultCount = $stmtCount->get_result();

        // Bulunan satır sayısını döndür
        return $resultCount->fetch_assoc()["COUNT(" .$column .")"];
    }

    // kullanıcıya ait projeleri sayma çoklu sorgu kontrol hakkı
  public function countmultiquery(
    string $table = null, string $column = null,
    array $querycolumn_array = [], array $querydata_array = [])
    {
        // belirli kullanıcı id'si girilmediği için
        // veya tablo ya da sütun girilmediğinden
        // -1 döndürsün ki hatalı olduğu anlaşılsın 
        if($table == null || $column == null) {
            return -1;
        }
    
        // sorgu sütunu veya değeri girilmediğinden
        // 0 döndür
        if($querycolumn_array == null || $querydata_array == null) {
            return false;
        }
    
        // eğer bir dizi diğerinden fazlaysa false dönsün
        if(count($querycolumn_array) != count($querydata_array)) {
            return false;
        }
    
        // NOT: 08-12-2023 21:29
        /* Eğer girilen table, column, querycolumn
        metinleri eğer tablo ve sütunların sıralandığı hiçbir
        dizi ile uyuşmuyorsa işlemi iptal ettirme yapılabilir
        ve bu sayede istenmeyen güvenlik açıkları önlenmiş olur
        */
        ConfigStaticTable::initialize();
    
        // geçici kontrol değeri otomatik false
        $temp_val = false;
        
        // Girilen tablo değeri doğru mu kontrol
        foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $ArrayTable) {
            // tablo uyuşmadı sonraki tura geç
            if($ArrayTable == $table) {
                foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $ArrayColumn) {
                    // sütun bulunamadı devam et
                    if(($ArrayColumn == $column || $column == "*")) {
                         // sütun bulundu
                        $temp_val = true;
                        break;
                    }
                }
            
                // dizileri kontrol etme (sorgu yapılacak sütun)
                foreach($querycolumn_array as $QueryColumn) {
                    // eğer herhangi bir sütun o tabloda yoksa false dönsün
                    foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $ArrayColumn) {
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
            }
        }
    
        // eğer doğru dönmemişse hatayı döndür
        if(!$temp_val) {
            return $temp_val;
        }
    
        // tablo ve sütun doğru, işleme devam
        // Sorguyu oluşturma
        // kullanıcı tablo ve sütunu girilmiş ve
        // 1 büyüktür 0'dan diyoruz çünkü 1'den fazla
        // parametre olacağı için devamına AND ekleyebilelim
        // ve 1 her zaman 0'dan büyüktür :)
        // kullanıcı id'si eşleşenleri say
        $sqlCount = "SELECT COUNT(" .$column .")"
        ." FROM " .$table ." WHERE 1 > 0";
        $sqlParam = "";
    
        // istenen sütun ve değer kadar sorgu eklemesi yapacak
        // kaç tane kontrol varsa o kadar parametre değeri ekleyecek
        foreach($querycolumn_array as $columnindex => $QueryColumn) {
            $sqlCount = $sqlCount ." AND ". $QueryColumn ." = ?";
            $sqlParam = $sqlParam ."s";
        }
    
        // Sorgu bağlantısı
        $stmtCount = $this->databaseConnection->prepare($sqlCount);
    
        // Sorgu argümanları
        // değerleri tek seferde parametre olarak girmek
        $stmtCount->bind_param("$sqlParam", ...$querydata_array);
    
        // Sorguyu çalıştır
        $stmtCount->execute();
    
        // Sorgu sonucunu al
        $resultCount = $stmtCount->get_result();
    
        // Bulunan satır sayısını döndür
        return $resultCount->fetch_assoc()["COUNT(" .$column .")"];
    }
}
?>