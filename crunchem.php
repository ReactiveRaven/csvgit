<?php

$aMinute = 60;
$anHour = $aMinute*60;

$lines = array();
while ($lines[] = fgetcsv(STDIN));

array_pop($lines);

$hashtags = array();
$hashtaglengths = array();

$prevDate = null;
$prevTime = null;
foreach ($lines as $line) {
   list($datetime, $description) = $line;
   $matches = array();
   preg_match_all("/(#\S+)/", $description, $matches);
   $matches = $matches[0];
   
   $time = strtotime($datetime);
   $date = date("Y-m-d", $time);
   $newday = false;
   if ($date != $prevDate) {
     $prevDate = $date;
     $prevTime = strtotime(date("Y-m-d 09:00:00", $time));
     $newday = true;
   }
   
   $timeLength = $time - $prevTime;
   
   $line[1] = date("H:i", $prevTime) . " -> " . date("H:i", $time) .  " = " . timeformat($timeLength) . " -- " . $line[1];
   $line[2] = $timeLength;
   
   $prevTime = $time;
   
   foreach ($matches as $hashtag) {
     if ($hashtag) {
      if (!isset($hashtags[$hashtag])) {
        $hashtags[$hashtag] = array();
        $hashtaglengths[$hashtag] = 0;
      }
      $hashtags[$hashtag][] = $line;
      $hashtaglengths[$hashtag] += $timeLength;
     }
   }
}

function timeformat($length) {
  global $anHour, $aMinute;
  $format = "";
  if ($length > $anHour) {
    $format .= floor($length / $anHour) . "h ";
    $length = $length % $anHour;
  } else {
    $format .= "   ";
  }
  $minutes = round($length / $aMinute);
  if ($minutes < 10) {
    $format .= " ";
  }
  $format .=  $minutes . "m";
  return $format;
}

function billabletimeformat($length) {
  global $aMinute;
  return round($length / ($aMinute * 15))/4 . "h";
}

if (isset($hashtags["#deploy"])) {
  unset($hashtags["#deploy"]);
}

foreach ($hashtags as $hashtag => $details) {
  echo $hashtag . " " . timeformat($hashtaglengths[$hashtag]) . "\n";
  $lastDate = null;
  $lastDateSum = 0;
  foreach ($details as $deet) {
    list($datetime, $description) = $deet;
    $date = date("Y-m-d", strtotime($datetime));
    if ($lastDate != $date) {
      $lastDate = $date;
      echo "  " . $date . "\n";
    }
    echo "    " . $description . "\n";
  }
}
