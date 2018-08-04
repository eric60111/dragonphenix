<?php

	include 'lib_link2DB.php';

	$IngredientPurchaseID = !empty($_POST['IngredientPurchaseID']) ? $_POST['IngredientPurchaseID'] : "%"; 
		
	$sql = 
	"SELECT IPD.IngredientPurchaseDetailID, IPD.IngredientPurchaseID, I.IngredientID, I.Name As IngredientName, IPD.UnitPrice, IPD.Quantity, IPD.ActualQuantity, I.Unit ".
	"FROM IngredientPurchaseDetails IPD, Ingredients I ".
	"WHERE ";
	if($IngredientPurchaseID != "%") {
		$sql = $sql."IPD.IngredientPurchaseID = '$IngredientPurchaseID' AND ";
	}
	$sql = $sql.
	"IPD.IngredientID = I.IngredientID AND ".
	"IPD.State = 1 ".
	"ORDER BY IPD.IngredientPurchaseDetailID ASC ;";

	
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