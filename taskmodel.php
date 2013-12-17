<?php
function connect()
{
	// DB connection info
	$host = "localhost";
	$user = "root";
	$pwd = "19881014";
	$db = "monitor";
	try{
		$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(Exception $e){
		die(print_r($e));
	}
	return $conn;
}


function updateStatus($ser_name, $ser_time, $ser_status, $ser_code, $isDown)
{
  $conn = connect();
  $sql = "UPDATE main SET time = ?, status=?, code=?, isDown=? where name = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(1, $ser_time);
	$stmt->bindValue(2, $ser_status);
	$stmt->bindValue(3, $ser_code);
	$stmt->bindValue(4, $isDown);
	$stmt->bindValue(5, $ser_name);
	$stmt->execute();

}

function updateServerConfig($ser_name, $ser_email, $ser_name_zh, $ser_ip, $ser_id)
{
  $conn = connect();
  $sql = "UPDATE server SET name = ?, email=?, name_zh=?, ip=? where ser_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(1, $ser_name);
	$stmt->bindValue(2, $ser_email);
	$stmt->bindValue(3, $ser_name_zh);
	$stmt->bindValue(4, $ser_ip);
	$stmt->bindValue(5, $ser_id);
	$stmt->execute();

}
function getAllItems()
{
	$conn = connect();
	$sql = "SELECT * FROM server";
	$stmt = $conn->query($sql);
	return $stmt->fetchAll(PDO::FETCH_NUM);
}
function getAllNames()
{
	$conn = connect();
	$sql = "SELECT name FROM server";
	$stmt = $conn->query($sql);
	return $stmt->fetchAll(PDO::FETCH_NUM);
}

function getAllStatus()
{
	$conn = connect();
	$sql = "SELECT * FROM main";
	$stmt = $conn->query($sql);
	return $stmt->fetchAll(PDO::FETCH_NUM);
}

function getOneStatus($ser_name)
{
	$conn = connect();
	$sql = "SELECT * FROM main where name ='$ser_name'";
	$stmt = $conn->query($sql);
	return $stmt->fetchAll(PDO::FETCH_NUM);
}
function getMyConfig($ser_name)
{
	$conn = connect();
	$sql = "SELECT * FROM server where name ='$ser_name'";
	$stmt = $conn->query($sql);
	return $stmt->fetchAll(PDO::FETCH_NUM);
}

function getIfdown($ser_name)
{
  $conn = connect();
  $sql = "SELECT isdown FROM main where name ='$ser_name'";
	$stmt = $conn->query($sql);
        $res = $stmt->fetch();
	return $res['isdown'];
}
function getMailname($ser_name)
{
  $conn = connect();
  $sql = "SELECT email FROM server where name ='$ser_name'";
	$stmt = $conn->query($sql);
	$res = $stmt->fetch();
	return $res['email'];
}
function getOldtime($ser_name)
{
  $conn = connect();
  $sql = "SELECT time FROM main where name ='$ser_name'";
	$stmt = $conn->query($sql);
	$res = $stmt->fetch();
	return $res['time'];
}
#$test = getMailname("linux.xidian.edu.cn");
#echo $test;

function addserver($name, $email, $interval, $sendemail)
{
  $isin = false;
  $items = getAllItems();
  foreach($items as $item)
  {
    if($item[1] == $name)
    {
      $isin = true;
      echo "该服务器已监控";
      return;
    }
  }
	$conn = connect();
	$sql = "INSERT INTO server (name, email, interval_time, send_email) VALUES (?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(1, $name);
	$stmt->bindValue(2, $email);
	$stmt->bindValue(3, $interval);
	$stmt->bindValue(4, $sendemail);
	$stmt->execute();

	$conn2 = connect();
	$sql2 = "INSERT INTO main (name, time, status, isDown) VALUES (?, ?, ?, 0)";
	$stmt2 = $conn2->prepare($sql2);
	$stmt2->bindValue(1, $name);
	$stmt2->bindValue(2, '0');
	$stmt2->bindValue(3, '0');
	$stmt2->execute();
}

function addEachStatus($name, $time, $status, $code, $isdown,$pingtime)
{
	$conn2 = connect();
	$sql2 = "INSERT INTO all_status (name, time, status, code, isdown, ping_time) VALUES (?, ?, ?, ?, ?, ?)";
	$stmt2 = $conn2->prepare($sql2);
	$stmt2->bindValue(1, $name);
	$stmt2->bindValue(2, $time);
	$stmt2->bindValue(3, $status);
	$stmt2->bindValue(4, $code);
	$stmt2->bindValue(5, $isdown);
	$stmt2->bindValue(6, $pingtime);
	$stmt2->execute();

}
#addEachStatus('life.xidian.edu.cn', 1233, 'Up', 301, 0, 0);
function deleteItem($item_name)
{
	$conn = connect();
	$sql = "DELETE FROM server WHERE name = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(1, $item_name);
	$stmt->execute();
	$conn = connect();
	$sql = "DELETE FROM main WHERE name = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(1, $item_name);
	$stmt->execute();
}

function getSuccessRate($ser_name, $interval)
{
  $seconds = 60 * 60 * 24 * $interval;
  $firstime = time() - $seconds;
  #echo $firstime;
  #echo "\n";
  $conn = connect();
  $sql = "SELECT COUNT(*) FROM all_status WHERE isdown = '0' and time > '$firstime' and name = '$ser_name'";
	$stmt = $conn->query($sql);
	$sucessTmp = $stmt->fetch();
	$success= $sucessTmp['COUNT(*)'];
  $conn2 = connect();
  $sql2 = "SELECT COUNT(*) FROM all_status WHERE isdown = '1' and time > '$firstime' and name = '$ser_name'";
	$stmt2 = $conn2->query($sql2);
	$failTmp = $stmt2->fetch();
  $fail= $failTmp['COUNT(*)'];
  $success_rate = round(($success*100)/($fail+$success), 2);
  return $success_rate;
}

function CheckPasswd($username, $password)
{
    $conn = connect();
    $str="SELECT * FROM admin_info WHERE username='$username';";
    $stmt = $conn->query($str);
    $dataTmp = $stmt->fetch();
    $data = $dataTmp['password'];
    if(md5($password)==$data)
    {
        $_SESSION["username"] = $username;
        echo("<script>window.location='./index.php'</script>");
    }else
    {
        echo("<script language=\"JavaScipt\">alert(\"密码错误!\");</script>");
        echo("<script>window.location='./login.html'</script>");
    }

}
?>
