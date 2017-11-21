<?php 
	$maindir = dirname(realpath('..'));
	include  $maindir.'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	ini_set('display_error',1);
	$expense = new expense();
	
	$postArr=$_POST;
	$action=$_POST["action"];
	
	if($action!='view') exit('illegal access!');
	
	$data = $expense->getExpenseReportData($postArr);
	
	$expDisp = $data['expDisp'];  
	
	
	
	//include_once($maindir.'/rapper/Spreadsheet/Excel/Writer.php');
	require_once($maindir.'/rapper/excel/Classes/PHPExcel.php');
	$objPHPExcel = new PHPExcel();
	
	$file = dirname($maindir).'/expense_report.xls';
	
	if(file_exists($file)) unlink($file);
	
	$objWorksheet = $objPHPExcel->getActiveSheet();
	
	
			
	
	
	$styleFormHeader = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true) );
	
	$styleFormSubHeader = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );
	
	$styleTotalHeader = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '99CCFF') ) );
	
	$styleJusBorder = array('font'  => array('bold'  => false,'color' => array('rgb' => '000000'),'size'  => 10,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))  ); 
	
	$styleJusBorder_right = array('font'  => array('bold'  => false,'color' => array('rgb' => '000000'),'size'  => 10,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))  ); 
	
	$styleTotalHeader_right = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '99CCFF') ) );
	
	$title = 'Expenses Report ';
	$totalColumns = 30;
	
	$r = 3;
	//column title
	$objWorksheet->setCellValueByColumnAndRow(1,$r,$title);
	$objWorksheet->mergeCells('B3:E3');	
	$objWorksheet->getStyle("B{$r}:E{$r}")->applyFromArray($styleFormHeader);
	$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
	
	
		
	foreach($expDisp as $expLpKey=>$expLpVal)
	{
		$r++;
		$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
		$objWorksheet->setCellValueByColumnAndRow(1,$r,'Employee name : '.$expLpKey);
		$objWorksheet->getStyle("B{$r}:E{$r}")->applyFromArray($styleFormSubHeader);
		$objWorksheet->mergeCells("B{$r}:E{$r}");	
	
		$r++;
		$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
		
		$objWorksheet->setCellValueByColumnAndRow(1,$r,'Date');
		$objWorksheet->setCellValueByColumnAndRow(2,$r,'Category');
		$objWorksheet->setCellValueByColumnAndRow(3,$r,'Sub Category');
		$objWorksheet->setCellValueByColumnAndRow(4,$r,'Expenses');
		$objWorksheet->getStyle("B{$r}:E{$r}")->applyFromArray($styleFormSubHeader);
		
		
	
		foreach($expLpVal["data"] as $expDataVal)
		{
			$r++;
			$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
			
			$objWorksheet->setCellValueByColumnAndRow(1,$r,$expDataVal["exp_date"]);
			$objWorksheet->setCellValueByColumnAndRow(2,$r,$expDataVal["cat_name"]);
			$objWorksheet->setCellValueByColumnAndRow(3,$r,$expDataVal["subcat_name"]);
			$objWorksheet->setCellValueByColumnAndRow(4,$r,number_format($expDataVal["exp_amount"],2));
			$objWorksheet->getStyle("B{$r}:D{$r}")->applyFromArray($styleJusBorder);
			$objWorksheet->getStyle("E{$r}")->applyFromArray($styleJusBorder_right);
			$objWorksheet->getStyle("E{$r}")->getNumberFormat()->setFormatCode("#,##0.00");
		}
		
		$r++;
		$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
		
		$objWorksheet->setCellValueByColumnAndRow(1,$r,'Total');
		$objWorksheet->setCellValueByColumnAndRow(4,$r,number_format($expLpVal["total"],2));
		$objWorksheet->mergeCells("B{$r}:D{$r}");
		$objWorksheet->getStyle("B{$r}:D{$r}")->applyFromArray($styleTotalHeader);
		$objWorksheet->getStyle("E{$r}")->applyFromArray($styleTotalHeader_right);
		$objWorksheet->getStyle("E{$r}")->getNumberFormat()->setFormatCode("#,##0.00");
		
	}
		
		for($cl = 1; $cl < 6; $cl++)
		{
			$objWorksheet->getColumnDimensionByColumn($cl)->setAutoSize(true);
		}	 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($file);
		
		
		$result = array('status'=>'success','file'=>'expense_report.xls');
		echo json_encode($result);
		
?>