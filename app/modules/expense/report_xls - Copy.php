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
	
	
	
	include_once($maindir.'/rapper/Spreadsheet/Excel/Writer.php');
	
	$file = dirname($maindir).'/expense_report.xls';
	
	if(file_exists($file)) unlink($file);
			
	$workbook = new Spreadsheet_Excel_Writer($file);
	//$workbook->send($file);
	
	$titleFormat =& $workbook->addFormat();
	$titleFormat->setFontFamily('Arial');
	$titleFormat->setBold();
	$titleFormat->setSize('10');
	$titleFormat->setAlign('left');
	
	$topheadertitle =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vcenter','Align' => 'center'));
	$topheadertitle->setFontFamily('Arial');	
	$topheadertitle->setBold();		
	$topheadertitle->setSize('10');				
	
	
	$topheadertitle_3left =& $workbook->addFormat(array('right' => 0, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'left'));
	$topheadertitle_3left->setFontFamily('Arial');	
	$topheadertitle_3left->setBold();		
	$topheadertitle_3left->setSize('10');	
	
	$topheadertitle_2tb =& $workbook->addFormat(array('right' => 0, 'bottom' => 2,'top' => 2, 'left' => 0, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'center'));
	$topheadertitle_2tb->setFontFamily('Arial');	
	//$topheadertitle_2tb->setBold();		
	$topheadertitle_2tb->setSize('10');	
	
	$topheadertitle_3right =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 0, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'center'));
	$topheadertitle_3right->setFontFamily('Arial');	
	//$topheadertitle_3right->setBold();		
	$topheadertitle_3right->setSize('10');	
	
	$topheadertitle_3left_1 =& $workbook->addFormat(array('right' => 0, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'center'));
	$topheadertitle_3left_1->setFontFamily('Arial');	
	//$topheadertitle_3left_1->setBold();		
	$topheadertitle_3left_1->setSize('10');
	
	$topheadertitle_3right_1 =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 0, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'center'));
	$topheadertitle_3right_1->setFontFamily('Arial');	
	//$topheadertitle_3right->setBold();		
	$topheadertitle_3right_1->setSize('10');	
	
	$topheadertitle_4br =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'center'));
	$topheadertitle_4br->setFontFamily('Arial');	
	//	
	$topheadertitle_4br->setSize('10');	
	
	
	$topheadertitle_4br_num =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'right'));
	$topheadertitle_4br_num->setFontFamily('Arial');	
	$topheadertitle_4br_num->setSize('10');	
	$topheadertitle_4br_num->setNumFormat('0.00');
	
	$topheadertitle_4br_num_tot =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => '31','vAlign' => 'vbottom','Align' => 'right'));
	$topheadertitle_4br_num_tot->setFontFamily('Arial');	
	$topheadertitle_4br_num_tot->setSize('10');	
	//$topheadertitle_4br_num_tot->setColor('white');
	$topheadertitle_4br_num_tot->setBold();		
	$topheadertitle_4br_num_tot->setNumFormat('0.00');
	
	$topheadertitle_4br_left =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'left'));
	$topheadertitle_4br_left->setFontFamily('Arial');	
	//$topheadertitle_3right->setBold();		
	$topheadertitle_4br_left->setSize('10');	
	
	$topheadertitle_4br_num_tot_center =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => '31','vAlign' => 'vbottom','Align' => 'center'));
	$topheadertitle_4br_num_tot_center->setFontFamily('Arial');	
	$topheadertitle_4br_num_tot_center->setSize('10');	
	
								  
	
	$subbordertitle =& $workbook->addFormat(array('right' => 2, 'bottom' => 2,'top' => 2, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vcenter','Align' => 'left'));
	$subbordertitle->setItalic();
	$subbordertitle->setBold();
	$subbordertitle->setColor(25);
	$subbordertitle->setFontFamily('Arial');	
	$subbordertitle->setAlign('middle');
	
	
	$inner_details_2lr =& $workbook->addFormat(array('right' => 2, 'bottom' => 0,'top' => 0, 'left' => 2, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vbottom','Align' => 'center'));
	$inner_details_2lr->setFontFamily('Arial');	
	//$topheadertitle_3right->setBold();		
	$inner_details_2lr->setSize('10');	
	
	
	//$subbordertitle->setTextWrap();	
	$borderinnertitle =& $workbook->addFormat(array('right' => 1, 'bottom' => 1,'top' => 1, 'left' => 1, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vtop'));
	$borderinnertitle->setFontFamily('Arial');	
	$borderinnertitle->setAlign('right');
	$borderinnertitle->setNumFormat('0.00');

	
													  
	$borderinnertitle_top =& $workbook->addFormat(array('right' => 1, 'bottom' => 1,'top' => 1, 'left' => 1, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vtop'));
	$borderinnertitle_top->setAlign('left');
	$borderinnertitle_top->setFontFamily('Arial');	
	$borderinnertitle_top->setAlign('top');
	$borderinnertitle_top->setTextWrap();		
	
	
	$borderinnertitlecenter =& $workbook->addFormat(array('right' => 1, 'bottom' => 1,'top' => 1, 'left' => 1, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vtop'));											  
	$borderinnertitlecenter->setAlign('center');
	$borderinnertitlecenter->setAlign('top');
	$borderinnertitlecenter->setTextWrap();	
	
	$NumberFormat =& $workbook->addFormat(array('right' => 1, 'bottom' => 1,'top' => 1, 'left' => 1, 'size' => 10,
													  'pattern' => 1, 'bordercolor' => 'black',
													  'fgcolor' => 'white','vAlign' => 'vtop'));
	$NumberFormat->setNumFormat('0.00');
					  
	
	$worksheet =& $workbook->addWorksheet();
	
	$title = 'Expenses Report ';
	$totalColumns = 30;
	
	$worksheet->setMerge(2, 1, 2, 4);
	$worksheet->write(2,1,$title,$titleFormat);
		
		//column title
		$r=3;
		$worksheet->setRow($r,20); //set row height
		
		foreach($expDisp as $expLpKey=>$expLpVal)
		{
			$r++;
			$worksheet->setRow($r,20);
			$worksheet->write($r,1, 'Employee name : '.$expLpKey, $topheadertitle);
			$worksheet->write($r,2, '', $topheadertitle);
			$worksheet->write($r,3, '', $topheadertitle);
			$worksheet->write($r,4, '', $topheadertitle);
			$worksheet->setMerge($r, 1, $r, 4);
		
			$r++;
			$worksheet->setRow($r,20);
			//$worksheet->write($r,0, '', $topheadertitle);
			$worksheet->write($r,1, 'Date', $topheadertitle);
			$worksheet->write($r,2, 'Category', $topheadertitle);
			$worksheet->write($r,3, 'Sub Category', $topheadertitle);
			$worksheet->write($r,4, 'Expenses', $topheadertitle);
		
			foreach($expLpVal["data"] as $expDataVal)
			{
				$r++;
				$worksheet->setRow($r,20);
				//$worksheet->write($r,0, '', $topheadertitle);
				$worksheet->write($r,1, $expDataVal["exp_date"], $topheadertitle_4br_left);
				$worksheet->write($r,2, $expDataVal["cat_name"], $topheadertitle_4br_left);
				$worksheet->write($r,3, $expDataVal["subcat_name"], $topheadertitle_4br_left);
				$worksheet->write($r,4, number_format($expDataVal["exp_amount"],2), $topheadertitle_4br_num);
			}
			
			$r++;
			$worksheet->setRow($r,20);
			//$worksheet->write($r,0, '', $topheadertitle);
			$worksheet->write($r,1, 'Total', $topheadertitle_4br_num_tot_center);
			$worksheet->write($r,2, '', $topheadertitle_4br_num_tot_center);
			$worksheet->write($r,3, '', $topheadertitle_4br_num_tot_center);
			$worksheet->setMerge($r, 1, $r, 3);
			$worksheet->write($r,4, number_format($expLpVal["total"],2), $topheadertitle_4br_num_tot);
			
		}
		
		for($cl = 1; $cl < 6; $cl++)
		{
			$wid = 20;
			$worksheet->setColumn($cl,$cl,$wid);
		}	 
		 
		
	/*	$r+=2;    // dont delete this pls
		for ($inc =0; $inc<64; $inc++) {
			// Sets the color of a cell's content
			$format = $workbook->addFormat();
			$format->setFgColor($inc);
			$worksheet->write($r, 0, 'Color (index '.$inc.')', $format);
			$r++;
		}*/
		
		$workbook->close();	
		
		$result = array('status'=>'success','file'=>'expense_report.xls');
		echo json_encode($result);
		
?>