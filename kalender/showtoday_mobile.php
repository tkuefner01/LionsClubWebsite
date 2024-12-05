<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
       "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<title>Adventskalender 2024</title>

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.min.css" rel="stylesheet">	
		<link href="table.css" rel="stylesheet">
	
	<script>


</script>
</head>

<body>
<?php include("conf.php");?>	
<?php

$con = mysqli_connect($db['host'],$db['user'],$db['password'],$db['database']);
if (!$con) {
    die('keine Verbindung zur Datenbank: ' . mysqli_error($con));
}
$n = intval($_GET['s']);
$c = $_GET['s'];
mysqli_set_charset($con, "utf8");
mysqli_select_db($con,$db['database']);

$currentDate = time(); // Get the current timestamp
$startDate = strtotime('2024-12-01'); // Convert the start date to a timestamp
$endDate = strtotime('2024-12-24');   // Convert the end date to a timestamp

if ($currentDate >= $startDate && $currentDate <= $endDate) {

    echo "<table width=\"350\">";
    echo "<tr><td>Die Gewinnzahlen für den ".date('d.m.Y')."</td><tr>";
    echo "<tr><td>Folgende Kalendernummern wurden heute gezogen und haben den angegebenen Preis gewonnen!</td>";
    echo "</table>";

$sql="SELECT Datum,Nummer,Gewinn, Sponsor FROM kalender WHERE datum =curdate() order by Nummer";
   
    $result = mysqli_query($con,$sql);



    echo "<table width=\"350\">";
	echo "<th>Losnummer</th><th>Gewinn</th><th>Sponsor</th>";
	while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['Nummer'] . "</td>";
			echo "<td>" . $row['Gewinn'] . "</td>";
			echo "<td>" . $row['Sponsor'] . "</td>";
			echo "</tr>";
		}
	echo "</table>";

} else {
    echo "<table width=\"350\">";
    echo "<tr><td>Heute ist <strong>kein Ziehungstag</strong></td><tr>";
    echo "</table>";
}
mysqli_close($con);

?>
</body>
</html>