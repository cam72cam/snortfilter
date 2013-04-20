<?php

include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::SEARCH, true);

db::connect();

$beginsSelected = $_POST['sigLike'] == "begins" ? "checked" : "";
$endsSelected = $_POST['sigLike'] == "ends" ? "checked" : "";
$containsSelected = $_POST['sigLike'] == "contains" ? "checked" : "";

$anySelected = $_POST['ipProto'] == "ANY" ? "selected" : "";
$tcpSelected = $_POST['ipProto'] == "TCP" ? "selected" : "";
$udpSelected = $_POST['ipProto'] == "UDP" ? "selected" : "";
$icmpSelected = $_POST['ipProto'] == "ICMP" ? "selected" : "";
$eigrpSelected = $_POST['ipProto'] == "EIGRP" ? "selected" : "";



?>

	Welcome to the search page. 

	<form method="post">
		<table>
			<tr>
				<td>Signature:</td>
				<td> <input type="text" name="sigName" value="<?php echo $_POST['sigName']; ?>"> </td>
			</tr>

		   	<tr>
				<td>
					<input type="radio" name="sigLike" value="begins" <?php echo $beginsSelected?> >Begins with
					<input type="radio" name="sigLike" value="ends" <?php echo $endsSelected?> >Ends with
					<input type="radio" name="sigLike" value="contains"<?php echo $containsSelected?> >Contains
				</td>
			</tr>
			<tr>
				<td>Start Date:</td>
				<td> <input type="text" name="startDate" id="startDate"></td>
			</tr>
			<tr>
				<td>End Date:</td>
				<td> <input type="text" name="endDate" id="endDate"></td>
			</tr>
			<tr>
				<td>Source IP:</td>
				<td> <input type="text" name="sourceIp" value="<?php echo $_POST['sourceIp']; ?>"></td>
			</tr>
			<tr>
				<td>IP Protocol</td>
				<td>
					<select name="ipProto">
							<option value="ANY" <?php echo $anySelected; ?>>Any</option>
							<option value="TCP" <?php echo $tcpSelected; ?>>TCP</option>
							<option value="UDP" <?php echo $udpSelected; ?>>UDP</option>
							<option value="ICMP" <?php echo $icmpSelected; ?>>ICMP</option>
							<option value="EIGRP" <?php echo $eigrpSelected; ?>>EIGRP</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Submit" name="submit"></td>
			</tr>
		</table>
	</form> 

	<script type="text/javascript">
		$(function() {
			var date_ = new Date();
			prevDate = date_.getDate() - 1;
			
			<?php
			if(isset($_POST['endDate'])) {
				$date = strtotime($_POST['endDate']);
				echo sprintf("date_ = new Date(%s);",  date('Y,m-1,d', $date)) ;
			}
			?>
			
			var end = $( "#endDate" ).datepicker();
			end.datepicker("setDate", date_);

			date_.setDate(prevDate);

			<?php
			if(isset($_POST['startDate'])) {
				$date = strtotime($_POST['startDate']);
				echo sprintf("date_ = new Date(%s);",  date('Y,m-1,d', $date)) ;
			}
			?>

			var start = $( "#startDate" ).datepicker();
			start.datepicker("setDate", date_);
		});
	</script>

<?php

// Handle User Input

if(isset($_POST['submit'])) {
	$params = array();
	foreach($_POST as $key => $value) {
		if($key != 'submit' && !empty($value)) {
			//TODO Add better checking
			$params[$key] = $value;
		}
	}
	event_table($params);
}

page_footer ();

?>
