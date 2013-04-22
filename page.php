<?php

class PAGE {
	const HOME = "Home";
	const SEARCH = "Search";
	const PACKET_INSPECTOR = "Packet Inspector";
	const SIGNATURES = "Signature Search";
	const HOSTS = "Hosts";
	const GLOBE = "Attacker Locations";
	const ATTACKER = "Attacker";
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
		<div class="top_bar">
			<a href="index.php">Home</a>
			<a href="search.php">Search</a>
			<a href="signatures.php">Signatures</a>
			<a href="hosts.php">Hosts</a></td>
			<a href="globe.php">Attack Locations</a>
			<a href="attacker.php">Attackers</a>
		</div>
	
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
