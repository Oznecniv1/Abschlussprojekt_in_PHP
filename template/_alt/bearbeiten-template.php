<?php
echo <<<TEMPLATE
<!Doctype html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<title>bearbeiten</title>
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
            <form action="{$_SERVER['PHP_SELF']}">
                <input type="submit" name="action" value="bearbeiten">  
            </form>
		</main>
		<footer>
			
		</footer>
	</body>
</html>
TEMPLATE;
?>