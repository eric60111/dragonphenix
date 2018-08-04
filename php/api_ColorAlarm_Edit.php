<?php

	include 'lib_link2DB.php';
	
	/** Edit ColorAlarm **/
	$ColorAlarmID = !empty($_POST['ColorAlarmID']) ? $_POST['ColorAlarmID'] : null; 
	$Number = !empty($_POST['Number']) ? $_POST['Number'] : null; 
	$Color = !empty($_POST['Color']) ? $_POST['Color'] : null;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	
	
	$sql = 
	"UPDATE ColorAlarms SET ".
	"Number = '$Number', ".
	"Color = '$Color', ".
	"State = '$State' ".
	"WHERE ColorAlarmID = '$ColorAlarmID'";
	
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);	
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'ColorAlarmID' => $ColorAlarmID
    );
	
	print(json_encode($resp));
?>