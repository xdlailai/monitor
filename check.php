<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>登录--NewPro校园网安全流量控制网关</title>
</head>

<body>
<?php
	session_start();
	include_once("taskmodel.php");
	$username = $_POST["username"];
	$password = $_POST["password"];
	//echo $username;
	//echo $password;
	CheckPasswd($username,$password);
?>
</body>
</html>
