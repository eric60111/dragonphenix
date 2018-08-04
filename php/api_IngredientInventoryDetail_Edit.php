<?php

	include 'lib_link2DB.php';
	
	/** Edit Inventory Detail **/
	$IngredientInventoryDetailID = !empty($_POST['IngredientInventoryDetailID']) ? $_POST['IngredientInventoryDetailID'] : null; 
	$Inventory = !empty($_POST['Inventory']) ? $_POST['Inventory'] : null; 
	
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory, Difference, IngredientID FROM IngredientInventoryDetails WHERE IngredientInventoryDetailID = '$IngredientInventoryDetailID'"));
	$result = mysqli_fetch_row($res);
	$InventoryOld = $result[0];
	$Difference = $result[1] + $Inventory - $InventoryOld;
	$IngredientID = $result[2];
	
	
	$sql = 
	"UPDATE IngredientInventoryDetails SET ".
	"Inventory = '$Inventory', ".
	"Difference = '$Difference' ".
	"WHERE IngredientInventoryDetailID = '$IngredientInventoryDetailID'";
	mysqli_query($lib_link, stripslashes($sql));
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