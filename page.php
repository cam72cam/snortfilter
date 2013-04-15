<?php

class PAGE {
	const HOME = "Home";
	const SEARCH = "Search";
}

function page_header($page, $title) {

	?>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<link rel="stylesheet" type="text/css" href="main.css">
	</head>
	<body>
	<table>
		<tr>
			<td><a href="index.php">Home</a></td>
			<td><a href="search.php">Search</a></td>
		</tr>
	</table>
	
	<?php
}

function page_footer() {
	?>
	</body>
</html>
	<?php
}

?>
