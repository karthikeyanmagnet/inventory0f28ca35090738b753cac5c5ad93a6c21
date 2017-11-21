<?php
	$maindir = dirname(realpath('..'));
	include  $maindir.'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	
	$expense = new expense();
	
	$postArr=$_POST;
	$action=$_POST["action"];
	
	if($action!='view') exit('illegal access!');
	
	$data = $expense->getExpenseReportData($postArr);
	
	$expDisp = $data['expDisp'];
	
	include $maindir.'/rapper/fpdf/PrintPosition.php';

	class RPDF extends PDF
	{
		function Header()
		{
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
			$this->Cell(0,8,'Copyright � '.date('Y'),0,0,'L');
		
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
			foreach($this->tableColumns as $colkey=>$column)
			{
				$this->Cell($tableWidth[$colkey],8,$column,1,0,$tableAlign[$colkey]);
			}
			
			$this->ln(8);
		}
		
		function printTableRow($row)
		{
			$tableWidth = $this->tableWidth;
			$tableColumns = $this->tableColumns;
			$tableAlign = $this->tableAlign;
			
			foreach($this->tableColumns as $colkey=>$column)
			{
				$this->Cell($tableWidth[$colkey],8,$row[$colkey],1,0,$tableAlign[$colkey]);
			}	
			
			$this->ln(8);
		}
	}
	
	$title = "Expense Report";

	$pdf = new RPDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('Verdana-Bold','','verdana_bold.php');
	$pdf->AddFont('Verdana','','verdana.php');
	$pdf->AddPage();
	$pdf->SetLeftMargin(15);
	$pdf->SetRightMargin(20);
	
	$pdf->SetXY(15,100);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Verdana','',14);
	$pdf->Cell(0,15,$title,1,0,'C');
	$pdf->ln(5);
	
	if(count($expDisp)>0)
	{
	
		$pdf->AddPage(); // expense sep page comment
		
		$pdf->SetX(13);
		$tableColumns = array('Date','Category','Sub Category','Expenses');
		$tableWidth = array(40,51,51,40);
		$tableAlign = array('C','L','L','R');
		
		$pdf->tableColumns = $tableColumns;
		$pdf->tableWidth = $tableWidth;
		$pdf->tableAlign = $tableAlign;
		$pdf->SetFont('Verdana','',9);
		
		foreach($expDisp as $expLpKey=>$expLpVal)
		{
			$pdf->subHeaderName = 'Employee name : '.$expLpKey;
			//$pdf->AddPage(); // expense sep page uncomment
			$pdf->ln(8);		
			$pdf->Cell(182,8,'Employee name : '.$expLpKey,1,0,'C');
			$pdf->ln(8);
			
			$pdf->printTableHeader();
			//$pdf->ln(8);
			
			foreach($expLpVal["data"] as $expDataVal)
			{
				$rowArr = array($expDataVal["exp_date"],  $expDataVal["cat_name"], $expDataVal["subcat_name"], number_format($expDataVal["exp_amount"],2));
				$pdf->printTableRow($rowArr);
			}
			//$pdf->ln(8);
			$pdf->Cell(142,8,'Total',1,0,'C');
			$pdf->Cell(40,8, number_format($expLpVal["total"],2),1,0,'R');
			$pdf->ln(8);
			
		}
	}
	$filePath =dirname($maindir).'/expense_report.pdf';
	$pdf->Output($filePath, 'F');
	$filePath = 'expense_report.pdf';
	$result = array('status'=>'success','file'=>$filePath.'?'.uniqid());
	echo json_encode($result);
?>