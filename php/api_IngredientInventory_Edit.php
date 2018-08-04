<?php

	include 'lib_link2DB.php';
	
	/** Edit Ingredient Purchase**/
	$IngredientInventoryID = !empty($_POST['IngredientInventoryID']) ? $_POST['IngredientInventoryID'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null;

	if($State == 0) {
		$sql = 
		"UPDATE IngredientInventories SET ".
		"State = '0' ".
		"WHERE IngredientInventoryID = '$IngredientInventoryID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE IngredientInventoryDetails SET ".
		"State = '0' ".
		"WHERE IngredientInventoryID = '$IngredientInventoryID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		$sql = 
		"SELECT I.IngredientID, IID.Difference, I.Inventory ".
		"FROM IngredientInventoryDetails IID, Ingredients I ".
		"WHERE IID.IngredientInventoryID = '$IngredientInventoryID' AND ".
		"IID.IngredientID = I.IngredientID ".
		"ORDER BY I.IngredientID ASC ;";
		
		$res = mysqli_query($lib_link, stripslashes($sql));
		while($result = mysqli_fetch_assoc($res) ) {
			$resp[] = $result;
		}
		
		foreach ($resp as $value) {
			$sum = $value['Inventory'] - $value['Difference'];
			$IngredientID = $value['IngredientID'];
			
			$editsql = 
			"UPDATE Ingredients SET ".
			"Inventory = '$sum'".
			"WHERE IngredientID = '$IngredientID'";
			mysqli_query($lib_link, stripslashes($editsql));
			LogSql($lib_link, $editsql);
			$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Ingredients WHERE IngredientID = '$IngredientID'"));
			$sum = mysqli_fetch_row($res)[0];
			
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
		}
		
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientInventoryID' => $IngredientInventoryID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
	
?>