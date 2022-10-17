<?php
echo <<<TEMPLATE
<!Doctype html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		
	</head>	
	<body>
	
	<header class=flex> 

	</header>
		<nav>
		
			<div id="navWrap">
			$nav
			</div>
		</nav>
		<main>
			$ausgabe
		</main>
		<footer>
			$ausgabe1 $ausgabe2
		</footer>
	</body>
</html>
TEMPLATE;
?>