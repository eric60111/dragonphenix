<?php

	include 'lib_link2DB.php';
	
	/** Add Ingredient Inventory Detail **/
	$IngredientInventoryID = !empty($_POST['IngredientInventoryID']) ? $_POST['IngredientInventoryID'] : null;
	$IngredientID = !empty($_POST['IngredientID']) ? $_POST['IngredientID'] : null; 
	$Inventory = !empty($_POST['Inventory']) ? $_POST['Inventory'] : null; 
	
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Ingredients WHERE IngredientID = '$IngredientID'"));
	$Difference = $Inventory - mysqli_fetch_row($res)[0];
		
	$sql = "INSERT INTO IngredientInventoryDetails VALUES ";
	$sql .= "(NULL, '$IngredientInventoryID', '$IngredientID', '$Inventory', '$Difference', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$IngredientInventoryDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Ingredients SET ".
	"Inventory = '$Inventory' ".
	"WHERE IngredientID = '$IngredientID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	$sum = $Inventory;
	$Cost = 0;	
	$calCostSql =
	"SELECT IngredientPurchaseDetailID, ActualQuantity, UnitPrice ".
	"FROM IngredientPurchaseDetails ".
	"WHERE IngredientID = '$IngredientID' AND ".
	"State = 1 ".
	"ORDER BY IngredientPurchaseDetailID DESC ;";
	
	$calCostRes = mysqli_query($lib_link, stripslashes($calCostSql));
	while($calCostResult = mysqli_fetch_assoc($calCostRes) ) {
		$Quantity =  $calCostResult['ActualQuantity'];
		$UnitPrice =  $calCostResult['UnitPrice'];
		if($sum - $Quantity > 0) {
			$Cost += ($Quantity * $UnitPrice);
			$sum -= $Quantity;
		} else {
			$Cost += ($sum * $UnitPrice);
			break;
		}
	}
	
	$editsql = 
	"UPDATE Ingredients SET ".
	"Cost = '$Cost'".
	"WHERE IngredientID = '$IngredientID'";
	mysqli_query($lib_link, stripslashes($editsql));
	LogSql($lib_link, $editsql);
	
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientInventoryDetailID' => $IngredientInventoryDetailID
    );
	
	print(json_encode($resp));
?>