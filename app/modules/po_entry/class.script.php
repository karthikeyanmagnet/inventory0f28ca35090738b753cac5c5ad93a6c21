<?php	

class po_entry extends rapper
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
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		  $bindArr=array();
		  $search = ($postArr['search']['value']);	  
		  $where = "";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where = 'where po_number like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_po_head $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select po.po_head_id, po.po_number, po.ship_date, cust.company_name, po.active_status, case po.active_status when 1 then 'Active' when 2 then 'Ordered' when 3 then 'Cancelled' when 4 then 'Shipment' when 5 then 'Closed' else '' end as active_status_desc, po.order_total  from bud_po_head po left join bud_customer_master as cust on po.customer_id=cust.customer_id  $where order by po.createdon "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["po_head_id"]);
				$po_number=$this->purifyString($rs["po_number"]);
				$ship_date=$this->convertDate($this->purifyString($rs["ship_date"]));  
				$company_name=$this->purifyString($rs["company_name"]);
				$order_total=$this->purifyString($rs["order_total"]); 
				
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);				
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
		 
				$sendRs[$rsCnt]=array($po_number, $ship_date, $company_name, $cstatus_desc, $order_total, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdatePOEntryMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeletePOEntryMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span> ');
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
		
		$sql="select po_head_id, customer_id, po_number, so_number, cust_po_number, ship_date, spg_order_type, po_type, issuer, po_terms, order_total, total_weight_lbs, total_weight_mt, po_notes,  active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_po_head where po_head_id=:po_head_id";
		$bindArr=array(":po_head_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
                
                
		$cid=$this->purifyString($recs["po_head_id"]);
		$customer_id=$this->purifyString($recs["customer_id"]);
		$po_number=$this->purifyString($recs["po_number"]);
		$so_number=$this->purifyString($recs["so_number"]);
		$cust_po_number=$this->purifyString($recs["cust_po_number"]);
                
                
                
		//$ship_date=$this->convertDate($this->purifyString($recs["ship_date"]));  
		$ship_date=($this->purifyString($recs["ship_date"]));  
		$spg_order_type=$this->purifyString($recs["spg_order_type"]);
		$po_type=$this->purifyString($recs["po_type"]);
		$issuer=$this->purifyString($recs["issuer"]);
		$po_terms=$this->purifyString($recs["po_terms"]);
                
                
                
		$order_total=$this->purifyString($recs["order_total"]);
		$total_weight_lbs=$this->purifyString($recs["total_weight_lbs"]);
                
                
                
		$total_weight_mt=$this->purifyString($recs["total_weight_mt"]); 
		$po_notes=$this->purifyString($recs["po_notes"]);
                
                
                		
		$cstatus=$this->purifyString($recs["active_status"]);
		$cstatus_desc=$this->purifyString($recs["active_status_desc"]);  
                
                //echo 'ok';exit();
		 
		$itemlist = $this->getModuleComboList('item',''); 
                
                //$itemlist=1;
                
                //echo 'ok';exit();
                
		$customerlist = $this->getModuleComboList('customer',''); 
		
//                print_r($customerlist);exit();
                $arr_va=[];
        foreach ($customerlist as $cus)
        {
            //print_r($cus);
            if($cus["company_name"]!="")
            {
                $arr_va[]=$cus;
            }
            
        }
        
        //print_r($arr_va);exit();
                //$customerlist=1;
                
                
		if(!$cid)
		{
			$itmgrpdet_details = array();
		}	
		else
		{
			$sql="select po_det_id, po_head_id, item_id, item_desc, whse_line, weight_line, ordered_qty, unit_cost, line_total  from bud_po_details where po_head_id=:po_head_id";
			$bindArr=array(":po_head_id"=>array("value"=>$cid,"type"=>"int"));
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			$itmgrpdet_details = array(); 
			
			foreach($recs as $key=>$rs)
			{ 
				$itmgrpdet_details[$key]=array('po_det_id'=>$rs['po_det_id'], 'po_head_id'=>$rs['po_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'whse_line'=>$this->purifyString($rs['whse_line']), 'weight_line'=>$this->purifyString($rs['weight_line']), 'ordered_qty'=>$this->purifyString($rs['ordered_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total']); 
				
			}
			
		}
		
                //echo 'ok';exit();
                
		 $itemgrouplist = $this->getModuleComboList('item_group',''); 
		
		$sendRs=array("po_head_id"=>$cid, "customer_id"=>$customer_id, "po_number"=>$po_number, "so_number"=>$so_number, "cust_po_number"=>$cust_po_number, "ship_date"=>$ship_date, "spg_order_type"=>$spg_order_type, "po_type"=>$po_type, "issuer"=>$issuer, "po_terms"=>$po_terms, "order_total"=>$order_total, "total_weight_lbs"=>$total_weight_lbs, "total_weight_mt"=>$total_weight_mt, "po_notes"=>$po_notes,"active_status"=>$cstatus,"status_desc"=>$cstatus_desc,"customerlist"=>$arr_va,"itemlist"=>$itemlist,"itmgrpdet_details"=>$itmgrpdet_details, "itemgrouplist"=>$itemgrouplist); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$customer_id=$this->purifyInsertString($postArr["customer_id"]);
		$po_number=$this->purifyInsertString($postArr["po_number"]);
		$so_number=$this->purifyInsertString($postArr["so_number"]);
		$cust_po_number=$this->purifyInsertString($postArr["cust_po_number"]);
		//$ship_date=$this->convertDate($this->purifyString($postArr["ship_date"])); 
		$ship_date=($this->purifyString($postArr["ship_date"])); 
		$spg_order_type=$this->purifyInsertString($postArr["spg_order_type"]);
		$po_type=$this->purifyInsertString($postArr["po_type"]);
		$issuer=$this->purifyInsertString($postArr["issuer"]);
		$po_terms=$this->purifyInsertString($postArr["po_terms"]);
		$order_total=$this->purifyInsertString($postArr["order_total"]);
		$total_weight_lbs=$this->purifyInsertString($postArr["total_weight_lbs"]);
		$total_weight_mt=$this->purifyInsertString($postArr["total_weight_mt"]);
		$po_notes=$this->purifyInsertString($postArr["po_notes"]);
		$active_status=$this->purifyInsertString($postArr["active_status"]); 
		
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);    
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_po_head where po_head_id=:po_head_id "; 
		$bindExtCntArr=array(":po_head_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_po_head SET customer_id=:customer_id, po_number=:po_number, so_number=:so_number, cust_po_number=:cust_po_number, ship_date=:ship_date, spg_order_type=:spg_order_type, po_type=:po_type, issuer=:issuer, po_terms=:po_terms, order_total=:order_total, total_weight_lbs=:total_weight_lbs, total_weight_mt=:total_weight_mt, po_notes=:po_notes, active_status=:active_status   ";
		
		$insBind=array(":customer_id"=>array("value"=>$customer_id,"type"=>"int"),  ":po_number"=>array("value"=>$po_number,"type"=>"text"), ":so_number"=>array("value"=>$so_number,"type"=>"text"), ":cust_po_number"=>array("value"=>$cust_po_number,"type"=>"text"), ":ship_date"=>array("value"=>$ship_date,"type"=>"text"), ":spg_order_type"=>array("value"=>$spg_order_type,"type"=>"text"), ":po_type"=>array("value"=>$po_type,"type"=>"text"), ":issuer"=>array("value"=>$issuer,"type"=>"text"), ":po_terms"=>array("value"=>$po_terms,"type"=>"text"), ":order_total"=>array("value"=>$order_total,"type"=>"text"), ":total_weight_lbs"=>array("value"=>$total_weight_lbs,"type"=>"text"), ":total_weight_mt"=>array("value"=>$total_weight_mt,"type"=>"text"), ":po_notes"=>array("value"=>$po_notes,"type"=>"text"), ":active_status"=>array("value"=>$active_status,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_po_head where trim(po_number)=:po_number ";
		$bindExtChkArr=array(":po_number"=>array("value"=>$po_number,"dtype"=>"text")); 
		
		$insuptyp="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where po_head_id=:po_head_id ";
			$insBind[":po_head_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and po_head_id<>:po_head_id ";
			$bindExtChkArr[":po_head_id"]=array("value"=>$id,"dtype"=>"int");  
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
					$maxid_sql="select max(po_head_id) as maxidval from bud_po_head "; 
					$bindmaxidArr=array();
					$rs_qry_maxid = $this->pdoObj->fetchSingle($maxid_sql, $bindmaxidArr); 
					$id=$rs_qry_maxid["maxidval"];
				}
				
				
				//-============= Details insert/update start
				
				
				 
			 
			$hdn_po_detid = $postArr['hdn_po_detid']; 
			$arr_item_id = $postArr['cmb_item'];
			$arr_item_desc = $postArr['item_desc'];
			$arr_whse_line = $postArr['whse_line'];
			$arr_weight_line = $postArr['weight_line'];
			$arr_ordered_qty = $postArr['ordered_qty'];
			$arr_unit_cost = $postArr['unit_cost'];
			$arr_line_total = $postArr['line_total'];
			
			//print_r($arr_item_id);
			
			foreach($arr_item_id as $cat_key=>$cat_val)
			{  
				$po_det_id = $this->purifyInsertString($hdn_po_detid[$cat_key]); 
				$item_id = $this->purifyInsertString($arr_item_id[$cat_key]);
				$item_desc = $this->purifyInsertString($arr_item_desc[$cat_key]);
				$whse_line = $this->purifyInsertString($arr_whse_line[$cat_key]);
				$weight_line = $this->purifyInsertString($arr_weight_line[$cat_key]);
				$ordered_qty = $this->purifyInsertString($arr_ordered_qty[$cat_key]);
				$unit_cost = $this->purifyInsertString($arr_unit_cost[$cat_key]);
				$line_total = $this->purifyInsertString($arr_line_total[$cat_key]);
				 
				
				if($arr_item_id and $ordered_qty)
				{   
					
					$ins=" bud_po_details SET item_id=:item_id, item_desc=:item_desc, whse_line=:whse_line, weight_line=:weight_line, ordered_qty=:ordered_qty, unit_cost=:unit_cost, line_total=:line_total ";
					$insBind=array(":item_id"=>array("value"=>$item_id,"type"=>"int"), ":item_desc"=>array("value"=>$item_desc,"type"=>"text"), ":whse_line"=>array("value"=>$whse_line,"type"=>"text"), ":weight_line"=>array("value"=>$weight_line,"type"=>"text"), ":ordered_qty"=>array("value"=>$ordered_qty,"type"=>"text"), ":unit_cost"=>array("value"=>$unit_cost,"type"=>"text"), ":line_total"=>array("value"=>$line_total,"type"=>"text")); 
					$mod = '';
					if($po_det_id>0) 
					{ 
						$strQuery="UPDATE $ins where po_det_id=:po_det_id";
						$insBind[":po_det_id"]=array("value"=>$po_det_id,"type"=>"int"); 
						$mod = 'up';
					}
					else
					{ 
						 
						$strQuery="INSERT INTO $ins, po_head_id=:po_head_id "; 						
						$insBind[":po_head_id"]=array("value"=>$id,"type"=>"int");  
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
						$strQuery=" delete from bud_po_details where po_det_id=:po_det_id  ";  						
						$bindArr=array( ":po_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
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
		
		$strQuery=" delete from bud_po_head where po_head_id=:po_head_id "; 
		$bindArr=array( ":po_head_id"=>array("value"=>$id,"dtype"=>"int"));  
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='PO details deleted successfully'; 
			
			$strSbQuery=" delete from bud_po_details where po_head_id=:po_head_id "; 
			$bindSbArr=array( ":po_head_id"=>array("value"=>$id,"dtype"=>"int"));  
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
		  	$bindArr=array( ":po_head_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or po_head_id=:po_head_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select po_head_id, po_number ,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_po_head where active_status = 1 $whereor order by po_head_id asc ";
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
	
	public function getPODetails($postArr)
	{
		$customer_id=$this->purifyString($postArr["customer_id"]);
		$customerlist = $this->getModuleComboList('customer'); 
		
		$sql="select po_head_id, customer_id, po_number, so_number, cust_po_number, ship_date, spg_order_type, po_type, issuer, po_terms, order_total, total_weight_lbs, total_weight_mt, po_notes,  active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_po_head where customer_id=:customer_id";
		$bindArr=array(":customer_id"=>array("value"=>$customer_id,"type"=>"int"));
		$recs_po = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		
		$po_details = array();
		
		 foreach($recs_po as $pokey=>$rs_po)
		 {
			 
			if($rs_po['po_head_id'])
			{
				
				$ship_date=$this->convertDate($this->purifyString($rs_po["ship_date"]));  
				$po_number=$this->purifyString($rs_po["po_number"]);
				$po_details[$pokey] = array('po_head_id'=>$rs_po['po_head_id'], 'ship_date'=>$ship_date, 'order_total'=>$rs_po['order_total'], 'po_number'=>$po_number);
				
				$sql="select po_det_id, po_head_id, po.item_id, item_desc, whse_line, weight_line, ordered_qty, unit_cost, line_total, itm.item_code
from bud_po_details po
left join bud_item_master itm on po.item_id = itm.item_id where po_head_id=:po_head_id";
				$bindArr=array(":po_head_id"=>array("value"=>$rs_po['po_head_id'],"type"=>"int"));
				$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
				$itmgrpdet_details = array(); 
				
				foreach($recs as $key=>$rs)
				{ 
					$itmgrpdet_details[$key]=array('po_det_id'=>$rs['po_det_id'], 'po_head_id'=>$rs['po_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'whse_line'=>$this->purifyString($rs['whse_line']), 'weight_line'=>$this->purifyString($rs['weight_line']), 'ordered_qty'=>$this->purifyString($rs['ordered_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total']); 
					
				}
				
				$po_details[$pokey]['itmgrpdet_details'] = $itmgrpdet_details;
			}
		}
		
		
		
		$sendRs=array("customer_id"=>$customer_id,"customerlist"=>$customerlist, 'po_details'=>$po_details); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
		
	}
	
	public function __destruct() 
	{
		
	} 
}

?>