<?php	

class expense extends rapper
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
		  $draw = $postArr['draw'];
		  $start = ($postArr['start'])?$postArr['start']:0;
		  $limit = ($postArr['length'])?$postArr['length']:0;
		  
		  
		  if($postArr['exp_status']=="") $exp_status=-1;
		  else $exp_status=$postArr['exp_status'];
		  
		  $sess_logintype = $this->sess_logintype;
		  $sess_userid = $this->sess_userid;
		  $user_previlage = $this->getUserPervilageSettings();
		  
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		  $bindArr=array();
		  $search = ($postArr['search']['value']);	  
		  $where = " where 1";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where.= ' and um.user_name like :search_str';
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  if($exp_status>-1)
		  {
		  		$where.= ' and eh.expenses_status =:exp_status';
				$bindArr[':exp_status']=array("value"=>$exp_status,"type"=>"int");
		  }
		  
		  //if($sess_logintype=="3")
		  if(!$user_previlage['approve_previlage'] && !$user_previlage['view_to_others'])
		  {
		  		$where.= ' and eh.createdby =:createdby';
				$bindArr[':createdby']=array("value"=>$sess_userid,"type"=>"int");
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_expenses as eh left join bud_user_master as um on um.user_id=eh.createdby  left join bud_user_master as aprj on aprj.user_id=eh.expenses_appr_reject_by $where ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		   
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		 // $sql="select expenses_id,cash_in_hand,expenses_status,  case expenses_status when 0 then 'Waiting' when 1 then 'Approved' when 2 then 'Rejected' end as expenses_status_desc, total_expenses from bud_expenses $where order by cash_in_hand "; 
		  
		 $sql=" select eh.expenses_id, um.user_name as bud_created_by, eh.expenses_date, min(ed.expense_det_date) as ed_min_date, max(ed.expense_det_date) as ed_max_date, eh.total_expenses,  case expenses_status when 0 then 'Waiting for approval' when 1 then 'Approved' when 2 then 'Rejected' end as expenses_status_desc, aprj.user_name as app_rejected_user, eh.expenses_appr_reject_on as app_rejected_date, expenses_status from bud_expenses as eh left join bud_expense_details as ed on ed.expenses_id=eh.expenses_id left join bud_user_master as um on um.user_id=eh.createdby  left join bud_user_master as aprj on aprj.user_id=eh.expenses_appr_reject_by $where group by ed.expenses_id order by eh.createdon desc "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			$xpstsclr[0]="#333399";
			$xpstsclr[1]="#00CC33";
			$xpstsclr[2]="#FF6633";
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["expenses_id"]);
				
				$bud_created_by=$this->purifyString($rs["bud_created_by"]); 
				$expenses_date=$this->convertDate($this->purifyString($rs["expenses_date"]));
				
				$exp_min_date=$this->convertDate($this->purifyString($rs["ed_min_date"]));
				$exp_max_date=$this->convertDate($this->purifyString($rs["ed_max_date"])); 
				$dt_range=$exp_min_date.' - '.$exp_max_date;
				
				$total_expenses=$this->purifyString($rs["total_expenses"]);
				$expenses_status_desc=$this->purifyString($rs["expenses_status_desc"]);
				$exp_app_rj="";
				if($rs["expenses_status"]=="1" or $rs["expenses_status"]=="2") 
				{
					$exp_app_rj=$this->purifyString($rs["app_rejected_user"]).' on '.$this->convertDate($this->purifyString($rs["app_rejected_date"]));	
				} 
				
				$actCtrl='<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateExpenseMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span> ';
				
				if(!$rs["expenses_status"]) $actCtrl='<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateExpenseMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete"  onclick="viewDeleteExpenseMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>';
				
				$expnStsDisp='<span style="color:'.$xpstsclr[$rs["expenses_status"]].'">'.$expenses_status_desc.'</span>';
				
				
				//$sendRs[$rsCnt]=array("expenses_id"=>$cid,"cash_in_hand"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1, $bud_created_by, $expenses_date, $dt_range, $total_expenses, $expnStsDisp, $exp_app_rj, $actCtrl);
				$rsCnt++;
				$PageSno++;
				
			}
			
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	public function getSingleView($postArr)
	{
		$sess_userid = $this->sess_userid;
		$sess_logintype = $this->sess_logintype; 
		  
		$getid=$this->purifyInsertString($postArr["id"]);
		$category = $this->getModuleComboList('category', 'all');
		$subcategory = $this->getModuleComboList('subcategory', 'all'); 
		
		$Filepath = $postArr['file_path'];
		$import_opt = $postArr['import_opt'];
		if($import_opt == 1)
		{
			// Excel reader from http://code.google.com/p/php-excel-reader/
			require($this->ProjFile.'/php-excel-reader/excel_reader2.php');
			require($this->ProjFile.'/SpreadsheetReader.php');
			
			
			
			if(file_exists($Filepath))
			{
				try
				{
					$Spreadsheet = new SpreadsheetReader($Filepath);
					$Sheets = $Spreadsheet -> Sheets();
					$Spreadsheet -> ChangeSheet(0);
					$headArr = array();
					foreach ($Spreadsheet as $Key => $Row)
					{
						if($Key == 0)
						{
							$headArr = $Row;
						}
						else
						if ($Row)
						{
							$data = array_combine($headArr,$Row);
							$expense_details[] = $this->getExpenseData($data);
						}
					}	
				}
				catch (Exception $E)
				{
					//echo $E -> getMessage();
				}	
			}
		}
		else
		{
		
		
		$sql="select expenses_id,cash_in_hand,expenses_status, total_expenses,  date_format(bud_expenses.createdon, '%d-%m-%Y %h:%i') as createdon, user_display_name ,expenses_appr_reject_notes, if(approve_previlage=1,'yes','no') as user_type from bud_expenses  left join bud_user_master on bud_expenses.createdby = bud_user_master.user_id where expenses_id=:expenses_id";
		$bindArr=array(":expenses_id"=>array("value"=>$getid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["expenses_id"]);
	
		$cash_in_hand=$this->purifyString($recs["cash_in_hand"]);
		$cstatus=$this->purifyString($recs["expenses_status"]);
		$total_expenses=$this->purifyString($recs["total_expenses"]);
		$expenses_description=$this->purifyString($recs["expenses_description"]);
		$expenses_appr_reject_notes=$this->purifyString($recs["expenses_appr_reject_notes"]);
		$createdon=$this->purifyString($recs["createdon"]);
		$createdby=$this->purifyString($recs["user_display_name"]);
		$user_type=$this->purifyString($recs["user_type"]);
		
		
		$sql_fare="  select fare_per_km, fare_effective_from from bud_fare_details_master where fare_effective_from<=curdate() order by fare_effective_from desc limit 1 "; 
		$fare_rs = $this->pdoObj->fetchSingle($sql_fare, ''); 
		$fare_per_km_hd = ($fare_rs["fare_per_km"])?$fare_rs["fare_per_km"]:0;
		
		if(!$getid)
		{
			$expense_details = array();
		}	
		else
		{
			$sql="select expenses_id,category_id,subcategory_id,expense_det_id,expense_det_date,expense_det_amount,expense_det_emo,expense_det_notes from bud_expense_details where expenses_id=:expenses_id";
			$bindArr=array(":expenses_id"=>array("value"=>$getid,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			$expense_details = array();
			
			foreach($recs as $key=>$rs)
			{
				$data = array('expense_det_id'=>$rs['expense_det_id'],'expenses_id'=>$rs['expenses_id']);
				
				$lcd = $this->getLCDDetails($data);
				
				$expense_details[$key]=array('expense_det_date'=>$this->convertDate($rs['expense_det_date']), 'expenses_id'=>$rs['expenses_id'], 'category_id'=>$rs['category_id'], 'subcategory_id'=>$rs['subcategory_id'], 'expense_det_id'=>$rs['expense_det_id'], 'expense_det_amount'=>$rs['expense_det_amount'], 'expense_det_emo'=>$rs['expense_det_emo'],'expense_det_notes'=>$rs['expense_det_notes'], 'lcd'=>$lcd);
				
			}
			
		}
		}
		$fileup_temp_usr_fold='tempdel_'.date('imdYsh').$sess_userid;
		
		$sendRs=array("expenses_id"=>$getid,"cash_in_hand"=>$cash_in_hand,"total_expenses"=>$total_expenses,"category"=>$category,"subcategory"=>$subcategory, "expense_details"=>$expense_details, 'fare_per_km_hd'=>$fare_per_km_hd, 'fileup_temp_usr_fold'=>$fileup_temp_usr_fold, 'temp_importfile'=>$Filepath, 'import_opt'=>$import_opt, 'createdon'=>$createdon, 'createdby'=>$createdby, 'user_type'=>$user_type,"expense_status"=>$cstatus,"sess_logintype"=>$sess_logintype);  
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	public function getExpenseData($arr)
	{
		$category_name = $arr['Category'];
		$subcategory_name = $arr['Sub Category'];
		
		$date = ($arr['Date'])?date('Y-m-d',strtotime($arr['Date'])):'';
		$expense_det_amount = $arr['Amount'];
		$expense_det_emo = (strtolower($arr['EMO'])=='yes')?1:0;
		$expense_det_notes = $arr['Details'];
		
		$strQuery = "select category_id from bud_category_master where trim(category_name)=:category_name ";
		$bindArr=array(":category_name"=>array("value"=>$category_name,"dtype"=>"text")); 
		$rs_cat = $this->pdoObj->fetchSingle($strQuery, $bindArr);
		$rs_cat['category_id'] = ($rs_cat['category_id'])?$rs_cat['category_id']:0;
		
		$strQuery = "select subcategory_id from bud_subcategory_master where trim(subcategory_name)=:subcategory_name and category_id=:category_id";
		$bindArr=array(":subcategory_name"=>array("value"=>$subcategory_name,"dtype"=>"text"), ":category_id"=>array("value"=>$rs_cat['category_id'],"dtype"=>"int")); 
		$rs_subcat = $this->pdoObj->fetchSingle($strQuery, $bindArr);  
		 
		
		$rs_subcat['subcategory_id'] = ($rs_subcat['subcategory_id'])?$rs_subcat['subcategory_id']:0;
		
		$expense_details=array('expense_det_date'=>$this->convertDate($date), 'expenses_id'=>0, 'category_id'=>$rs_cat['category_id'], 'subcategory_id'=>$rs_subcat['subcategory_id'], 'expense_det_id'=>$rs['expense_det_id'], 'expense_det_amount'=>$expense_det_amount, 'expense_det_emo'=>$expense_det_emo,'expense_det_notes'=>$expense_det_notes, 'lcd'=>array());
		
		return $expense_details;

	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cash_in_hand=$this->purifyInsertString($postArr["cash_hand"]);
		$total_expenses=$this->purifyInsertString($postArr["total_expenses"]); 
		$hid_fileup_temp_usr_fold=$this->purifyInsertString($postArr["hid_fileup_temp_usr_fold"]);  
		
		$hid_temp_del = $this->purifyInsertString($postArr["hid_temp_del"]);   
		$hid_temp_lcd_del= $this->purifyInsertString($postArr["hid_temp_lcd_del"]);
		
		$opn_app_reject = $this->purifyInsertString($postArr["opn_app_reject"]);  
		$txt_reject_notes = $this->purifyInsertString($postArr["txt_reject_notes"]);  
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_expenses where expenses_id=:expenses_id "; 
		$bindExtCntArr=array(":expenses_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_expenses SET cash_in_hand=:cash_in_hand,total_expenses=:total_expenses";
		$insBind=array(":cash_in_hand"=>array("value"=>$cash_in_hand,"type"=>"text"), ":total_expenses"=>array("value"=>$total_expenses,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		
		if($ext_cnt_val>0) 
		{ 
			if($opn_app_reject>0)
			{
				$ins.=", expenses_status=:expenses_status, expenses_appr_reject_by=:sess_user_id, expenses_appr_reject_on=now()";
				$insBind[":expenses_status"]=array("value"=>$opn_app_reject,"type"=>"int");
				
				if($opn_app_reject==2)
				{
					$ins.=", expenses_appr_reject_notes=:expenses_appr_reject_notes";
					$insBind[":expenses_appr_reject_notes"]=array("value"=>$txt_reject_notes,"type"=>"text");
				}	
			}	
				
				
				
			
			
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where expenses_id=:expenses_id ";
			$insBind[":expenses_id"]=array("value"=>$id,"type"=>"text"); 
			
			$bindExtChkArr[":expenses_id"]=array("value"=>$id,"dtype"=>"int");  
			
			$opmsg="Expenses updated successfully!";
		}
		else
		{
			$getmaxid = $this->pdoObj->getMaxRecord('expenses_id', 'bud_expenses');
			$id = $getmaxid + 1;
			$strQuery="INSERT INTO $ins, expenses_date=now(), expenses_id=:expenses_id, createdon=now(),createdby=:sess_user_id "; 
			$insBind[":expenses_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Expenses inserted successfully!";
		}
		
		$opStatus='failure';
		$opMessage='failure';
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			if($exec)
			{
				$hid_fileimport = $postArr['hid_fileimport'];
				$hid_import = $postArr['hid_import'];
				
				if($hid_import == 1)
				{
					$to_path = 'public/data/expenses/'.$id.'/import/';
					$this->makedirectory($to_path);
					$filename = basename($hid_fileimport);
					$toFileName=$to_path.$filename;
					if(file_exists($hid_fileimport))
					{
						$fc=copy($hid_fileimport,$toFileName);
						if($fc) 
						{
							$insFilename=$f;
							
						} 
						unlink($hid_fileimport);
					}
				}

				$hdn_exp_detid = $postArr['hdn_exp_detid'];
				$cmb_category = $postArr['cmb_category'];
				$cmb_subcategory = $postArr['cmb_subcategory'];
				$txt_expense_date = $postArr['txt_expense_date'];
				$txt_expense_amt = $postArr['txt_expense_amt'];
				$chk_expense_emo = $postArr['chk_expense_emo1'];
				$txt_expense_notes = $postArr['txt_expense_notes'];
				$lcd = $postArr['lcd'];
				
				//print_r($postArr);
				
				foreach($cmb_category as $cat_key=>$cat_val)
				{
					if($cat_val)
					{
						//print_r($postArr["lcd_$cat_key"]);
						$expense_det_id = $this->purifyInsertString($hdn_exp_detid[$cat_key]);
						$category_id = $this->purifyInsertString($cmb_category[$cat_key]);
						$subcategory_id = $this->purifyInsertString($cmb_subcategory[$cat_key]);
						$expense_det_date = $this->convertDate($this->purifyInsertString($txt_expense_date[$cat_key]));
						$expense_det_amount = $this->purifyInsertString($txt_expense_amt[$cat_key]);
						$expense_det_emo = $this->purifyInsertString($chk_expense_emo[$cat_key]);
						$expense_det_notes = $this->purifyInsertString($txt_expense_notes[$cat_key]);
						
						$cnt_ext_sql="select count(*) as ext_cnt from bud_expense_details where expenses_id=:expenses_id and expense_det_id=:expense_det_id"; 
						$bindExtCntArr=array(":expenses_id"=>array("value"=>$id,"type"=>"int"),":expense_det_id"=>array("value"=>$expense_det_id,"type"=>"int"));
						$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
						$ext_cnt_val=$rs_qry_exts["ext_cnt"];
						
						$ins=" bud_expense_details SET category_id=:category_id,subcategory_id=:subcategory_id,expense_det_date=:expense_det_date, expense_det_amount=:expense_det_amount, expense_det_emo=:expense_det_emo, expense_det_notes=:expense_det_notes";
						$insBind=array(":expenses_id"=>array("value"=>$id,"type"=>"int"),":category_id"=>array("value"=>$category_id,"type"=>"text"), ":subcategory_id"=>array("value"=>$subcategory_id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ":expense_det_date"=>array("value"=>$expense_det_date,"type"=>"text"), ":expense_det_amount"=>array("value"=>$expense_det_amount,"type"=>"text"), ":expense_det_emo"=>array("value"=>$expense_det_emo,"type"=>"int"), ":expense_det_notes"=>array("value"=>$expense_det_notes,"type"=>"text")); 
						if($ext_cnt_val>0) 
						{ 
							$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where expense_det_id=:expense_det_id and expenses_id=:expenses_id";
							$insBind[":expense_det_id"]=array("value"=>$expense_det_id,"type"=>"int"); 
							
						}
						else
						{
							$getmaxid = $this->pdoObj->getMaxRecord('expense_det_id', 'bud_expense_details');
							$expense_det_id = $getmaxid + 1;
							$strQuery="INSERT INTO $ins, expenses_id=:expenses_id, createdon=now(),createdby=:sess_user_id "; 
						}
						//echo $strQuery.json_encode($insBind);
						//echo '<br>';
						$exec = $this->pdoObj->execute($strQuery, $insBind);
						
						if($exec)
						{	
							//--------------------- File upload start
							$from_main_path = 'public/data/temp/'.$hid_fileup_temp_usr_fold.'/'; 
							$from_path = $from_main_path.'/'.($cat_key).'/'; 
														
							if(file_exists($from_path))
							{
								 
							$files = scandir($from_path); 
							$files = array_diff(scandir($from_path), array('.', '..'));  
							
							$to_path = 'public/data/expenses/'.$id.'/'.$expense_det_id.'/';
							$this->makedirectory($to_path);
							
							$insFilename='';
							foreach($files as $f)
							{
								$fromFileName=$from_path.$f;
								$toFileName=$to_path.$f;
								
								$fc=copy($fromFileName,$toFileName);
								if($fc) 
								{
									$insFilename=$f;
									
								} 
								unlink($fromFileName);
							}
							rmdir($from_path);
							rmdir($from_main_path);
							
							$cnt_ext_im_sql="select count(*) as ext_cnt from bud_expense_attachments where expenses_id=:expenses_id and expense_det_id=:expense_det_id "; 
							$bindExtCntImgArr=array(":expenses_id"=>array("value"=>$id,"type"=>"int"),":expense_det_id"=>array("value"=>$expense_det_id,"type"=>"int"));
							$rs_qry_imgexts = $this->pdoObj->fetchSingle($cnt_ext_im_sql, $bindExtCntImgArr); 
							$ext_cnt_imgval=$rs_qry_imgexts["ext_cnt"];	
							
							$expense_det_id = (int) $expense_det_id;
							$expenses_id = (int) $expenses_id;
							
							$insimgDt=" bud_expense_attachments SET attachment_name=:attachment_name ";
							$insImgBindDt=array(":expenses_id"=>array("value"=>$id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ":expense_det_id"=>array("value"=>$expense_det_id,"type"=>"int"), ":attachment_name"=>array("value"=>$insFilename,"type"=>"text")); 		
							
							if($ext_cnt_imgval>0) 
							{ 
								$strImgQueryDt="UPDATE $insimgDt, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where expense_det_id=:expense_det_id and expenses_id=:expenses_id"; 
							}
							else
							{
								$strImgQueryDt="INSERT INTO $insimgDt,expense_det_id=:expense_det_id,expenses_id=:expenses_id, createdon=now(),createdby=:sess_user_id "; 
							} 
							$execimg = $this->pdoObj->execute($strImgQueryDt, $insImgBindDt);
							
							}
							//--------------------- File upload End
							
							
							$lcd_dt = $postArr["lcd_$cat_key"];
							
							if(is_array($lcd_dt) && count($lcd_dt))
							{
							
							foreach($lcd_dt as $lcd)
							{
							
							$local_conv_id =  $this->purifyInsertString($lcd['local_conv_id']);
							
							$cnt_ext_sql="select count(*) as ext_cnt from bud_expense_local_conveyances where expenses_id=:expenses_id and expense_det_id=:expense_det_id and local_conv_id=:local_conv_id"; 
							$bindExtCntArr=array(":expenses_id"=>array("value"=>$id,"type"=>"int"),":expense_det_id"=>array("value"=>$expense_det_id,"type"=>"int"),":local_conv_id"=>array("value"=>$local_conv_id,"type"=>"int"));
							$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
							$ext_cnt_val=$rs_qry_exts["ext_cnt"];	
							
							$local_conv_date = $this->convertDate($this->purifyInsertString($lcd['local_conv_date']));
							$place_of_origin = $this->purifyInsertString($lcd['place_of_origin']);
							$place_of_destination = $this->purifyInsertString($lcd['place_of_destination']);
							$local_conv_distance = $this->purifyInsertString($lcd['local_conv_distance']);
							$fare_per_km = $this->purifyInsertString($lcd['fare_per_km']);
							$local_conv_fare_total = $this->purifyInsertString($lcd['local_conv_fare_total']);
							$food_expenses = $this->purifyInsertString($lcd['food_expenses']);
							$local_conv_line_total = $this->purifyInsertString($lcd['local_conv_line_total']);
							
							$expense_det_id = (int) $expense_det_id;
							$expenses_id = (int) $expenses_id;
							
							$ins=" bud_expense_local_conveyances SET local_conv_date=:local_conv_date,place_of_origin=:place_of_origin,place_of_destination=:place_of_destination, local_conv_distance=:local_conv_distance, fare_per_km=:fare_per_km, local_conv_fare_total=:local_conv_fare_total,food_expenses=:food_expenses, local_conv_line_total=:local_conv_line_total";
						$insBind=array(":expenses_id"=>array("value"=>$id,"type"=>"int"),":local_conv_date"=>array("value"=>$local_conv_date,"type"=>"text"), ":place_of_origin"=>array("value"=>$place_of_origin,"type"=>"text"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ":place_of_destination"=>array("value"=>$place_of_destination,"type"=>"text"), ":local_conv_distance"=>array("value"=>$local_conv_distance,"type"=>"text"), ":fare_per_km"=>array("value"=>$fare_per_km,"type"=>"text"), ":local_conv_fare_total"=>array("value"=>$local_conv_fare_total,"type"=>"text"),":food_expenses"=>array("value"=>$food_expenses,"type"=>"text"),":local_conv_line_total"=>array("value"=>$local_conv_line_total,"type"=>"text"),":expense_det_id"=>array("value"=>$expense_det_id,"type"=>"int")); 		
						
						if($ext_cnt_val>0) 
						{ 
							$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where local_conv_id=:local_conv_id and expense_det_id=:expense_det_id and expenses_id=:expenses_id";
							$insBind[":local_conv_id"]=array("value"=>$local_conv_id,"type"=>"int"); 
							
						}
						else
						{
							$strQuery="INSERT INTO $ins,expense_det_id=:expense_det_id,expenses_id=:expenses_id, createdon=now(),createdby=:sess_user_id "; 
						}
						//echo $strQuery.json_encode($insBind);
						//echo '<br>';
						$exec = $this->pdoObj->execute($strQuery, $insBind);
						
							}	
							}		
						}
						
					}	
				}	
				
				if($hid_temp_del != '')
				{
					$temp_arr = explode(',', $hid_temp_del);
					foreach($temp_arr as $del_id)
					{
						if($del_id)
						{
							$strQuery=" select * from bud_expense_attachments where expense_det_id=:expense_det_id "; 
							$bindArr=array( ":expense_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							$rsop = $this->pdoObj->fetchSingle($strQuery, $bindArr); 
							
							$filedir = 'public/data/expenses/'.$rsop['expenses_id'].'/'.$rsop['expense_det_id'].'/';
							
							$this->deleteDirectory($filedir);
							
							
							
							$strQuery=" delete from bud_expense_details where expense_det_id=:expense_det_id "; 
							$bindArr=array( ":expense_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							
							$exec = $this->pdoObj->execute($strQuery, $bindArr);
							
							$strQuery=" delete from bud_expense_local_conveyances where expense_det_id=:expense_det_id "; 
							$bindArr=array( ":expense_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							
							$exec = $this->pdoObj->execute($strQuery, $bindArr);
							
							$strQuery=" delete from bud_expense_attachments where expense_det_id=:expense_det_id "; 
							$bindArr=array( ":expense_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							
							$exec = $this->pdoObj->execute($strQuery, $bindArr);
						}
					}
				}
				//delete lcd details
				
				if($hid_temp_lcd_del != '')
				{
					$temp_arr = explode(',', $hid_temp_lcd_del);
					foreach($temp_arr as $del_id)
					{
						if($del_id)
						{
						$strQuery=" delete from bud_expense_local_conveyances where local_conv_id=:local_conv_id "; 
							$bindArr=array( ":local_conv_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							
							$exec = $this->pdoObj->execute($strQuery, $bindArr);
						}
					}
				}	
				
				//remove old files
				$removefile = ($postArr['removefile'])?$postArr['removefile']:array();
				
				foreach($removefile as $file)
				{
					if(file_exists($file))
					unlink($file);
				}
				
								
				
				
				
				
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_expense_details where expenses_id=:expenses_id "; 
			$bindArr=array( ":expenses_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$strQuery=" delete from bud_expenses where expenses_id=:expenses_id "; 
			$bindArr=array( ":expenses_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$strQuery=" delete from bud_expense_local_conveyances where expenses_id=:expenses_id "; 
			$bindArr=array( ":expenses_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$strQuery=" delete from bud_expense_attachments where expenses_id=:expenses_id "; 
			$bindArr=array( ":expenses_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Expense details deleted successfully'; 
			} 
			
			$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
			
			return json_encode($sendArr);
			
	}		
	
	public function getLCDDetails($postArr)
	{
			$expenses_id=$this->purifyInsertString($postArr["expenses_id"]);
			$expense_det_id=$this->purifyInsertString($postArr["expense_det_id"]);
			
			$sql="select expenses_id,local_conv_date,place_of_origin, place_of_destination, local_conv_distance,fare_per_km, local_conv_line_total,food_expenses,local_conv_fare_total,expense_det_id,local_conv_id from bud_expense_local_conveyances where expenses_id=:expenses_id and expense_det_id=:expense_det_id";
			$bindArr=array(":expenses_id"=>array("value"=>$expenses_id,"type"=>"int"),":expense_det_id"=>array("value"=>$expense_det_id,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			$expense_details = array();
				
			foreach($recs as $key=>$rs)
			{
				$expense_details[$key]=array('local_conv_id'=>$rs['local_conv_id'],'local_conv_date'=>$this->convertDate($rs['local_conv_date']), 'expenses_id'=>$rs['expenses_id'], 'expense_det_id'=>$rs['expense_det_id'], 'place_of_origin'=>$rs['place_of_origin'], 'place_of_destination'=>$rs['place_of_destination'], 'local_conv_distance'=>$rs['local_conv_distance'], 'fare_per_km'=>$rs['fare_per_km'],'local_conv_line_total'=>$rs['local_conv_line_total'],'food_expenses'=>$rs['food_expenses'],'local_conv_fare_total'=>$rs['local_conv_fare_total']);
			}
			
			return $expense_details;

		
	}
	
	public function uploadprocess($postArr)
	{
		//print_r($_REQUEST);
		//print_r($_FILES);
		
		$name = $_FILES['upload_file']['name'];
		$size = $_FILES['upload_file']['size'];
		$tmp = $_FILES['upload_file']['tmp_name'];
		$hdn_exp_file_rw_id = $_POST['hdn_exp_file_rw_id'];
		$hdn_exp_file_upload_folder = ($_POST['hdn_exp_file_upload_folder'])?$_POST['hdn_exp_file_upload_folder']:'tempdel_111223344';
		$path = 'public/data/temp/'.$hdn_exp_file_upload_folder.'/'.$hdn_exp_file_rw_id.'/';
		$this->makedirectory($path);
		
		$files = scandir($path); 
		$files = array_diff(scandir($path), array('.', '..'));
		
		$sendFilesArr=array();
		foreach($files as $f)
		{
			unlink($path.$f);
		} 
		
		if(move_uploaded_file($tmp, $path.$name))
		{
			$opMessage = 'File uploaded successfully';
			$opStatus = 'success';
		}
		else
		{
			$opMessage = 'Error in file upload';
			$opStatus = 'failure';
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
			
		return json_encode($sendArr);
	}
	public function getuploadedfile($postArr)
	{ 
		$hdn_exp_file_rw_id = $postArr['hdn_exp_file_rw_id'];
		$hdn_exp_file_upload_folder = ($postArr['hdn_exp_file_upload_folder'])?$postArr['hdn_exp_file_upload_folder']:'tempdel_111223344'; 
		$hidelist=($postArr['hidelist'])?$postArr['hidelist']:array();
		
		$path = 'public/data/temp/'.$hdn_exp_file_upload_folder.'/'.$hdn_exp_file_rw_id.'/'; 
		$files = scandir($path); 
		$files = array_diff(scandir($path), array('.', '..'));
		
		$sendFilesArr=array();
		foreach($files as $f)
		{
			if(!in_array($path.$f, $hidelist))
			$sendFilesArr[]=array('filename'=>$f,'fileloc'=>$path);
		} 
		
		if($postArr['expense_id'])
		{
			if($postArr['expense_det_id'])
			{
				$path = 'public/data/expenses/'.$postArr['expense_id'].'/'.$postArr['expense_det_id'].'/'; 
				$files = scandir($path); 
				$files = array_diff(scandir($path), array('.', '..'));
				
				foreach($files as $f)
				{
					if(!in_array($path.$f, $hidelist))
					$sendFilesArr[]=array('filename'=>$f,'fileloc'=>$path);
				} 
			}
		}
		
		$sendArr=array('rsData'=>$sendFilesArr,'status'=>'success');   
			
		return json_encode($sendArr);
	}
	
	public function importprocess()
	{
		$name = $_FILES['import_file']['name'];
		$size = $_FILES['import_file']['size'];
		$tmp = $_FILES['import_file']['tmp_name'];
		
		$user_id = $this->sess_userid;
		$time = strtotime(date('d-m-Y H:i:s'));
		$path = 'public/data/temp/users/'.$user_id.'/import/'.$time.'/';
		$this->makedirectory($path);
		
		if(move_uploaded_file($tmp, $path.$name))
		{
			$opMessage = 'File uploaded successfully';
			$opStatus = 'success';
			$file_path = $path.$name;
		}
		else
		{
			$opMessage = 'Error in file upload';
			$opStatus = 'failure';
			$file_path = '';
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus, 'file_path'=>$file_path);  
			
		return json_encode($sendArr);
	}
	
	public function removeTempFiles($postArr)
	{
		$hid_fileimport = $postArr['hid_fileimport'];
		if(file_exists($hid_fileimport))
		{
			unlink($hid_fileimport);
		}
		
		$hid_fileup_temp_usr_fold = $postArr['hid_fileup_temp_usr_fold'];		
		$temp_dir = 'public/data/temp/'.$hid_fileup_temp_usr_fold.'/';		
		if(is_dir($temp_dir))
		{
			$this->deleteDirectory($temp_dir);
		}
		
		$hdn_exp_file_upload_folder = $postArr['hdn_exp_file_upload_folder'];		
		$temp_dir = 'public/data/temp/'.$hdn_exp_file_upload_folder.'/'; 
		if(is_dir($temp_dir))
		{
			$this->deleteDirectory($temp_dir);
		}
		
		
		$sendArr=array('message'=>'','status'=>'success');  
		return json_encode($sendArr);
	}
	
	public function getExpenseReportData($postArr)
	{
		$bindExpnsArr=array(); 
 
		$cmb_employee=$this->purifyInsertString($postArr["cmb_employee"]);
		$cmb_category=$this->purifyInsertString($postArr["cmb_category"]);
		$cmb_subcategory=$this->purifyInsertString($postArr["cmb_subcategory"]);
		$txt_expense_fromdate=$this->purifyInsertString($postArr["txt_expense_fromdate"]);
		$txt_expense_todate=$this->purifyInsertString($postArr["txt_expense_todate"]);
		
		$extraFilt="";
		if($cmb_employee)
		{
			$extraFilt.=" and eh.createdby=:createdby ";
			$bindExpnsArr[":createdby"]=array("value"=>$cmb_employee,"type"=>"int");	
		}
		else
		{
			$user_previlage = $this->getUserPervilageSettings();			
			if(!$user_previlage['view_to_others'])
			{
				$extraFilt.=" and eh.createdby=:createdby ";
				$bindExpnsArr[":createdby"]=array("value"=>$this->sess_userid,"type"=>"int");					
			}
		}
		
		if($cmb_category)
		{
			$extraFilt.=" and ed.category_id=:category_id ";
			$bindExpnsArr[":category_id"]=array("value"=>$cmb_category,"type"=>"int");	
		}
		if($cmb_subcategory)
		{
			$extraFilt.=" and ed.subcategory_id=:subcategory_id ";
			$bindExpnsArr[":subcategory_id"]=array("value"=>$cmb_subcategory,"type"=>"int");	
		}
		if($txt_expense_fromdate and $txt_expense_todate)
		{
			 
			$extraFilt.=" and ed.expense_det_date between :e_startdate and :e_enddate ";
			$bindExpnsArr[":e_startdate"]=array("value"=>$this->convertDate($txt_expense_fromdate),"type"=>"text");	
			$bindExpnsArr[":e_enddate"]=array("value"=>$this->convertDate($txt_expense_todate),"type"=>"text");	
		} 
		
			
		
		$sqlExpns="select um.user_display_name as emp_name, ed.expense_det_date, cat.category_name, scat.subcategory_name, sum(ed.expense_det_amount) as exp_sum_amount,  ed.category_id, ed.subcategory_id, um.user_id  from bud_expenses as eh inner join bud_expense_details as ed on ed.expenses_id=eh.expenses_id inner join bud_user_master as um on eh.createdby=um.user_id left join bud_subcategory_master as scat on scat.subcategory_id=ed.subcategory_id left join  bud_category_master as cat on cat.category_id=scat.category_id  where  eh.expenses_status=1 $extraFilt group by eh.createdby, ed.expense_det_date, ed.category_id, ed.subcategory_id order by emp_name, ed.expense_det_date, cat.category_name, scat.subcategory_name";  
		$recexpns = $this->pdoObj->fetchMultiple($sqlExpns, $bindExpnsArr);   
		
		 
		 
		$expDisp = array();
		$tmpName = "";
		foreach($recexpns as $rs_expn)
		{   
			$m_cat_name=$this->purifyString($rs_expn['category_name']);
			$m_subcat_name=$this->purifyString($rs_expn['subcategory_name']);
			$m_emp_name=$this->purifyString($rs_expn['emp_name']); 
			$m_exp_date=$this->convertDate($rs_expn['expense_det_date']);
			$m_exp_amount=$rs_expn['exp_sum_amount'];
			
			if($tmpName!=$m_emp_name)
			{
				$tmpName=$m_emp_name;
			}
			$expDisp[$m_emp_name]["data"][]=array('empname'=>$m_emp_name, 'exp_date'=>$m_exp_date, 'cat_name'=>$m_cat_name, 'subcat_name'=>$m_subcat_name, 'exp_amount'=>$m_exp_amount);
			$expDisp[$m_emp_name]["total"]+=$m_exp_amount; 
		}  
		
		return array('expDisp'=>$expDisp);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>