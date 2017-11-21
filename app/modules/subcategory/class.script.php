<?php	

class subcategory extends rapper
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
				$where = 'where (subcategory_name like :search_str or category_name  like :search_str)';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_subcategory_master left join bud_category_master on bud_subcategory_master.category_id = bud_category_master.category_id $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select subcategory_id,subcategory_name,bud_subcategory_master.active_status, category_name, case bud_subcategory_master.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, subcategory_display_order from bud_subcategory_master left join bud_category_master on bud_subcategory_master.category_id = bud_category_master.category_id $where order by subcategory_display_order "; 
			
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["subcategory_id"]);
				$cname=$this->purifyString($rs["subcategory_name"]);
				$mainCatg=$this->purifyString($rs["category_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$subcategory_display_order=$this->purifyString($rs["subcategory_display_order"]);
				
				
				//$sendRs[$rsCnt]=array("subcategory_id"=>$cid,"subcategory_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname,$mainCatg,$cstatus_desc, $subcategory_display_order, '<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateSubCategoryMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete"  onclick="viewDeleteSubCategoryMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
		
		$sql="select subcategory_id,subcategory_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, category_id, subcategory_display_order from bud_subcategory_master where subcategory_id=:subcategory_id";
		$bindArr=array(":subcategory_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
	 
		$cid=$this->purifyString($recs["subcategory_id"]);
	
		$cname=$this->purifyString($recs["subcategory_name"]);
		$cstatus=$this->purifyString($recs["active_status"]);
		$category_id=$this->purifyString($recs["category_id"]);
		$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
		$category = $this->getModuleComboList('category', $category_id);
		$subcategory_display_order = $this->purifyString($recs["subcategory_display_order"]);
		
		$sendRs=array("subcategory_id"=>$cid,"subcategory_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc, 'category_id'=>$category_id, 'categorylist'=>$category, 'subcategory_display_order'=>$subcategory_display_order); 
		
		$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
		
		return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$subcategory_name=$this->purifyInsertString($postArr["subcategory_name"]);
		$status=$this->purifyInsertString($postArr["subcategory_status"]);
		$category_id=$this->purifyInsertString($postArr["category_id"]);
		$subcategory_display_order=$this->purifyInsertString($postArr["subcategory_display_order"]);
		
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_subcategory_master where subcategory_id=:subcategory_id "; 
		$bindExtCntArr=array(":subcategory_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_subcategory_master SET subcategory_name=:subcategory_name,active_status=:active_status, category_id=:category_id, subcategory_display_order=:subcategory_display_order";
		$insBind=array(":subcategory_name"=>array("value"=>$subcategory_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ":category_id"=>array("value"=>$category_id,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ":subcategory_display_order"=>array("value"=>$subcategory_display_order,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_subcategory_master where trim(subcategory_name)=:subcategory_name and category_id=:category_id ";
		$bindExtChkArr=array(":subcategory_name"=>array("value"=>$subcategory_name,"dtype"=>"text"), ":category_id"=>array("value"=>$category_id,"dtype"=>"int")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where subcategory_id=:subcategory_id ";
			$insBind[":subcategory_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and subcategory_id<>:subcategory_id ";
			$bindExtChkArr[":subcategory_id"]=array("value"=>$id,"dtype"=>"int");  
			
			$opmsg="Subcategory updated successfully!"; 
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Subcategory inserted successfully!"; 
		} 
		
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		$opExists='';  
		
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
			} 
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_subcategory_master where subcategory_id=:subcategory_id "; 
			$bindArr=array( ":subcategory_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Subcategory deleted successfully'; 
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
		  	$bindArr=array( ":subcategory_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or subcategory_id=:subcategory_id";
		  }
		  if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select subcategory_id,subcategory_name,active_status, category_id,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_subcategory_master where active_status = 1 $whereor order by subcategory_display_order asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Sub category referring Budget allocation' as msg from bud_budget_allocation_details where subcategory_id=:id) union all (select count(*) as ext_cnt, 'Sub category linked to Expenses' as msg from bud_expense_details where subcategory_id=:id) "; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); 
		
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
			$_msg = "Sub category cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>