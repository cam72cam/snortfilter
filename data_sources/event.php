<?php
	include_once "../lib/db.php";
	
	$aColumns = array( 'event.sid', 'event.cid', 'sig_name', 'timestamp', 'ip_src', 'ip_proto');
	
	db::connect();
	
	$query = new query();
	
	$i = 0;
	if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
	{
		$Order = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
		$Dir   = ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
		$Order = $Order.' '.$Dir;
		$query->add_order($Order);
	}
	
	
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$query->signature = $_GET['sSearch'];
		$query->sig_opt = "contains";
	}

	if( isset($_GET['startDate'])) {
		$date = strtotime($_GET['startDate']);
		$query->start = date('Y-m-d', $date);
	}

	if( isset($_GET['endDate'])) {
		$date = strtotime($_GET['endDate']);
		$query->end = date('Y-m-d', $date);
	}

	if( !isset($_GET['startDate']) && !isset($_GET['endDate'])) {
		$date = strtotime("-1 day");
		$query->start = date('Y-m-d', $date);
	}

	if( isset($_GET['ipProto'])) {
		$proto = str2proto($_GET['ipProto']);
		if($proto != -1) {
			$query->proto = $proto;
		}
	}
	if( isset($_GET['sourceIp'])) {
		$query->ip_src = $_GET['sourceIp'];
	}
	
	if( isset($_GET['sigName']) && isset($_GET['sigLike'])) {
		$like = $_GET['sigLike'];
		if($like == "contains" || $like == "begins" || $like == "ends") {
			$query->signature = $_GET['sigName'];
			$query->sig_opt = $like;
		}
	}

	$res = db::query($query->build_count());
	$num_total = mysqli_fetch_array($res);
	$num_total = $num_total[0];


	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1' ) {
		$query->start_limit = intval( $_GET['iDisplayStart'] );
		$query->num_limit = intval( $_GET['iDisplayLength'] );
	} else {
		$query->num_limit=10;
	}

	$query->select  = sprintf(" %s", str_replace(" , ", " ", implode(", ", $aColumns)));
	$rResult = db::query($query->build());
	$num_results = max($rResult->num_rows, $_GET['iDisplayLength']);

	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $num_results,
		"iTotalDisplayRecords" => $num_total,
		"aaData" => array()
	);
	$aRow = mysqli_fetch_assoc( $rResult );
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$data = $aRow[$i];
			if ( $aColumns[$i] == "ip_src" ) 
				$row[] = long2ip($data);
			else if ( $aColumns[$i] == "ip_proto" )
				$row[] = proto2str($data);
			else
				$row[] = $data;
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>
