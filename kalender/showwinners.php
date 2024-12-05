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

$q = intval($_GET['q']);
$con = mysqli_connect($db['host'],$db['user'],$db['password'],$db['database']);

if (!$con) {
    die('keine Verbindung zur Datenbank: ' . mysqli_error($con));
}

mysqli_set_charset($con, "utf8");
mysqli_select_db($con,$db['database']);


if ($q==1919) {$sql="SELECT Datum, Nummer,Gewinn, Sponsor FROM kalender ORDER BY Tag";
$result = mysqli_query($con,$sql);

	echo "<table width=\"450\">";
	echo "<th>Datum</th><th>Losnummer</th><th>Gewinn</th><th>Sponsor</th>";
	while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['Datum'] . "</td>";
			echo "<td>" . $row['Nummer'] . "</td>";
			echo "<td>" . $row['Gewinn'] . "</td>";
			echo "<td>" . $row['Sponsor'] . "</td>";
			echo "</tr>";
		}
	echo "</table>";

}

if (mysqli_real_escape_string ($con,$q )==0) {
echo "<table width=\"450\">";
echo "<tr><td>Für dieses Datum liegt keine Ziehung vor</td></tr>";
echo "</table>";
  
}
else
{

$sql="SELECT Nummer,Gewinn, Sponsor FROM kalender WHERE (datum='2024-12-".mysqli_real_escape_string ($con,sprintf("%02d", $q) )."') AND (datum <=curdate()) ORDER BY Nummer";
$sql_ohne="SELECT Gewinn, Sponsor FROM kalender WHERE (datum='2024-12-".mysqli_real_escape_string ($con,sprintf("%02d", $q) )."')  ORDER BY Gewinn";


  
$result = mysqli_query($con,$sql);
$result_ohne = mysqli_query($con,$sql_ohne);


	echo "<table width=\"450\">";
//	echo "<tr><td><img src=\".$gewinnbild.\" width=\"200\" height=\"190\"></td><td><img src=\".$sponsorbild.\" width=\"200\" height=\"200\"></td>"; -->
  echo "</tr>";
	echo "</table>";

	echo "<table width=\"450\">";

if (mysqli_num_rows($result)==0 ) {
	echo "<tr><td><div id=\"meldung\">Für diesen Tag liegen noch keine Ziehungsdaten vor!</div></td></tr>";
	echo "<tr><td>Aber das sind die Gewinne für diesen Tag:</td><tr>";
	echo "</table>";
 
	echo "<table width=\"450\">";
	echo "<th>Gewinn</th><th>Sponsor</th>";
	while($row = mysqli_fetch_array($result_ohne)) {
			echo "<tr>";
			echo "<td>" . $row['Gewinn'] . "</td>";
			echo "<td>" . $row['Sponsor'] . "</td>";
			echo "</tr>";
		}

}
else
{
	echo "<th>Losnummer</th><th>Gewinn</th><th>Sponsor</th>";
	while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['Nummer'] . "</td>";
			echo "<td>" . $row['Gewinn'] . "</td>";
			echo "<td>" . $row['Sponsor'] . "</td>";
			echo "</tr>";
		}
}
	echo "</table>";
}

mysqli_close($con);
?>
</body>
</html>
