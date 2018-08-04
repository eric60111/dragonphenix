<?php

	include 'lib_link2DB.php';

	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : "%"; 
	
	$sql = 
	"SELECT * ".
	"FROM Companies ".
	"WHERE ";
	if($CompanyID != "%") {
		$sql = $sql."CompanyID = '$CompanyID' AND ";
	}
	$sql = $sql.
	"State = 1 ".
	"ORDER BY CompanyID ASC ;";
	
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