<?php

	include 'lib_link2DB.php';
	include 'lib_BarcodePrint.php';
	/** Edit Material Purchase**/
	$MaterialPurchaseID = !empty($_POST['MaterialPurchaseID']) ? $_POST['MaterialPurchaseID'] : null; 
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : 0; 
	$VerifyState = !empty($_POST['VerifyState']) ? $_POST['VerifyState'] : null;
	$VerifyAction = !empty($_POST['VerifyAction']) ? $_POST['VerifyAction'] : 0;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	$MaterialPurchaseDetail = !empty($_POST['MaterialPurchaseDetail']) ? $_POST['MaterialPurchaseDetail'] : 'undefined';
	
	if($CompanyID != 0) {
		$sql = 
		"UPDATE MaterialPurchases SET ".
		"CompanyID = '$CompanyID' ".
		"WHERE MaterialPurchaseID = '$MaterialPurchaseID'";
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
	}
	if($State == 0) {
		$sql = 
		"UPDATE MaterialPurchases SET ".
		"State = '0' ".
		"WHERE MaterialPurchaseID = '$MaterialPurchaseID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE MaterialPurchaseDetails SET ".
		"State = '0' ".
		"WHERE MaterialPurchaseID = '$MaterialPurchaseID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		if($VerifyState == 1) {
			$sql = 
			"SELECT M.MaterialID, MPD.ActualQuantity, M.Inventory ".
			"FROM MaterialPurchaseDetails MPD, Materials M ".
			"WHERE MPD.MaterialPurchaseID = '$MaterialPurchaseID' AND ".
			"MPD.MaterialID = M.MaterialID ".
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
			}
		}
	} else if ($VerifyAction == 1) {
		$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
		$VerifyDate = date("Y-m-d"); 
		$IsPrintBarcode = !empty($_POST['IsPrintBarcode']) ? $_POST['IsPrintBarcode'] : false; 
		
		$sql = "SELECT VerifyState FROM MaterialPurchases WHERE MaterialPurchaseID = '$MaterialPurchaseID'";
		$res = mysqli_query($lib_link, stripslashes($sql));
		$nowverifystate = mysqli_fetch_assoc($res)['VerifyState'];
		
		if($nowverifystate == 0) {
			if($MaterialPurchaseDetail != 'undefined') {
				$data = json_decode($MaterialPurchaseDetail);
				foreach($data as $value) {
					if($value != null) {
						$MaterialPurchaseDetailID = $value->{'MaterialPurchaseDetailID'};
						$ActualQuantity = $value->{'ActualQuantity'};
						$UnitPrice = $value->{'UnitPrice'};
						$editsql = 
						"UPDATE MaterialPurchaseDetails SET ".
						"ActualQuantity = '$ActualQuantity', ".
						"UnitPrice = '$UnitPrice' ".
						"WHERE MaterialPurchaseDetailID = '$MaterialPurchaseDetailID'";
						mysqli_query($lib_link, stripslashes($editsql));
						LogSql($lib_link, $editsql);
					}
				}
				
			}
			
			
			$sql = 
			"UPDATE MaterialPurchases SET ".
			"VerifyState = '1', ".
			"VerifyEmployeeID = '$EmployeeID', ".
			"VerifyDate = '$VerifyDate'".
			"WHERE MaterialPurchaseID = '$MaterialPurchaseID'";
			
			mysqli_query($lib_link, stripslashes($sql));	
			LogSql($lib_link, $sql);
			$sql = 
			"SELECT M.MaterialID, MPD.ActualQuantity, M.Inventory, M.Name ".
			"FROM MaterialPurchaseDetails MPD, Materials M ".
			"WHERE MPD.MaterialPurchaseID = '$MaterialPurchaseID' AND ".
			"MPD.MaterialID = M.MaterialID AND ".
			"MPD.State = 1 ".
			"ORDER BY M.MaterialID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$Inventory = $value['Inventory'];
				$ActualQuantity = $value['ActualQuantity'];
				$sum = $Inventory + $ActualQuantity;
				$MaterialID = $value['MaterialID'];
				$Name = $value['Name'];
				
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
				if($IsPrintBarcode) {
					$Cmd =
					'CLS&DIRECTION 1&'.
					'SIZE 32 mm, 25 mm&'.
					'GAP 2mm, 0&'.
					'TEXT 30,5,"TST24.BF2",0,1,1,"'.$Name.'"&'.
					'BARCODE 30,40,"128",100,1,0,2,2,"'.date("ymd").'2'.sprintf("%03d", $MaterialID).'"&'.
					'PRINT '.$ActualQuantity;
					Printer_Cmd('dragonphenix', $Cmd);
				}
			}
		}
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialPurchaseID' => $MaterialPurchaseID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
	
?>