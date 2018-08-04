<?php

	include 'lib_link2DB.php';

	$IngredientShipmentID = !empty($_POST['IngredientShipmentID']) ? $_POST['IngredientShipmentID'] : "%"; 
		
	$sql = 
	"SELECT ISD.IngredientShipmentDetailID, ISD.IngredientShipmentID, I.IngredientID, I.Name As IngredientName, ISD.SoldPrice, ISD.Quantity, ISD.ActualQuantity, I.Unit ".
	"FROM IngredientShipmentDetails ISD, Ingredients I ".
	"WHERE ";
	if($IngredientShipmentID != "%") {
		$sql = $sql."ISD.IngredientShipmentID = '$IngredientShipmentID' AND ";
	}
	$sql = $sql.
	"ISD.IngredientID = I.IngredientID AND ".
	"ISD.State = 1 ".
	"ORDER BY ISD.IngredientShipmentDetailID ASC ;";

	
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