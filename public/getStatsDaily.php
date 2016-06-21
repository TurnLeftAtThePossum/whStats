<?php

  // Commentssss
  //did i do it
  $servername = "localhost";
  $username = "whdata";
  $password = "whdata";
  $db = "killstats";
  $toEncode = array();

  $conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

  $query = "SELECT * FROM lastkill";
  $result = $conn->query($query)->fetch_assoc();
  $fromDate = $result["fromDate"];
  $lastCheck = $result["lastCheck"];

  $from = date_format(date_create($lastCheck), 'Ymd0000');
  $lastCheck = date_format(date_create($lastCheck), 'Ymd2359');

  $query = "SELECT * FROM dailykills";
  $out = "Kills from ".$from." to ".$lastCheck." <br />\n";
  $result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $class = $row["class"];
        $out .= "C".$row["class"]." - ".$row["hourKills"]."<br />\n";
        $toEncode[] = $row;
    }
}

  $out .= "<br />Running total since {$fromDate}<br />\n";
  $result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $class = $row["class"];
        $out .= "C".$row["class"]." - ".$row["totalKills"]."<br />\n";
    }
}
  //printf($out);
  $output = json_encode($toEncode);
  echo $output;
  return $output;
