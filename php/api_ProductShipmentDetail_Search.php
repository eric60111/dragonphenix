<?php

	include 'lib_link2DB.php';

	$ProductShipmentID = !empty($_POST['ProductShipmentID']) ? $_POST['ProductShipmentID'] : "%"; 
		
	$sql = 
	"SELECT PSD.ProductShipmentDetailID, PSD.ProductShipmentID, P.ProductID, P.Name As ProductName, PSD.SoldPrice, PSD.Quantity, PSD.ActualQuantity, P.Unit ".
	"FROM ProductShipmentDetails PSD, Products P ".
	"WHERE ";
	if($ProductShipmentID != "%") {
		$sql = $sql."PSD.ProductShipmentID = '$ProductShipmentID' AND ";
	}
	$sql = $sql.
	"PSD.ProductID = P.ProductID AND ".
	"PSD.State = 1 ".
	"ORDER BY PSD.ProductShipmentDetailID ASC ;";

	
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