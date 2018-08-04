<?php

	include 'lib_link2DB.php';

	$MaterialPurchaseID = !empty($_POST['MaterialPurchaseID']) ? $_POST['MaterialPurchaseID'] : "%"; 
	
		
	$sql = 
	"SELECT M.MaterialPurchaseID, M.Barcode, C.CompanyID, C.CompanyName , C.Phone, C.Address, C.TaxID, E.EmployeeID, E.Name as SetupEmployee, M.SetupDate, M.VerifyDate, M.VerifyEmployee, M.VerifyState ".
	"From (
		SELECT M.*, E.Name as VerifyEmployee
        FROM MaterialPurchases M Left JOIN Employees E 
        ON
        M.VerifyEmployeeID = E.EmployeeID 
		WHere
        M.State = 1 
        ORDER BY M.MaterialPurchaseID ASC) M, Companies C, Employees E ".
	"WHERE ";
	if($MaterialPurchaseID != "%") {
		$sql = $sql."M.MaterialPurchaseID = '$MaterialPurchaseID' AND ";
	}
	$sql = $sql.
	"M.CompanyID = C.CompanyID AND ".
	"M.SetupEmployeeID = E.EmployeeID AND ".
	"M.State = 1 ".
	"ORDER BY M.MaterialPurchaseID ASC ;";

	$res = mysqli_query($lib_link, stripslashes($sql));
	while($result = mysqli_fetch_assoc($res) ) {
		$resp[] = $result;
	}
	mysqli_close($lib_link);
	
	if(isset($resp)) {
		print(json_encode($resp));
	}else{
		print(json_encode(array('result' => 'empty')));
	}
?>