<?php

	include 'lib_link2DB.php';
	
	/** Edit Purchase Detail **/
	$IngredientPurchaseDetailID = !empty($_POST['IngredientPurchaseDetailID']) ? $_POST['IngredientPurchaseDetailID'] : null; 
	$UnitPrice = !empty($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
	$ActualQuantity = !empty($_POST['ActualQuantity']) ? $_POST['ActualQuantity'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE IngredientPurchaseDetails SET ".
	"UnitPrice = '$UnitPrice' ".
	"WHERE IngredientPurchaseDetailID = '$IngredientPurchaseDetailID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	$res = mysqli_query($lib_link, stripslashes("SELECT IngredientID FROM IngredientPurchaseDetails WHERE IngredientPurchaseDetailID = '$IngredientPurchaseDetailID'"));
	$IngredientID = mysqli_fetch_row($res)[0];
	
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
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientPurchaseDetailID' => $IngredientPurchaseDetailID
    );
	
	print(json_encode($resp));
?>