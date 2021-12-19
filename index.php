<link rel="stylesheet" href="style.css">

<?php
require_once "db/credentials.php";
$conn = new mysqli($servername, $username, $password, $db);

echo "<table><th>Paragraf</th><th>Rozpočet</th><th>Čerpání</th>";

//detail
if (strlen($_GET["paragraf"])){

  //strankovani
  $limit = 20;
  $total = "SELECT count(*) FROM rozpocet where paragraf like '".$_GET["paragraf"]."%'";
  $total = $conn->query($total);
  $total = $total->fetch_assoc()["count(*)"];
  $pages = ceil($total / $limit);

  if($_GET["s"]){
    $s = (int) $_GET["s"];
    $s = $s * $limit;
    $paragraf = "SELECT paragraf, rozpocet, cerpani FROM rozpocet where paragraf like '".$_GET["paragraf"]."%' limit ".$s.", ".$limit;
  }
  else{
    $paragraf = "SELECT paragraf, rozpocet, cerpani FROM rozpocet where paragraf like '".$_GET["paragraf"]."%' limit ".$limit;
  }

  $paragraf = $conn->query($paragraf);

  if ($paragraf->num_rows > 0) {
    while($r = $paragraf->fetch_assoc()) {
      echo "<tr><td class='paragraf'>".$r["paragraf"]."</td><td>".number_format($r["rozpocet"])."</td><td>".number_format($r["cerpani"])."</td></tr>";
    }
    echo "</table>";

    //strankovani
    if($pages > 1){
      for ($i = 0; $i < $pages;  $i++){
        echo "<a href='?paragraf=".$_GET["paragraf"]."&s=".($i)."'>".$i."</a>&nbsp;";
      }
    }
  }
}
//vypis
else {
  $paragraf1 = "SELECT LEFT(paragraf, 1) AS paragraf1 FROM rozpocet GROUP BY paragraf1";
  $paragraf1 = $conn->query($paragraf1);

  if ($paragraf1->num_rows > 0) {

    while($r = $paragraf1->fetch_assoc()) {
      $sum = "SELECT SUM(rozpocet), SUM(cerpani) FROM rozpocet where paragraf like '".$r["paragraf1"]."%'";
      $sumRow = $conn->query($sum)->fetch_assoc();

      $sumRozpocetFinal += $sumRow["SUM(rozpocet)"];
      $sumCerpaniFinal  += $sumRow["SUM(cerpani)"];

      echo "<tr><td class='paragraf'><a href='?paragraf=".$r["paragraf1"]."'>".$r["paragraf1"]."</a></td><td>".number_format($sumRow["SUM(rozpocet)"])."</td><td>".number_format($sumRow["SUM(cerpani)"])."</td></tr>";
    }
    echo "<th>Celkem</th><th>".number_format($sumRozpocetFinal)."</th><th>".number_format($sumCerpaniFinal)."</th>";
    echo "</table>";
  }
}

$conn->close();


?>