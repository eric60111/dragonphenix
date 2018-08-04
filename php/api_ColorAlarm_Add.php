<?php

	include 'lib_link2DB.php';
	
	/** Add ColorAlarm **/
	$ModelID = !empty($_POST['ModelID']) ? $_POST['ModelID'] : null;
	$Number = !empty($_POST['Number']) ? $_POST['Number'] : null; 
	$Color = !empty($_POST['Color']) ? $_POST['Color'] : null;
	
	
	$sql = "INSERT INTO ColorAlarms VALUES ";
	$sql .= "(NULL, '$ModelID', '$Number', '$Color', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$ColorAlarmID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ColorAlarmID' => $ColorAlarmID
    );
	
	print(json_encode($resp));
?>