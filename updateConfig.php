<?php
include_once 'taskmodel.php';

$ser_id = $_POST['ser_id'];
$ser_name = $_POST['ser_name'];
$ser_email = $_POST['ser_email'];
$ser_name_zh = $_POST['ser_name_zh'];
$ser_ip = $_POST['ser_ip'];
#echo $ser_id;
updateServerConfig($ser_name, $ser_email, $ser_name_zh, $ser_ip, $ser_id);

header('Location: index.php');
?>
