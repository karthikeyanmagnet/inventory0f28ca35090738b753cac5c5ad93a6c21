<?php 
	$maindir = dirname(realpath('..'));
	include  $maindir.'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	ini_set('display_error',1);
	$modobj = new stock_report();
	
	
	
	$postArr=$_POST;
	$action=$_POST["action"];
	
	if($action!='view') exit('illegal access!');
	
	$get_categoryid=$modobj->purifyInsertString($postArr["category_id"]);
	$get_cmbitemid=$modobj->purifyInsertString($postArr["cmb_item_id"]);
	$get_datefrom=$modobj->purifyInsertString($postArr["date_from"]);
	$get_dateto=$modobj->purifyInsertString($postArr["date_to"]);
	
	
	if($get_datefrom!="" and $get_dateto=="") { $get_dateto=$get_datefrom; }
	else if($get_datefrom=="" and $get_dateto!="") { $get_datefrom=$get_dateto; }
	
	if($get_datefrom=="" ) { $get_datefrom=date('d-m-Y'); }
	if($get_dateto=="") { $get_dateto=date('d-m-Y'); }
	
	$categorylist = $modobj->getModuleComboList('category');
	$itemlist = $modobj->getModuleComboList('item'); 
	
	$pass_start_date = date('Y-m-d', strtotime($get_datefrom));
	$pass_end_date = date('Y-m-d', strtotime($get_dateto)); 
	
	$dispItm=array();
	$dispDateArr=array();
 	//for($dti=1; $dti<=30; $dti++)
	while(strtotime($pass_start_date) <= strtotime($pass_end_date))  
	{
		$putdate=$pass_start_date;
		
		$pass_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($pass_start_date)));
		
		$dispDateArr[]=$putdate;
		
		$bindPassDateArr=array( ":pass_date"=>array("value"=>$putdate,"dtype"=>"text"));
		$bindPassDateOnlyArr=array( ":pass_date"=>array("value"=>$putdate,"dtype"=>"text"));
		
		$extFilt="";
		if($get_categoryid)
		{
			$extFilt.=" and itm.category_id=:category_id";
			$bindPassDateArr[":category_id"]=array("value"=>$get_categoryid,"dtype"=>"text");
		}
		if($get_cmbitemid)
		{
			$extFilt.=" and itm.item_id=:item_id";
			$bindPassDateArr[":item_id"]=array("value"=>$get_cmbitemid,"dtype"=>"text");
		}
		
		//$itms_sql="select itm.item_id, itm.item_code, itm.item_description, if(date(itm.createdon)<=:pass_date,itm.item_open_stock,0) as item_open_stock, itm.createdon from bud_item_master as itm order by itm.item_code, itm.item_description"; 
		
			$itms_sql=" select item_id,  item_code, item_description, sum(sum_received_qty) -sum(sum_dispatch_qty) as sum_opening_qty, category_name, item_unit_cost, item_currency_id     from ( select itm.item_id, itm.item_code, itm.item_description, if(date(itm.createdon)<=:pass_date,itm.item_open_stock,0) as sum_received_qty, 0 as sum_dispatch_qty, cat.category_name, itm.item_unit_cost, itm.item_currency_id  from bud_item_master as itm left join bud_category_master as cat on itm.category_id=cat.category_id where 1 {$extFilt}
 
 union all
 
 select   ssub.item_id, '' as item_code, '' as item_desc,  sum(ssub.received_qty) as sum_received_qty, 0 as sum_dispatch_qty, '' as category_name, itm.item_unit_cost, itm.item_currency_id from bud_stock_details as ssub left join bud_stock_head as shd on ssub.stock_head_id=shd.stock_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id left join bud_item_master as itm on ssub.item_id=itm.item_id where ssub.stock_status=1 and (ssub.received_date<:pass_date or ph.ship_date<:pass_date) {$extFilt} group by ssub.item_id 
 
 union all
 
 select ssub.item_id, '' as item_code, '' as item_description, 0 as sum_received_qty, sum(ssub.dispatch_qty) as sum_dispatch_qty, '' as category_name, itm.item_unit_cost, itm.item_currency_id from bud_stock_dispatch_details as ssub left join bud_stock_dispatch_head as shd on ssub.stock_dispatch_head_id =shd.stock_dispatch_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id left join bud_item_master as itm on ssub.item_id=itm.item_id where ssub.stock_dispatch_status =1 and (ssub.dispatch_date<:pass_date or ph.ship_date<:pass_date) {$extFilt} group by ssub.item_id
 
 ) as dd group by item_id order by category_name, item_code, item_description";
		$recs_itm = $modobj->pdoObj->fetchMultiple($itms_sql, $bindPassDateArr);  
		//echo $itms_sql.json_encode($bindPassDateArr);
		
		//============ Stock ENTRY 
		$stock_in_sql="select ssub.item_id,  sum(ssub.received_qty) as sum_received_qty from bud_stock_details as ssub left join bud_stock_head as shd on ssub.stock_head_id=shd.stock_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id where ssub.stock_status=1 and (ssub.received_date=:pass_date or ph.ship_date=:pass_date) group by ssub.item_id; ";
		$recs_stock_in = $modobj->pdoObj->fetchMultiple($stock_in_sql, $bindPassDateOnlyArr); 
		
		$stock_in_arr=array();
		foreach($recs_stock_in as $stock_in_lp)
		{
			$stock_in_arr[$stock_in_lp["item_id"]]=$stock_in_lp["sum_received_qty"];
		}
		
		
		
		//============ Stock DISPATCH 
		$stock_dis_sql="select ssub.item_id, sum(ssub.dispatch_qty) as sum_dispatch_qty from bud_stock_dispatch_details as ssub left join bud_stock_dispatch_head as shd on ssub.stock_dispatch_head_id =shd.stock_dispatch_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id where ssub.stock_dispatch_status =1 and (ssub.dispatch_date=:pass_date or ph.ship_date=:pass_date) group by ssub.item_id; ";
		$recs_stock_dis = $modobj->pdoObj->fetchMultiple($stock_dis_sql, $bindPassDateOnlyArr); 
		
		$stock_dis_arr=array();
		foreach($recs_stock_dis as $stock_dis_lp)
		{
			$stock_dis_arr[$stock_dis_lp["item_id"]]=$stock_dis_lp["sum_dispatch_qty"];
		} 
		
		
		$lpisno=0;
		foreach($recs_itm as $rs_lp_itm)
		{
			$lpisno++;
			$lp_itmstk_item_id=$rs_lp_itm["item_id"];
			$lp_itmstk_item_code=$rs_lp_itm["item_code"];
			$lp_itmstk_item_description=$rs_lp_itm["item_description"];
			$lp_itmstk_category_name=$rs_lp_itm["category_name"];
			
			$lp_itmstk_item_open_stock=intval($rs_lp_itm["sum_opening_qty"]);
			$lp_itmstk_received=intval($stock_in_arr[$lp_itmstk_item_id]);
			$lp_itmstk_dispatched=intval($stock_dis_arr[$lp_itmstk_item_id]);
			$lp_itmstk_item_close_stock=($lp_itmstk_item_open_stock+$lp_itmstk_received-$lp_itmstk_dispatched);
			
			$lp_goods_value = ($rs_lp_itm['item_unit_cost'] * $lp_itmstk_item_close_stock).' '.$modobj->getItemGoodsValue($rs_lp_itm['item_currency_id']);
			
			$dispItm[$lp_itmstk_item_id]['item_id']=$lp_itmstk_item_id;
			$dispItm[$lp_itmstk_item_id]['category_name']=$lp_itmstk_category_name;
			$dispItm[$lp_itmstk_item_id]['item_code']=$lp_itmstk_item_code; 
			$dispItm[$lp_itmstk_item_id]['item_desc']=$lp_itmstk_item_description; 
			$dispItm[$lp_itmstk_item_id]['details'][$putdate]=array('item_open_stock'=>$lp_itmstk_item_open_stock, 'item_received'=>$lp_itmstk_received, 'item_dispatched'=>$lp_itmstk_dispatched, 'item_close_stock'=>$lp_itmstk_item_close_stock , 'item_goods_value'=>$lp_goods_value);
			
			 
		} 
		 
	}
	
	
	
	
	//include_once($maindir.'/rapper/Spreadsheet/Excel/Writer.php');
	require_once($maindir.'/rapper/excel/Classes/PHPExcel.php');
	$objPHPExcel = new PHPExcel();
	
	$file = dirname($maindir).'/stock_report.xls';
	
	if(file_exists($file)) unlink($file);
	
	$objWorksheet = $objPHPExcel->getActiveSheet();
	
	
			
	
	
	$styleFormHeader = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true) );
	
	$styleFormSubHeader = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)) );
	
	$styleTotalHeader = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '99CCFF') ) );
	
	$styleJusBorder = array('font'  => array('bold'  => false,'color' => array('rgb' => '000000'),'size'  => 10,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))  ); 
	
	$styleJusBorder_right = array('font'  => array('bold'  => false,'color' => array('rgb' => '000000'),'size'  => 10,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))  ); 
	
	$styleTotalHeader_right = array('font'  => array('bold'  => true,'color' => array('rgb' => '000000'),'size'  => 11,'name'  => 'Calibri'),'alignment' => array('horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,'vertical' =>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true), 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '99CCFF') ) );
	
	$title = 'Stock Report ';
	$totalColumns = 30;
	
	$r = 3;
	//column title
	$objWorksheet->setCellValueByColumnAndRow(1,$r,$title);
	$objWorksheet->mergeCells('B3:E3');	
	$objWorksheet->getStyle("B{$r}:E{$r}")->applyFromArray($styleFormHeader);
	$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
	
	$r++;
		/*$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
		$objWorksheet->setCellValueByColumnAndRow(1,$r,'Stock Report');
		$objWorksheet->getStyle("B{$r}:E{$r}")->applyFromArray($styleFormSubHeader);
		$objWorksheet->mergeCells("B{$r}:E{$r}");	
	
		$r++;*/
		$objWorksheet->getRowDimension($r)->setRowHeight(20); //set row height
		
		$objWorksheet->setCellValueByColumnAndRow(1,$r,'Category');
		$objWorksheet->setCellValueByColumnAndRow(2,$r,'Item Code');
		$objWorksheet->setCellValueByColumnAndRow(3,$r,'Item Description');
		$objWorksheet->mergeCells("B{$r}:B".($r+1));	
		$objWorksheet->mergeCells("C{$r}:C".($r+1));
		$objWorksheet->mergeCells("D{$r}:D".($r+1));
		
		$objWorksheet->getStyle("B{$r}:D{$r}")->applyFromArray($styleFormSubHeader);
			
		$cl = 4;
		
		foreach($dispDateArr as $dispDateVal)
		{
			$date = $modobj->convertDate($dispDateVal);
			$cl1 = $cl+1;
			$cl2 = $cl1+4;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r,$date);
			
			$Col1 = stringFromColumnIndex($cl1);
			$Col2 = stringFromColumnIndex($cl2);
			$objWorksheet->mergeCells("{$Col1}{$r}:{$Col2}{$r}");
			
			$objWorksheet->setCellValueByColumnAndRow($cl,$r+1,'Open'); $cl++;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r+1,'Received'); $cl++;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r+1,'Dispatched'); $cl++;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r+1,'Closing'); $cl++;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r+1,'Value'); $cl++;
			
			$objWorksheet->getStyle("{$Col1}{$r}:{$Col2}{$r}")->applyFromArray($styleFormSubHeader);
			$objWorksheet->getStyle("{$Col1}".($r+1).":{$Col2}".($r+1))->applyFromArray($styleFormSubHeader);
			
			
		}
		$totCol = $cl;
		$r+=2;
		
		foreach($dispItm as $dispItmKey=>$dispItmVal)
		{
			$cl = 1;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r,$dispItmVal["category_name"]); $cl++;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r,$dispItmVal["item_code"]); $cl++;
			$objWorksheet->setCellValueByColumnAndRow($cl,$r,$dispItmVal["item_desc"]); $cl++;
			
			$objWorksheet->getStyle("B{$r}:D{$r}")->applyFromArray($styleJusBorder);
			
			foreach($dispDateArr as $dispDateVal)
			{
			
				$lpd_open=$dispItmVal["details"][$dispDateVal]['item_open_stock'];
				$lpd_received=$dispItmVal["details"][$dispDateVal]['item_received'];
				$lpd_dispatched=$dispItmVal["details"][$dispDateVal]['item_dispatched'];
				$lpd_closed=$dispItmVal["details"][$dispDateVal]['item_close_stock'];
				$lpd_goods_value=$dispItmVal["details"][$dispDateVal]['item_goods_value'];
				
				$cl1 = $cl;
				$cl2 = $cl1+5;
				$Col1 = stringFromColumnIndex($cl1);
				$Col2 = stringFromColumnIndex($cl2);
				
				$objWorksheet->setCellValueByColumnAndRow($cl,$r,$lpd_open); $cl++;
				$objWorksheet->setCellValueByColumnAndRow($cl,$r,$lpd_received); $cl++;
				$objWorksheet->setCellValueByColumnAndRow($cl,$r,$lpd_dispatched); $cl++;
				$objWorksheet->setCellValueByColumnAndRow($cl,$r,$lpd_closed); $cl++;
				$objWorksheet->setCellValueByColumnAndRow($cl,$r,$lpd_goods_value); $cl++;
				
				$objWorksheet->getStyle("{$Col1}{$r}:{$Col2}{$r}")->applyFromArray($styleJusBorder);
				
				
			}	
			
			$r++;
			
		}
		
		for($cl = 2; $cl < $totCol; $cl++)
		{
			$objWorksheet->getColumnDimensionByColumn($cl)->setAutoSize(true);
		}	 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($file);
		
		
		$result = array('status'=>'success','file'=>'stock_report.xls');
		echo json_encode($result);
		
		function stringFromColumnIndex($col) 
		{
			$numeric = ($col - 1) % 26;
			$letter = chr(65 + $numeric);
			$col2 = intval(($col - 1) / 26);
			
			if ($col2 > 0) 
				return stringFromColumnIndex($col2) . $letter;
			else 
				return $letter;
		} 
		
?>