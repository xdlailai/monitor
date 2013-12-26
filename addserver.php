<?php
include_once 'taskmodel.php';

$ser_name = $_POST['ser_name'];
$ser_email = $_POST['ser_email'];

$ser_nameZh = $_POST['ser_nameZh'];
$ser_ip = $_POST['ser_ip'];


addserver($ser_name, $ser_nameZh, $ser_ip, $ser_email);

header('Location: index.php');
?>
