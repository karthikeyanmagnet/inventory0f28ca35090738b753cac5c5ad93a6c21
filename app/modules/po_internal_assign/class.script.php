<?php	

class po_internal_assign extends rapper
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
		  $po_assign_head = ($postArr['po_assign_head'])?$postArr['po_assign_head']:0;
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		  $bindArr=array();
		  $search = ($postArr['search']['value']);	  
		  $where = " where po.po_assign_head_id=$po_assign_head";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where.=' and po_number like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_po_internal_assign_head po $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		
		  $sql="select po.internal_po_number,po.po_grant_total, date_format(po.createdon, '%d %b %Y') as created_dt, po.po_internal_assign_head_id,  po.received_date, cust.supplier_name, po.assigned_date, 
case po.po_internal_assign_status when 1 then 'Created' when 2 then 'Sent' when 3 then 'Completed' when 4 then 'Cancelled' end as status  from bud_po_internal_assign_head po left join bud_vendor_master as cust on po.vendor_id=cust.vendor_id  $where order by po.createdon "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["po_internal_assign_head_id"]);
				$po_number=$this->purifyString($rs["internal_po_number"]);
				$received_date=$this->convertDate($this->purifyString($rs["received_date"]));  
				$supplier_name=$this->purifyString($rs["supplier_name"]);
				$order_total=$this->purifyString($rs["order_total"]); 
				
				$cstatus=$this->purifyString($rs["active_status"]);
				$status=$this->purifyString($rs["status"]);				
				$po_grant_total=$this->purifyString($rs["po_grant_total"]);
				$created_dt=$this->purifyString($rs["created_dt"]);
		 
				$sendRs[$rsCnt]=array($po_number, $created_dt, $supplier_name, $po_grant_total, $received_date, $status, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdatePOInternalAssignMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeletePOInternalAssignMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
				$rsCnt++;
				$PageSno++;
				
			}
			
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	public function getSingleView($postArr)
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		
		$sql="select po_internal_assign_head_id, vendor_id,  received_date, assigned_date, po_internal_assign_terms,  po_internal_assign_remarks, internal_po_number, po_number, po_internal_assign_status, po_internal_assign_type, po_internal_assign_ext, po_total, po_grant_total  from bud_po_internal_assign_head where po_internal_assign_head_id=:po_internal_assign_head_id";
		$bindArr=array(":po_internal_assign_head_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["po_internal_assign_head_id"]);
		$vendor_id=$this->purifyString($recs["vendor_id"]);
		$received_date=(($recs["received_date"]));  
		$assigned_date=($recs["assigned_date"]);
		$po_internal_assign_terms=$this->purifyString($recs["po_internal_assign_terms"]);
		$po_internal_assign_remarks=$this->purifyString($recs["po_internal_assign_remarks"]);
		
		$internal_po_number=$this->purifyString($recs["internal_po_number"]);
		$po_number=$this->purifyString($recs["po_number"]);
		$po_internal_assign_status=$this->purifyString($recs["po_internal_assign_status"]);
		$po_internal_assign_type=$this->purifyString($recs["po_internal_assign_type"]);
		$po_internal_assign_ext=$this->purifyString($recs["po_internal_assign_ext"]);
		$po_total=$this->purifyString($recs["po_total"]);
		$po_grant_total=$this->purifyString($recs["po_grant_total"]);
		
		$dir = 'public/data/po_internal/';
		$file = $dir.$cid.'.'.$po_internal_assign_ext;
		$logoUrl = '';
		 
		$itemlist = $this->getModuleComboList('item'); 
		$customerlist = $this->getModuleComboList('vendor'); 
		
		if(!$cid)
		{
			$itmgrpdet_details = array();
		}	
		else
		{
			$sql="select po_internal_assign_det_id, po_internal_assign_head_id, item_id, item_desc, req_qty, item_tax, po_qty, unit_cost, line_total  from bud_po_internal_assign_details where po_internal_assign_head_id=:po_internal_assign_head_id";
			$bindArr=array(":po_internal_assign_head_id"=>array("value"=>$cid,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			$itmgrpdet_details = array(); 
			
			foreach($recs as $key=>$rs)
			{ 
				$itmgrpdet_details[$key]=array('po_internal_assign_det_id'=>$rs['po_internal_assign_det_id'], 'po_internal_assign_head_id'=>$rs['po_internal_assign_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'req_qty'=>$this->purifyString($rs['req_qty']), 'item_tax'=>$this->purifyString($rs['item_tax']), 'po_qty'=>$this->purifyString($rs['po_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total']); 
				
			}
			
		}
		
		if($cid)
		{
			if(file_exists($file))
			{
				$logoUrl = $file.'?'.uniqid();
			}	
		}
		
		$sendRs=array("po_internal_assign_head_id"=>$cid, "vendor_id"=>$vendor_id, "received_date"=>$received_date, "assigned_date"=>$assigned_date,  "po_internal_assign_terms"=>$po_internal_assign_terms, "po_internal_assign_remarks"=>$po_internal_assign_remarks,"customerlist"=>$customerlist,"itemlist"=>$itemlist,"itmgrpdet_details"=>$itmgrpdet_details,"po_total"=>$po_total, "po_grant_total"=>$po_grant_total, "internal_po_number"=>$internal_po_number, "po_number"=>$po_number, "po_internal_assign_status"=>$po_internal_assign_status, "po_internal_assign_type"=>$po_internal_assign_type, "po_internal_assign_ext"=>$po_internal_assign_ext, "po_internal_file"=>$logoUrl); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$vendor_id=$this->purifyInsertString($postArr["vendor_id"]);
		$received_date=(($postArr["received_date"])); 
		$assigned_date=($postArr["assigned_date"]);
		$po_internal_assign_terms=$this->purifyInsertString($postArr["po_internal_assign_terms"]);
		$po_internal_assign_remarks=$this->purifyInsertString($postArr["po_internal_assign_remarks"]);
		$internal_po_number=$this->purifyInsertString($postArr["internal_po_number"]);
		$po_number=$this->purifyInsertString($postArr["po_number"]);
		$po_internal_assign_status=$this->purifyInsertString($postArr["po_internal_assign_status"]);
		$po_internal_assign_type=$this->purifyInsertString($postArr["po_internal_assign_type"]);
		$po_internal_assign_ext=$this->purifyInsertString($postArr["po_internal_assign_ext"]);
		$po_total=$this->purifyInsertString($postArr["po_total"]);
		$po_grant_total=$this->purifyInsertString($postArr["po_grant_total"]);
		$hdn_po_assign_head_id = $this->purifyInsertString($postArr['hdn_po_assign_head_id']);
		
		
		
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);    
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_po_internal_assign_head where po_internal_assign_head_id=:po_internal_assign_head_id "; 
		$bindExtCntArr=array(":po_internal_assign_head_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_po_internal_assign_head SET vendor_id=:vendor_id, received_date=:received_date, assigned_date=:assigned_date, po_internal_assign_terms=:po_internal_assign_terms, po_internal_assign_remarks=:po_internal_assign_remarks, po_number=:po_number, internal_po_number=:internal_po_number, po_internal_assign_status=:po_internal_assign_status, po_internal_assign_type=:po_internal_assign_type, po_internal_assign_ext=:po_internal_assign_ext,po_total=:po_total, po_grant_total=:po_grant_total,po_assign_head_id=:po_assign_head_id";
		
		$insBind=array(":vendor_id"=>array("value"=>$vendor_id,"type"=>"int"), ":received_date"=>array("value"=>$received_date,"type"=>"text"), ":assigned_date"=>array("value"=>$assigned_date,"type"=>"text"), ":po_internal_assign_remarks"=>array("value"=>$po_internal_assign_remarks,"type"=>"text"),":po_internal_assign_terms"=>array("value"=>$po_internal_assign_terms,"type"=>"text"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":internal_po_number"=>array("value"=>$internal_po_number,"type"=>"text"), ":po_number"=>array("value"=>$po_number,"type"=>"text"), ":po_internal_assign_status"=>array("value"=>$po_internal_assign_status,"type"=>"text"), ":po_internal_assign_type"=>array("value"=>$po_internal_assign_type,"type"=>"text"), ":po_internal_assign_ext"=>array("value"=>$po_internal_assign_ext,"type"=>"text"), ":po_total"=>array("value"=>$po_total,"type"=>"text"), ":po_grant_total"=>array("value"=>$po_grant_total,"type"=>"text"), ':po_assign_head_id'=>array("value"=>$hdn_po_assign_head_id,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_po_internal_assign_head where trim(vendor_id)=:vendor_id ";
		$bindExtChkArr=array(":vendor_id"=>array("value"=>$vendor_id,"dtype"=>"text")); 
		
		$insuptyp="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where po_internal_assign_head_id=:po_internal_assign_head_id ";
			$insBind[":po_internal_assign_head_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and po_internal_assign_head_id<>:po_internal_assign_head_id ";
			$bindExtChkArr[":po_internal_assign_head_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="PO details updated successfully!";
		}
		else
		{
			$insuptyp="insert";
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="PO details inserted successfully!";
		}
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		//$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Record already exists'; 
			$opExists='exists';
		} 
		else 
		{
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			if($exec)
			{
				$opStatus='success';
				$opMessage=$opmsg; 
				
				if($insuptyp=="insert")
				{
					$maxid_sql="select max(po_internal_assign_head_id) as maxidval from bud_po_internal_assign_head "; 
					$bindmaxidArr=array();
					$rs_qry_maxid = $this->pdoObj->fetchSingle($maxid_sql, $bindmaxidArr); 
					$id=$rs_qry_maxid["maxidval"];
				}
				
				
				//-============= Details insert/update start
				
				
				 
			 
			$hdn_po_detid = $postArr['hdn_po_detid']; 
			$arr_item_id = $postArr['cmb_item'];
			$arr_item_desc = $postArr['item_desc'];
			$arr_req_qty = $postArr['req_qty'];
			$arr_item_tax = $postArr['item_tax'];
			$arr_po_qty = $postArr['po_qty'];
			$arr_qty_avaliable = $postArr['qty_avaliable'];
			$arr_unit_cost = $postArr['unit_cost'];
			$arr_line_total = $postArr['line_total'];
			$arr_chk_po_internal_assign = $postArr['chk_po_internal_assign'];
			
			//print_r($arr_item_id);
			
			foreach($arr_item_id as $cat_key=>$cat_val)
			{  
				$po_internal_assign_det_id = $this->purifyInsertString($hdn_po_detid[$cat_key]); 
				$item_id = $this->purifyInsertString($arr_item_id[$cat_key]);
				$item_desc = $this->purifyInsertString($arr_item_desc[$cat_key]);
				$req_qty = $this->purifyInsertString($arr_req_qty[$cat_key]);
				$item_tax = $this->purifyInsertString($arr_item_tax[$cat_key]);
				$po_qty = $this->purifyInsertString($arr_po_qty[$cat_key]);
				$qty_avaliable = $this->purifyInsertString($arr_qty_avaliable[$cat_key]);
				$unit_cost = $this->purifyInsertString($arr_unit_cost[$cat_key]);
				$line_total = $this->purifyInsertString($arr_line_total[$cat_key]);
				$chk_internal = $this->purifyInsertString($arr_chk_po_internal_assign[$cat_key]);
				 
				
				if($arr_item_id and $po_qty and $chk_internal)
				{   
					
					$ins=" bud_po_internal_assign_details SET item_id=:item_id, item_desc=:item_desc, req_qty=:req_qty, item_tax=:item_tax, po_qty=:po_qty, qty_avaliable=:qty_avaliable, unit_cost=:unit_cost, line_total=:line_total ";
					$insBind=array(":item_id"=>array("value"=>$item_id,"type"=>"int"), ":item_desc"=>array("value"=>$item_desc,"type"=>"text"), ":req_qty"=>array("value"=>$req_qty,"type"=>"text"), ":item_tax"=>array("value"=>$item_tax,"type"=>"text"), ":po_qty"=>array("value"=>$po_qty,"type"=>"text"), ":unit_cost"=>array("value"=>$unit_cost,"type"=>"text"), ":line_total"=>array("value"=>$line_total,"type"=>"text"),":qty_avaliable"=>array("value"=>$qty_avaliable,"type"=>"text")); 
					$mod = '';
					if($po_internal_assign_det_id>0) 
					{ 
						$strQuery="UPDATE $ins where po_internal_assign_det_id=:po_internal_assign_det_id";
						$insBind[":po_internal_assign_det_id"]=array("value"=>$po_internal_assign_det_id,"type"=>"int"); 
						$mod = 'up';
					}
					else
					{ 
						 
						$strQuery="INSERT INTO $ins, po_internal_assign_head_id=:po_internal_assign_head_id "; 						
						$insBind[":po_internal_assign_head_id"]=array("value"=>$id,"type"=>"int");  
						$mod = 'ins';  
					}
					 
					$sbexec = $this->pdoObj->execute($strQuery, $insBind);  
					 
				}	
			}	
			
			if($hid_temp_del != '')
			{
				$temp_arr = explode(',', $hid_temp_del);
				foreach($temp_arr as $del_id)
				{
					if($del_id)
					{  
						$strQuery=" delete from bud_po_internal_assign_details where po_internal_assign_det_id=:po_internal_assign_det_id  ";  						
						$bindArr=array( ":po_internal_assign_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
						$exec = $this->pdoObj->execute($strQuery, $bindArr);  
					}
				}
			}
			
			
			if(isset($_FILES['file_internal_po']))
			{
				$dir = 'public/data/po_internal/';
				$this->makedirectory($dir);
				
				$file = $_FILES['file_internal_po'];
				$name = $_FILES['file_internal_po']['name'];
				
				if($name)
				{
					$po_internal_assign_ext = pathinfo($name,PATHINFO_EXTENSION);
					$new_file  =$dir.$id.'.'.$po_internal_assign_ext;
					if(file_exists($new_file)){ unlink($new_file);}
					if(move_uploaded_file($_FILES['file_internal_po']['tmp_name'], $dir.$id.'.'.$po_internal_assign_ext))
					{
						$update = "UPDATE bud_po_internal_assign_head SET po_internal_assign_ext=:po_internal_assign_ext where po_internal_assign_head_id=:po_internal_assign_head_id ";
						$insUP[":po_internal_assign_head_id"]=array("value"=>$id,"type"=>"int"); 
						$insUP[":po_internal_assign_ext"]=array("value"=>$po_internal_assign_ext,"type"=>"text"); 
						$exec = $this->pdoObj->execute($update, $insUP);
					}
				}
			}
				
				
				//============== Details save end
				
			} // head table success
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$bindArr=array(); 
		
		$strQuery=" delete from bud_po_internal_assign_head where po_internal_assign_head_id=:po_internal_assign_head_id "; 
		$bindArr=array( ":po_internal_assign_head_id"=>array("value"=>$id,"dtype"=>"int"));  
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='PO details deleted successfully'; 
			
			$strSbQuery=" delete from bud_po_internal_assign_details where po_internal_assign_head_id=:po_internal_assign_head_id "; 
			$bindSbArr=array( ":po_internal_assign_head_id"=>array("value"=>$id,"dtype"=>"int"));  
			$execsb = $this->pdoObj->execute($strSbQuery, $bindSbArr);
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);
		
	}		
	
	public function comboview($id=0)
	{
		  $bindArr=array();
		  $whereor = '';
		  if($id>0)
		  {
		  	$bindArr=array( ":po_internal_assign_head_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or po_internal_assign_head_id=:po_internal_assign_head_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select po_internal_assign_head_id, po_number ,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_po_internal_assign_head where active_status = 1 $whereor order by po_internal_assign_head_id asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		//$cnt_ext_sql="(select count(*) as ext_cnt, 'Item Group referring Sub Item Group' as msg from bud_subcategory_master where category_id=:id) union all (select count(*) as ext_cnt, 'Item Group linked to Expenses' as msg from bud_expense_details where category_id=:id) "; 
		//$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		//$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); 
		
		$_cnt = 0;
		$status = 'success';
		$msgArr = array();
		$_msg = 'Do you want to delete?';
		foreach($rs_qry_exts as $rsop)
		{
			if($rsop['ext_cnt']>0)
			{
				$msgArr[] = $rsop['msg'];
				$_cnt++;
			}
		}
		
		if($_cnt>0)
		{
			$status = 'failure';
			//$_msg = implode("\n",$msgArr);
			$_msg = "PO cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>