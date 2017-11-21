<?php	

class item_group extends rapper
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
				$where = 'where item_group_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_item_group_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select item_group_id,item_group_name,item_group_desc, item_group_unit_cost, cat.active_status,  case cat.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc,   if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(cat.lastmodifiedon, '%d %b %Y'), date_format(cat.createdon, '%d %b %Y')) as updated_on from bud_item_group_master cat left join bud_user_master usr_crt on cat.createdby = usr_crt.user_id left join bud_user_master usr_upd on cat.lastmodifiedby = usr_upd.user_id $where order by item_group_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["item_group_id"]);
				$cname=$this->purifyString($rs["item_group_name"]);
                                
                                
                                $cdesc=$this->purifyString($rs["item_group_desc"]);
                                $cunitcost=$this->purifyString($rs["item_group_unit_cost"]);
                                
                                
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);				
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				
				
				//$sendRs[$rsCnt]=array("category_id"=>$cid,"item_group_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); //$PageSno+1,
				$sendRs[$rsCnt]=array($cname, $cdesc, $cunitcost, $updated_on, $updated_by, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateItemGroupMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteItemGroupMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
                        $sql="select item_group_id,item_group_name,item_group_desc,item_group_weight,item_group_htc,item_group_unit_cost,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_item_group_master where item_group_id=:item_group_id";
			$bindArr=array(":item_group_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
                        
                        
			$cid=$this->purifyString($recs["item_group_id"]);		
			$cname=$this->purifyString($recs["item_group_name"]);
                        
                        
                        $cdesc=$this->purifyString($recs["item_group_desc"]);
                        $cweight=$this->purifyString($recs["item_group_weight"]);
                        $chtc=$this->purifyString($recs["item_group_htc"]);
                        $cunitcost=$this->purifyString($recs["item_group_unit_cost"]);
                        
                        
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]); 
			
			$categorylist = $this->getModuleComboList('category','');
                        
                        //echo 'ok';exit();
                        
			$itemlist = $this->getModuleComboList('item',''); 
			
                        
                        
			if(!$cid)
			{
				$itmgrpdet_details = array();
			}	
			else
			{
				$sql="select item_group_det_id,item_group_id,category_id,item_id,item_quantity from bud_item_group_details_master where item_group_id=:item_group_id";
				$bindArr=array(":item_group_id"=>array("value"=>$cid,"type"=>"int"));
				$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
				$itmgrpdet_details = array(); 
				
				foreach($recs as $key=>$rs)
				{ 
					$itmgrpdet_details[$key]=array('item_group_det_id'=>$rs['item_group_det_id'], 'item_group_id'=>$rs['item_group_id'], 'category_id'=>$rs['category_id'], 'item_id'=>$rs['item_id'], 'item_quantity'=>$rs['item_quantity']);
					
				}
				
			}
			
			$sendRs=array("item_group_id"=>$cid,"item_group_name"=>$cname,"item_group_desc"=>$cdesc,"item_group_weight"=>$cweight,"item_group_htc"=>$chtc,"item_group_unit_cost"=>$cunitcost,"status"=>$cstatus,"status_desc"=>$cstatus_desc,"categorylist"=>$categorylist,"itemlist"=>$itemlist,"itmgrpdet_details"=>$itmgrpdet_details); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
            
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$item_group_name=$this->purifyInsertString($postArr["item_group_name"]);
                
                
                $item_group_desc=$this->purifyInsertString($postArr["item_group_desc"]);
                $item_group_weight=$this->purifyInsertString($postArr["item_group_weight"]);
                $item_group_htc=$this->purifyInsertString($postArr["item_group_htc"]);
                $item_group_unit_cost=$this->purifyInsertString($postArr["item_group_unit_cost"]);
                
                //print_r($item_group_desc) ;exit();
                
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);    
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_item_group_master where item_group_id=:item_group_id "; 
		$bindExtCntArr=array(":item_group_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
                
                //print_r($cnt_ext_sql);exit();
                
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_item_group_master SET item_group_name=:item_group_name ";              
                                
		$insBind=array(":item_group_name"=>array("value"=>$item_group_name,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_item_group_master where trim(item_group_name)=:item_group_name ";
		$bindExtChkArr=array(":item_group_name"=>array("value"=>$item_group_name,"dtype"=>"text")); 
		
                //print_r($sql_ext_chk) ;exit();
                
		$insuptyp="";
		if($ext_cnt_val>0) 
		{ 
			//$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where item_group_id=:item_group_id ";
			
                        $strQuery="update bud_item_group_master set item_group_name=:item_group_name , item_group_desc='".$item_group_desc."' , item_group_weight='".$item_group_weight."' , item_group_htc='".$item_group_htc."' , item_group_unit_cost='".$item_group_unit_cost."' , lastmodifiedon=now(), lastmodifiedby=:sess_user_id where item_group_id=:item_group_id ";
                    
                        
                        //print_r($strQuery);exit();
                        
                        $insBind[":item_group_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and item_group_id<>:item_group_id ";
			$bindExtChkArr[":item_group_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Item Group updated successfully!";
		}
		else
		{
			$insuptyp="insert";
			//$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
                        
                        $strQuery="INSERT INTO bud_item_group_master set item_group_name=:item_group_name , item_group_desc='".$item_group_desc."' , item_group_weight='".$item_group_weight."' , item_group_htc='".$item_group_htc."' , item_group_unit_cost='".$item_group_unit_cost."' , createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Item Group inserted successfully!";
		}
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
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
					$maxid_sql="select max(item_group_id) as maxidval from bud_item_group_master "; 
					$bindmaxidArr=array();
					$rs_qry_maxid = $this->pdoObj->fetchSingle($maxid_sql, $bindmaxidArr); 
					$id=$rs_qry_maxid["maxidval"];
				}
				
				
				//-============= Details insert/update start
				
				 
			 
			$hdn_itmgrp_detid = $postArr['hdn_itmgrp_detid']; 
			$cmb_category = $postArr['cmb_category'];
			$cmb_item = $postArr['cmb_item'];
			$txt_quantity = $postArr['txt_quantity']; 
			
			foreach($cmb_category as $cat_key=>$cat_val)
			{  
				$item_group_det_id = $this->purifyInsertString($hdn_itmgrp_detid[$cat_key]); 
				$category_id = $this->purifyInsertString($cmb_category[$cat_key]);
				$item_id = $this->purifyInsertString($cmb_item[$cat_key]);
				$item_quantity = $this->purifyInsertString($txt_quantity[$cat_key]);
				 
				
				if($category_id and $item_id and $item_quantity)
				{   
					
					$ins=" bud_item_group_details_master SET category_id=:category_id, item_id=:item_id, item_quantity=:item_quantity ";
					$insBind=array(":category_id"=>array("value"=>$category_id,"type"=>"int"),":item_id"=>array("value"=>$item_id,"type"=>"int"), ":item_quantity"=>array("value"=>$item_quantity,"type"=>"int")); 
					$mod = '';
					if($item_group_det_id>0) 
					{ 
						$strQuery="UPDATE $ins where item_group_det_id=:item_group_det_id";
						$insBind[":item_group_det_id"]=array("value"=>$item_group_det_id,"type"=>"int"); 
						$mod = 'up';
					}
					else
					{ 
						 
						$strQuery="INSERT INTO $ins, item_group_id=:item_group_id "; 						
						$insBind[":item_group_id"]=array("value"=>$id,"type"=>"int");  
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
						$strQuery=" delete from bud_item_group_details_master where item_group_det_id=:item_group_det_id  ";  						
						$bindArr=array( ":item_group_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
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
		
		$strQuery=" delete from bud_item_group_master where item_group_id=:item_group_id "; 
		$bindArr=array( ":item_group_id"=>array("value"=>$id,"dtype"=>"int"));  
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Item Group details deleted successfully'; 
			
			$strSbQuery=" delete from bud_item_group_details_master where item_group_id=:item_group_id "; 
			$bindSbArr=array( ":item_group_id"=>array("value"=>$id,"dtype"=>"int"));  
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
		  	$bindArr=array( ":item_group_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or item_group_id=:item_group_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select item_group_id,item_group_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_item_group_master where active_status = 1 $whereor order by item_group_name asc ";
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
			$_msg = "Item Group cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>