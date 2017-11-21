<?php	

class category extends rapper
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
				$where = 'where category_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_category_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select category_id,category_name,cat.active_status,  case cat.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc,   if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(cat.lastmodifiedon, '%d %b %Y'), date_format(cat.createdon, '%d %b %Y')) as updated_on from bud_category_master cat left join bud_user_master usr_crt on cat.createdby = usr_crt.user_id left join bud_user_master usr_upd on cat.lastmodifiedby = usr_upd.user_id $where order by category_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["category_id"]);
				$cname=$this->purifyString($rs["category_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]); 
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				
				
				//$sendRs[$rsCnt]=array("category_id"=>$cid,"category_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); //$PageSno+1,
				$sendRs[$rsCnt]=array($cname,$cstatus_desc, $updated_on, $updated_by, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateCategoryMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteCategoryMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select category_id,category_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_category_master where category_id=:category_id";
			$bindArr=array(":category_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["category_id"]);
		
			$cname=$this->purifyString($recs["category_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			 
			
			$sendRs=array("category_id"=>$cid,"category_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$category_name=$this->purifyInsertString($postArr["category_name"]);
		$status=$this->purifyInsertString($postArr["category_chk_status"]); 
		$from=$this->purifyInsertString($postArr["from"]); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_category_master where category_id=:category_id "; 
		$bindExtCntArr=array(":category_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_category_master SET category_name=:category_name,active_status=:active_status";
		$insBind=array(":category_name"=>array("value"=>$category_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_category_master where trim(category_name)=:category_name ";
		$bindExtChkArr=array(":category_name"=>array("value"=>$category_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where category_id=:category_id ";
			$insBind[":category_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and category_id<>:category_id ";
			$bindExtChkArr[":category_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Category updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Category inserted successfully!";
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
			} 
		}
		
		if($from == 'other')
		{
			$data = $this->lastInsertData($category_name);
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists, 'data'=>$data);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_category_master where category_id=:category_id "; 
			$bindArr=array( ":category_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Category details deleted successfully'; 
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
		  	$bindArr=array( ":category_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or category_id=:category_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select category_id,category_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_category_master where active_status = 1 $whereor order by category_name asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Category referring Item master' as msg from bud_item_master where category_id=:id) "; 
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
			$_msg = "The record can't be deleted as it has been mapped with other records. If this is not required, please inactivate the same";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function lastInsertData($name)
	{
		 $sql="select category_id,category_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_category_master where active_status = 1 and category_name = :name order by category_id desc ";
		 $bindArr=array(":name"=>array("value"=>$name,"type"=>"text"));
		 //echo $sql.json_encode($bindArr);
		 $recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 return $recs;
	}
	
	public function __destruct() 
	{
		
	} 
}

?>