<?php
require('ElStr.class.php') ;
?>
<!doctype html>
<html lang="el">
<head>
	<meta charset="utf-8">
	<title>ElStr.class example</title>
</head>
<body>
	<form name="tolatin" action="test.php" method="get">
		<label for="grtxt">Greek Text</label>
		<input id="grtxt" name="grtxt" type="text" size="100" value="Το μπαρμπουνάκι θέλει μπυρίτσα και το κορίτσι θάλασσα">
		<input type="submit">
	</form><br>
	<div>
	<?php
	if(isset($_GET["grtxt"])){
		$usrin = trim(htmlspecialchars($_GET["grtxt"])) ;
		$elstrObj = new El_Str() ;

		echo "<div><strong>Original text: </strong>" ;
		echo $usrin ;
		echo "</div><br>" ;

		echo "<div><strong><code>to_latin</code> method applied: </strong>" ;
		echo $elstrObj->to_latin($usrin) ;
		echo "</div><br>" ;

		echo "<div><strong><code>strtoupper_no_accent</code> method applied: </strong>" ;
		echo $elstrObj->strtoupper_no_accent($usrin) ;
		echo "</div><br>" ;

		echo "<div><strong><code>strtolower_no_accent</code> method applied: </strong>" ;
		echo $elstrObj->strtolower_no_accent($usrin) ;
		echo "</div><br>" ;

		echo "<div><strong><code>str_no_accent</code> method applied: </strong>" ;
		echo $elstrObj->str_no_accent($usrin) ;
		echo "</div><br>" ;
	}
	?>
	</div>
</body>
</html>