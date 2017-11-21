<?php
	ini_set('display_errors',0);   
	
	define(restrictCallPages,'AllowFromOnlyMain'); 
	 
	$ProjDir = dirname(__DIR__);
	$ProjFile = dirname(__FILE__);
	
	
	include $ProjFile."/class_htmlpurifier.php";
	include $ProjFile.'/class.pdo.php';
	include $ProjFile.'/encrypt_string.php';
	
	class rapper
	{
		public $pdoObj=null;
		public $htmpurify = null;
		
		public function __construct()
		{  
			$this->pdoObj 	= 	new PDOClass(); 
			$this->moduleDir = dirname(__DIR__).'/modules/';
			$this->htmpurify 	= 	new htmlpuri_obj(); 
			$this->ProjFile		= dirname(__FILE__);
			
			$this->shwYearDropDownFrom='2014';
			$this->getSessionDetails();
			//$this->rfq_url = 'http://eng.sigmaco.com/';   //live
			$this->rfq_url = 'http://localhost/projects/rfq/public/';	//local
		}
		public function getSessionDetails()
		{
			@session_start();  
			
			$this->sess_userid 				= ($_SESSION['sess_log_userid'])?intval($_SESSION['sess_log_userid']):0;
			$this->sess_username 			= ($_SESSION['sess_log_username'])?$_SESSION['sess_log_username']:'';
			$this->sess_logintype 			= ($_SESSION['sess_log_usertype'])?intval($_SESSION['sess_log_usertype']):0;  
			$this->sess_log_employee_id 	= ($_SESSION['sess_log_employee_id'])?intval($_SESSION['sess_log_employee_id']):0;
			$this->sess_log_user_role 		= ($_SESSION['sess_log_user_role'])?intval($_SESSION['sess_log_user_role']):0;
		}
		
		public function convertDate($date)
		{
			$conv_date="";
			if($date=='' || $date=='0000-00-00')
			{
				$conv_date="";
			}
			else
			{
				$split=explode("-",$date);
				
				if(strlen($split[0])!=4)
				{  
					$conv_date=date('Y-m-d',strtotime($date));	
				}
				elseif(strlen($split[2])>4)
				{
					$conv_date=date('d-m-Y H:i',strtotime($date)); 
				}
				else
				{ 
					$conv_date=date('d-m-Y',strtotime($date));	
				}
				
				if ($conv_date=="--") $conv_date="";
				
			}
			return $conv_date;
		}
		public function purifyInsertString($data)
		{		
			//$data = $this->htmpurify->purifier->purify($data);
			return trim($data);
		} 
		
		public function purifyString($data)
		{ 	
			//$data = $this->htmpurify->purifier->purify($data);
			
			/*$data = html_entity_decode($data);
			
			$data = str_replace('"','&quot;',$data);
			$data = str_replace("'",'&apos;',$data);*/
			
			$data = $this->htmpurify->purifier->purify($data);
			
			$data = html_entity_decode($data);
	
						
			return $data;
		} 
		
		
		// Function to get the client IP address
		function get_client_ip() {
			$ipaddress = '';
			if ($_SERVER['HTTP_CLIENT_IP'])
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if($_SERVER['HTTP_X_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if($_SERVER['HTTP_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if($_SERVER['HTTP_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
		
		public function getModuleView($module, $view)
		{
			$templateFile = '';
			if($view)
			$templateFile = $this->moduleDir.$module.'/'.$view.'.php';
			
			return $templateFile;
			
		}
		
		public function getModuleProcess($module)
		{
			$templateFile = '';
			if($module)
			$templateFile = $this->moduleDir.$module.'/process_control.php';
			
			return $templateFile;
			
		}
		
		public function getModule($module)
		{	
			$templateFile = $this->moduleDir.$module.'/class.script.php';
			
			if(is_file($templateFile))
			{
				include_once($templateFile);
				
				$obj = new $module;	
				
				return $obj;
			}
		}
		//dialog
		public function getModuleList($module)
		{
			$obj = $this->getModule($module);
			if($obj)
			{
				$list = $obj->listview();	
				return $list;
			}
		
		}
		
		//dropdown
		public function getModuleComboList($module, $id)
		{
			$obj = $this->getModule($module);
			if($obj)
			{
				$list = $obj->comboview($id);	
				return $list;
			}
		
		}
		
		
		function makedirectory($up)
		{
			$splt=explode("/",$up);
			$pth = "";
			for($i=0;$i<=sizeof($splt);$i++)
			{
				$pth=$pth.$splt[$i]. "/";
				if(!is_dir($pth)){
						if(!mkdir($pth,0777)){
						 echo "<script language='javascript'>
								alert('Permission denied to create directory');
								</script>";
							return false;				
		
						}
				}
				
			}
			return true;
	
		
		}
		
		function deleteDirectory($dir) { 
		if (!file_exists($dir)) return true; 
		if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
			foreach (scandir($dir) as $item) { 
				if ($item == '.' || $item == '..') continue; 
				if (!$this->deleteDirectory($dir . "/" . $item)) { 
					chmod($dir . "/" . $item, 0777); 
					if (!$this->deleteDirectory($dir . "/" . $item)) return false; 
				}; 
			} 
			return rmdir($dir); 
		} 
		
		//================================ Sample code. 
		
		
		public function getCourseMasterList()
		{
			$sql="select COURSE_ID,COURSE_CODE,COURSE_NAME,COURSE_STATUS, case COURSE_STATUS when 1 then 'Active' else 'Inactive' end as COURSE_STATUS_DESC from ACAD_COURSE_MASTER order by COURSE_NAME ";
			$bindArr=array();
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			 
			$sendRs=array();
			
			$rsCnt=0;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["COURSE_ID"]);
				$ccode=$this->purifyString($rs["COURSE_CODE"]);
				$cname=$this->purifyString($rs["COURSE_NAME"]);
				$cstatus=$this->purifyString($rs["COURSE_STATUS"]);
				$cstatus_desc=$this->purifyString($rs["COURSE_STATUS_DESC"]);
				
				
				$sendRs[$rsCnt]=array("COURSE_ID"=>$cid,"COURSE_CODE"=>$ccode,"COURSE_NAME"=>$cname,"COURSE_STATUS"=>$cstatus,"COURSE_STATUS_DESC"=>$cstatus_desc,);
				$rsCnt++;
				
			}
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			
			
			return json_encode($sendArr);
		}
		
		public function getCourseMasterView($postArr)
		{
			$getcid=$this->purifyInsertString($postArr["id"]);
			
			$sql="select COURSE_ID,COURSE_CODE,COURSE_NAME,COURSE_STATUS, case COURSE_STATUS when 1 then 'Active' else 'Inactive' end as COURSE_STATUS_DESC from ACAD_COURSE_MASTER where COURSE_ID=:COURSE_ID";
			$bindArr=array(":COURSE_ID"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["COURSE_ID"]);
			$ccode=$this->purifyString($recs["COURSE_CODE"]);
			$cname=$this->purifyString($recs["COURSE_NAME"]);
			$cstatus=$this->purifyString($recs["COURSE_STATUS"]);
			$cstatus_desc=$this->purifyString($recs["COURSE_STATUS_DESC"]);
			
			
			$sendRs=array("COURSE_ID"=>$cid,"COURSE_CODE"=>$ccode,"COURSE_NAME"=>$cname,"COURSE_STATUS"=>$cstatus,"COURSE_STATUS_DESC"=>$cstatus_desc,); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
		} 
		public function saveCourseMasterView($postArr)
		{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			$course_code=$this->purifyInsertString($postArr["course_code"]);
			$course_name=$this->purifyInsertString($postArr["course_name"]);
			$course_status=$this->purifyInsertString($postArr["course_status"]); 
			
			$cnt_ext_sql="select count(*) as ext_cnt from ACAD_COURSE_MASTER where COURSE_ID=:COURSE_ID "; 
			$bindExtCntArr=array(":COURSE_ID"=>array("value"=>$id,"type"=>"int"));
			$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
			$ext_cnt_val=$rs_qry_exts["ext_cnt"];
			
			$ins=" ACAD_COURSE_MASTER SET COURSE_CODE=:COURSE_CODE,COURSE_NAME=:COURSE_NAME,COURSE_STATUS=:COURSE_STATUS ";
			$insBind=array(":COURSE_CODE"=>array("value"=>$course_code,"type"=>"text"), ":COURSE_NAME"=>array("value"=>$course_name,"type"=>"text"), ":COURSE_STATUS"=>array("value"=>$course_status,"type"=>"text")); 
			
			if($ext_cnt_val>0) 
			{ 
				$strQuery="UPDATE $ins, LASTMODIFIEDBY=1,LASTMODIFIEDON=now() where COURSE_ID=:COURSE_ID ";
				$insBind[":COURSE_ID"]=array("value"=>$id,"type"=>"text"); 
				 
			}
			else
			{
				$strQuery="INSERT INTO $ins, CREATEDBY=1,CREATEDON=now() "; 
			} 
			
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Record inserted successfully'; 
			} 
			
			$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
			
			return json_encode($sendArr);
		}
		
		public function _UrlEncode($string) {
			$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
			$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
			return str_replace($entities, $replacements, ($string));
		}
		
		public function getMenuModuleList()
		{
			$bindArr=array();
			$sql = "select main.module_id as main_module_id, sub.module_id as sub_module_id, main.module_name as main_module_name, sub.module_name as sub_module_name, main.parent_module_id as main_parent, sub.parent_module_id as sub_parent, main.module_display_order as main_order, sub.module_display_order sub_order, if(isnull(sub.module_id),main.module_id,sub.module_id) as module_id, if(isnull(sub.module_actions),main.module_actions,sub.module_actions) as module_actions, if(isnull(sub.module_id),main.js_call_identify,sub.js_call_identify) as js_call_identify, main.module_icon_class as main_icon_class, sub.module_icon_class as sub_icon_class from bud_modules_list as main
	left join bud_modules_list as sub on sub.parent_module_id=main.module_id  
	where  main.parent_module_id=0 order by main.module_display_order, sub.module_display_order ";
			 $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			 return $recs;
			
		}
		
		public function getMenuModulePrevilegeList()
		{
			$bindArr=array(":user_role_id"=>array("value"=>$this->sess_log_user_role,"type"=>"int"));
			
			$sql = "select main.module_id as main_module_id, sub.module_id as sub_module_id, main.module_name as main_module_name, sub.module_name as sub_module_name, main.parent_module_id as main_parent, sub.parent_module_id as sub_parent, main.module_display_order as main_order, sub.module_display_order sub_order, if(isnull(sub.module_id),main.module_id,sub.module_id) as module_id, if(isnull(sub.module_actions),main.module_actions,sub.module_actions) as module_actions, if(isnull(sub.module_id),main.js_call_identify,sub.js_call_identify) as js_call_identify, main.module_icon_class as main_icon_class, sub.module_icon_class as sub_icon_class, main_pre.module_actions as main_actions, sub_pre.module_actions as sub_actions from bud_modules_list as main
	left join bud_modules_list as sub on sub.parent_module_id=main.module_id
	left join bud_user_role_modules as main_pre on main.module_id = main_pre.module_id and main_pre.user_role_id=:user_role_id 
	left join bud_user_role_modules as sub_pre on sub.module_id = sub_pre.module_id and sub_pre.user_role_id=:user_role_id
	where  main.parent_module_id=0 
	order by main.module_display_order, sub.module_display_order ";
	
			//$bindArr=array();
			
			//$sql = "select main.id as main_module_id, sub.id as sub_module_id,main.description as main_module_name, sub.display_name as sub_module_name, sub.name as js_call_identify, main.icon as main_icon_class, sub.icon as sub_icon_class, sub.type from rfq_modules sub left join rfq_modules main on sub.parent_id = main.id  where  main.menu_status=1 order by sub.menu_display_order asc";
			
			//$sql = "select main.id as main_module_id, sub.id as sub_module_id,main.display_name as main_module_name, sub.display_name as sub_module_name, sub.name as js_call_identify from rfq_modules sub left join rfq_modules main on sub.parent_id = main.id where sub.parent_id in (select id from rfq_modules WHERE parent_id = 0 and  display_name like '%budget%')";
			//echo $sql.json_encode($bindArr);
			 $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			 return $recs;
			
		}
		
		public function getCategoryID($category_name)
		{
			$sql = "select count(*) as cnt, b.category_id from bud_category_master b where b.category_name = '$category_name' and b.active_status=1";
			$bindArr=array();
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs['cnt']>0)
			{
				return $recs['category_id'];
			}
			else
			{
				$status = 1;
				$ins=" bud_category_master SET category_name=:category_name,active_status=:active_status";
				$insBind=array(":category_name"=>array("value"=>$category_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ':sess_user_id'=>array("value"=>$_SESSION['sess_log_userid'],"type"=>"int"));
				
				$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id ";
				
				$exec = $this->pdoObj->execute($strQuery, $insBind);
				
				if($exec)
				{
					$sql = "select count(*) as cnt, b.category_id from bud_category_master b where b.category_name = '$category_name' and b.active_status=1";
					$bindArr=array();
					$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					if($recs['cnt']>0)
					{
						return $recs['category_id'];
					}
				}  
			}
		}
		
		public function getSubCategoryID($category_name, $category_id)
		{
			$sql = "select count(*) as cnt, b.subcategory_id from bud_subcategory_master b where b.subcategory_name = '$category_name' and category_id=:category_id and b.active_status=1";
			$bindArr=array(":category_id"=>array("value"=>$category_id,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs['cnt']>0)
			{
				return $recs['subcategory_id'];
			}
			else
			{
				$status = 1;
				$ins=" bud_subcategory_master SET subcategory_name=:subcategory_name,active_status=:active_status,category_id=:category_id";
				$insBind=array(":subcategory_name"=>array("value"=>$category_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ':sess_user_id'=>array("value"=>$_SESSION['sess_log_userid'],"type"=>"int"),":category_id"=>array("value"=>$category_id,"type"=>"int"));
				
				$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id ";
				
				$exec = $this->pdoObj->execute($strQuery, $insBind);
				
				if($exec)
				{
					$sql = "select count(*) as cnt, b.subcategory_id from bud_subcategory_master b where b.subcategory_name = '$category_name' and category_id=:category_id and b.active_status=1";
					$bindArr=array(":category_id"=>array("value"=>$category_id,"type"=>"int"));
					$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					if($recs['cnt']>0)
					{
						return $recs['subcategory_id'];
					}
				}  
			}
		}
		
		public function getUserPervilageSettings()
		{
			$sql = "select approve_previlage,view_to_others from bud_user_master where user_id=:user_id";
			$bindArr=array(":user_id"=>array("value"=>$this->sess_userid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			return $recs;
		}
		
		public function getEmployeeList()
		{
			$user_previlage = $this->getUserPervilageSettings();
			$bindEmplArr=array();
			$strEmp = '';
			if(!$user_previlage['view_to_others'])
			{
				$bindArr=array(":user_id"=>array("value"=>$this->sess_userid,"type"=>"int"));
				$strEmp = ' where user_id=:user_id';
			}	
			$sqlEmpl="select um.user_id, um.user_display_name from bud_user_master as um $strEmp order by um.user_display_name";
			
			$recempl = $this->pdoObj->fetchMultiple($sqlEmpl, $bindArr); 
			
			 
			$emplArr=array(); 
			foreach($recempl as $empl_val)
			{
				$m_user_id=$empl_val['user_id']; 
				$m_user_display_name=$this->purifyString($empl_val['user_display_name']); 
				
				$emplArr[] = array('user_id'=>$m_user_id, 'user_display_name'=>$m_user_display_name);  
			}
			
			return $emplArr;
		}
		
		public function rfq_menus()
		{
			$action_menu_rfq = array('role_management'=>$this->rfq_url.'masters/user_group/list', 'customer_managment'=>$this->rfq_url.'masters/us_counter_part_master/list', 'user_managment'=>$this->rfq_url.'masters/employee_management/list', 'supplier_management'=>$this->rfq_url.'masters/supplier_mgmt/list', 'process_type'=>$this->rfq_url.'masters/type_master/list', 'department'=>$this->rfq_url.'masters/department_master/list', 'designation'=>$this->rfq_url.'masters/destination_master/list', 'country'=>$this->rfq_url.'masters/country_master/list', 'audit_template'=>$this->rfq_url.'audit_template/audit_template_info', 'rfq_dashboard'=>$this->rfq_url,'rfq_register'=>$this->rfq_url.'transaction/rfq_register/list', 'report'=>$this->rfq_url.'Report');
			
			$arrRFQ = array(array('main_module_name' =>'RFQ Dashboard', 'sub_module_name' => '', 'js_call_identify' => $this->rfq_url, 'main_icon_class' =>'fa fa-home', 'sub_icon_class' => 'fa fa-dashboard', 'chk_action'=>''),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'User Group', 'js_call_identify' => $this->rfq_url.'masters/user_group/list', 'main_icon_class' =>'fa fa-shopping-cart', 'sub_icon_class' => '','chk_action'=>'role_management'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Customer Name', 'js_call_identify' => $this->rfq_url.'masters/us_counter_part_master/list', 'main_icon_class' =>'', 'sub_icon_class' => '','chk_action'=>'customer_managment'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Employee Management', 'js_call_identify' => $this->rfq_url.'masters/employee_management/list', 'main_icon_class' =>'', 'sub_icon_class' => '','chk_action'=>'user_managment'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Supplier Management', 'js_call_identify' => $this->rfq_url.'masters/supplier_mgmt/list', 'main_icon_class' =>'', 'sub_icon_class' => '', 'chk_action'=>'supplier_management'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Process Master', 'js_call_identify' => $this->rfq_url.'masters/type_master/list', 'main_icon_class' =>'', 'sub_icon_class' => '', 'chk_action'=>'process_type'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Department Master', 'js_call_identify' => $this->rfq_url.'masters/department_master/list', 'main_icon_class' =>'', 'sub_icon_class' => '','chk_action'=>'department'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Designation Master', 'js_call_identify' => $this->rfq_url.'masters/destination_master/list', 'main_icon_class' =>'', 'sub_icon_class' => '','chk_action'=>'designation'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Country Master', 'js_call_identify' => $this->rfq_url.'masters/country_master/list', 'main_icon_class' =>'', 'sub_icon_class' => '', 'chk_action'=>'country'),
		array('main_module_name' =>'RFQ Masters', 'sub_module_name' => 'Audit Template', 'js_call_identify' => $this->rfq_url.'audit_template/audit_template_info', 'main_icon_class' =>'', 'sub_icon_class' => '','chk_action'=>'audit_template'),
		array('main_module_name' =>'RFQ Management', 'sub_module_name' => '', 'js_call_identify' => $this->rfq_url.'transaction/rfq_register/list', 'main_icon_class' =>'fa fa-rocket', 'sub_icon_class' => '','chk_action'=>'rfq_register'),
		array('main_module_name' =>'RFQ Report', 'sub_module_name' => '', 'js_call_identify' => $this->rfq_url.'Report', 'main_icon_class' =>'fa fa-bar-chart', 'sub_icon_class' => '','chk_action'=>'report'));
		
		return $action_menu_rfq;
		
		}

		public function getEarningMasterID($earning_name)
		{
			$earning_name=trim($earning_name);
			
			$sql = "select earning_id from bud_earning_master where earning_name = '$earning_name' and active_status=1";
			$bindArr=array();
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs['earning_id']>0)
			{
				return $recs['earning_id'];
			}
			else
			{
				$status = 1;
				$ins=" bud_earning_master SET earning_name=:earning_name, active_status=:active_status";
				$insBind=array(":earning_name"=>array("value"=>$earning_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ':sess_user_id'=>array("value"=>$_SESSION['sess_log_userid'],"type"=>"int"));
				
				$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id, hide_in_list=1 ";
				
				$exec = $this->pdoObj->execute($strQuery, $insBind);
				
				if($exec)
				{
					$sql = "select earning_id from bud_earning_master where earning_name = '$earning_name' and active_status=1";
					$bindArr=array();
					$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					if($recs['earning_id']>0)
					{
						return $recs['earning_id'];
					}
				}  
			}
		}
		public function getDeductionMasterID($deduction_name)
		{
			$deduction_name=trim($deduction_name);
			
			$sql = "select deduction_id from bud_deduction_master where deduction_name = '$deduction_name' and active_status=1";
			$bindArr=array();
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			if($recs['deduction_id']>0)
			{
				return $recs['deduction_id'];
			}
			else
			{
				$status = 1;
				$ins=" bud_deduction_master SET deduction_name=:deduction_name, active_status=:active_status";
				$insBind=array(":deduction_name"=>array("value"=>$deduction_name,"type"=>"text"), ":active_status"=>array("value"=>$status,"type"=>"int"), ':sess_user_id'=>array("value"=>$_SESSION['sess_log_userid'],"type"=>"int"));
				
				$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id, hide_in_list=1 ";
				
				$exec = $this->pdoObj->execute($strQuery, $insBind);
				
				if($exec)
				{
					$sql = "select deduction_id from bud_deduction_master where deduction_name = '$deduction_name' and active_status=1";
					$bindArr=array();
					$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
					
					if($recs['deduction_id']>0)
					{
						return $recs['deduction_id'];
					}
				}  
			}
		}		
	
		public function getItemQuantity($postArr, $opt=0)
	{
		$searchval = $postArr['item_id'];
		$bindArr=array( ":item_id"=>array("value"=>$searchval,"dtype"=>"int")); 
		$sql="select sum(stc.received_qty)+itm.item_open_stock as ava from bud_stock_details stc left join  bud_item_master itm on stc.item_id = itm.item_id where itm.item_id =:item_id and stc.stock_status!=2 group by stc.item_id";
		$rs_Ava = $this->pdoObj->fetchSingle($sql, $bindArr); 
		$where ='';
		if($postArr['stock_dispatch_head_id'])
		{
			$stock_dispatch_head_id = $postArr['stock_dispatch_head_id'];
			$where = ' and stock_dispatch_head_id!=:stock_dispatch_head_id';
			$bindArr[":stock_dispatch_head_id"]=array("value"=>$stock_dispatch_head_id,"dtype"=>"int"); 
		}
		$sql="select sum(stc.dispatch_qty) as rej from bud_stock_dispatch_details stc left join  bud_item_master itm on stc.item_id = itm.item_id where itm.item_id =:item_id and stc.stock_dispatch_status!=2 $where group by stc.item_id";
		$rs_Rej = $this->pdoObj->fetchSingle($sql, $bindArr); 
		
		$avai = $rs_Ava['ava'] - $rs_Rej['rej'];
		if($opt == 1) return $avai;
		$sendArr=array('availabily'=>$avai,'status'=>'success'); 			
		return json_encode($sendArr);
	}	

	}	
?>