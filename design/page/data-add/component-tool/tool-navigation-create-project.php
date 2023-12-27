<?php
require_once("tool-navigation-toolkit.php");

// proje isim ve açıklama
$tempProjectName = isset($_POST["create-new-project-name"]) ? $_POST["create-new-project-name"] : null;
$tempProjectDescription = isset($_POST["create-new-project-description"]) ? $_POST["create-new-project-description"] : null;

// sorgu işlemleri
$DataNavigationToolkit = new DataNavigationToolkit($databaseconn);
$SendData = $DataNavigationToolkit->dataCreateNewProject($tempProjectName, $tempProjectDescription);

// sorgu sonucunu gönder
echo json_encode($SendData);
?>