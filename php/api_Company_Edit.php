<?php

	include 'lib_link2DB.php';
	
	/** Edit Company **/
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : null; 
	$CompanyName = !empty($_POST['CompanyName']) ? $_POST['CompanyName'] : null;
	$ContactName = !empty($_POST['ContactName']) ? $_POST['ContactName'] : null; 
	$Address = !empty($_POST['Address']) ? $_POST['Address'] : null; 
	$Phone = !empty($_POST['Phone']) ? $_POST['Phone'] : null; 
	$TaxID = !empty($_POST['TaxID']) ? $_POST['TaxID'] : null; 
	$Remark = !empty($_POST['Remark']) ? $_POST['Remark'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE Companies SET ".
	"CompanyName = '$CompanyName', ".
	"ContactName = '$ContactName', ".
	"Address = '$Address', ".
	"Phone = '$Phone', ".
	"TaxID = '$TaxID', ".
	"Remark = '$Remark', ".
	"State = '$State' ".
	"WHERE CompanyID = '$CompanyID'";
	
	mysqli_query($lib_link, stripslashes($sql));	
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'CompanyID' => $CompanyID
    );
	
	print(json_encode($resp));
?>