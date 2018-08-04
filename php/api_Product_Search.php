<?php

	include 'lib_link2DB.php';


	$ProductID = !empty($_POST['ProductID']) ? $_POST['ProductID'] : "%";  
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : "%";    
	
	$sql = 
	"SELECT * ".
	"FROM Products ".
	"WHERE ";
	if($ProductID != "%") {
		$sql = $sql."ProductID = '$ProductID' AND ";
	}
	$sql = $sql.
	"Name LIKE '%$Name%' AND ".
	"State = 1 ".
	"ORDER BY ProductID ASC ;";


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