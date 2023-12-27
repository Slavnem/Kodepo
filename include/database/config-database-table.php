<?php
class ConfigTable {
  public static $table_a = "Users";
  public static $table_b = "Projects";
  public static $table_c = "Codes";
  public static $table_d = "AutoSystem";
  public static $table_e = "Languages";
  public static $table_f = "UserCustomizations";
  public static $table_g = "UserCustomMod";
  //////////////////////////////////////

  //////////////////////////////////////
  public static $column_table_a_0 = "USERID";
  public static $column_table_a_1 = "UNAME";
  public static $column_table_a_2 = "UPASSWORD";
  public static $column_table_a_3 = "UEMAIL";
  public static $column_table_a_4 = "UFIRSTNAME";
  public static $column_table_a_5 = "ULASTNAME";
  public static $column_table_a_6 = "URANK";
  public static $column_table_a_7 = "UREGISTER";
  public static $column_table_a_8 = "ULANGUAGE";
  public static $column_table_a_9 = "UMOD";

  //////////////////////////////////////
  public static $column_table_b_0 = "PROJECTID";
  public static $column_table_b_1 = "POWNERID";
  public static $column_table_b_2 = "PNAME";
  public static $column_table_b_3 = "PCOMMENT";
  public static $column_table_b_4 = "PLIKE";
  public static $column_table_b_5 = "PDISLIKE";
  public static $column_table_b_6 = "PSECURE";
  public static $column_table_b_7 = "PDOWNLOADCOUNT";
  public static $column_table_b_8 = "PSIZE";
  public static $column_table_b_9 = "PURL";
  public static $column_table_b_10 = "PTIME";
  public static $column_table_b_11 = "PUPDATE";
  public static $column_table_b_12 = "PBACKUP";

  public static $enum_table_b_6_0 = "!";
  public static $enum_table_b_6_1 = "?";
  public static $enum_table_b_6_2 = "*";
  public static $enum_table_b_6_3 = "**";
  public static $enum_table_b_6_4 = "***";

  public static $enum_table_b_12_0 = 0;
  public static $enum_table_b_12_1 = 1;

  //////////////////////////////////////
  public static $column_table_c_0 = "CODEID";
  public static $column_table_c_1 = "COWNERID";
  public static $column_table_c_2 = "CPROJECTID";
  public static $column_table_c_3 = "CNAME";
  public static $column_table_c_4 = "CCOMMENT";
  public static $column_table_c_5 = "CCODE";
  public static $column_table_c_6 = "CSECURE";
  public static $column_table_c_7 = "CLIKE";
  public static $column_table_c_8 = "CDISLIKE";
  public static $column_table_c_9 = "CSIZE";
  public static $column_table_c_10 = "CURL";
  public static $column_table_c_11 = "CDOWNLOADCOUNT";
  public static $column_table_c_12 = "CTIME";
  public static $column_table_c_13 = "CUPDATE";

  public static $enum_table_c_5_0 = 1; // assembly
  public static $enum_table_c_5_1 = 2; // web assembly
  public static $enum_table_c_5_2 = 3; // c
  public static $enum_table_c_5_3 = 4; // c++
  public static $enum_table_c_5_4 = 5; // c header
  public static $enum_table_c_5_5 = 6; // c++ header
  public static $enum_table_c_5_6 = 7; // java
  public static $enum_table_c_5_7 = 8; // python
  public static $enum_table_c_5_8 = 9; // javascript
  public static $enum_table_c_5_9 = 10; // reactjs
  public static $enum_table_c_5_10 = 11; // html
  public static $enum_table_c_5_11 = 12; // html
  public static $enum_table_c_5_12 = 13; // css
  public static $enum_table_c_5_13 = 14; // ruby
  public static $enum_table_c_5_14 = 15; // swift
  public static $enum_table_c_5_15 = 16; // objective c
  public static $enum_table_c_5_16 = 17; // objective c
  public static $enum_table_c_5_17 = 18; // c#
  public static $enum_table_c_5_18 = 19; // php
  public static $enum_table_c_5_19 = 20; // go
  public static $enum_table_c_5_20 = 21; // rush
  public static $enum_table_c_5_21 = 22; // typescript
  public static $enum_table_c_5_22 = 23; // typescript
  public static $enum_table_c_5_23 = 24; // kotlin
  public static $enum_table_c_5_24 = 25; // kotlin
  public static $enum_table_c_5_25 = 26; // perl
  public static $enum_table_c_5_26 = 27; // shell
  public static $enum_table_c_5_27 = 28; // sql
  public static $enum_table_c_5_28 = 29; // json
  public static $enum_table_c_5_29 = 30; // xml
  public static $enum_table_c_5_30 = 31; // markdown
  public static $enum_table_c_5_31 = 32; // yaml
  public static $enum_table_c_5_32 = 33; // yaml
  public static $enum_table_c_5_33 = 34; // dart
  public static $enum_table_c_5_34 = 35; // r

  public static $enum_table_c_1_0 = 0;
  public static $enum_table_c_1_1 = 1;

  //////////////////////////////////////
  public static $column_table_d_0 = "AUID";
  public static $column_table_d_1 = "AUPID";
  public static $column_table_d_2 = "AUNAME";
  public static $column_table_d_3 = "AULONGNAME";
  public static $column_table_d_4 = "AUURL";

  public static $enum_table_d_1_0 = 0; // logo icon
  public static $enum_table_d_1_1 = 1; // logo
  public static $enum_table_d_1_2 = 2; // sign background
  public static $enum_table_d_1_3 = 3; // background
  public static $enum_table_d_1_4 = 4; // custom art design

  //////////////////////////////////////
  public static $column_table_e_0 = "LANGID";
  public static $column_table_e_1 = "LANGNAME";
  public static $column_table_e_2 = "LANGSHORT";

  public static $enum_table_e_0_0 = 1; // Türkçe - TR
  public static $enum_table_e_0_1 = 2; // English - EN
  public static $enum_table_e_0_2 = 3; // Русский - RU

  //////////////////////////////////////
  public static $column_table_f_0 = "UCID";
  public static $column_table_f_1 = "UCUSERID";
  public static $column_table_f_2 = "UCNAME";
  public static $column_table_f_3 = "UCPID";
  public static $column_table_f_4 = "UCMODID";
  public static $column_table_f_5 = "UCSTATUS";

  public static $enum_table_f_5_0 = 0; // disabled
  public static $enum_table_f_5_1 = 1; // enabled

  public static $enum_table_f_4_0 = 1; // Message - Ask
  public static $enum_table_f_4_1 = 2; // Message - Info

  //////////////////////////////////////
  public static $column_table_g_0 = "UCMID";
  public static $column_table_g_1 = "UCMUSERID";
  public static $column_table_g_2 = "UCMMODID";
}

class ConfigStaticTable {
  // ARRAY
  public static $ARRAY_TABLE;
  public static $ARRAY_COLUMN_ALL;
  public static $ARRAY_LANG_SUPPORT;
  public static $ARRAY_FILE_TYPE_SUPPORT;

  public static function initialize() {
    self::$ARRAY_TABLE = [
      ConfigTable::$table_a,
      ConfigTable::$table_b,
      ConfigTable::$table_c,
      ConfigTable::$table_d,
      ConfigTable::$table_e,
      ConfigTable::$table_f,
      ConfigTable::$table_g
    ];

    self::$ARRAY_COLUMN_ALL = [
      // table a
      [
        ConfigTable::$column_table_a_0,
        ConfigTable::$column_table_a_1,
        ConfigTable::$column_table_a_2,
        ConfigTable::$column_table_a_3,
        ConfigTable::$column_table_a_4,
        ConfigTable::$column_table_a_5,
        ConfigTable::$column_table_a_6,
        ConfigTable::$column_table_a_7,
        ConfigTable::$column_table_a_8,
        ConfigTable::$column_table_a_9
      ],
      // table b
      [
        ConfigTable::$column_table_b_0,
        ConfigTable::$column_table_b_1,
        ConfigTable::$column_table_b_2,
        ConfigTable::$column_table_b_3,
        ConfigTable::$column_table_b_4,
        ConfigTable::$column_table_b_5,
        ConfigTable::$column_table_b_6,
        ConfigTable::$column_table_b_7,
        ConfigTable::$column_table_b_8,
        ConfigTable::$column_table_b_9,
        ConfigTable::$column_table_b_10,
        ConfigTable::$column_table_b_11,
        ConfigTable::$column_table_b_12
      ],
      // table c
      [
        ConfigTable::$column_table_c_0,
        ConfigTable::$column_table_c_1,
        ConfigTable::$column_table_c_2,
        ConfigTable::$column_table_c_3,
        ConfigTable::$column_table_c_4,
        ConfigTable::$column_table_c_5,
        ConfigTable::$column_table_c_6,
        ConfigTable::$column_table_c_7,
        ConfigTable::$column_table_c_8,
        ConfigTable::$column_table_c_9,
        ConfigTable::$column_table_c_10,
        ConfigTable::$column_table_c_11,
        ConfigTable::$column_table_c_12
      ],
      // table d
      [
        ConfigTable::$column_table_d_0,
        ConfigTable::$column_table_d_1,
        ConfigTable::$column_table_d_2,
        ConfigTable::$column_table_d_3,
        ConfigTable::$column_table_d_4
      ],
      // table e
      [
        ConfigTable::$column_table_e_0,
        ConfigTable::$column_table_e_1,
        ConfigTable::$column_table_e_2
      ],
      // table f
      [
        ConfigTable::$column_table_f_0,
        ConfigTable::$column_table_f_1,
        ConfigTable::$column_table_f_2,
        ConfigTable::$column_table_f_3,
        ConfigTable::$column_table_f_4,
        ConfigTable::$column_table_f_5
      ],
      // table g
      [
        ConfigTable::$column_table_g_0,
        ConfigTable::$column_table_g_1,
        ConfigTable::$column_table_g_2
      ]
    ];

    self::$ARRAY_LANG_SUPPORT = [
      ConfigTable::$enum_table_e_0_0,
      ConfigTable::$enum_table_e_0_1,
      ConfigTable::$enum_table_e_0_2
    ];

    self::$ARRAY_FILE_TYPE_SUPPORT = [
      ConfigTable::$enum_table_c_5_0,
      ConfigTable::$enum_table_c_5_1,
      ConfigTable::$enum_table_c_5_2,
      ConfigTable::$enum_table_c_5_3,
      ConfigTable::$enum_table_c_5_4,
      ConfigTable::$enum_table_c_5_5,
      ConfigTable::$enum_table_c_5_6,
      ConfigTable::$enum_table_c_5_7,
      ConfigTable::$enum_table_c_5_8,
      ConfigTable::$enum_table_c_5_9,
      ConfigTable::$enum_table_c_5_10,
      ConfigTable::$enum_table_c_5_11,
      ConfigTable::$enum_table_c_5_12,
      ConfigTable::$enum_table_c_5_13,
      ConfigTable::$enum_table_c_5_14,
      ConfigTable::$enum_table_c_5_15,
      ConfigTable::$enum_table_c_5_16,
      ConfigTable::$enum_table_c_5_17,
      ConfigTable::$enum_table_c_5_18,
      ConfigTable::$enum_table_c_5_19,
      ConfigTable::$enum_table_c_5_20,
      ConfigTable::$enum_table_c_5_21,
      ConfigTable::$enum_table_c_5_22,
      ConfigTable::$enum_table_c_5_23,
      ConfigTable::$enum_table_c_5_24,
      ConfigTable::$enum_table_c_5_25,
      ConfigTable::$enum_table_c_5_26,
      ConfigTable::$enum_table_c_5_27,
      ConfigTable::$enum_table_c_5_28,
      ConfigTable::$enum_table_c_5_29,
      ConfigTable::$enum_table_c_5_30,
      ConfigTable::$enum_table_c_5_31,
      ConfigTable::$enum_table_c_5_32,
      ConfigTable::$enum_table_c_5_33,
      ConfigTable::$enum_table_c_5_34
    ];
  }
}
?>