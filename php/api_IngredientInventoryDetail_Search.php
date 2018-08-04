<?php

	include 'lib_link2DB.php';

	$IngredientInventoryID = !empty($_POST['IngredientInventoryID']) ? $_POST['IngredientInventoryID'] : "%"; 
		
	$sql = 
	"SELECT IID.IngredientInventoryDetailID, IID.IngredientInventoryID, I.IngredientID, I.Name As IngredientName, IID.Inventory, IID.Difference, I.Unit ".
	"FROM IngredientInventoryDetails IID, Ingredients I ".
	"WHERE ";
	if($IngredientInventoryID != "%") {
		$sql = $sql."IID.IngredientInventoryID = '$IngredientInventoryID' AND ";
	}
	$sql = $sql.
	"IID.IngredientID = I.IngredientID AND ".
	"IID.State = 1 ".
	"ORDER BY IID.IngredientInventoryDetailID ASC ;";

	
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