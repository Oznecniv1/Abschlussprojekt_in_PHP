<?php
echo <<<TEMPLATE
<!Doctype html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>rezepteansicht</title>
		<link rel="stylesheet" href="css/spiel.css" type="text/css">
	</head>
	<header>

	</header>
	<nav>
		<div id=navWrap>
		</div>
	</nav>
	<body>
		<main>
			$ausgabe
		</main>
		<footer id=footer>
			
		</footer>
	</body>
</html>
TEMPLATE;
?>