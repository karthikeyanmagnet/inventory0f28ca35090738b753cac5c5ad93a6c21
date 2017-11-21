<?php	

class customer extends rapper
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
				$where = 'where ( vendor_code like :search_str or company_name like :search_str)';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_customer_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select customer_id,vendor_code, company_name, primary_contact, mobile_no, cat.active_status,  case cat.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(cat.lastmodifiedon, '%d %b %Y'), date_format(cat.createdon, '%d %b %Y')) as updated_on from bud_customer_master cat left join bud_user_master usr_crt on cat.createdby = usr_crt.user_id left join bud_user_master usr_upd on cat.lastmodifiedby = usr_upd.user_id $where order by vendor_code "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["customer_id"]);
				$vendor_code=$this->purifyString($rs["vendor_code"]);
				$company_name=$this->purifyString($rs["company_name"]);
				$primary_contact=$this->purifyString($rs["primary_contact"]);
				$mobile_no=$this->purifyString($rs["mobile_no"]);
				$cstatus=$this->purifyString($rs["active_status"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				 
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				
				
				 
				$sendRs[$rsCnt]=array($vendor_code, $company_name, $primary_contact, $mobile_no, $cstatus_desc, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateCustomerMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteCustomerMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select *,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_customer_master where customer_id=:customer_id";
			$bindArr=array(":customer_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
			
			$sendRs=array();
			foreach($recs as $rsky=>$rsval)
			{
				$sendRs[$rsky]=$this->purifyString($rsval);
			}
			
			$cstatus=$this->purifyString($recs["active_status"]);
			$cstatus_desc=$this->purifyString($recs["active_status_desc"]);
			$sendRs["status"]=$cstatus;
			$sendRs["status_desc"]=$cstatus_desc; 
			
			$supplier_logo_ext =  $this->purifyString($sendRs['company_logo_ext']);
			$dir = 'public/data/customer/';
			$file = $dir.$getcid.'.'.$supplier_logo_ext;
			$logoUrl = '';
			
			if($sendRs["customer_id"])
			{
				if(file_exists($file))
				{
					$logoUrl = $file.'?'.uniqid();
				}	
			}
			$_details = array();
			if(!$getcid)
			{
				
			}	
			else
			{
				$sql="select * from bud_customer_delivery_master where customer_id=:customer_id";
				$bindArr=array(":customer_id"=>array("value"=>$getcid,"type"=>"int"));
				$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
				$itmgrpdet_details = array(); 
				
				foreach($recs as $key=>$rs)
				{ 
					$_details[$key]=$rs;					
				}
				
			}
				 
			//$sendRs=array("customer_id"=>$cid,"customer_name"=>$vendor_code,"status"=>$cstatus,"status_desc"=>$cstatus_desc,"vendor_code"=>$vendor_code); 
			$sendRs['logoUrl'] = $logoUrl;
			$sendRs['details'] = $_details;
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]); 
		$status=$this->purifyInsertString($postArr["customer_chk_status"]);
		
		$vendor_code=$this->purifyInsertString($postArr["vendor_code"]);
		$company_name=$this->purifyInsertString($postArr["company_name"]);
		$primary_contact=$this->purifyInsertString($postArr["primary_contact"]);
		$phone_no=$this->purifyInsertString($postArr["phone_no"]);
		$mobile_no=$this->purifyInsertString($postArr["mobile_no"]); 
		$website_url=$this->purifyInsertString($postArr["website_url"]);
		$from=$this->purifyInsertString($postArr["from"]); 
		
		
		
		$attention_bill_addr=$this->purifyInsertString($postArr["attention_bill_addr"]);
		$warehouse_bill_addr=$this->purifyInsertString($postArr["warehouse_bill_addr"]);
		$address_bill_addr=$this->purifyInsertString($postArr["address_bill_addr"]);
		$city_bill_addr=$this->purifyInsertString($postArr["city_bill_addr"]);
		$state_bill_addr=$this->purifyInsertString($postArr["state_bill_addr"]);
		$zipcode_bill_addr=$this->purifyInsertString($postArr["zipcode_bill_addr"]);
		$country_bill_addr=$this->purifyInsertString($postArr["country_bill_addr"]);
		$company_phone_bill_addr=$this->purifyInsertString($postArr["company_phone_bill_addr"]);
		$company_email_bill_addr=$this->purifyInsertString($postArr["company_email_bill_addr"]);
		
		$customer_status=$this->purifyInsertString($postArr["customer_status"]);
		
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_customer_master where customer_id=:customer_id "; 
		$bindExtCntArr=array(":customer_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$max_ext_sql="select max(customer_id)+1 as mid from bud_customer_master "; 
		$rs_qry_max = $this->pdoObj->fetchSingle($max_ext_sql); 
		$ext_max_val=($rs_qry_max["mid"])?$rs_qry_max["mid"]:1;
		
		$ins=" bud_customer_master SET vendor_code=:vendor_code, company_name=:company_name, primary_contact=:primary_contact, phone_no=:phone_no, mobile_no=:mobile_no, website_url=:website_url,   attention_bill_addr=:attention_bill_addr, warehouse_bill_addr=:warehouse_bill_addr, address_bill_addr=:address_bill_addr, city_bill_addr=:city_bill_addr, state_bill_addr=:state_bill_addr, zipcode_bill_addr=:zipcode_bill_addr,  country_bill_addr=:country_bill_addr, company_phone_bill_addr=:company_phone_bill_addr, company_email_bill_addr=:company_email_bill_addr,  active_status=:active_status";
		
		$insBind=array(":vendor_code"=>array("value"=>$vendor_code,"type"=>"text"), ":company_name"=>array("value"=>$company_name,"type"=>"text"), ":primary_contact"=>array("value"=>$primary_contact,"type"=>"text"), ":phone_no"=>array("value"=>$phone_no,"type"=>"text"), ":mobile_no"=>array("value"=>$mobile_no,"type"=>"text"), ":website_url"=>array("value"=>$website_url,"type"=>"text"),
		
		
		
		":attention_bill_addr"=>array("value"=>$attention_bill_addr,"type"=>"text"), ":warehouse_bill_addr"=>array("value"=>$warehouse_bill_addr,"type"=>"text"), ":address_bill_addr"=>array("value"=>$address_bill_addr,"type"=>"text"), ":city_bill_addr"=>array("value"=>$city_bill_addr,"type"=>"text"), ":state_bill_addr"=>array("value"=>$state_bill_addr,"type"=>"text"), ":zipcode_bill_addr"=>array("value"=>$zipcode_bill_addr,"type"=>"text"), ":country_bill_addr"=>array("value"=>$country_bill_addr,"type"=>"text"), ":company_phone_bill_addr"=>array("value"=>$company_phone_bill_addr,"type"=>"text"), ":company_email_bill_addr"=>array("value"=>$company_email_bill_addr,"type"=>"text"),
		
		 ":active_status"=>array("value"=>$customer_status,"type"=>"int"),':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int") ); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_customer_master where trim(vendor_code)=:vendor_code ";
		$bindExtChkArr=array(":vendor_code"=>array("value"=>$vendor_code,"dtype"=>"text")); 
		$insuptyp="";
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where customer_id=:customer_id ";
			$insBind[":customer_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and customer_id<>:customer_id ";
			$bindExtChkArr[":customer_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Customer updated successfully!";
		}
		else
		{
			$id = $ext_max_val;
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Customer inserted successfully!";
			$insuptyp="insert";
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
				if(isset($_FILES['company_logo']))
				{
					$dir = 'public/data/customer/';
					$this->makedirectory($dir);
					
					$file = $_FILES['company_logo'];
					$name = $_FILES['company_logo']['name'];
					
					if($name)
					{
						$company_logo_ext = pathinfo($name,PATHINFO_EXTENSION);
						$new_file  =$dir.$id.'.'.$supplier_logo_ext;
						if(file_exists($new_file)){ unlink($new_file);}
						if(move_uploaded_file($_FILES['company_logo']['tmp_name'], $dir.$id.'.'.$company_logo_ext))
						{
							$update = "UPDATE bud_customer_master SET company_logo_ext=:company_logo_ext where customer_id=:customer_id ";
							$insUP[":customer_id"]=array("value"=>$id,"type"=>"int"); 
							$insUP[":company_logo_ext"]=array("value"=>$company_logo_ext,"type"=>"text"); 
							$exec = $this->pdoObj->execute($update, $insUP);
						}
					}
				}
				
				$opStatus='success';
				$opMessage=$opmsg; 
				
				$arr_hdn_custmer_delivery_detid = $postArr['hdn_custmer_delivery_detid']; 
				$arr_attention_mail_addr=($postArr["attention_mail_addr"]);
				$arr_warehouse_mail_addr=($postArr["warehouse_mail_addr"]);
				$arr_address_mail_addr=($postArr["address_mail_addr"]);
				$arr_city_mail_addr=($postArr["city_mail_addr"]);
				$arr_state_mail_addr=($postArr["state_mail_addr"]);
				$arr_zipcode_mail_addr=($postArr["zipcode_mail_addr"]);
				$arr_country_mail_addr=($postArr["country_mail_addr"]);
				$arr_company_phone_mail_addr=($postArr["company_phone_mail_addr"]);
				$arr_company_email_mail_addr=($postArr["company_email_mail_addr"]);
				
				foreach($arr_warehouse_mail_addr as $cat_key=>$cat_val)
				{
					$customer_delivery_id = $this->purifyInsertString($arr_hdn_custmer_delivery_detid[$cat_key]); 
					$attention_mail_addr = $this->purifyInsertString($arr_attention_mail_addr[$cat_key]);
					$warehouse_mail_addr = $this->purifyInsertString($arr_warehouse_mail_addr[$cat_key]);
					$address_mail_addr = $this->purifyInsertString($arr_address_mail_addr[$cat_key]);
					$city_mail_addr = $this->purifyInsertString($arr_city_mail_addr[$cat_key]);
					$state_mail_addr = $this->purifyInsertString($arr_state_mail_addr[$cat_key]);
					$zipcode_mail_addr = $this->purifyInsertString($arr_zipcode_mail_addr[$cat_key]);
					$country_mail_addr = $this->purifyInsertString($arr_country_mail_addr[$cat_key]);
					$company_phone_mail_addr = $this->purifyInsertString($arr_company_phone_mail_addr[$cat_key]);
					$company_email_mail_addr = $this->purifyInsertString($arr_company_email_mail_addr[$cat_key]);
					
					$ins=" bud_customer_delivery_master SET attention_mail_addr=:attention_mail_addr, warehouse_mail_addr=:warehouse_mail_addr, address_mail_addr=:address_mail_addr, city_mail_addr=:city_mail_addr, state_mail_addr=:state_mail_addr, zipcode_mail_addr=:zipcode_mail_addr,  country_mail_addr=:country_mail_addr, company_phone_mail_addr=:company_phone_mail_addr, company_email_mail_addr=:company_email_mail_addr ";
					$insBind=array(":attention_mail_addr"=>array("value"=>$attention_mail_addr,"type"=>"text"), ":warehouse_mail_addr"=>array("value"=>$warehouse_mail_addr,"type"=>"text"), ":address_mail_addr"=>array("value"=>$address_mail_addr,"type"=>"text"), ":city_mail_addr"=>array("value"=>$city_mail_addr,"type"=>"text"), ":state_mail_addr"=>array("value"=>$state_mail_addr,"type"=>"text"), ":zipcode_mail_addr"=>array("value"=>$zipcode_mail_addr,"type"=>"text"), ":country_mail_addr"=>array("value"=>$country_mail_addr,"type"=>"text"), ":company_phone_mail_addr"=>array("value"=>$company_phone_mail_addr,"type"=>"text"), ":company_email_mail_addr"=>array("value"=>$company_email_mail_addr,"type"=>"text")); 
					$mod = '';
					$strQuery = '';
					if($customer_delivery_id>0) 
					{ 
						$strQuery="UPDATE $ins where customer_delivery_id=:customer_delivery_id";
						$insBind[":customer_delivery_id"]=array("value"=>$customer_delivery_id,"type"=>"int"); 
						$mod = 'up';
					}
					else
					{ 
						 
						if($warehouse_mail_addr!='')
						{						
						$strQuery="INSERT INTO $ins, customer_id=:customer_id "; 						
						$insBind[":customer_id"]=array("value"=>$id,"type"=>"int");  
						$mod = 'ins';  
						}
					}
					if($strQuery)
					$sbexec = $this->pdoObj->execute($strQuery, $insBind);  
				}  
				
				if($hid_temp_del != '')
				{
					$temp_arr = explode(',', $hid_temp_del);
					foreach($temp_arr as $del_id)
					{
						if($del_id)
						{  
							$strQuery=" delete from bud_customer_delivery_master where customer_delivery_id=:customer_delivery_id  ";  						
							$bindArr=array( ":customer_delivery_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							$exec = $this->pdoObj->execute($strQuery, $bindArr);  
						}
					}
				}
			} 
		} 
		
		//if($from == 'other')
		{
			$data = $this->lastInsertData($vendor_code);
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists, 'data'=>$data);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$bindArr=array(); 
		
		$strQuery=" delete from bud_customer_master where customer_id=:customer_id "; 
		$bindArr=array( ":customer_id"=>array("value"=>$id,"dtype"=>"int")); 
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Customer details deleted successfully'; 
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
		  	$bindArr=array( ":customer_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or customer_id=:customer_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select customer_id, vendor_code, company_name, active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_customer_master where active_status = 1 $whereor order by vendor_code asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Customer referring PO entry' as msg from bud_po_head where customer_id=:id) "; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); 
		
		 $sql="select customer_id, vendor_code, company_name, active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_customer_master where customer_id=:id order by vendor_code asc ";
		 $recs = $this->pdoObj->fetchSingle($sql, $bindExtCntArr);
		 
		 $customer = $recs['vendor_code']; 
		
		$_cnt = 0;
		$status = 'success';
		$msgArr = array();
		$_msg = 'Do you want to delete '.$customer.'?';
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
		 $sql="select customer_id as id, vendor_code as name, company_name, active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_customer_master where active_status = 1 and vendor_code = :name order by id desc ";
		 $bindArr=array(":name"=>array("value"=>$name,"type"=>"text"));
		 //echo $sql.json_encode($bindArr);
		 $recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 return $recs;
	}
        
        public function getCustomerDeliveryMaster($getcid){
                $sql="select * from bud_customer_delivery_master where customer_id=:customer_id";
		$bindArr=array(":customer_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetch($sql, $bindArr); 
                return $recs;
        }


        public function __destruct() 
	{
		
	} 
}

?>