<?php
// genel araç kiti sınıfı
class ToolkitGlobal {
  // istenmeyen harfleri silmek
  public static function clearLetter(string $text) {
    // temizlenmiş metini döndürmek
    return str_replace(
      [' ', ',', '!', '"', '`', "''", '~', '^', '*', '/', '+',
      '$', ';','½','¾','&','{','}','[',']','}','?','|'],
      "", $text
    );
  }
}
?>
