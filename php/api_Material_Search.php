<?php

	include 'lib_link2DB.php';


	$MaterialID = !empty($_POST['MaterialID']) ? $_POST['MaterialID'] : "%";  
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : "%";   
		
	$sql = 
	"SELECT * ".
	"FROM Materials ".
	"WHERE ";
	if($MaterialID != "%") {
		$sql = $sql."MaterialID = '$MaterialID' AND ";
	}
	$sql = $sql.
	"Name LIKE '%$Name%' AND ".
	"State = 1 ".
	"ORDER BY MaterialID ASC ;";
	
	
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