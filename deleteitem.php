<?php
include_once 'taskmodel.php';

$id = $_GET['id'];
echo "hello";
echo $id;

deleteItem($id);

header('Location: index.php');
?>
