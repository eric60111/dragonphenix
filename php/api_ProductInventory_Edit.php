<?php

	include 'lib_link2DB.php';
	
	/** Edit Product Purchase**/
	$ProductInventoryID = !empty($_POST['ProductInventoryID']) ? $_POST['ProductInventoryID'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null;

	if($State == 0) {
		$sql = 
		"UPDATE ProductInventories SET ".
		"State = '0' ".
		"WHERE ProductInventoryID = '$ProductInventoryID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE ProductInventoryDetails SET ".
		"State = '0' ".
		"WHERE ProductInventoryID = '$ProductInventoryID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		$sql = 
		"SELECT P.ProductID, PID.Difference, P.Inventory ".
		"FROM ProductInventoryDetails PID, Products P ".
		"WHERE PID.ProductInventoryID = '$ProductInventoryID' AND ".
		"PID.ProductID = P.ProductID ".
		"ORDER BY P.ProductID ASC ;";
		
		$res = mysqli_query($lib_link, stripslashes($sql));
		while($result = mysqli_fetch_assoc($res) ) {
			$resp[] = $result;
		}
		
		foreach ($resp as $value) {
			$sum = $value['Inventory'] - $value['Difference'];
			$ProductID = $value['ProductID'];
			
			$editsql = 
			"UPDATE Products SET ".
			"Inventory = '$sum'".
			"WHERE ProductID = '$ProductID'";
			mysqli_query($lib_link, stripslashes($editsql));
			LogSql($lib_link, $editsql);
			$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Products WHERE ProductID = '$ProductID'"));
			$sum = mysqli_fetch_row($res)[0];
			
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
		}
		
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductInventoryID' => $ProductInventoryID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
	
?>