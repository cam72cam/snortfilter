<?php

include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::SEARCH, true);

db::connect();
 

?>

	Welcome to the search page. 

	<form method="post">
		<table>
			<tr>
				<td>Signature:</td>
				<td> <input type="text" name="sigName" value="<?php echo $_POST['sigid']; ?>"> </td>
			</tr>

		   	<tr>
				<td>
					<input type="radio" name="sigLike" value="begins">Begins with
					<input type="radio" name="sigLike" value="ends">Ends with
					<input type="radio" name="sigLike" value="contains">Contains
				</td>
			</tr>
			<tr>
				<td>Start Date:</td>
				<td> <input type="text" name="startDate" id="startDate" value="<?php echo $_POST['startDay']; ?>"></td>
			</tr>
			<tr>
				<td>End Date:</td>
				<td> <input type="text" name="endDate" id="endDate" value="<?php echo $_POST['endDay']; ?>"></td>
			</tr>
			<tr>
				<td>Source IP:</td>
				<td> <input type="text" name="sourceIp" value="<?php echo $_POST['Source IP']; ?>"></td>
			</tr>
			<tr>
				<td>IP Protocol</td>
				<td>
					<select name="ipProto">
							<option value="ANY">Any</option>
							<option value="TCP">TCP</option>
							<option value="UDP">UDP</option>
							<option value="ICMP">ICMP</option>
							<option value="EIGRP">EIGRP</option>
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

			var end = $( "#endDate" ).datepicker();
			end.datepicker("setDate", date_);

			date_.setDate(prevDate);

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
