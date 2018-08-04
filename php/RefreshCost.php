<?php

	include 'lib_link2DB.php';
	
	$sql = 
	"SELECT * ".
	"FROM Products ".
	"WHERE State = 1 ".
	"ORDER BY ProductID ASC;";
	
	$res = mysqli_query($lib_link, stripslashes($sql));
	while($result = mysqli_fetch_assoc($res) ) {
		$resp[] = $result;
	}
	
	foreach ($resp as $value) {
		$Inventory = $value['Inventory'];
		$sum = $Inventory;
		$ProductID = $value['ProductID'];
		
		$editsql = 
		"UPDATE Products SET ".
		"Inventory = '$sum'".
		"WHERE ProductID = '$ProductID'";
		mysqli_query($lib_link, stripslashes($editsql));
		
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
	}
?>