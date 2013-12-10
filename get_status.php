<?php

/*
 * Checks a website and returns a one-word summary of its status and an HTTP code.
 */

/*
 * Define how you'd like to present your various http codes  in this array.
 * The values should be one word only, as they are used as css classes.
 */

$httpCodesList = array(
    '0'   => 'Unreachable',
    '100' => 'Up',
    '101' => 'Up',
    '102' => 'Up',
    '103' => 'Up',
    '122' => 'Problem',
    '200' => 'Up',
    '201' => 'Up',
    '202' => 'Problem',
    '203' => 'Up',
    '204' => 'Problem',
    '205' => 'Problem',
    '206' => 'Up',
    '207' => 'Up',
    '208' => 'Up',
    '226' => 'Up',
    '300' => 'Up',
    '301' => 'Up',
    '302' => 'Up',
    '303' => 'Up',
    '304' => 'Up',
    '305' => 'Problem',
    '306' => 'Up',
    '307' => 'Redirecting',
    '308' => 'Problem',
    '400' => 'Problem',
    '401' => 'Up',
    '402' => 'Up',
    '403' => 'Up',
    '404' => 'Down',
    '405' => 'Problem',
    '406' => 'Problem',
    '407' => 'Problem',
    '408' => 'Timeout',
    '409' => 'Problem',
    '410' => 'Down',
    '411' => 'Problem',
    '412' => 'Down',
    '413' => 'Problem',
    '414' => 'Problem',
    '415' => 'Problem',
    '416' => 'Problem',
    '417' => 'Down',
    '418' => 'Teapot',
    '422' => 'Problem',
    '423' => 'Problem',
    '424' => 'Problem',
    '425' => 'Problem',
    '426' => 'Problem',
    '428' => 'Problem',
    '429' => 'Problem',
    '431' => 'Problem',
    '444' => 'Down',
    '449' => 'Problem',
    '450' => 'Unreachable',
    '499' => 'Problem',
    '500' => 'Down',
    '501' => 'Down',
    '502' => 'Down',
    '503' => 'Down',
    '504' => 'Timeout',
    '505' => 'Problem',
    '506' => 'Problem',
    '507' => 'Problem',
    '508' => 'Problem',
    '509' => 'Down',
    '510' => 'Problem',
    '511' => 'Problem',
    '598' => 'Timeout',
    '599' => 'Timeout'
);

/**
 * Returns the http status code for a given url.
 *
 * @param string $url
 * @return int
 */
function getHttpStatusCode($url) {
    $curl = curl_init($url);
    curl_setopt_array($curl, array(
        CURLOPT_HEADER => false,
        CURLOPT_NOBODY => true
    ));
    $request = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $status;
}
function get_data($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

/**
 * Returns a human readable, one word summary of an http status code.
 * Example: 200 = "Up"
 * Example: 404 = "Down"
 * Example: 301 = "Redirecting"
 *
 * @param int $httpCode
 * @param array $httpCodesList a keyed array of http codes and their summaries.
 * @return string
 */
function getStatus($httpCode, $httpCodesList) {
    return $httpCodesList[strval($httpCode)];
}


include_once 'getitems.php';
$tmpEmail = "13571899664@139.com";
$items = getItems();
if(!empty($items)) {
    foreach($items as $item)
    {
      $old_isdown = getIfdown($item[1]);
      $response = getHttpStatusCode($item[1]);
      $email = getMailname($item[1]);
      $oldtime = getOldtime($item[1]);
      $subject_err = $item[1]." error";
      $subject_rec = $item[1]." recover";
      $status['url'] = $item[1];
      $status['status'] = getStatus($response, $httpCodesList);
      $status['code'] = $response;
      $t=time();
      if($status['status'] == "Up")
        $now_isdown = 0;
      else
        $now_isdown = 1;
      addEachStatus($status['url'], $t, $status['status'],$status['code'], $now_isdown, 0);
      if($old_isdown == 0){
        if($status['status'] == "Up"){
          updateStatus($status['url'], $t, $status['status'], $status['code'], 0);
        }else{
          updateStatus($status['url'], $t, $status['status'], $status['code'], 1);
          echo $status['url']." error, send to ".$email;
          $webContent = get_data($status['url']);
          #mail 服务器down;
          mail($email,$subject_err,$webContent);
          mail($tmpEmail,$subject_err,$webContent);
        }
      }else{
        if($status['status'] == "Up"){
          updateStatus($status['url'], $t, $status['status'], $status['code'], 0);
          #mail 服务器recover
          $longTime = round(($t - $oldtime)/60);
          $webdata = "故障持续时间". $longTime. "分";
          echo $status['url']." recover, send to ".$email;
          mail($email,$subject_rec,$webdata);
          mail($tmpEmail,$subject_rec,$webdata);
        }else{
          updateStatus($status['url'], $oldtime, $status['status'], $status['code'], 1);
        }
      }
      #print_r($status);
    }
}
else {
    echo " no items to do.";
}

?>
