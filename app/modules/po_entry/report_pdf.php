<?php
	$maindir = dirname(realpath('..'));
	include  $maindir.'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	//ini_set('display_errors',1);
	//exit($maindir.'/rapper/class.rapper.php');
	include $maindir.'/rapper/fpdf/PrintPosition.php';
	
	$po_entry = new po_entry;
	$postArr = $_POST;
	$data = $po_entry->getSingleView($postArr);
	$data = json_decode($data, true);
	$rsData = $data['rsData'];
	$itmgrpdet_details = $rsData['itmgrpdet_details'];
	$itemlist = $rsData['itemlist'];
	
	$items = array();
	foreach($itemlist as $itm)
	{
		$items[$itm['item_id']] = $itm['item_code'];
	}
	class RPDF extends PDF
	{
		function Header()
		{
			//logo_payslip
			
			$this->Rect(10, 10, 190, 277, 'D'); //For A4
			
			$this->SetFont('Verdana-Bold','',14);
			$this->SetTextColor(255, 0, 0);
			$this->Cell(0,10,'PSMITH PROCUREMENT ',0,0,'L');
			$this->ln(5);
			$this->Cell(0,10,'PRIVATE LIMITED ',0,0,'L');			
			$this->ln(10);
			
			$this->ImagePngWithAlpha(dirname($this->maindir).'/assets/images/pl_invt_logo.png',90,11,52);
			$this->ln(10);
			$this->SetXY(150,13);
			$this->SetFont('Verdana-Bold','',12);
			$this->SetTextColor(0, 0, 0);
			$this->Cell(0,15,'PURCHASE ORDER',0,0,'L');
			$this->ln(10);
			
			$y = $this->y;
			
			$this->SetFont('Arial','',8);
			$this->Cell(0,15,'Head office: 2nd Floor, Emerald Towers, #5',0,0,'L');
			$this->ln(5);
			//$this->SetX(9);
			$this->Cell(0,15,'Krishnasamy nagar, Ramanathapuram,',0,0,'L');
			$this->ln(5);
			//$this->SetX(9);
			$this->Cell(0,15,'COIMBATORE - 641045 Phone / Fax: 0422-4204070',0,0,'L');
			$this->ln(5);
			//$this->SetX(9);
			$this->Cell(0,15,'TIN: 33366404099 Dated 13/08/2016',0,0,'L');
			$this->ln(15);
			$this->SetXY(115, $y+5);
			$this->Cell(34,6,'DATE',0,0,'R');
			$this->Cell(45,6,'28-Feb-2017',1,0,'L');
			$this->ln(6);
			$y = $this->y;
			$this->SetXY(115, $y);
			$this->Cell(34,6,'PO#',0,0,'R');
			$this->Cell(45,6,'M000056',0,0,'L');
			$this->ln(6);
			$y = $this->y;
			$this->SetXY(115, $y);
			$this->Cell(34,6,'Your Ref#',0,0,'R');
			$this->Cell(45,6,'M103337-42 Dated 02-16-2017',0,0,'L');
			$this->ln(6);
			$y = $this->y;
			$this->SetXY(115, $y);
			$this->Cell(34,6,'Requested Delivery Date',0,0,'R');
			$this->SetTextColor(255, 0, 0);
			$this->Cell(45,6,'Not before 03/31 or after 04/07/17',0,0,'L');
			$this->ln(8);
			$this->SetTextColor(0, 0, 0);
			
		}
		
		function Footer()
		{
			// Go to 1.5 cm from bottom
			$this->SetY(-8);
			// Select Arial italic 8
			$this->SetFont('Verdana','',8);
			
			$this->SetFillColor(100, 100, 100);
			
			$this->SetX(115);
			$this->Cell(0,8,'Page '.$this->PageNo().' of '.$this->AliasNbPages,0,0,'L');
			$this->SetX(183);
			$this->Cell(0,8,'Copyright © '.date('Y'),0,0,'L');
		
			$this->ln(4);
			
			$this->AliasNbPages();
			
			//$this->Cell(0,10,'Page '.$this->PageNo().' of '.$this->AliasNbPages,0,0,'C');
		}
		
		function CheckPageBreak($h)
		{
			$h = 15;
			//$this->PageBreakTrigger = 230;
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>170)
			{
				$this->AddPage($this->CurOrientation);
			}
		}
		
		function printTableHeader()
		{
			$tableWidth = $this->tableWidth;
			$tableColumns = $this->tableColumns;
			$tableAlign = $this->tableAlign;
			$this->SetTextColor(255, 255, 255);
			$this->SetFont('Verdana-Bold','',8);
			foreach($this->tableColumns as $colkey=>$column)
			{
				$this->Cell($tableWidth[$colkey],8,$column,1,0,'C',1);
			}
			$this->SetTextColor(0, 0, 0);
			$this->ln(8);
		}
		
		function printTableRow($row)
		{
			$tableWidth = $this->tableWidth;
			$tableColumns = $this->tableColumns;
			$tableAlign = $this->tableAlign;
			
			foreach($this->tableColumns as $colkey=>$column)
			{
				$this->Cell($tableWidth[$colkey],5,$row[$colkey],1,0,$tableAlign[$colkey]);
			}	
			
			$this->ln(5);
		}
	}
	//if(count($paydet)>0)
	{
	
	$title = "Payslip for the Month of";
	$monYer = date('M Y', strtotime($date));
	$sigma_addr = '700 Goldman Drive, Cream Ridge, NJ 08514';

	$pdf = new RPDF();
	$pdf->maindir = $maindir;
	$pdf->title = $title;
	$pdf->monYer = $monYer;
	$pdf->sigma_addr = $sigma_addr;
	$pdf->employee_name = $employee_name;
	$pdf->employee_doj = $employee_doj;
	$pdf->employee_id = $employee_id;
	$pdf->employee_code = $employee_code;
	$pdf->designation_name = $designation_name;
	$pdf->esi_number = $esi_number;
	$pdf->pf_number = $pf_number;
	$pdf->AliasNbPages();
	$pdf->AddFont('Verdana-Bold','','verdana_bold.php');
	$pdf->AddFont('Verdana','','verdana.php'); 
	//$pdf->AddFont('Times-Italic','','timesi.php');
	$pdf->AddPage();
	$pdf->SetLeftMargin(10);
	$pdf->SetRightMargin(15);

	
	$curHeadPos = $pdf->y;
	$pdf->SetFont('Verdana-Bold','',8);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFillColor(0, 0, 255);	
	$pdf->Cell(70,7,'  VENDOR',0,0,'L',1);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'  SHIP AND BILLED TO',0,0,'L',1);
	$pdf->ln(7);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Verdana','',8);
	$pdf->Cell(70,6,'M/s MAKRO CAST PVT LTD,',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'PSMITH PROCUREMENT PRIVATE LIMITED,',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(70,6,'R.S.NO-612/2AB, ZP ROAD,',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'Plot no. 34,',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(70,6,'KOLAVENNU POST, KANKIPADU MANDAL,',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'Block B, Industrial Park,',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(70,6,'KRISHNA DIST, ANDHAR PARDESH-521 153.',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'Menakuru SEZ,',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(70,6,'INDIA',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'NAIDUPET - 524 421',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(70,6,'TIN / CST#: 37100247347',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'(Dist: Nellore, State: AP) TIN# 37805792891',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(70,6,'EXCISE NO.: AAECM2270KXM001',0,0,'L',0);
	$pdf->Cell(30,7,' ',0,0,'L',0);
	$pdf->Cell(90,7,'Contact Person: Mr. Yogesh Mobile: +91-99493 55049',0,0,'L',0);
	$pdf->ln(5);
	$pdf->Cell(55,6,'Contact Person: Mr. Durga Rao Mobile: +91-9949778021',0,0,'L',0);
	$pdf->ln(5);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Verdana-Bold','',8);
	$pdf->Cell(40,7,'REQUISITIONER',0,0,'C',1);
	$pdf->Cell(30,7,'SHIP VIA',0,0,'C',1);
	$pdf->Cell(30,7,'F.O.B.',0,0,'C',1);
	$pdf->Cell(90,7,'SHIPPING TERMS',0,0,'C',1);
	$pdf->ln(7);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Verdana','',8);
	$pdf->Cell(40,5,'P. Pugalenthi',1,0,'L');
	$pdf->Cell(30,5,'Road',1,0,'L');
	$pdf->Cell(30,5,'Ex. Works',1,0,'L');
	$pdf->Cell(90,5,'45 Days from the Date of your Commercial Invoice',1,0,'L');
	$pdf->ln(10);
	
	$pdf->tableWidth = array(30, 50, 28, 32, 50);
	$pdf->tableColumns = array('ITEM #', 'DESCRIPTION', 'QTY in Nos.', 'UNIT PRICE in INR', 'TOTAL in INR');
	$pdf->tableAlign = array('L','L','C','R','R');
	$pdf->printTableHeader();
	
	$i = 0;
	foreach($itmgrpdet_details as $itmg)
	{
		$arr = array($items[$itmg['item_id']], $itmg['item_desc'], $itmg['ordered_qty'], $itmg['unit_cost'], $itmg['line_total']);
		$pdf->printTableRow($arr);
		$i++;
	}
	
	for($i = $i; $i<19; $i++)
	{
		$arr = array('','','','','');
		$pdf->printTableRow($arr);
	}
	
	$pdf->SetX(118);
	$pdf->SetFont('Verdana','',8);
	$pdf->Cell(32,5,'SUBTOTAL',0,0,'L');
	$pdf->Cell(50,5,'',1,0,'R');
	$pdf->ln(5);
	$pdf->SetFont('Verdana-Bold','',8);
	$pdf->Cell(80,10,'Comments or Special Instructions',1,0,'L',1);
	$y = $pdf->y;
	$pdf->ln(10);
	$pdf->SetFont('Verdana','',8);
	$pdf->MultiCell(80,5,'Cost for only Casting. Ex.works Price. For 16" weight based on exisitng available weight. For 18", 20" and 30" Parts weights are calculated based on limited samples. This will be corrected after average weight taken for 5 Nos. from your production.',1,'L',0);
	$pdf->SetXY(118,$y);
	$pdf->MultiCell(32,5,'Excise Duty & Cess @ 12.5%',0,'L',0);
	$y1 = $pdf->y;
	$pdf->SetXY(150,$y);
	$pdf->Cell(50,10,'',1,0,'R');
	$pdf->ln(5);
	$pdf->y = $y1;
	
	$pdf->SetX(118);
	$pdf->Cell(32,5,'',0,0,'L');
	$pdf->Cell(50,5,'',1,0,'R');
	$pdf->ln(5);
	
	$pdf->SetX(118);
	$pdf->Cell(32,5,'VAT@ 5%',0,0,'L');
	$pdf->Cell(50,5,'',1,0,'R');
	$pdf->ln(5);
	
	$pdf->SetX(118);
	$pdf->Cell(32,5,'TOTAL',0,0,'L');
	$pdf->SetFont('Verdana-Bold','',8);
	$pdf->Cell(50,5,'',1,0,'R');
	$pdf->ln(5);
	
	$pdf->SetX(118);
	$pdf->Cell(32,5,'Round Off',0,0,'L');
	$pdf->Cell(50,5,'',1,0,'R');
	$pdf->ln(5);
	$pdf->SetFont('Verdana','',8);
	$pdf->SetX(150);
	$pdf->Cell(50,5,'Authorized Signatory',0,0,'L');
	$pdf->ln(5);
	$pdf->SetX(118);
	$pdf->Cell(82,5,date('m/d/Y'),0,0,'R');
	$pdf->ln(8);
	$pdf->SetX(125);
	$pdf->SetFont('Verdana','',18.5);
	$pdf->Cell(10,5,'X',0,0,'L');
	$pdf->Line(120,$pdf->y+8, 190, $pdf->y+8);
	$pdf->ImagePngWithAlpha(dirname($pdf->maindir).'/assets/images/pl_invt_sign.png',131,$pdf->y-7,50);
	$pdf->ln(10);
	$pdf->PageBreakTrigger = 290;
	//
	$pdf->SetX(130);
	$pdf->SetFont('Verdana','',6);
	$pdf->Cell(10,3,'P. PUGALENTHI',0,0,'L');	
	$pdf->ln(3);
	$pdf->SetX(130);
	$pdf->Cell(10,3,'Assitant Manager-Supply Chain',0,0,'L');	
	$pdf->ln(3);
	$pdf->ln(15);
	
	
	$monYer = str_replace(' ', '', $monYer);
	//$filenamec="Payslip_$monYer.pdf";
	$filenamec="Payslip_report.pdf";
	$filePath =dirname($maindir)."/".$filenamec;
	if($download == 1)
	{
		$data = dirname($maindir)."/data/export/".session_id()."/";
		//$rapper->makedirectory($data);
		$name = preg_replace("/[^a-zA-Z0-9]/", "", $employee_name);
		$filenamec="{$name}_$monYer.pdf"; 
		$filePath =$data."/".$filenamec;
	}
	
	
	
	$pdf->Output($filePath, 'F');
	$filePath = $filenamec;//"Payslip_$monYer.pdf";
	$result = array('status'=>'success','file'=>$filePath.'?'.uniqid());
	}
	//else
	{
		//$result = array('status'=>'failure','file'=>'', 'message'=>'No records found');
	}
	echo json_encode($result);
?>