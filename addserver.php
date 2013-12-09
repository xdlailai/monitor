<?php
include_once 'taskmodel.php';

$ser_name = $_POST['ser_name'];
$ser_email = $_POST['ser_email'];
$ser_interval = $_POST['ser_interval'];
$ser_sendemail = $_POST['ser_sendemail'];

addserver($ser_name, $ser_email, $ser_interval, $ser_sendemail);

header('Location: index.php');
?>
