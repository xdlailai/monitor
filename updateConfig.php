<?php
include_once 'taskmodel.php';

$ser_id = $_POST['ser_id'];
$ser_name = $_POST['ser_name'];
$ser_email = $_POST['ser_email'];
$ser_frequency = $_POST['ser_frequency'];
$ser_sendemail = $_POST['ser_sendemail'];
#echo $ser_id;
updateServerConfig($ser_name, $ser_email, $ser_frequency, $ser_sendemail, $ser_id);

header('Location: index.php');
?>
