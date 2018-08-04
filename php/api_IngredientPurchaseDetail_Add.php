<?php

	include 'lib_link2DB.php';
	
	/** Add Ingredient Purchase Detail **/
	$IngredientPurchaseID = !empty($_POST['IngredientPurchaseID']) ? $_POST['IngredientPurchaseID'] : null;
	$IngredientID = !empty($_POST['IngredientID']) ? $_POST['IngredientID'] : null; 
	$UnitPrice = !empty($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
		
	$sql = "INSERT INTO IngredientPurchaseDetails VALUES ";
	$sql .= "(NULL, '$IngredientPurchaseID', '$IngredientID', '$UnitPrice', '$Quantity', '$Quantity', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$IngredientPurchaseDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Ingredients SET ".
	"UnitPrice = '$UnitPrice' ".
	"WHERE IngredientID = '$IngredientID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientPurchaseDetailID' => $IngredientPurchaseDetailID
    );
	
	print(json_encode($resp));
?>