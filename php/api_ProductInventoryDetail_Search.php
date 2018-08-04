<?php

	include 'lib_link2DB.php';

	$ProductInventoryID = !empty($_POST['ProductInventoryID']) ? $_POST['ProductInventoryID'] : "%"; 
		
	$sql = 
	"SELECT PID.ProductInventoryDetailID, PID.ProductInventoryID, P.ProductID, P.Name As ProductName, PID.Inventory, PID.Difference, P.Unit ".
	"FROM ProductInventoryDetails PID, Products P ".
	"WHERE ";
	if($ProductInventoryID != "%") {
		$sql = $sql."PID.ProductInventoryID = '$ProductInventoryID' AND ";
	}
	$sql = $sql.
	"PID.ProductID = P.ProductID AND ".
	"PID.State = 1 ".
	"ORDER BY PID.ProductInventoryDetailID ASC ;";

	
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