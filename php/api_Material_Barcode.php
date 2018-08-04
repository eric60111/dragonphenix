<?php
	include 'lib_BarcodePrint.php';
	$MaterialID = !empty($_POST['MaterialID']) ? $_POST['MaterialID'] : "%";
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : "%";
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : "1";

	$Cmd =
	'CLS&DIRECTION 1&'.
	'SIZE 32 mm, 25 mm&'.
	'GAP 2mm, 0&'.
	'TEXT 30,5,"TST24.BF2",0,1,1,"'.$Name.'"&'.
	'BARCODE 30,40,"128",100,1,0,2,2,"'.date("ymd").'2'.sprintf("%03d", $MaterialID).'"&'.
	'PRINT '.$Quantity;
	
	//echo $Cmd;
	Printer_Cmd('dragonphenix', $Cmd);
	
?>





