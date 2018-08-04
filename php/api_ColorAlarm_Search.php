<?php

	include 'lib_link2DB.php';

	$ModelID = !empty($_POST['ModelID']) ? $_POST['ModelID'] : "%"; 
		
	$sql = 
	"SELECT * ".
	"FROM ColorAlarms ".
	"WHERE ";
	if($ModelID != "%") {
		$sql = $sql."ModelID = '$ModelID' AND ";
	}
	$sql = $sql.
	"State = 1 ".
	"ORDER BY ModelID, Number ASC ;";
	
	$res = mysqli_query($lib_link, stripslashes($sql));
	while($result = mysqli_fetch_assoc($res) ) {
		$resp[] = $result;
	}
	mysqli_close($lib_link);
	
	if(isset($resp)) {
		print(json_encode($resp));
	}else{
		print(json_encode(array('result' => 'empty')));
	}
?>