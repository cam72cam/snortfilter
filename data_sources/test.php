<?php
	include_once "../lib/db.php";
	
	$aColumns = array( 'sig_name', 'timestamp', 'ip_src', 'ip_proto');
#	/* Indexed column (used for fast and accurate table cardinality) */
#	$sIndexColumn = "id";
	
	db::connect();
	
	$query = new query();
	

/*	if ( isset( $_GET['iSortCol_0'] ) )
	{
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}*/
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */

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
			if ( $aColumns[$i] == "ip_src" ) {
				$row[] = long2ip($aRow[ $aColumns[$i] ]);
			}
			else if ( $aColumns[$i] == "ip_proto" ) {
				$row[] = proto2str($aRow[$aColumns[$i]]);
			}
			else {
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>
