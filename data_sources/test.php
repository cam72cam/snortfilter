<?php
	include_once "../lib/db.php";
	
	$aColumns = array( 'sig_name', 'timestamp', 'ip_src', 'ip_proto');
	
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
	}
	
	
	$res = db::query($query->build_count());
	$num_total = mysqli_fetch_array($res);
	$num_total = $num_total[0];


	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1' ) {
		$query->start_limit = intval( $_GET['iDisplayStart'] );
		$query->num_limit = intval( $_GET['iDisplayLength'] );
	}

	$query->select  = sprintf(" `%s`", str_replace(" , ", " ", implode("`, `", $aColumns)));
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
			$data = $aRow[ $aColumns[$i] ];
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
