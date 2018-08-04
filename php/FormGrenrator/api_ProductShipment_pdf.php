<?php

$ProductShipmentID = !empty($_GET['ProductShipmentID']) ? $_GET['ProductShipmentID'] : null; 

require_once('../lib_link2DB.php');
require_once('../tcpdf/config/lang/zho.php');
require_once('../tcpdf/tcpdf.php');
require_once('../tcpdf/tcpdf_barcodes_1d.php');

$sql = 
"SELECT P.ProductShipmentID, C.CompanyName, C.ContactName, C.Phone, C.Address, C.TaxID, E.Name, P.SetupDate, P.VerifyDate, P.VerifyEmployee ".
"From (
		SELECT P.*, E.Name as VerifyEmployee
        FROM ProductShipments P Left JOIN Employees E 
        ON
        P.VerifyEmployeeID = E.EmployeeID 
		WHere
        P.State = 1 
        ORDER BY P.ProductShipmentID ASC) P, Companies C, Employees E ".
	"WHERE ";
	if($ProductShipmentID != "%") {
		$sql = $sql."P.ProductShipmentID = '$ProductShipmentID' AND ";
	}
	$sql = $sql.
	"P.CompanyID = C.CompanyID AND ".
	"P.SetupEmployeeID = E.EmployeeID AND ".
	"P.State = 1 ".
	"ORDER BY P.ProductShipmentID ASC ;";

$res = mysqli_query($lib_link, stripslashes($sql));
while($result = mysqli_fetch_assoc($res) ) {
	$resp[] = $result;
}


$CompanyName = $resp[0]['CompanyName'];
$ContactName = $resp[0]['ContactName'];
$Phone = $resp[0]['Phone'];
$Address = $resp[0]['Address'];
$TaxID = $resp[0]['TaxID'];
$Name = $resp[0]['Name'];
$SetupDate = $resp[0]['SetupDate'];
$VerifyDate = $resp[0]['VerifyDate'] != '0000-00-00' ? date_format(date_create($resp[0]['VerifyDate']), "Y年 m月 d日") : '       年       月       日';
$VerifyEmployee = $resp[0]['VerifyEmployee'] != '' ? $resp[0]['VerifyEmployee'] : ' ________________ (親簽)';
$barcode = date_format(date_create($SetupDate), "ymd").'9'.sprintf("%03d", $ProductShipmentID);

class MYPDF extends TCPDF {
		
	//Page header
	public function Header() {
		// Barcode
		$barcode = $this->getHeaderData()['string'];
		$style = array(
			'position' => '',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => true,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 4
		);
		$this->write1DBarcode($barcode, 'C128', '', '', '', 16, 0.4, $style, 'N');
		
		// Set font
		$this->SetFont('msjhbd', 'B', 20);
		
		// Title
		$this->Cell(0, 30, '產品出貨單', 0, false, 'C', 0, '', 0, false, 'L', 'C');
	
		// Logo
		$image_file = '../tcpdf/image/logo.jpg';
		$this->Image($image_file, 0, 10, 40, '', 'JPG', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
		
		// add separate line
		$pageWidth    = $this->getHeaderData()['logo_width'];
		$x1   = $this->getX();
		$x2   = $pageWidth - $x1;
		$y   = PDF_MARGIN_TOP;
		$style = array();
		$this->Line($x1, $y, $x2, $y, $style);
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set barcode number
$pdf->setHeaderData($ln='', $lw=$pdf->getPageWidth(), $ht='', $hs=$barcode, $tc=array(0,0,0), $lc=array(0,0,0));

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('識觀科技');
$pdf->SetTitle('產品出貨單');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setCellPaddings(1,2,1,2);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('msjhbd', 'BI', 12);

$pdf->Cell(0, 0, '建單日期：'.date_format(date_create($SetupDate), "Y/m/d"), 0, 1, 'R', 0, '', 0);

// set font
$pdf->SetFont('msjhbd', 'BI', 16);

$tbl = <<<EOD
<table cellpadding="5" border="1">
	<tr>
		<td colspan="4"  align="center">廠商資訊</td>
	</tr>
	<tr>
		<td bgcolor="#F2F2F2">廠商名稱</td>
		<td colspan="3">$CompanyName</td>
	</tr>
	<tr>
		<td bgcolor="#F2F2F2">聯絡人</td>
		<td colspan="3">$ContactName</td>
	</tr>
	<tr>
		<td bgcolor="#F2F2F2">電話</td>
		<td colspan="3">$Phone</td>
	</tr>
	<tr>
		<td bgcolor="#F2F2F2">地址</td>
		<td colspan="3">$Address</td>
	</tr>
	<tr>
		<td bgcolor="#F2F2F2">統編</td>
		<td colspan="3">$TaxID</td>
	</tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$Detailsql = 
"SELECT PSD.ProductShipmentDetailID, P.Name As ProductName, PSD.SoldPrice, PSD.Quantity, P.Unit ".
"FROM ProductShipmentDetails PSD, Products P ".
"WHERE PSD.ProductShipmentID = '$ProductShipmentID' AND ".
"PSD.ProductID = P.ProductID AND ".
"PSD.State = 1 ".
"ORDER BY PSD.ProductShipmentDetailID ASC ;";

$Detailres = mysqli_query($lib_link, stripslashes($Detailsql));
while($Detailresult = mysqli_fetch_assoc($Detailres) ) {
	$Detailresp[] = $Detailresult;
}


$tbl_header = 
	'<table cellpadding="5" border="1">'.
		'<tr>'.
			'<td colspan="4"  align="center">產品出貨明細</td>'.
		'</tr>'.
		'<tr bgcolor="#F2F2F2">'.
			'<td>品名</td>'.
			'<td align="right">數量</td>'.
			'<td align="right">單價</td>'.
			'<td align="right">總價</td>'.
		'</tr>';
$tbl_footer = '</table>';


$tbl = '';
$Sum = 0;
foreach ($Detailresp as $value) {
	$tbl = $tbl.
	'<tr>'.
		'<td>'.$value['ProductName'].'</td>'.
		'<td align="right">'.$value['Quantity'].$value['Unit'].'</td>'.
		'<td align="right">'.$value['SoldPrice'].'</td>'.
		'<td align="right">'.($value['SoldPrice'] * $value['Quantity']).'</td>'.
	'</tr>';
	$Sum += $value['SoldPrice'] * $value['Quantity'];
}

$tbl = $tbl.
'<tr>'.
	'<td colspan="3">運費</td>'.
	'<td align="right">0</td>'.
'</tr>'.
'<tr>'.
	'<td colspan="3">總計金額</td>'.
	'<td align="right">'.$Sum.'</td>'.
'</tr>';

$pdf->writeHTML($tbl_header.$tbl.$tbl_footer, true, false, false, false, '');

$pdf->Cell(50, 0, '建單人員', 0, 0, 'L', 0, '', 4);
$pdf->Cell(0, 0, '：'.$Name, 0, 1, 'L', 0, '', 0);

$pdf->Cell(50, 0, '簽核人員', 0, 0, 'L', 0, '', 4);
$pdf->Cell(0, 0, '：'.$VerifyEmployee, 0, 1, 'L', 0, '', 0);

$pdf->Cell(50, 0, '簽收日期', 0, 0, 'L', 0, '', 4);
$pdf->Cell(0, 0, '：'.$VerifyDate, 0, 1, 'L', 0, '', 0);

//Close and output PDF document
ob_clean();
$pdf->Output($_SERVER['DOCUMENT_ROOT'].$_SERVER['DOCUMENT_ROOT'].'ProductShipment.pdf', 'I');

mysqli_close($lib_link);
?>