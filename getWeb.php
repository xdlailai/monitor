<?php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://coolshell.cn');
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($curl);
curl_close($curl);
var_dump($data);
