<?php

class PAGE {
	const HOME = "Home";
	const SEARCH = "Search";
	const PACKET_INSPECTOR = "Packet Inspector";
}

function page_header($page, $header) {

	?>
<html>
	<head>
		<title><?php echo $page; ?></title>
		<link rel="stylesheet" type="text/css" href="main.css">
	</head>
	<body>
		<?php if($header) { ?>
		<table>
			<tr>
				<td><a href="index.php">Home</a></td>
				<td><a href="search.php">Search</a></td>
			</tr>
		</table>
	
	<?php
	}
}

function page_footer() {
	?>
	</body>
</html>
	<?php
}
?>
