<?php	

class stock_dispatch extends rapper
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
				$where = 'where (bud_po_head.po_number like :search_str or bud_stock_dispatch_details.po_number like :search_str) ';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		 // $tot_sql="select count(*) as cnt from bud_stock_dispatch_head left join bud_po_head on bud_stock_dispatch_head.po_head_id = bud_po_head.po_head_id  $where";
		 $tot_sql="select count(*) as cnt from bud_stock_dispatch_head left join bud_po_head on bud_stock_dispatch_head.po_head_id = bud_po_head.po_head_id  left join  bud_stock_dispatch_details on bud_stock_dispatch_details.stock_dispatch_head_id = bud_stock_dispatch_head.stock_dispatch_head_id $where ";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0; 
		  
		   $sql="select bud_stock_dispatch_head.stock_dispatch_head_id so_number, if(coalesce(bud_po_head.po_head_id,0)=0,group_concat(bud_stock_dispatch_details.po_number),bud_po_head.po_number) as po_number, group_concat(bud_stock_dispatch_details.dispatch_date) as dispatch_date, bud_stock_dispatch_head.stock_dispatch_head_id, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(bud_stock_dispatch_head.lastmodifiedon, '%d %b %Y'), date_format(bud_stock_dispatch_head.createdon, '%d %b %Y')) as updated_on, usr_crt.user_name as created_by, group_concat(itm.item_code) as item_code, group_concat(bud_stock_dispatch_details.dispatch_qty) as dispatch_qty  from bud_stock_dispatch_head left join bud_po_head on bud_stock_dispatch_head.po_head_id = bud_po_head.po_head_id left join  bud_stock_dispatch_details on bud_stock_dispatch_details.stock_dispatch_head_id = bud_stock_dispatch_head.stock_dispatch_head_id left join bud_user_master usr_crt on bud_stock_dispatch_head.createdby = usr_crt.user_id left join bud_user_master usr_upd on bud_stock_dispatch_head.lastmodifiedby = usr_upd.user_id   left join bud_item_master itm on bud_stock_dispatch_details.item_id = itm.item_id $where group by bud_stock_dispatch_head.stock_dispatch_head_id   order by bud_stock_dispatch_head.stock_dispatch_head_id  ";
		  
		  //$sql="select so_number, po_number, ship_date, stock_dispatch_head_id, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(bud_stock_dispatch_head.lastmodifiedon, '%d %b %Y'), date_format(bud_stock_dispatch_head.createdon, '%d %b %Y')) as updated_on from bud_stock_dispatch_head left join bud_po_head on bud_stock_dispatch_head.po_head_id = bud_po_head.po_head_id left join bud_user_master usr_crt on bud_stock_dispatch_head.createdby = usr_crt.user_id left join bud_user_master usr_upd on bud_stock_dispatch_head.lastmodifiedby = usr_upd.user_id $where order by stock_dispatch_head_id "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["stock_dispatch_head_id"]);
				$po_number=$this->purifyString($rs["po_number"]);
				$created_by=$this->purifyString($rs["created_by"]);
				//$dispatch_date=$this->convertDate($rs["dispatch_date"]);
				$active_status_desc=$this->purifyString($rs["active_status_desc"]);
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				$dispatch_qty=$this->purifyString($rs["dispatch_qty"]);
				$item_code=$this->purifyString($rs["item_code"]);
				
				$dispatch_date="";
				if($rs["dispatch_date"])
				{
					foreach(explode(",",$rs["dispatch_date"]) as $lprcvddat)
					{
						if($dispatch_date) $received_date.=", ";
						$dispatch_date.=($lprcvddat)?$this->convertDate($lprcvddat):'';
					}
				}
				
				//$sendRs[$rsCnt]=array("stock_dispatch_head_id"=>$cid,"stock_dispatch_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); //$PageSno+1,
				$sendRs[$rsCnt]=array($dispatch_date, $item_code, $dispatch_qty, $po_number,  $created_by, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateStockDispatchMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteStockDispatchMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select po_head_id, stock_dispatch_head_id, item_group_id, item_group_qty  from bud_stock_dispatch_head where stock_dispatch_head_id=:stock_dispatch_head_id";
			$bindArr=array(":stock_dispatch_head_id"=>array("value"=>$getcid,"type"=>"int")); 
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["stock_dispatch_head_id"]);
			$po_head_id=$this->purifyString($recs["po_head_id"]);
			$item_group_id=$this->purifyString($recs["item_group_id"]);
			$item_group_qty=$this->purifyString($recs["item_group_qty"]);
		
			
			if(!$cid)
			{
				$itmgrpdet_details = array();
			}	
			else
			{
				$sql="select stock_dispatch_det_id,stock_dispatch_head_id,dispatch_qty,dispatch_date,stcdet.item_id,po_number, po_det_id,stock_dispatch_status, itm.item_code from bud_stock_dispatch_details stcdet left join bud_item_master itm on stcdet.item_id = itm.item_id  where stock_dispatch_head_id=:stock_dispatch_head_id";
				$bindArr=array(":stock_dispatch_head_id"=>array("value"=>$cid,"type"=>"int"));
				$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
				$itmgrpdet_details = array(); 
				
				foreach($recs as $key=>$rs)
				{ 
					$arr = array('item_id'=>$rs['item_id'], 'stock_dispatch_head_id'=>$rs['stock_dispatch_head_id']);
					$avability = $this->getItemQuantity($arr,1);
					$itmgrpdet_details[$key]=array('stock_dispatch_det_id'=>$rs['stock_dispatch_det_id'], 'stock_dispatch_head_id'=>$rs['stock_dispatch_head_id'], 'dispatch_qty'=>$rs['dispatch_qty'], 'item_id'=>$rs['item_id'], 'po_number'=>$rs['po_number'], 'po_det_id'=>$rs['po_det_id'], 'stock_dispatch_status'=>$rs['stock_dispatch_status'], 'item_code'=>$rs['item_code'],'dispatch_date'=>$this->convertDate($rs['dispatch_date']),'avability'=>$avability);
					
				}
				
			}
			
			
			$itemlist = $this->getModuleComboList('item'); 
			$itemgrouplist = $this->getModuleComboList('item_group'); 
			
			$sendRs=array("stock_dispatch_head_id"=>$cid,"po_head_id"=>$po_head_id,"item_group_id"=>$item_group_id, "item_group_qty"=>$item_group_qty, "itemlist"=>$itemlist, "itmgrpdet_details"=>$itmgrpdet_details, 'itemgrouplist'=>$itemgrouplist); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
	
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$chk_stock_dispatch=$this->purifyInsertString($postArr["chk_stock_dispatch"]);
		$hdn_po_head_id=$this->purifyInsertString($postArr["hdn_po_head_id"]);
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);
		$item_group_id = $this->purifyInsertString($postArr['cmb_item_group']); //
		
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_stock_dispatch_head where stock_dispatch_head_id=:stock_dispatch_head_id "; 
		$bindExtCntArr=array(":stock_dispatch_head_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$max_ext_sql="select max(stock_dispatch_head_id)+1 as mid from bud_stock_dispatch_head "; 
		$rs_qry_max = $this->pdoObj->fetchSingle($max_ext_sql); 
		$ext_max_val=($rs_qry_max["mid"])?$rs_qry_max["mid"]:1;
		
		$ins=" bud_stock_dispatch_head SET po_head_id=:po_head_id ";
		$insBind=array(":po_head_id"=>array("value"=>$hdn_po_head_id,"type"=>"int"),
		':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		if($chk_stock_dispatch == 3)
		{
			$ins.=', item_group_id=:item_group_id';
			$insBind['item_group_id'] = array("value"=>$item_group_id,"type"=>"int");
		}
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_stock_dispatch_head where trim(po_head_id)=:po_head_id ";
		$bindExtChkArr=array(":po_head_id"=>array("value"=>$hdn_po_head_id,"dtype"=>"int")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where stock_dispatch_head_id=:stock_dispatch_head_id ";
			$insBind[":stock_dispatch_head_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and stock_dispatch_head_id<>:stock_dispatch_head_id ";
			$bindExtChkArr[":stock_dispatch_head_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Stock Dispatch updated successfully!";
		}
		else
		{
			$id = $ext_max_val;
			$strQuery="INSERT INTO $ins, stock_dispatch_head_id=$id, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Stock Dispatch inserted successfully!";
		}
		//$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		
		$rec_exist_cnt_val = 0;
		
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
			
			 
				
				if($chk_stock_dispatch == 2)
				{
				$hdn_stock_dispatch_det_id = $postArr['hdn_stock_dispatch_det_id']; 
				$hdn_po_det_id = $postArr['hdn_po_det_id']; 
				$hdn_item_id = $postArr['hdn_item_id'];
				$txt_disp_qty = $postArr['txt_disp_qty'];
				$cmb_poitem_status = $postArr['cmb_poitem_status'];
				foreach($hdn_item_id as $cat_key=>$cat_val)
				{  
					$stock_dispatch_det_id = $this->purifyInsertString($hdn_stock_dispatch_det_id[$cat_key]); 
					$po_det_id = $this->purifyInsertString($hdn_po_det_id[$cat_key]);
					$item_id = $this->purifyInsertString($hdn_item_id[$cat_key]);
					$dispatch_qty = $this->purifyInsertString($txt_disp_qty[$cat_key]);
					$stock_dispatch_status = $this->purifyInsertString($cmb_poitem_status[$cat_key]);
					
					if($item_id)
					{   
						
						$ins=" bud_stock_dispatch_details SET po_det_id=:po_det_id, item_id=:item_id, dispatch_qty=:dispatch_qty, stock_dispatch_status=:stock_dispatch_status ";
						$insBind=array(":po_det_id"=>array("value"=>$po_det_id,"type"=>"text"),":item_id"=>array("value"=>$item_id,"type"=>"int"), ":dispatch_qty"=>array("value"=>$dispatch_qty,"type"=>"int"), ":stock_dispatch_status"=>array("value"=>$stock_dispatch_status,"type"=>"int")); 
						$mod = '';
						if($stock_dispatch_det_id>0) 
						{ 
							$strQuery="UPDATE $ins where stock_dispatch_det_id=:stock_dispatch_det_id";
							$insBind[":stock_dispatch_det_id"]=array("value"=>$stock_dispatch_det_id,"type"=>"int"); 
							$mod = 'up';
						}
						else
						{ 
							 
							$strQuery="INSERT INTO $ins, stock_dispatch_head_id=:stock_dispatch_head_id "; 						
							$insBind[":stock_dispatch_head_id"]=array("value"=>$id,"type"=>"int");  
							$mod = 'ins';  
						}
						 
						$sbexec = $this->pdoObj->execute($strQuery, $insBind);  
						 
					}	
				}	
				
				}
				
				
				if($chk_stock_dispatch == 1)
				{
				$hdn_stock_dispatch_det_id = $postArr['hdn_itmgrp_detid']; 
				$txt_dispatch_date = $postArr['txt_dispatch_date']; 
				$hdn_item_id = $postArr['cmb_item'];
				$txt_qty = $postArr['txt_qty'];
				$txt_po = $postArr['txt_po'];
				foreach($hdn_item_id as $cat_key=>$cat_val)
				{  
					$stock_dispatch_det_id = $this->purifyInsertString($hdn_stock_dispatch_det_id[$cat_key]); 
					$dispatch_date = $this->convertDate($txt_dispatch_date[$cat_key]);
					$item_id = $this->purifyInsertString($hdn_item_id[$cat_key]);
					$dispatch_qty = $this->purifyInsertString($txt_qty[$cat_key]);
					$po_number = $this->purifyInsertString($txt_po[$cat_key]);
					 
					
					if($item_id)
					{   
						
						$ins=" bud_stock_dispatch_details SET dispatch_date=:dispatch_date, item_id=:item_id, dispatch_qty=:dispatch_qty, po_number=:po_number ";
						$insBind=array(":dispatch_date"=>array("value"=>$dispatch_date,"type"=>"text"),":item_id"=>array("value"=>$item_id,"type"=>"int"), ":dispatch_qty"=>array("value"=>$dispatch_qty,"type"=>"int"), ":po_number"=>array("value"=>$po_number,"type"=>"text")); 
						$mod = '';
						if($stock_dispatch_det_id>0) 
						{ 
							$strQuery="UPDATE $ins where stock_dispatch_det_id=:stock_dispatch_det_id";
							$insBind[":stock_dispatch_det_id"]=array("value"=>$stock_dispatch_det_id,"type"=>"int"); 
							$mod = 'up';
						}
						else
						{ 
							 
							$strQuery="INSERT INTO $ins, stock_dispatch_head_id=:stock_dispatch_head_id "; 						
							$insBind[":stock_dispatch_head_id"]=array("value"=>$id,"type"=>"int");  
							$mod = 'ins';  
						}
						 
						$sbexec = $this->pdoObj->execute($strQuery, $insBind);  
						 
					}	
				}	
				
				}
				
				if($chk_stock_dispatch == 3)
				{
				
				//print_r($postArr);
				$hdn_stock_dispatch_det_id = $postArr['hdn_itmgrp_detid']; 
				$hdn_item_id = $postArr['hdn_prd_item_id'];
				$txt_qty = $postArr['txt_prd_disp_qty'];
				foreach($hdn_item_id as $cat_key=>$cat_val)
				{  
					$item_id = $this->purifyInsertString($hdn_item_id[$cat_key]);
					$dispatch_qty = $this->purifyInsertString($txt_qty[$cat_key]);
					$stock_dispatch_det_id = $this->purifyInsertString($hdn_stock_dispatch_det_id[$cat_key]); 
					 
					
					if($item_id)
					{   
						
						$ins=" bud_stock_dispatch_details SET  item_id=:item_id, dispatch_qty=:dispatch_qty ";
						$insBind=array(":item_id"=>array("value"=>$item_id,"type"=>"int"), ":dispatch_qty"=>array("value"=>$dispatch_qty,"type"=>"int")); 
						$mod = '';
						if($stock_dispatch_det_id>0) 
						{ 
							$strQuery="UPDATE $ins where stock_dispatch_det_id=:stock_dispatch_det_id";
							$insBind[":stock_dispatch_det_id"]=array("value"=>$stock_dispatch_det_id,"type"=>"int"); 
							$mod = 'up';
						}
						else
						{ 
							 
							$strQuery="INSERT INTO $ins, stock_dispatch_head_id=:stock_dispatch_head_id "; 						
							$insBind[":stock_dispatch_head_id"]=array("value"=>$id,"type"=>"int");  
							$mod = 'ins';  
						}
						 
						$sbexec = $this->pdoObj->execute($strQuery, $insBind);  
						 
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
							$strQuery=" delete from bud_stock_dispatch_details where stock_dispatch_det_id=:stock_dispatch_det_id  ";  						
							$bindArr=array( ":stock_dispatch_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							$exec = $this->pdoObj->execute($strQuery, $bindArr);  
						}
					}
				}
				
				
				
				
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			
			$strQuery=" delete from bud_stock_dispatch_details where stock_dispatch_head_id=:stock_dispatch_head_id "; 
			$bindArr=array( ":stock_dispatch_head_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			
			$strQuery=" delete from bud_stock_dispatch_head where stock_dispatch_head_id=:stock_dispatch_head_id "; 
			$bindArr=array( ":stock_dispatch_head_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Stock Dispatch details deleted successfully'; 
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
		  	$bindArr=array( ":stock_dispatch_head_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or stock_dispatch_head_id=:stock_dispatch_head_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select stock_dispatch_head_id,stock_dispatch_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_stock_dispatch_master where active_status = 1 $whereor order by stock_dispatch_display_order asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		//$cnt_ext_sql="(select count(*) as ext_cnt, 'StockDispatch referring Sub StockDispatch' as msg from bud_substock_dispatch_master where stock_dispatch_head_id=:id) union all (select count(*) as ext_cnt, 'StockDispatch linked to Expenses' as msg from bud_expense_details where stock_dispatch_head_id=:id) "; 
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
			$_msg = "StockDispatch cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function getPOSODetails($postArr)
	{
		$searchval = $postArr['po_so_number'];
		$where = 'where so_number=:search_str or po_number=:search_str'; 
		$bindArr=array( ":search_str"=>array("value"=>$searchval,"dtype"=>"text")); 
		$sql="select po.item_id, po.ordered_qty, itm.item_code as item_desc, poh.po_number, po.po_det_id, po.po_head_id from bud_po_details po left join bud_po_head poh on po.po_head_id = poh.po_head_id left join bud_item_master itm on po.item_id = itm.item_id $where";
		$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		$data = array();
		foreach($recs as $key=>$rs)
		{	
			$arr = array('item_id'=>$rs['item_id']);
			$rs['avability'] = $this->getItemQuantity($arr,1);
			$data[$key] = $rs;
		}
		$sendArr=array('rsData'=>$data,'status'=>'success');  
			
		return json_encode($sendArr);
	}
	
	public function getProductDetails($postArr)
	{
		$searchval = $postArr['item_group_id'];
		$txt_grp_qty = $postArr['txt_grp_qty'];
		$where = 'where grpdet.item_group_id=:item_group_id'; 
		$bindArr=array( ":item_group_id"=>array("value"=>$searchval,"dtype"=>"int")); 
		$sql="select grpdet.item_id, grpdet.item_quantity, itm.item_code, itm.item_open_stock from bud_item_group_details_master grpdet left join bud_item_group_master grp on grpdet.item_group_id = grp.item_group_id left join bud_item_master itm on grpdet.item_id = itm.item_id $where";
		$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		$data = array();
		foreach($recs as $key=>$rs)
		{	
			$arr = array('item_id'=>$rs['item_id']);
			$rs['avability'] = $this->getItemQuantity($arr,1);
			if($txt_grp_qty)
			{
				$rs['dispatch_req_qty'] =  $rs['item_quantity'] * $txt_grp_qty; 
			}
			else
			{
				$rs['dispatch_req_qty'] =  $rs['item_quantity'];
			}
			$data[$key] = $rs;
		}
		$sendArr=array('rsData'=>$data,'status'=>'success'); 
			
		return json_encode($sendArr);
	}	
	
	
	
	public function __destruct() 
	{
		
	} 
}

?>