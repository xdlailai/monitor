<?php
include_once 'taskmodel.php';

$id = $_GET['id'];

deleteItem($id);

header('Location: index.php');
?>