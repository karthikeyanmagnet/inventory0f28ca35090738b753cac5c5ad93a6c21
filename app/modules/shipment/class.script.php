<?php	

class shipment extends rapper
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
				$where = 'where cust.company_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_po_head $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  //$sql="select po.shipment_order_id,  po.customer_id , cust.company_name, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(po.lastmodifiedon, '%d %b %Y'), date_format(po.createdon, '%d %b %Y')) as updated_on from bud_shipment_order po left join bud_customer_master as cust on po.customer_id=cust.customer_id left join bud_user_master usr_crt on po.createdby = usr_crt.user_id left join bud_user_master usr_upd on po.lastmodifiedby = usr_upd.user_id  group by po.customer_id  order by po.createdon "; 
                  $sql="select po.shipment_order_id,  po.customer_id , cust.company_name, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(po.lastmodifiedon, '%d %b %Y'), date_format(po.createdon, '%d %b %Y')) as updated_on from bud_shipment_order po left join bud_customer_master as cust on po.customer_id=cust.customer_id left join bud_user_master usr_crt on po.createdby = usr_crt.user_id left join bud_user_master usr_upd on po.lastmodifiedby = usr_upd.user_id  order by po.createdon "; 
                  
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$customer_id=$this->purifyString($rs["customer_id"]);
				$shipment_order_id=$this->purifyString($rs["shipment_order_id"]);
				$company_name=$this->purifyString($rs["company_name"]);
				
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);				
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
		 
				$sendRs[$rsCnt]=array($company_name,'','', '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateShipmentList('.$shipment_order_id.');"><i class="fa fa-edit"></i> Edit </span> ');
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
		
		$sql="select po.po_head_id,  po.customer_id , po.po_number, po.ship_date, cust.company_name, po.active_status, case po.active_status when 1 then 'Active' when 2 then 'Ordered' when 3 then 'Cancelled' when 4 then 'Shipment' when 5 then 'Closed' else '' end as active_status_desc, po.order_total  from bud_po_head po left join bud_customer_master as cust on po.customer_id=cust.customer_id  group by po.customer_id  order by po.createdon "; 
		 
		$recs_cus = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		 
		$customerlist = $recs_cus; // $this->getModuleComboList('customer'); 
		
		$sql="select po_head_id, customer_id, po_number, so_number, cust_po_number, ship_date, spg_order_type, po_type, issuer, po_terms, order_total, total_weight_lbs, total_weight_mt, po_notes,  active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_po_head po where po_head_id in (select po_head_id from bud_shipment_details a left join bud_po_details b on a.po_det_id = b.po_det_id where a.shipment_order_id=:shipment_order_id  )";
		$bindArr=array(":shipment_order_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs_po = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		
		$po_details = array();
		
		 foreach($recs_po as $pokey=>$rs_po)
		 {
			 
			if($rs_po['po_head_id'])
			{
				
				$ship_date=$this->convertDate($this->purifyString($rs_po["ship_date"]));  
				$po_number=$this->purifyString($rs_po["po_number"]);
				$customer_id = $this->purifyString($rs_po['customer_id']);
				
				$sql="select po_det_id, po_head_id, po.item_id, item_desc, whse_line, weight_line, ordered_qty, unit_cost, line_total, itm.item_code
from bud_po_details po
left join bud_item_master itm on po.item_id = itm.item_id where po_head_id=:po_head_id and po_det_id  in (select po_det_id from bud_shipment_details)";
				$bindArr=array(":po_head_id"=>array("value"=>$rs_po['po_head_id'],"type"=>"int"));
				$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
				$itmgrpdet_details = array();
				if(count($recs)>0)
				{
				$po_details[$pokey] = array('po_head_id'=>$rs_po['po_head_id'], 'ship_date'=>$ship_date, 'order_total'=>$rs_po['order_total'], 'po_number'=>$po_number); 
				
				foreach($recs as $key=>$rs)
				{ 
					$itmgrpdet_details[$key]=array('po_det_id'=>$rs['po_det_id'], 'po_head_id'=>$rs['po_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'whse_line'=>$this->purifyString($rs['whse_line']), 'weight_line'=>$this->purifyString($rs['weight_line']), 'ordered_qty'=>$this->purifyString($rs['ordered_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total'], 'item_code'=>$this->purifyString($rs['item_code'])); 
					
				}
				
				$po_details[$pokey]['itmgrpdet_details'] = $itmgrpdet_details;
				
				}
			}
		}
		
		
		
		$sendRs=array("customer_id"=>$customer_id, "shipment_order_id"=>$getcid, "customerlist"=>$customerlist, 'po_details'=>$po_details); 
		
		return $sendRs;
	}
	
	public function saveprocess($postArr)
	{
		
		$customer_id=$this->purifyInsertString($postArr["customer_id"]);
		$hid_id=$this->purifyInsertString($postArr["hid_id"]);
		$item_det_id = $postArr["item_det_id"];
		$cnt_ext_sql="select count(*) as ext_cnt, shipment_order_id from bud_shipment_order where shipment_order_id=:shipment_order_id"; 
		$bindExtCntArr=array(":shipment_order_id"=>array("value"=>$hid_id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		if(!$ext_cnt_val)
		{
			$ins=" bud_shipment_order SET customer_id=:customer_id";
			$insBind=array(":customer_id"=>array("value"=>$customer_id,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			$shipment_order_id = $this->pdoObj->getMaxRecord('shipment_order_id', 'bud_shipment_order');
		}
		else
		{
			$shipment_order_id = $rs_qry_exts["shipment_order_id"];
		}
		
		foreach($item_det_id as $po_det_id)
		{
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_shipment_details where shipment_order_id=:shipment_order_id and po_det_id=:po_det_id "; 
		$bindExtCntArr=array(":shipment_order_id"=>array("value"=>$shipment_order_id,"type"=>"int"), ":po_det_id"=>array("value"=>$po_det_id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_shipment_details SET customer_id=:customer_id, shipment_order_id=:shipment_order_id, po_det_id=:po_det_id";
		
		$insBind=array(":customer_id"=>array("value"=>$customer_id,"type"=>"int"), ":shipment_order_id"=>array("value"=>$shipment_order_id,"type"=>"int"), ":po_det_id"=>array("value"=>$po_det_id,"type"=>"int"), ':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"));
		
		$insuptyp="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where shipment_order_id=:shipment_order_id and po_det_id=:po_det_id ";
			$insBind[":shipment_id"]=array("value"=>$id,"type"=>"text"); 
			
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
		
		if($strQuery)
		{
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
                
		
		}
		}
			
		if($exec)
		{
			$opStatus='success';
			$opMessage=$opmsg; 
                      
		}
                $strQuery="SELECT shipment_order_id FROM `bud_shipment_order` ORDER BY `bud_shipment_order`.`shipment_order_id` DESC";
		$lastInsertId = $this->pdoObj->fetch($strQuery);
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists,'shipment_order_id'=>$lastInsertId[0]['shipment_order_id']);  
		
		return json_encode($sendArr);
		
		
		/*$po_number=$this->purifyInsertString($postArr["po_number"]);
		$so_number=$this->purifyInsertString($postArr["so_number"]);
		$cust_po_number=$this->purifyInsertString($postArr["cust_po_number"]);
		$id=$this->purifyInsertString($postArr["hid_id"]);
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
		
		return json_encode($sendArr);*/
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
		$id=$this->purifyString($postArr["id"]);
		
		if($customer_id>0)
		$sendRs = $this->loadCustomerPO($customer_id);
		else
		{
			$sendRs = $this->getSingleView($postArr);
		}
		
		
		
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
		
	}
	
	public function loadCustomerPO($customer_id)
	{
		 $sql="select po.po_head_id,  po.customer_id , po.po_number, po.ship_date, cust.company_name, po.active_status, case po.active_status when 1 then 'Active' when 2 then 'Ordered' when 3 then 'Cancelled' when 4 then 'Shipment' when 5 then 'Closed' else '' end as active_status_desc, po.order_total  from bud_po_head po left join bud_customer_master as cust on po.customer_id=cust.customer_id  group by po.customer_id  order by po.createdon "; 
		 
		$recs_cus = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		 
		$customerlist = $recs_cus; // $this->getModuleComboList('customer'); 
		
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
					$itmgrpdet_details[$key]=array('po_det_id'=>$rs['po_det_id'], 'po_head_id'=>$rs['po_head_id'], 'item_id'=>$rs['item_id'], 'item_desc'=>$this->purifyString($rs['item_desc']), 'whse_line'=>$this->purifyString($rs['whse_line']), 'weight_line'=>$this->purifyString($rs['weight_line']), 'ordered_qty'=>$this->purifyString($rs['ordered_qty']), 'item_desc'=>$this->purifyString($rs['item_desc']), 'item_quantity'=>$rs['item_quantity'], 'unit_cost'=>$rs['unit_cost'], 'line_total'=>$rs['line_total'], 'item_code'=>$this->purifyString($rs['item_code'])); 
					
				}
				
				$po_details[$pokey]['itmgrpdet_details'] = $itmgrpdet_details;
			}
		}
		
		
		
		$sendRs=array("customer_id"=>$customer_id,"customerlist"=>$customerlist, 'po_details'=>$po_details); 
		
		return $sendRs;
	}
	
	public function saveInvoicePackDetails($post_arr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]); 
		$invoice_no = $this->purifyInsertString($postArr["invoice_no"]);
		$invoice_date = $this->purifyInsertString($postArr["invoice_date"]);
		$invoice_reverse_charge = $this->purifyInsertString($postArr["invoice_reverse_charge"]);
		$invoice_precarriage_mode = $this->purifyInsertString($postArr["invoice_precarriage_mode"]);
		$vehicle_no = $this->purifyInsertString($postArr["vehicle_no"]);
		$supply_date = $this->purifyInsertString($postArr["supply_date"]);
		$supply_place = $this->purifyInsertString($postArr["supply_place"]);
		$vessel_flight_no = $this->purifyInsertString($postArr["vessel_flight_no"]);
		$port_load = $this->purifyInsertString($postArr["port_load"]);
		$port_discharge = $this->purifyInsertString($postArr["port_discharge"]);
		$final_destination = $this->purifyInsertString($postArr["final_destination"]);
		$original_consignee = $this->purifyInsertString($postArr["original_consignee"]);
		$duplicate_transporter = $this->purifyInsertString($postArr["duplicate_transporter"]);
		$triplicate_file = $this->purifyInsertString($postArr["triplicate_file"]);
		$other_reference = $this->purifyInsertString($postArr["other_reference"]);
		$origin_country = $this->purifyInsertString($postArr["origin_country"]);
		$destination_country = $this->purifyInsertString($postArr["destination_country"]);
		$customer_id = $this->purifyInsertString($postArr["customer_id"]);
		$sess_user_id = $this->sess_user_id;
		
		$ins=" bud_shipment_invoice SET invoice_id=:invoice_id,invoice_no=:invoice_no,invoice_date=:invoice_date,invoice_reverse_charge=:invoice_reverse_charge,invoice_precarriage_mode=:invoice_precarriage_mode,vehicle_no=:vehicle_no,supply_date=:supply_date,supply_place=:supply_place,vessel_flight_no=:vessel_flight_no,port_load=:port_load,port_discharge=:port_discharge,final_destination=:final_destination,original_consignee=:original_consignee,duplicate_transporter=:duplicate_transporter,triplicate_file=:triplicate_file,other_reference=:other_reference,origin_country=:origin_country,destination_country=:destination_country,customer_id=:customer_id,shipment_order_id=:shipment_order_id";
		
		$insBind=array(":invoice_id"=>array("value"=>$invoice_id,"type"=>"int"),":invoice_no"=>array("value"=>$invoice_no,"type"=>"text"),":invoice_date"=>array("value"=>$invoice_date,"type"=>"text"),":invoice_reverse_charge"=>array("value"=>$invoice_reverse_charge,"type"=>"text"),":invoice_precarriage_mode"=>array("value"=>$invoice_precarriage_mode,"type"=>"text"),":vehicle_no"=>array("value"=>$vehicle_no,"type"=>"text"),":supply_date"=>array("value"=>$supply_date,"type"=>"text"),":supply_place"=>array("value"=>$supply_place,"type"=>"text"),":vessel_flight_no"=>array("value"=>$vessel_flight_no,"type"=>"text"),":port_load"=>array("value"=>$port_load,"type"=>"text"),":port_discharge"=>array("value"=>$port_discharge,"type"=>"text"),":final_destination"=>array("value"=>$final_destination,"type"=>"text"),":original_consignee"=>array("value"=>$original_consignee,"type"=>"int"),":duplicate_transporter"=>array("value"=>$duplicate_transporter,"type"=>"int"),":triplicate_file"=>array("value"=>$triplicate_file,"type"=>"int"),":other_reference"=>array("value"=>$other_reference,"type"=>"text"),":origin_country"=>array("value"=>$origin_country,"type"=>"text"),":destination_country"=>array("value"=>$destination_country,"type"=>"text"),":customer_id"=>array("value"=>$customer_id,"type"=>"int"),":sess_user_id"=>array("value"=>$sess_user_id,"type"=>"int"),":shipment_order_id"=>array("value"=>$id,"type"=>"int"));
		
		$cnt_ext_sql="select count(*) as ext_cnt, invoice_id from bud_shipment_invoice where shipment_order_id=:shipment_order_id "; 
		$bindExtCntArr=array(":shipment_order_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		$invoice_id = $rs_qry_exts['invoice_id'];
		
		if($ext_cnt_val>0)
		{
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where shipment_order_id=:shipment_order_id ";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Invoice inserted successfully!";
			$insuptyp="insert";
		}
		
		$exec = $this->pdoObj->execute($strQuery, $insBind);
			
		if($exec)
		{
			if($insuptyp == 'insert')
			{
				$invoice_id = $this->pdoObj->getMaxRecord('invoice_id', 'bud_shipment_invoice');
			}	
			
			$item_id = $this->purifyInsertString($postArr["item_id"]);
			$hsn_code = $this->purifyInsertString($postArr["hsn_code"]);
			$uom_unit = $this->purifyInsertString($postArr["uom_unit"]);
			$quantity = $this->purifyInsertString($postArr["quantity"]);
			$usd_rate = $this->purifyInsertString($postArr["usd_rate"]);
			$item_amount = $this->purifyInsertString($postArr["item_amount"]);
			$discount = $this->purifyInsertString($postArr["discount"]);
			$net_amount = $this->purifyInsertString($postArr["net_amount"]);		
			
			$ins=" bud_shipment_invoice_items SET invoice_det_id=:invoice_det_id,invoice_id=:invoice_id,item_id=:item_id,hsn_code=:hsn_code,uom_unit=:uom_unit,quantity=:quantity,usd_rate=:usd_rate,item_amount=:item_amount,discount=:discount,net_amount=:net_amount";
			
			$insBind=array(":invoice_det_id"=>array("value"=>$invoice_det_id,"type"=>"text"),":invoice_id"=>array("value"=>$invoice_id,"type"=>"text"),":item_id"=>array("value"=>$item_id,"type"=>"text"),":hsn_code"=>array("value"=>$hsn_code,"type"=>"text"),":uom_unit"=>array("value"=>$uom_unit,"type"=>"text"),":quantity"=>array("value"=>$quantity,"type"=>"text"),":usd_rate"=>array("value"=>$usd_rate,"type"=>"text"),":item_amount"=>array("value"=>$item_amount,"type"=>"text"),":discount"=>array("value"=>$discount,"type"=>"text"),":net_amount"=>array("value"=>$net_amount,"type"=>"text"));		
		}
		
	}
        
        public function updateShipmentOrder($post_arr) {
            
          
          $shipment_order_id=$post_arr['shipment_order_id'];
          $fields=[];
          unset($post_arr['shipment_order_id']);
          unset($post_arr['module']);
          unset($post_arr['action']);
          foreach($post_arr as $key=>$val){
              $fields[]="`$key` = '$val'";
              
          }
          if(!isset($post_arr['original_consignee'])){
              $fields[]="`original_consignee` = '0'";
          }
           if(!isset($post_arr['duplicate_transporter'])){
              $fields[]="`duplicate_transporter` = '0'";
          }
           if(!isset($post_arr['triplicate_file'])){
              $fields[]="`triplicate_file` = '0'";
          }
          
          $fields=  implode(",", $fields);
          
          $strQuery="UPDATE `bud_shipment_order` SET $fields WHERE `bud_shipment_order`.`shipment_order_id` = $shipment_order_id;";
          $exec = $this->pdoObj->execute($strQuery);
          $sendArr=array('code'=>'100','status'=>'success');  
		
		return json_encode($sendArr);
            
            
        }
        
        public function getSingleViewOnlyOrder($shipment_order_id)
        {
             
             $strQuery="SELECT * FROM `bud_shipment_order` WHERE `bud_shipment_order`.`shipment_order_id` = $shipment_order_id;";
             $exec = $this->pdoObj->fetchSingle($strQuery);
             return $exec;
            
        }

                public function __destruct() 
	{
		
	} 
}

?>