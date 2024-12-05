<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
       "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<title>Adventskalender 2024</title>

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">

        <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.min.css" rel="stylesheet">	
		<link href="table.css" rel="stylesheet">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script type="text/javascript">

function ZeigeGewinn(Nummer) {
 
	$.ajax({
 url:"showselect.php?n="+Nummer
 }).done(function(data) {
						 $("#zeige").html(data);						 
						 });
  };

$(function() {
	$("#Abfrage").click(function () {
		ZeigeGewinn($("#num").val());
	});	
});
		
</script>


</head>
<body margin="none" padding="none">

<table width="290">
<th>Bitte tragen Sie Ihre Losnummer ein</th>
<tr><td><input class="ui-corner-all" type="text" name="nummer" id="num"><button class="ui-button-text" id="Abfrage">Los!</button></td></tr>

</table>
<div id="zeige"></div>
