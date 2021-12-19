<?php

/*
 * https://test.petrvopalecky.cz/
 */

require_once "db/credentials.php";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$file = fopen("data.csv", "r");

while (($line = fgetcsv($file)) !== FALSE) {
  $line = explode(";", $line[0]);
  $paragraf = $line[0];
  $rozpocet = $line[1];
  $cerpani  = $line[2];
  
  if(is_numeric($paragraf) && is_numeric($rozpocet) && is_numeric($cerpani)){
    $select = "SELECT * FROM rozpocet where paragraf = '".$paragraf."'";
    $result = $conn->query($select);

    if ($result->num_rows > 0) {
      while($r = $result->fetch_assoc()) {
        $rozpocetSum = $r["rozpocet"] + $rozpocet;
        $cerpaniSum  = $r["cerpani"] + $cerpani;

        $update = "UPDATE rozpocet SET rozpocet='".$rozpocetSum."', cerpani='".$cerpaniSum."' WHERE  paragraf = '".$paragraf."'";
        mysqli_query($conn, $update);

        $rozpocetSum = 0;
        $cerpaniSum = 0;
      }
    }
    else {
      $insert = $sql = "INSERT INTO rozpocet (paragraf, rozpocet, cerpani) VALUES ('".$paragraf."', '".$rozpocet."', '".$cerpani."')";
      mysqli_query($conn, $insert);
    }
  }
}

fclose($file);

?>

