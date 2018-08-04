<?php

	include 'lib_link2DB.php';
	/** Edit Material Purchase**/


	$sql = 
	"SELECT M.MaterialID, MPD.ActualQuantity, M.Inventory, M.Name ".
	"FROM MaterialPurchaseDetails MPD, Materials M ".
	"WHERE MPD.MaterialPurchaseID = '27' AND ".
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
		
		echo $Inventory;
	}
	
	
	mysqli_close($lib_link);
	
?>