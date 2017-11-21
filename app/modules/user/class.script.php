<?php	

class user extends rapper
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
				$where = 'where (employee_code like :search_str or employee_name like :search_str)';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_user_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select user_id, employee_code, employee_name, ur.user_role_name, user_name,cat.active_status,  case cat.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_master cat left join bud_user_role_master ur on cat.user_role_id = ur.user_role_id $where order by employee_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["user_id"]);
				$employee_code=$this->purifyString($rs["employee_code"]);
				$employee_name=$this->purifyString($rs["employee_name"]);
				$user_role_name=$this->purifyString($rs["user_role_name"]);
				
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]); 
				 
				$sendRs[$rsCnt]=array($employee_code,$employee_name, $user_role_name, $cstatus_desc, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateUserMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteUserMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
				$rsCnt++;
				$PageSno++;
				
			} 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	public function getSingleView($postArr)
	{
			$getcid=$this->purifyInsertString($postArr["id"]);
			
			$sql="select user_id, user_name, employee_code, employee_name, user_address, user_city, user_state, user_postalcode, user_phone, user_email, user_role_id, active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_master where user_id=:user_id";
			$bindArr=array(":user_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$user_id=$this->purifyString($recs["user_id"]) ;
			$user_name=$this->purifyString($recs["user_name"]);
			$employee_code=$this->purifyString($recs["employee_code"]);
			$employee_name=$this->purifyString($recs["employee_name"]);
			$user_address=$this->purifyString($recs["user_address"]);
			$user_city=$this->purifyString($recs["user_city"]);
			$user_state=$this->purifyString($recs["user_state"]);
			$user_postalcode=$this->purifyString($recs["user_postalcode"]);
			$user_phone=$this->purifyString($recs["user_phone"]);
			$user_email=$this->purifyString($recs["user_email"]);
			$user_role_id=$this->purifyString($recs["user_role_id"]);
			
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]); 
			
			$userroleslist = $this->getModuleComboList('user_role');
			
			$sendRs=array("user_id"=>$user_id, "user_name"=>$user_name, "employee_code"=>$employee_code, "employee_name"=>$employee_name, "user_address"=>$user_address, "user_city"=>$user_city, "user_state"=>$user_state, "user_postalcode"=>$user_postalcode, "user_phone"=>$user_phone, "user_email"=>$user_email, "user_role_id"=>$user_role_id, "status"=>$cstatus, "status_desc"=>$cstatus_desc, "userroleslist"=>$userroleslist ); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$employee_code=$this->purifyInsertString($postArr["employee_code"]);
		$employee_name=$this->purifyInsertString($postArr["employee_name"]);
		$user_address=$this->purifyInsertString($postArr["user_address"]);
		$user_city=$this->purifyInsertString($postArr["user_city"]);
		$user_state=$this->purifyInsertString($postArr["user_state"]);
		$user_postalcode=$this->purifyInsertString($postArr["user_postalcode"]);
		$user_phone=$this->purifyInsertString($postArr["user_phone"]);
		$user_email=$this->purifyInsertString($postArr["user_email"]);
		$user_role_id=$this->purifyInsertString($postArr["user_role_id"]);
		
		$user_name=$this->purifyInsertString($postArr["user_name"]);
		$user_password=$this->purifyInsertString($postArr["user_password"]); 
		
		$user_password = $this->encrypt_password($user_password);
		
		$status=$this->purifyInsertString($postArr["user_chk_status"]); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_user_master where user_id=:user_id "; 
		$bindExtCntArr=array(":user_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_user_master SET employee_code=:employee_code, employee_name=:employee_name, user_address=:user_address, user_city=:user_city, user_state=:user_state, user_postalcode=:user_postalcode, user_phone=:user_phone, user_email=:user_email, user_role_id=:user_role_id, user_name=:user_name, active_status=:active_status ";
		
		$insBind=array(":employee_code"=>array("value"=>$employee_code,"type"=>"text"), ":employee_name"=>array("value"=>$employee_name,"type"=>"text"), ":user_address"=>array("value"=>$user_address,"type"=>"text"), ":user_city"=>array("value"=>$user_city,"type"=>"text"), ":user_state"=>array("value"=>$user_state,"type"=>"text"), ":user_postalcode"=>array("value"=>$user_postalcode,"type"=>"text"), ":user_phone"=>array("value"=>$user_phone,"type"=>"text"), ":user_email"=>array("value"=>$user_email,"type"=>"text"), ":user_role_id"=>array("value"=>$user_role_id,"type"=>"int"), ":user_name"=>array("value"=>$user_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_user_master where (trim(employee_code)=:employee_code or trim(user_name)=:user_name) ";
		$bindExtChkArr=array(":employee_code"=>array("value"=>$employee_code,"dtype"=>"text"), ":user_name"=>array("value"=>$user_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where user_id=:user_id ";
			$insBind[":user_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and user_id<>:user_id ";
			$bindExtChkArr[":user_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="User updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, user_password=:user_password, createdon=now(),createdby=:sess_user_id "; 
			$insBind[":user_password"]=array("value"=>$user_password,"type"=>"text"); 	
			$opmsg="User inserted successfully!";
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
			$userprofile_name = '';
			$userprofile_email = '';
			if($exec)
			{
				$_SESSION['sess_log_employee_name'] = $employee_name;
				$_SESSION['sess_log_employee_email'] = $user_email;
				
				$userprofile_name = $employee_name;
				$userprofile_email = $user_email;
				
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists,'userprofile_name'=>$userprofile_name, 'userprofile_email'=>$userprofile_email);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_user_master where user_id=:user_id "; 
			$bindArr=array( ":user_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='User details deleted successfully'; 
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
		  	$bindArr=array( ":user_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or user_id=:user_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select user_id, employee_code, employee_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_user_master where active_status = 1 $whereor order by employee_name asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		//$cnt_ext_sql="(select count(*) as ext_cnt, 'User referring Sub User' as msg from bud_subuser_master where user_id=:id) union all (select count(*) as ext_cnt, 'User linked to Expenses' as msg from bud_expense_details where user_id=:id) "; 
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
		
		//if($_cnt>0)
		{
			$status = 'failure';
			//$_msg = implode("\n",$msgArr);
			$_msg = "User cannot be deleted. If this is not required, please inactivate the same";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function getUserProfile($postArr)
	{
			$getcid=$_SESSION['sess_log_userid'];
			
			$sql="select user_id, employee_code, employee_name, user_address, user_city, user_state, user_postalcode,user_name, user_phone, user_email, user_role_name, user_logo_ext, company_logo_ext from bud_user_master usr left join bud_user_role_master role on usr.user_role_id = role.user_role_id where user_id=:user_id";
			$bindArr=array(":user_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$user_id=$this->purifyString($recs["user_id"]) ;
			$employee_code=$this->purifyString($recs["employee_code"]);
			$employee_name=$this->purifyString($recs["employee_name"]);
			$user_address=$this->purifyString($recs["user_address"]);
			$user_city=$this->purifyString($recs["user_city"]);
			$user_state=$this->purifyString($recs["user_state"]);
			$user_postalcode=$this->purifyString($recs["user_postalcode"]);
			$user_phone=$this->purifyString($recs["user_phone"]);
			$user_email=$this->purifyString($recs["user_email"]);
			$user_name=$this->purifyString($recs["user_name"]);
			$user_role_name=$this->purifyString($recs["user_role_name"]);
			
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]); 
			$user_logo_ext=$this->purifyString($recs["user_logo_ext"]); 
			$company_logo_ext=$this->purifyString($recs["company_logo_ext"]); 
			
			$userroleslist = $this->getModuleComboList('user_role');
			
			$dir = 'public/data/user/company/';
			$file = $dir.$user_id.'.'.$company_logo_ext;
			$companylogoUrl = '';
			if(file_exists($file))
			{
				$companylogoUrl = $file.'?'.uniqid();
			}
			
			$dir = 'public/data/user/profile/';
			$file = $dir.$user_id.'.'.$user_logo_ext;
			$userlogoUrl = '';
			if(file_exists($file))
			{
				$userlogoUrl = $file.'?'.uniqid();
			}
			
			$sendRs=array("user_id"=>$user_id, "employee_code"=>$employee_code, "employee_name"=>$employee_name, "user_address"=>$user_address, "user_city"=>$user_city, "user_state"=>$user_state, "user_postalcode"=>$user_postalcode, "user_phone"=>$user_phone, "user_email"=>$user_email, "user_role_name"=>$user_role_name, "status"=>$cstatus, "status_desc"=>$cstatus_desc, "userroleslist"=>$userroleslist, 'user_name'=>$user_name, 'companylogoUrl'=>$companylogoUrl, 'userlogoUrl'=>$userlogoUrl); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function updateUserProfile($postArr)
	{
		$id=$this->sess_userid;
		
		$employee_name=$this->purifyInsertString($postArr["employee_name"]);
		$user_address=$this->purifyInsertString($postArr["user_address"]);
		$user_city=$this->purifyInsertString($postArr["user_city"]);
		$user_state=$this->purifyInsertString($postArr["user_state"]);
		$user_postalcode=$this->purifyInsertString($postArr["user_postalcode"]);
		$user_phone=$this->purifyInsertString($postArr["user_phone"]);
		$user_email=$this->purifyInsertString($postArr["user_email"]);
		$user_role_id=$this->purifyInsertString($postArr["user_role_id"]);
		
		$status=$this->purifyInsertString($postArr["user_chk_status"]); 
		
		
		
		$ins=" bud_user_master SET employee_name=:employee_name, user_phone=:user_phone, user_email=:user_email";
		$insBind=array(":employee_name"=>array("value"=>$employee_name,"type"=>"text"),  ":user_phone"=>array("value"=>$user_phone,"type"=>"text"), ":user_email"=>array("value"=>$user_email,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where user_id=:user_id ";
			$insBind[":user_id"]=array("value"=>$id,"type"=>"text"); 
			
			$opmsg="User Profile updated successfully!";
			
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			$userprofile_logoURL = '';
			$userprofile_name = '';
			$userprofile_email = '';
			if($exec)
			{
				$_SESSION['sess_log_employee_name'] = $employee_name;
				$_SESSION['sess_log_employee_email'] = $user_email;
				
				$userprofile_name = $employee_name;
				$userprofile_email = $user_email;
				
				if(isset($_FILES['user_logo']))
				{
					$dir = 'public/data/user/profile/';
					$this->makedirectory($dir);
					
					$file = $_FILES['user_logo'];
					$name = $_FILES['user_logo']['name'];
					
					if($name)
					{
						$user_logo_ext = pathinfo($name,PATHINFO_EXTENSION);
						$new_file  =$dir.$id.'.'.$user_logo_ext;
						if(file_exists($new_file)){ unlink($new_file);}
						if(move_uploaded_file($_FILES['user_logo']['tmp_name'], $dir.$id.'.'.$user_logo_ext))
						{
							$update = "UPDATE bud_user_master SET user_logo_ext=:user_logo_ext where user_id=:user_id ";
							$insUP[":user_id"]=array("value"=>$id,"type"=>"int"); 
							$insUP[":user_logo_ext"]=array("value"=>$user_logo_ext,"type"=>"text"); 
							$exec = $this->pdoObj->execute($update, $insUP);
							
							$_SESSION['sess_user_logo'] = ($user_logo_ext)?$id.'.'.$user_logo_ext:'';
							
							$userprofile_logoURL = ($user_logo_ext)?$dir.$id.'.'.$user_logo_ext:'';
						}
					}
				}
				
				if(isset($_FILES['company_logo']))
				{
					$dir = 'public/data/user/company/';
					$this->makedirectory($dir);
					$insUP = array();
					$file = $_FILES['company_logo'];
					$name = $_FILES['company_logo']['name'];
					
					if($name)
					{
						$company_logo_ext = pathinfo($name,PATHINFO_EXTENSION);
						$new_file  =$dir.$id.'.'.$company_logo_ext;
						if(file_exists($new_file)){ unlink($new_file);}
						if(move_uploaded_file($_FILES['company_logo']['tmp_name'], $dir.$id.'.'.$company_logo_ext))
						{
							$update = "UPDATE bud_user_master SET company_logo_ext=:company_logo_ext where user_id=:user_id ";
							$insUP[":user_id"]=array("value"=>$id,"type"=>"int"); 
							$insUP[":company_logo_ext"]=array("value"=>$company_logo_ext,"type"=>"text"); 
							$exec = $this->pdoObj->execute($update, $insUP);
						}
					}
				}
				
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'logoURL'=>$userprofile_logoURL,'userprofile_name'=>$userprofile_name, 'userprofile_email'=>$userprofile_email);  
		return json_encode($sendArr);
	}
	public function encrypt_password($password)
	{
		$password = pwd_encrypt($password);
		return $password;
	} 
	
	public function __destruct() 
	{
		
	} 
}

?>