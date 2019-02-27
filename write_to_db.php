<?php

require_once "z_config.php";
require_once "z_mysql.php";
require_once "errors.php";


$con = new Z_MySQL();
$file = 'jk1.csv';
$i = 0;
$handle = fopen($file, "r");
if($handle){
    while (($row = fgetcsv($handle, 4096)) !== false) {
        // print_r($row);
        if (empty($fields)) {
            $fields = $row;
            continue;
        }
        foreach ($row as $k=>$value) {
            $array[$i][$fields[$k]] = $value;
        }
        $i++;
    }
}
//echo "<pre>";
//print_r($array);
//echo "</pre>";


foreach ($array as $key=>$value) {

     //print_r($value);
    $merchent_name_eng =  $value['Merchant name / անգլերեն'];
    $merchent_name_arm =  $value['Merchant Name / հայերեն'];
    $merchant_city_arm = $value['Merchant city հայերեն'];
    $merchant_address_arm = $value['Merchant address / հայերեն'];
    $merchant_address_eng = $value['Merchant address / Անգլերեն'];
    $merchant_category = $value['Merchant Category'];
    $status = $value['Status Redy/No'];
    $gorc = $value[' Գործունեության կոնկրետ տեսակը '];



    $con->queryDML("INSERT INTO `merchant_names` (`merchantNameID`,`text`,`langID`) VALUES (NULL,'{$merchent_name_eng}','1')");
    $merchent_name_id = $con->lastID();
    $con->queryDML("INSERT INTO `merchant_names` (`merchantNameID`,`text`,`langID`) VALUES ('{$merchent_name_id}','{$merchent_name_arm}','3')");


    $category_id = $con->queryNoDML("SELECT `category`.`categoryID` AS 'category_id' FROM `category` WHERE `category`.`text` = '{$merchant_category}' AND `category`.`langID` = '3'")[0]['category_id'];
    $city_id =  $con->queryNoDML("SELECT `city`.`cityID` AS 'city_id' FROM `city` WHERE `city`.`text` = '{$merchant_city_arm}' AND `city`.`langID` = '3'")[0]['city_id'];
    $con->queryDML("INSERT INTO `merchant` (`merchantID`,`categoryID`,`address`,`cityID`,`merchantNameID`,`isFavorite`,`coordinates`,`image`,`show_in_table`) VALUES (NULL,'$category_id','$merchant_address_arm','$city_id','$merchent_name_id','22','0','empty','1')");


}
