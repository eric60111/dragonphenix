<?php

	include 'lib_link2DB.php';
	
	/** Edit Material Purchase**/
	$MaterialInventoryID = !empty($_POST['MaterialInventoryID']) ? $_POST['MaterialInventoryID'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null;

	if($State == 0) {
		$sql = 
		"UPDATE MaterialInventories SET ".
		"State = '0' ".
		"WHERE MaterialInventoryID = '$MaterialInventoryID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE MaterialInventoryDetails SET ".
		"State = '0' ".
		"WHERE MaterialInventoryID = '$MaterialInventoryID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		$sql = 
		"SELECT M.MaterialID, MID.Difference, M.Inventory ".
		"FROM MaterialInventoryDetails MID, Materials M ".
		"WHERE MID.MaterialInventoryID = '$MaterialInventoryID' AND ".
		"MID.MaterialID = M.MaterialID ".
		"ORDER BY M.MaterialID ASC ;";
		
		$res = mysqli_query($lib_link, stripslashes($sql));
		while($result = mysqli_fetch_assoc($res) ) {
			$resp[] = $result;
		}
		
		foreach ($resp as $value) {
			$sum = $value['Inventory'] - $value['Difference'];
			$MaterialID = $value['MaterialID'];
			
			$editsql = 
			"UPDATE Materials SET ".
			"Inventory = '$sum'".
			"WHERE MaterialID = '$MaterialID'";
			mysqli_query($lib_link, stripslashes($editsql));
			LogSql($lib_link, $editsql);
			$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Materials WHERE MaterialID = '$MaterialID'"));
			$sum = mysqli_fetch_row($res)[0];
			
			$Cost = 0;	
			$calCostSql =
			"SELECT MaterialPurchaseDetailID, ActualQuantity, UnitPrice ".
			"FROM MaterialPurchaseDetails ".
			"WHERE MaterialID = '$MaterialID' AND ".
			"State = 1 ".
			"ORDER BY MaterialPurchaseDetailID DESC ;";
			
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
			"UPDATE Materials SET ".
			"Cost = '$Cost'".
			"WHERE MaterialID = '$MaterialID'";
			mysqli_query($lib_link, stripslashes($editsql));
			LogSql($lib_link, $editsql);
		}
		
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialInventoryID' => $MaterialInventoryID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
	
?>