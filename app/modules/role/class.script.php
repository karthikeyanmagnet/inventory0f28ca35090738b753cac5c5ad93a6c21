<?php	

class role extends rapper
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
				$where = 'where user_role_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_user_role_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select cat.user_role_id,user_role_name,cat.active_status,  case cat.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(cat.lastmodifiedon, '%d %b %Y'), date_format(cat.createdon, '%d %b %Y')) as updated_on from bud_user_role_master cat left join bud_user_master usr_crt on cat.createdby = usr_crt.user_id left join bud_user_master usr_upd on cat.lastmodifiedby = usr_upd.user_id $where order by user_role_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["user_role_id"]);
				$cname=$this->purifyString($rs["user_role_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$role_display_order = $this->purifyString($rs["role_display_order"]);
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				
				
				//$sendRs[$rsCnt]=array("user_role_id"=>$cid,"user_role_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); //$PageSno+1,
				$sendRs[$rsCnt]=array($cname,$cstatus_desc, $updated_on, $updated_by, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateRoleMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteRoleMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select user_role_id,user_role_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_role_master where user_role_id=:user_role_id";
			$bindArr=array(":user_role_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["user_role_id"]);
		
			$cname=$this->purifyString($recs["user_role_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$role_display_order = $this->purifyString($recs["role_display_order"]);
			
			$sendRs=array("user_role_id"=>$cid,"user_role_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc,"role_display_order"=>$role_display_order); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$user_role_name=$this->purifyInsertString($postArr["role_name"]);
		$status=$this->purifyInsertString($postArr["role_chk_status"]);
		$role_display_order=$this->purifyInsertString($postArr["role_display_order"]);
		$from=$this->purifyInsertString($postArr["from"]); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_user_role_master where user_role_id=:user_role_id "; 
		$bindExtCntArr=array(":user_role_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_user_role_master SET user_role_name=:user_role_name,active_status=:active_status";
		$insBind=array(":user_role_name"=>array("value"=>$user_role_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_user_role_master where trim(user_role_name)=:user_role_name ";
		$bindExtChkArr=array(":user_role_name"=>array("value"=>$user_role_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where user_role_id=:user_role_id ";
			$insBind[":user_role_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and user_role_id<>:user_role_id ";
			$bindExtChkArr[":user_role_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Role updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Role inserted successfully!";
		}
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		//echo $sql_ext_chk.json_encode($bindExtChkArr);
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
			$data = $this->lastInsertData($user_role_name);
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists, 'data'=>$data);  
		
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_user_role_master where user_role_id=:user_role_id "; 
			$bindArr=array( ":user_role_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Role details deleted successfully'; 
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
		  	$bindArr=array( ":user_role_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or user_role_id=:user_role_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select user_role_id,user_role_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_role_master where active_status = 1 $whereor order by user_role_name asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'User role referring User master' as msg from bud_user_master where user_role_id=:id) union all (select count(*) as ext_cnt, 'User role referring Roles assigning module' as msg from bud_user_role_modules where user_role_id=:id) union all (select count(*) as ext_cnt, 'User role referring Roles assigning head' as msg from bud_assign_role where user_role_id=:id) "; 
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
		 $sql="select user_role_id as id,user_role_name as name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_role_master where active_status = 1 and user_role_name = :name order by id desc ";
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