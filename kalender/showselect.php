<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	
</head>
<body>

<?php include("conf.php");?>	

<?php

$con = mysqli_connect($db['host'],$db['user'],$db['password'],$db['database']);
if (!$con) {
    die('keine Verbindung zur Datenbank: ' . mysqli_error($con));
}
$n = intval($_GET['n']);
$c = $_GET['n'];
mysqli_set_charset($con, "utf8");
mysqli_select_db($con,$db['database']);

if (preg_match('/^secr/', $c))
{
	$n = substr($c, 4);
	$sql="SELECT Datum,Nummer,Gewinn, Sponsor FROM kalender WHERE (Nummer=".mysqli_real_escape_string ($con,substr($c, 4) ).")  ORDER BY Nummer";
	$result = mysqli_query($con,$sql);
	}
else
{ if (!$n) {
	echo "<table width=\"290\">";
	echo "<tr><td><div id=\"meldung\">Bitte prüfen Sie Ihre Eingabe.</div></td></tr>";
	echo "</table>";	
	die;
	}
else {
	$sql="SELECT Datum,Nummer,Gewinn, Sponsor FROM kalender WHERE (Nummer=".mysqli_real_escape_string ($con,$n ).") AND (datum <=curdate()) ORDER BY Nummer";
	$result = mysqli_query($con,$sql);
	}
}

// Set the current date and Christmas date
$currentDate = new DateTime();
$christmasDate = new DateTime('2024-12-25');
// Calculate the difference in days
$interval = $currentDate->diff($christmasDate);
$Ziehungstage = $interval->format('%a');


if (mysqli_num_rows($result)==0 ) {
	echo "<table width=\"290\">";
	echo "<tr><td><div id=\"meldung\">Die Nummer ".mysqli_real_escape_string ($con,$n )." wurde leider nicht gezogen.</div></td></tr>";
	echo "<tr><td>Nicht aufgeben, es sind noch $Ziehungstage Ziehungstage.!</td><tr>";
  echo "</table>";
}
else
{
	echo "<table width=\"290\">";
		while($row = mysqli_fetch_array($result)) {

			echo "<th>Die Nummer "; 
			echo $row['Nummer'];
			echo " hat am ";
			echo preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3.$2.$1",$row['Datum']);
			#echo $row['Datum'];
			echo " gewonnen</th>";
			echo "<tr>";
			echo "<td>Gewinn: " . $row['Gewinn'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>Sponsor: " . $row['Sponsor'] . "</td>";
			echo "</tr>";
		}
	echo "</table>";
}
mysqli_close($con);

?>
</body>
</html>