<?php
require "taskmodel.php";
set_time_limit(0);
$host = "202.117.120.237";
$port = 7000;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("could not create socket\n");
$connection = socket_connect($socket, $host, $port) or die("could not connect server\n");
#while($buff = socket_read($socket, 1024, PHP_NORMAL_READ)) {
#  echo("response was ". $buff ."\n");
#}
$buf = '';
if(false !== ($byte_received = socket_recv($socket, $buff, 6000, MSG_WAITALL))){
    echo "read $byte_received bytes from socket..\n";
    echo $buff;
}else{
    echo "socket_recv() failed; reason: ". socket_strerror(socket_last_error($socket)) ."\n";
}
socket_close($socket);
$xml=simplexml_load_string($buff);
$cpu=(float)$xml->info->cpu;
$mem=(float)$xml->info->mem;
$load=(float)$xml->info->load;
$download_rate=(float)$xml->info->download_rate;
$upload_rate=(float)$xml->info->upload_rate;
$partition_total=(float)$xml->info->partition->total;
$partition_used=(float)$xml->info->partition->used;
$partition_pct=(float)$xml->info->partition->pct;
$time=(int)$xml->info->time;
$ser_name = "life.xidian.edu.cn";
updateTotalInfo($ser_name, $time, $cpu, $mem, $load,$download_rate,$upload_rate,$partition_total, $partition_used, $partition_pct);
addEachInfo($ser_name, $time, $cpu, $mem, $load,$download_rate,$upload_rate,$partition_total, $partition_used, $partition_pct);
?>
