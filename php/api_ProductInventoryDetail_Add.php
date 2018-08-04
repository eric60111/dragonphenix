<?php

	include 'lib_link2DB.php';
	
	/** Add Product Inventory Detail **/
	$ProductInventoryID = !empty($_POST['ProductInventoryID']) ? $_POST['ProductInventoryID'] : null;
	$ProductID = !empty($_POST['ProductID']) ? $_POST['ProductID'] : null; 
	$Inventory = !empty($_POST['Inventory']) ? $_POST['Inventory'] : null; 
	
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Products WHERE ProductID = '$ProductID'"));
	$Difference = $Inventory - mysqli_fetch_row($res)[0];
		
	$sql = "INSERT INTO ProductInventoryDetails VALUES ";
	$sql .= "(NULL, '$ProductInventoryID', '$ProductID', '$Inventory', '$Difference', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$ProductInventoryDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Products SET ".
	"Inventory = '$Inventory' ".
	"WHERE ProductID = '$ProductID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	$sum = $Inventory;
	$Cost = 0;	
	$calCostSql =
	"SELECT ProductPurchaseDetailID, ActualQuantity, UnitPrice ".
	"FROM ProductPurchaseDetails ".
	"WHERE ProductID = '$ProductID' AND ".
	"State = 1 ".
	"ORDER BY ProductPurchaseDetailID DESC ;";
	
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
	"UPDATE Products SET ".
	"Cost = '$Cost'".
	"WHERE ProductID = '$ProductID'";
	mysqli_query($lib_link, stripslashes($editsql));
	LogSql($lib_link, $editsql);
	
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductInventoryDetailID' => $ProductInventoryDetailID
    );
	
	print(json_encode($resp));
?>