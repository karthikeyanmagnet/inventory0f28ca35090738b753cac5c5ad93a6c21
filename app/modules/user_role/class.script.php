<?php	

class user_role extends rapper
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
		   
		  $tot_sql="select count(*) as cnt from bud_assign_role asn_role left join bud_user_role_master usrole on asn_role.user_role_id = usrole.user_role_id $where "; 
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select asn_role.user_role_id,user_role_name, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(asn_role.lastmodifiedon, '%d %b %Y'), date_format(asn_role.createdon, '%d %b %Y')) as updated_on from bud_assign_role asn_role left join bud_user_role_master usrole on asn_role.user_role_id = usrole.user_role_id left join bud_user_master usr_crt on asn_role.createdby = usr_crt.user_id left join bud_user_master usr_upd on asn_role.lastmodifiedby = usr_upd.user_id $where order by user_role_name "; 
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
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				
				
				//$sendRs[$rsCnt]=array("user_role_id"=>$cid,"user_role_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($cname,$updated_on, $updated_by, '<span class="edit act-edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateUserRoleMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete  act-delete"  onclick="viewDeleteUserRoleMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
				$rsCnt++;
				$PageSno++;
				
			}
			
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	public function getSingleView($postArr)
	{
			$getcid= (int) $this->purifyInsertString($postArr["id"]);
			
			$sql="select user_role_id,user_role_name,active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_role_master where user_role_id=:user_role_id";
			$bindArr=array(":user_role_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["user_role_id"]);
		
			$cname=$this->purifyString($recs["user_role_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			//$approve_expense=$this->purifyString($recs["approve_expense"]);
			//$expense_to_others=$this->purifyString($recs["expense_to_others"]);
			//$payslip_report_to_others=$this->purifyString($recs["payslip_report_to_others"]);
			$roles = $this->getModuleComboList('role', $cid);
			
			$sql = "select * from bud_user_role_modules where user_role_id=:user_role_id";
			$user_mod_actions = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			$sendRs=array("user_role_id"=>$cid,"user_role_name"=>$cname,"status"=>$cstatus,"roles"=>$roles, 'user_mod_actions'=>$user_mod_actions); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return ($sendArr);
	}
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$user_role_id=$this->purifyInsertString($postArr["user_role_id"]); 
		
				
		$cnt_ext_sql="select count(*) as ext_cnt from bud_assign_role where user_role_id=:user_role_id "; 
		$bindExtCntArr=array(":user_role_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_assign_role SET user_role_id=:user_role_id ";
		$insBind=array(':user_role_id'=>array("value"=>$user_role_id,"dtype"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_assign_role where trim(user_role_id)=:user_role_id ";
		$bindExtChkArr=array(":user_role_id"=>array("value"=>$user_role_id,"dtype"=>"int")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where user_role_id=:user_role_id ";
			$insBind[":user_role_id"]=array("value"=>$user_role_id,"type"=>"text"); 
			
			$sql_ext_chk .= " and user_role_id<>:user_role_id ";
			$bindExtChkArr[":user_role_id"]=array("value"=>$user_role_id,"dtype"=>"int");  
			$opmsg="User Role Permission updated successfully!";
			
			$insUpType = "update";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="User Role Permission inserted successfully!";
			
			$insUpType = "insert";
		}
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Role already exists'; 
			$opExists='exists';
		} 
		else 
		{
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			if($exec)
			{
				$opStatus='success';
				$opMessage=$opmsg; 
				
				/*if($insUpType=="insert")
				{
					$sql_max="select max(user_role_id) as max_id from bud_user_role_master ";
					$rs_max = $this->pdoObj->fetchSingle($sql_max, '');  
					$max_id	=	$rs_max['max_id'];
					
					$id=$max_id;  
				}*/
				$module_id = $postArr['module_id'];
				$module_actions = $postArr['module_actions'];
				foreach($module_id as $key=>$modid)
				{
					$modid = $this->purifyString($modid);
					$mod_actions = $module_actions[$key];
					$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_user_role_modules where (user_role_id)=:user_role_id and module_id=:module_id  ";
					$bindExtChkArr=array(":module_id"=>array("value"=>$modid,"dtype"=>"int"),":user_role_id"=>array("value"=>$user_role_id,"dtype"=>"int")); 
					
					$ins=" bud_user_role_modules SET user_role_id=:user_role_id,module_id=:module_id,module_actions=:module_actions";
					$insBind=array(":user_role_id"=>array("value"=>$user_role_id,"type"=>"int"), ":module_id"=>array("value"=>$modid,"type"=>"int"), ":module_actions"=>array("value"=>$mod_actions,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int") );
					
					$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
					$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt']; 
					if($rec_exist_cnt_val>0) 
					{ 
						$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where (user_role_id)=:user_role_id and module_id=:module_id";
						//$insBind[":payroll_id"]=array("value"=>$id,"type"=>"text"); 
						
					}
					else
					{
						$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
					}	
					
					$exec = $this->pdoObj->execute($strQuery, $insBind);
				}
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function OLDsaveprocessOLD($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$user_role_id=$this->purifyInsertString($postArr["user_role_id"]); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_assign_role where user_role_id=:user_role_id "; 
		$bindExtCntArr=array(":user_role_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_assign_role SET user_role_id=:user_role_id ";
		$insBind=array(':user_role_id'=>array("value"=>$user_role_id,"dtype"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_assign_role where trim(user_role_id)=:user_role_id ";
		$bindExtChkArr=array(":user_role_id"=>array("value"=>$user_role_id,"dtype"=>"int")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where user_role_id=:user_role_id ";
			$insBind[":user_role_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and user_role_id<>:user_role_id ";
			$bindExtChkArr[":user_role_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="User Role Permission updated successfully!";
			
			$insUpType = "update";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="User Role Permission inserted successfully!";
			
			$insUpType = "insert";
		}
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Role already exists'; 
			$opExists='exists';
		} 
		else 
		{
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			if($exec)
			{
				$opStatus='success';
				$opMessage=$opmsg; 
				
				if($insUpType=="insert")
				{
					$sql_max="select max(user_role_id) as max_id from bud_user_role_master ";
					$rs_max = $this->pdoObj->fetchSingle($sql_max, '');  
					$max_id	=	$rs_max['max_id'];
					
					$id=$max_id;  
				}
				$module_id = $postArr['module_id'];
				$module_actions = $postArr['module_actions'];
				foreach($module_id as $key=>$modid)
				{
					$modid = $this->purifyString($modid);
					$mod_actions = $module_actions[$key];
					$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_user_role_modules where (user_role_id)=:user_role_id and module_id=:module_id  ";
					$bindExtChkArr=array(":module_id"=>array("value"=>$modid,"dtype"=>"int"),":user_role_id"=>array("value"=>$id,"dtype"=>"int")); 
					
					$ins=" bud_user_role_modules SET user_role_id=:user_role_id,module_id=:module_id,module_actions=:module_actions";
					$insBind=array(":user_role_id"=>array("value"=>$id,"type"=>"int"), ":module_id"=>array("value"=>$modid,"type"=>"int"), ":module_actions"=>array("value"=>$mod_actions,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int") );
					
					$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
					$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt']; 
					if($rec_exist_cnt_val>0) 
					{ 
						$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where (user_role_id)=:user_role_id and module_id=:module_id";
						//$insBind[":payroll_id"]=array("value"=>$id,"type"=>"text"); 
						
					}
					else
					{
						$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
					}	
					
					$exec = $this->pdoObj->execute($strQuery, $insBind);
				}
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_assign_role where user_role_id=:user_role_id "; 
			$bindArr=array( ":user_role_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$strQuery=" delete from bud_user_role_modules where user_role_id=:user_role_id ";
			$exec = $this->pdoObj->execute($strQuery, $bindArr); 
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='User Role details deleted successfully'; 
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
		  $sql="select user_role_id,user_role_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_role_master where active_status = 1 $whereor order by user_role_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'User role referring User master' as msg from bud_user_master where user_role_id=:id) "; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr);  
		//echo $cnt_ext_sql.json_encode($bindExtCntArr);
		
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
	
	
	
	public function __destruct() 
	{
		
	} 
}

?>