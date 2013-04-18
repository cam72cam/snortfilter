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
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.2/themes/ui-darkness/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
		<script type="text/javascript" src="jquery/jquery.dataTables.js"></script>
		<script type="text/javascript" src="site.js"></script>
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
