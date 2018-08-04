<?php

	include 'lib_link2DB.php';
	
	/** Delete Material Shipment**/
	$MaterialShipmentID = !empty($_POST['MaterialShipmentID']) ? $_POST['MaterialShipmentID'] : null; 
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : 0; 
	$VerifyState = !empty($_POST['VerifyState']) ? $_POST['VerifyState'] : null;
	$VerifyAction = !empty($_POST['VerifyAction']) ? $_POST['VerifyAction'] : 0;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	$MaterialShipmentDetail = !empty($_POST['MaterialShipmentDetail']) ? $_POST['MaterialShipmentDetail'] : 'undefined';
	
	if($CompanyID != 0) {
		$sql = 
		"UPDATE MaterialShipments SET ".
		"CompanyID = '$CompanyID' ".
		"WHERE MaterialShipmentID = '$MaterialShipmentID'";
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
	}
	if($State == 0) {
		$sql = 
		"UPDATE MaterialShipments SET ".
		"State = '0' ".
		"WHERE MaterialShipmentID = '$MaterialShipmentID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE MaterialShipmentDetails SET ".
		"State = '0' ".
		"WHERE MaterialShipmentID = '$MaterialShipmentID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		if($VerifyState == 1) {
			$sql = 
			"SELECT M.MaterialID, MSD.ActualQuantity, M.Inventory ".
			"FROM MaterialShipmentDetails MSD, Materials M ".
			"WHERE MSD.MaterialShipmentID = '$MaterialShipmentID' AND ".
			"MSD.MaterialID = M.MaterialID ".
			"ORDER BY M.MaterialID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] + $value['ActualQuantity'];
				$MaterialID = $value['MaterialID'];
				
				$editsql = 
				"UPDATE Materials SET ".
				"Inventory = '$sum'".
				"WHERE MaterialID = '$MaterialID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	} else if ($VerifyAction == 1) {
		$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
		$VerifyDate = date("Y-m-d"); 
		$IsPrintBarcode = !empty($_POST['IsPrintBarcode']) ? $_POST['IsPrintBarcode'] : false; 
		
		$sql = "SELECT VerifyState FROM MaterialShipments WHERE MaterialShipmentID = '$MaterialShipmentID'";
		$res = mysqli_query($lib_link, stripslashes($sql));
		$nowverifystate = mysqli_fetch_assoc($res)['VerifyState'];
		
		if($nowverifystate == 0) {
			if($MaterialShipmentDetail != 'undefined') {
				$data = json_decode($MaterialShipmentDetail);
				foreach($data as $value) {
					if($value != null) {
						$MaterialShipmentDetailID = $value->{'MaterialShipmentDetailID'};
						$ActualQuantity = $value->{'ActualQuantity'};
						$SoldPrice = $value->{'SoldPrice'};
						$editsql = 
						"UPDATE MaterialShipmentDetails SET ".
						"ActualQuantity = '$ActualQuantity', ".
						"SoldPrice = '$SoldPrice' ".
						"WHERE MaterialShipmentDetailID = '$MaterialShipmentDetailID'";
						mysqli_query($lib_link, stripslashes($editsql));
						LogSql($lib_link, $editsql);
					}
				}
				
			}
			
			$sql = 
			"UPDATE MaterialShipments SET ".
			"VerifyState = '1', ".
			"VerifyEmployeeID = '$EmployeeID', ".
			"VerifyDate = '$VerifyDate'".
			"WHERE MaterialShipmentID = '$MaterialShipmentID'";
			
			mysqli_query($lib_link, stripslashes($sql));	
			LogSql($lib_link, $sql);
			$sql = 
			"SELECT M.MaterialID, MSD.ActualQuantity, M.Inventory ".
			"FROM MaterialShipmentDetails MSD, Materials M ".
			"WHERE MSD.MaterialShipmentID = '$MaterialShipmentID' AND ".
			"MSD.MaterialID = M.MaterialID AND ".
			"MSD.State = 1 ".
			"ORDER BY M.MaterialID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] - $value['ActualQuantity'];
				$MaterialID = $value['MaterialID'];
				
				$editsql = 
				"UPDATE Materials SET ".
				"Inventory = '$sum'".
				"WHERE MaterialID = '$MaterialID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
				
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
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialShipmentID' => $MaterialShipmentID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
?>