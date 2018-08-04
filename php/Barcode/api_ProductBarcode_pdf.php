<?php

require_once('../lib_link2DB.php');
require_once('../tcpdf/config/lang/zho.php');
require_once('../tcpdf/tcpdf.php');
require_once('../tcpdf/tcpdf_barcodes_1d.php');

$pdfName = 'ProductBarcode.pdf';
$TitleText = array('產品條碼清單(進)', '產品條碼清單(出)', '產品條碼清單(盤點)');
$type = 3;
$Id = 'ProductID';
$name = 'Name';
$sql = 
"SELECT * ".
"FROM Products ".
"WHERE State = 1 ".
"ORDER BY ProductID ASC ;";


$row = !empty($_GET['row']) ? $_GET['row'] : 6;
$column = !empty($_GET['column']) ? $_GET['column'] : 3;
$row = ($row > 6) ? 6 : $row;
$column = ($column > 4) ? 4 : $column;
$row = ($row < 1) ? 6 : $row;
$column = ($column < 1) ? 3 : $column;

class MYPDF extends TCPDF {
		
	//Page header
	public function Header() {
		
		// Set font
		$this->SetFont('msjhbd', 'B', 20);
		
		$TitleText = $this->getHeaderData()['string'];
		// Title
		$this->Cell(0, 30, $TitleText, 0, false, 'C', 0, '', 0, false, 'C', 'B');
	
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
		$this->SetFont('helvetica', 'B', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('識觀科技');
$pdf->SetTitle('條碼清單');



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

$CellWidth = ($pdf->getPageWidth() - PDF_MARGIN_LEFT * 2) / 3;
$style = array(
	'position' => 'S',
	'border' => false,
	'hpadding' => 8,
	'vpadding' => 4,
	'fgcolor' => array(0,0,0),
	'bgcolor' => array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 8,
	'stretchtext' => 4
);


$CellWidth = ($pdf->getPageWidth() - PDF_MARGIN_LEFT * 2) / $column;

$res = mysqli_query($lib_link, stripslashes($sql));
while($result = mysqli_fetch_assoc($res) ) {
	$resp[] = $result;
}

for($i = 1; $i < (count($TitleText) + 1); $i++) {
	$pdf->setHeaderData($ln='', $lw=$pdf->getPageWidth(), $ht='', $hs=$TitleText[$i-1], $tc=array(0,0,0), $lc=array(0,0,0));
	$count = 0;
	
	
	
	for($j = 0; $j < floor((count($resp) - 1) /($row * $column)) + 1; $j++) {
		// add a page
		$pdf->AddPage();
		

		// set font
		$pdf->SetFont('msjhbd', 'B', 16);
		$pdf->Cell(0, 0, '', 0, 1, 'R', 0, '', 0);
		for($k = 0; $k < $row; $k++) {
			if($count < count($resp)) {
				for($l = 0; $l < $column; $l++) {
					if($count < count($resp)) {
						$pdf->Cell($CellWidth, 0, $resp[$count][$name], 1, 0, 'C', 0, '', 0);
					} else {
						$pdf->Cell($CellWidth, 0, '', 1, 0, 'C', 0, '', 0);
					}
					$count++;
				}
				$pdf->Ln();
				$count-=$column;
				for($l = 0; $l < $column; $l++) {
					if($count < count($resp)) {
						$x = $pdf->GetX();
						$y = $pdf->GetY();
						$barcode = '00000'.$i.$type.sprintf("%03d", $resp[$count][$Id]);
						$pdf->write1DBarcode($barcode, 'C128', '', '', $CellWidth, 30, 0.4, $style, 'T');
						$pdf->SetXY($x,$y);
						$pdf->Cell($CellWidth, 30, '', 1, 0, 'C', 0, '', 0);
					} else {
						$pdf->Cell($CellWidth, 30, '', 1, 0, 'C', 0, '', 0);
					}
					$count++;
				}
				$pdf->Ln();
			} else {
				for($l = 0; $l < $column; $l++) {
					$pdf->Cell($CellWidth, 0, '', 1, 0, 'C', 0, '', 0);
				}
				$pdf->Ln();
				for($l = 0; $l < $column; $l++) {
					$pdf->Cell($CellWidth, 30, '', 1, 0, 'C', 0, '', 0);
				}
				$pdf->Ln();
			}
		}
	}
}
//Close and output PDF document
$pdf->Output($pdfName, 'I');

mysqli_close($lib_link);
?>