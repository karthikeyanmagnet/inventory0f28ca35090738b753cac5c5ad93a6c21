<?php	

class employee extends rapper
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
				$where = 'where employee_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_employee_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select employee_id,employee_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_employee_master $where order by employee_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["employee_id"]);
				$cname=$this->purifyString($rs["employee_name"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				
				
				//$sendRs[$rsCnt]=array("employee_id"=>$cid,"employee_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1,$cname,$cstatus_desc, '<span class="edit js-open-modal" data-modal-id="popup1" onclick="CreateUpdateEmployeeMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete"  onclick="viewDeleteEmployeeMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select employee_id,employee_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, employee_doj, designation_id  from bud_employee_master where employee_id=:employee_id";
			$bindArr=array(":employee_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["employee_id"]);
		
			$cname=$this->purifyString($recs["employee_name"]);
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$employee_doj=$this->convertDate($this->purifyString($recs["employee_doj"]));
			$designation_id = $this->purifyString($recs["designation_id"]);
			
			$designation = $this->getModuleComboList('designation', $designation_id);
			
			$sendRs=array("employee_id"=>$cid,"employee_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc, 'designation_id'=>$designation_id, 'designationlist'=>$designation, "employee_doj"=>$employee_doj); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$employee_name=$this->purifyInsertString($postArr["employee_name"]);
		$status=$this->purifyInsertString($postArr["employee_status"]);
		$designation_id=$this->purifyInsertString($postArr["designation_id"]);
		$employee_doj=$this->convertDate($this->purifyInsertString($postArr["employee_doj"]));
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_employee_master where employee_id=:employee_id "; 
		$bindExtCntArr=array(":employee_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_employee_master SET employee_name=:employee_name,active_status=:active_status,designation_id=:designation_id,employee_doj=:employee_doj";
		$insBind=array(":employee_name"=>array("value"=>$employee_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ":designation_id"=>array("value"=>$designation_id,"type"=>"int"), ":employee_doj"=>array("value"=>$employee_doj,"type"=>"text"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_employee_master where trim(employee_name)=:employee_name ";
		$bindExtChkArr=array(":employee_name"=>array("value"=>$employee_name,"dtype"=>"text")); 
		
		$insUptype="";
		if($ext_cnt_val>0) 
		{ 
			$insUptype="update";
			
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where employee_id=:employee_id ";
			$insBind[":employee_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and employee_id<>:employee_id ";
			$bindExtChkArr[":employee_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Employee updated successfully!";
		}
		else
		{
			$insUptype="insert";
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Employee inserted successfully!";
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
				if($insUptype=="update" and $id)
				{
					$upPassEmpupArr=array('employee_id'=>$id,'employee_name'=>$employee_name);	
					$this->updateEMployeeNamesInOtherMasters($upPassEmpupArr);
				}
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	public function updateEMployeeNamesInOtherMasters($postArr)
	{
		$employee_id=$postArr["employee_id"];
		$employee_name=$postArr["employee_name"];
		
		if($employee_id)
		{
			$bindArr=array(); 
		
			$strQuery=" Update bud_user_master set user_display_name=:employee_name,lastmodifiedon=now(),lastmodifiedby=:sess_user_id where employee_id=:employee_id and user_type in (2,3) "; 
			$bindArr=array( ":employee_name"=>array("value"=>$employee_name,"dtype"=>"text"), ":employee_id"=>array("value"=>$employee_id,"dtype"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr); 
		} 
	}
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_employee_master where employee_id=:employee_id "; 
			$bindArr=array( ":employee_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Employee details deleted successfully'; 
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
		  	$bindArr=array( ":employee_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or employee_id=:employee_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select employee_id,employee_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_employee_master where active_status = 1 $whereor order by employee_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		/*$cnt_ext_sql="(select count(*) as ext_cnt, 'Employee referring Sub Employee' as msg from bud_subemployee_master where employee_id=:id) union all (select count(*) as ext_cnt, 'Employee linked to Expenses' as msg from bud_expense_details where employee_id=:id) "; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); 
		
		
		foreach($rs_qry_exts as $rsop)
		{
			if($rsop['ext_cnt']>0)
			{
				$msgArr[] = $rsop['msg'];
				$_cnt++;
			}
		}*/
		
		$_cnt = 0;
		$status = 'success';
		$msgArr = array();
		$_msg = 'Do you want to delete?';
		
		if($_cnt>0)
		{
			$status = 'failure';
			//$_msg = implode("\n",$msgArr);
			$_msg = "Employee cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	
	public function __destruct() 
	{
		
	} 
}

?>