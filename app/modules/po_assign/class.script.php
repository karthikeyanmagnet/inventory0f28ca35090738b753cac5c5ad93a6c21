<?php	

class po_assign extends rapper
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
		  $gv_po_head = $postArr['gv_po_head'];
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  $bindArr=array();
		  $where = "";
		  if($gv_po_head>0)
		  {
		  	$bindArr[':po_head_id']=array("value"=>$gv_po_head,"type"=>"int");
			$where.= " and po_head_id=:po_head_id";
			$opt = 0;
		  }	
		  else
		  {
		  	$opt = 1;
		  }
		  $search = ($postArr['search']['value']);	  
		  
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where.= ' and po_number like :search_str';
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  
		  
		  
		  $tot_sql="select count(*) as cnt from bud_po_assign_head where 1 $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select po.po_assign_head_id,  po.delivery_date_before, cust.supplier_name, po.internal_po_number, date_format(po.createdon, '%d %b %Y') as created_date, po.po_grant_total from bud_po_assign_head po left join bud_vendor_master as cust on po.vendor_id=cust.vendor_id where 1 $where order by po.createdon "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["po_assign_head_id"]);
				$internal_po_number=$this->purifyString($rs["internal_po_number"]);
				$created_date=($this->purifyString($rs["created_date"]));  
				$supplier_name=$this->purifyString($rs["supplier_name"]);
				$po_grant_total=$this->purifyString($rs["po_grant_total"]); 
				
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);				
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
		 
				$sendRs[$rsCnt]=array($internal_po_number, $created_date, $supplier_name, $po_grant_total, '<span class="edit act-edit" data-modal-id="popup1" onclick="EditPOAssignMasterList('.$cid.','.$opt.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeletePOAssignMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span> <span class="print act-print" style="cursor:pointer" onclick="printPOAssignMaster('.$cid.');"><i class="fa fa-file-pdf-o"></i> Print</span>');
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
		
		$sql="select po_assign_head_id, vendor_id,  delivery_date_before, delivery_date_after, po_assign_terms,  po_assign_remarks,po_grant_total,po_total,internal_po_number, active_status from bud_po_assign_head where po_assign_head_id=:po_assign_head_id";
		$bindArr=array(":po_assign_head_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["po_assign_head_id"]);
		$vendor_id=$this->purifyString($recs["vendor_id"]);
		$delivery_date_before=(($recs["delivery_date_before"]));  
		$delivery_date_after=($recs["delivery_date_after"]);
		$po_assign_terms=$this->purifyString($recs["po_assign_terms"]);
		$po_assign_remarks=$this->purifyString($recs["po_assign_remarks"]);
		$po_grant_total=($recs["po_grant_total"]);
		$po_total=$this->purifyString($recs["po_total"]);
		$internal_po_number=$this->purifyString($recs["internal_po_number"]);
		$active_status=$this->purifyString($recs["active_status"]);
		$gv_po_head = $postArr['gv_po_head'];
		 
		$itemlist = $this->getModuleComboList('item'); 
		$customerlist = $this->getModuleComboList('vendor'); 
		
		if(!$cid)
		{
			$itmgrpdet_details = array();
			$sql="select po_det_id, po_head_id, item_id, item_desc, whse_line, weight_line, ordered_qty, unit_cost, line_total  from bud_po_details where po_head_id=:po_head_id";
			$bindArr=array(":po_head_id"=>array("value"=>$gv_po_head,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			$itmgrpdet_details = array(); 
			
			foreach($recs as $key=>$rs)
			{ 
				//$itmgrpdet_details[$key]=array('po_det_id'=>$rs['po_det_id'], 'po_head_id'=>$rs['po_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'whse_line'=>$this->purifyString($rs['whse_line']), 'weight_line'=>$this->purifyString($rs['weight_line']), 'ordered_qty'=>$this->purifyString($rs['ordered_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total']); 
				
				$arr = array('item_id'=>$rs['item_id']);
				$avability = $this->getItemQuantity($arr,1);
				
				$itmgrpdet_details[$key]=array('po_assign_det_id'=>0, 'po_assign_head_id'=>0, 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'req_qty'=>$this->purifyString($rs['req_qty']), 'item_tax'=>$this->purifyString($rs['item_tax']), 'po_qty'=>$this->purifyString($rs['ordered_qty']),'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>0, 'qty_avaliable'=>$avability);
				
				
			}

		}	
		else
		{
			$sql="select po_assign_det_id, po_assign_head_id, item_id, item_desc, req_qty, item_tax, po_qty, unit_cost, line_total,qty_avaliable  from bud_po_assign_details where po_assign_head_id=:po_assign_head_id";
			$bindArr=array(":po_assign_head_id"=>array("value"=>$cid,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			$itmgrpdet_details = array(); 
			
			foreach($recs as $key=>$rs)
			{ 
				
				$arr = array('item_id'=>$rs['item_id']);
				$avability = $this->getItemQuantity($arr,1);
				$itmgrpdet_details[$key]=array('po_assign_det_id'=>$rs['po_assign_det_id'], 'po_assign_head_id'=>$rs['po_assign_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'req_qty'=>$this->purifyString($rs['req_qty']), 'item_tax'=>$this->purifyString($rs['item_tax']), 'po_qty'=>$this->purifyString($rs['po_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total'], 'qty_avaliable'=>$avability); 
				
			}
			
		}
		
		$sendRs=array("po_assign_head_id"=>$cid, "vendor_id"=>$vendor_id, "delivery_date_before"=>$delivery_date_before, "delivery_date_after"=>$delivery_date_after,  "po_assign_terms"=>$po_assign_terms, "po_assign_remarks"=>$po_assign_remarks,"po_total"=>$po_total, "po_grant_total"=>$po_grant_total,"internal_po_number"=>$internal_po_number,"customerlist"=>$customerlist,"itemlist"=>$itemlist,"itmgrpdet_details"=>$itmgrpdet_details, "active_status"=>$active_status); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$vendor_id=$this->purifyInsertString($postArr["vendor_id"]);
		$delivery_date_before=(($postArr["delivery_date_before"])); 
		$delivery_date_after=($postArr["delivery_date_after"]);
		$po_assign_terms=$this->purifyInsertString($postArr["po_assign_terms"]);
		$po_assign_remarks=$this->purifyInsertString($postArr["po_assign_remarks"]);
		$po_assign_po_head_id = $this->purifyInsertString($postArr["hid_po_orderid"]);
		$internal_po_number = $this->purifyInsertString($postArr['internal_po_number']);
		$po_total=$this->purifyInsertString($postArr["po_total"]);
		$po_grant_total=$this->purifyInsertString($postArr["po_grant_total"]);
		$active_status=$this->purifyInsertString($postArr["active_status"]); 
		
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);    
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_po_assign_head where po_assign_head_id=:po_assign_head_id "; 
		$bindExtCntArr=array(":po_assign_head_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_po_assign_head SET vendor_id=:vendor_id, delivery_date_before=:delivery_date_before, delivery_date_after=:delivery_date_after, po_assign_terms=:po_assign_terms, po_assign_remarks=:po_assign_remarks, po_head_id=:po_head_id, internal_po_number=:internal_po_number,po_total=:po_total, po_grant_total=:po_grant_total, active_status=:active_status ";
		
		$insBind=array(":vendor_id"=>array("value"=>$vendor_id,"type"=>"int"), ":delivery_date_before"=>array("value"=>$delivery_date_before,"type"=>"text"), ":delivery_date_after"=>array("value"=>$delivery_date_after,"type"=>"text"), ":po_assign_remarks"=>array("value"=>$po_assign_remarks,"type"=>"text"),":po_assign_terms"=>array("value"=>$po_assign_terms,"type"=>"text"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"),":po_head_id"=>array("value"=>$po_assign_po_head_id,"type"=>"int"),":internal_po_number"=>array("value"=>$internal_po_number,"type"=>"text"), ":po_total"=>array("value"=>$po_total,"type"=>"text"), ":po_grant_total"=>array("value"=>$po_grant_total,"type"=>"text"), ":active_status"=>array("value"=>$active_status,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_po_assign_head where trim(vendor_id)=:vendor_id ";
		$bindExtChkArr=array(":vendor_id"=>array("value"=>$vendor_id,"dtype"=>"text")); 
		
		$insuptyp="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where po_assign_head_id=:po_assign_head_id ";
			$insBind[":po_assign_head_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and po_assign_head_id<>:po_assign_head_id ";
			$bindExtChkArr[":po_assign_head_id"]=array("value"=>$id,"dtype"=>"int");  
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
					$maxid_sql="select max(po_assign_head_id) as maxidval from bud_po_assign_head "; 
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
			//$arr_chk_po_assign = $postArr['chk_po_assign'];
			//echo $insuptyp;
		    //print_r($arr_chk_po_assign);
			
			foreach($arr_item_id as $cat_key=>$cat_val)
			{  
				$po_assign_det_id = $this->purifyInsertString($hdn_po_detid[$cat_key]); 
				$item_id = $this->purifyInsertString($arr_item_id[$cat_key]);
				$item_desc = $this->purifyInsertString($arr_item_desc[$cat_key]);
				$req_qty = $this->purifyInsertString($arr_req_qty[$cat_key]);
				$item_tax = $this->purifyInsertString($arr_item_tax[$cat_key]);
				$po_qty = $this->purifyInsertString($arr_po_qty[$cat_key]);
				$qty_avaliable = $this->purifyInsertString($arr_qty_avaliable[$cat_key]);
				$unit_cost = $this->purifyInsertString($arr_unit_cost[$cat_key]);
				$line_total = $this->purifyInsertString($arr_line_total[$cat_key]);
				$chk_internal = $this->purifyInsertString($postArr['chk_po_assign_'.$cat_key]);
				
				if($arr_item_id and $po_qty and $chk_internal)
				{   
					
					$ins=" bud_po_assign_details SET item_id=:item_id, item_desc=:item_desc, req_qty=:req_qty, item_tax=:item_tax, po_qty=:po_qty, qty_avaliable=:qty_avaliable, unit_cost=:unit_cost, line_total=:line_total ";
					$insBind=array(":item_id"=>array("value"=>$item_id,"type"=>"int"), ":item_desc"=>array("value"=>$item_desc,"type"=>"text"), ":req_qty"=>array("value"=>$req_qty,"type"=>"text"), ":item_tax"=>array("value"=>$item_tax,"type"=>"text"), ":po_qty"=>array("value"=>$po_qty,"type"=>"text"), ":unit_cost"=>array("value"=>$unit_cost,"type"=>"text"), ":line_total"=>array("value"=>$line_total,"type"=>"text"),":qty_avaliable"=>array("value"=>$qty_avaliable,"type"=>"text")); 
					$mod = '';
					if($po_assign_det_id>0) 
					{ 
						$strQuery="UPDATE $ins where po_assign_det_id=:po_assign_det_id";
						$insBind[":po_assign_det_id"]=array("value"=>$po_assign_det_id,"type"=>"int"); 
						$mod = 'up';
					}
					else
					{ 
						 
						$strQuery="INSERT INTO $ins, po_assign_head_id=:po_assign_head_id "; 						
						$insBind[":po_assign_head_id"]=array("value"=>$id,"type"=>"int");  
						$mod = 'ins';  
					}
					// echo $strQuery.json_encode($insBind);
					// echo '<br>';
					$sbexec = $this->pdoObj->execute($strQuery, $insBind);  
					 
				}
				else
				{
					
				}	
			}	
			
			if($hid_temp_del != '')
			{
				$temp_arr = explode(',', $hid_temp_del);
				foreach($temp_arr as $del_id)
				{
					if($del_id)
					{  
						$strQuery=" delete from bud_po_assign_details where po_assign_det_id=:po_assign_det_id  ";  						
						$bindArr=array( ":po_assign_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
						$exec = $this->pdoObj->execute($strQuery, $bindArr);  
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
		
		$strQuery=" delete from bud_po_assign_head where po_assign_head_id=:po_assign_head_id "; 
		$bindArr=array( ":po_assign_head_id"=>array("value"=>$id,"dtype"=>"int"));  
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='PO details deleted successfully'; 
			
			$strSbQuery=" delete from bud_po_assign_details where po_assign_head_id=:po_assign_head_id "; 
			$bindSbArr=array( ":po_assign_head_id"=>array("value"=>$id,"dtype"=>"int"));  
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
		  	$bindArr=array( ":po_assign_head_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or po_assign_head_id=:po_assign_head_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select po_assign_head_id, po_number ,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_po_assign_head where active_status = 1 $whereor order by po_assign_head_id asc ";
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
	
	public function getPOAssignData($postArr)
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		$sql = "select  date(hd.createdon) as po_created , hd.internal_po_number, po.po_number, date(po.createdon) as po_createddate, hd.delivery_date_before, hd.delivery_date_after, vend.supplier_name, vend.supplier_address, vend.supplier_city, vend.supplier_state, vend.supplier_zipcode, vend.supplier_tin_cst, vend.supplier_excise_no, vend.supplier_contact_name, vend.supplier_contact_no, cust.company_name, cust.address_bill_addr, cust.city_bill_addr, cust.state_bill_addr, cust.zipcode_bill_addr, cust.country_bill_addr, cust.primary_contact as cust_primary_contact, cust.mobile_no as cut_mobile, po.issuer as po_requisitioner, hd.po_assign_terms,hd.po_total, hd.po_grant_total   from bud_po_assign_head as hd left join bud_po_head as po on hd.po_head_id=po.po_head_id left join bud_vendor_master as vend on hd.vendor_id=vend.vendor_id left join bud_customer_master as cust on po.customer_id=cust.customer_id where hd.po_assign_head_id=:po_assign_head_id";
		$bindArr=array(":po_assign_head_id"=>array("value"=>$getcid,"type"=>"int"));
		$sendRs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		
		//if(!$cid)
		{
			$sql="select po_assign_det_id, po_assign_head_id, po.item_id, item_desc, req_qty, item_tax, po_qty, unit_cost, line_total,qty_avaliable, itm.item_code  from bud_po_assign_details po left join bud_item_master itm on po.item_id = itm.item_id where po_assign_head_id=:po_assign_head_id";
			$bindArr=array(":po_assign_head_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			$itmgrpdet_details = array(); 
			
			foreach($recs as $key=>$rs)
			{ 
				
				$arr = array('item_id'=>$rs['item_id']);
				$avability = $this->getItemQuantity($arr,1);
				$itmgrpdet_details[$key]=array('po_assign_det_id'=>$rs['po_assign_det_id'], 'po_assign_head_id'=>$rs['po_assign_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'req_qty'=>$this->purifyString($rs['req_qty']), 'item_tax'=>$this->purifyString($rs['item_tax']), 'po_qty'=>$this->purifyString($rs['po_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total'], 'qty_avaliable'=>$avability, 'item_code'=>$rs['item_code']); 
				
			}
		}	
		
		$sendRs["itmgrpdet_details"]=$itmgrpdet_details;
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);

	}
	
	public function __destruct() 
	{
		
	} 
}

?>