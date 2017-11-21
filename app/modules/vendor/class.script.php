<?php	

class vendor extends rapper
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
				$where = 'where supplier_name like :search_str';
				$bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_vendor_master $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0; 
		  
		  $sql="select vendor_id,supplier_name,supplier_contact_name, cat.active_status,  case cat.active_status when 1 then 'Active' else 'Inactive' end as active_status_desc,  supplier_contact_no, if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(cat.lastmodifiedon, '%d %b %Y'), date_format(cat.createdon, '%d %b %Y')) as updated_on from bud_vendor_master cat left join bud_user_master usr_crt on cat.createdby = usr_crt.user_id left join bud_user_master usr_upd on cat.lastmodifiedby = usr_upd.user_id $where order by supplier_name "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["vendor_id"]);
				$supplier_name=$this->purifyString($rs["supplier_name"]);
				$supplier_contact_name=$this->purifyString($rs["supplier_contact_name"]);
				$supplier_contact_no=$this->purifyString($rs["supplier_contact_no"]);
				$active_status_desc=$this->purifyString($rs["active_status_desc"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
				
				
				//$sendRs[$rsCnt]=array("vendor_id"=>$cid,"vendor_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); //$PageSno+1,
				$sendRs[$rsCnt]=array($supplier_name, $supplier_contact_name, $supplier_contact_no, $active_status_desc, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateVendorMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteVendorMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select vendor_id,supplier_name, supplier_address, supplier_city, supplier_state, supplier_zipcode, supplier_phone, supplier_email, supplier_website, supplier_tin_cst, supplier_excise_no, supplier_contact_name, supplier_contact_no, supplier_shipping_terms, supplier_payment_terms, active_status, supplier_logo_ext  from bud_vendor_master where vendor_id=:vendor_id";
			$bindArr=array(":vendor_id"=>array("value"=>$getcid,"type"=>"int")); 
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["vendor_id"]);
			$supplier_city=$this->purifyString($recs["supplier_city"]);
		
			$supplier_name=$this->purifyString($recs["supplier_name"]);
			$supplier_address=$this->purifyString($recs["supplier_address"]);
			$supplier_state=$this->purifyString($recs["supplier_state"]);
			$supplier_zipcode = $this->purifyString($recs["supplier_zipcode"]);
			$supplier_phone = $this->purifyString($recs["supplier_phone"]);
			$supplier_email = $this->purifyString($recs["supplier_email"]);
			$supplier_website = $this->purifyString($recs["supplier_website"]);
			$supplier_tin_cst = $this->purifyString($recs["supplier_tin_cst"]);
			$supplier_excise_no = $this->purifyString($recs["supplier_excise_no"]);
			$supplier_contact_no = $this->purifyString($recs["supplier_contact_no"]);
			$supplier_contact_name = $this->purifyString($recs["supplier_contact_name"]);
			$supplier_shipping_terms = $this->purifyString($recs["supplier_shipping_terms"]);
			$supplier_payment_terms = $this->purifyString($recs["supplier_payment_terms"]);
			$active_status = $this->purifyString($recs["active_status"]);
			$supplier_logo_ext =  $this->purifyString($recs['supplier_logo_ext']);
			$dir = 'public/data/vendor/';
			$file = $dir.$cid.'.'.$supplier_logo_ext;
			$logoUrl = '';
			
			
			if(!$cid)
			{
				$itmgrpdet_details = array();
			}	
			else
			{
				$sql="select vendor_det_id,vendor_id,item_description,item_id,item_amount from bud_vendor_details_master where vendor_id=:vendor_id";
				$bindArr=array(":vendor_id"=>array("value"=>$cid,"type"=>"int"));
				$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
				$itmgrpdet_details = array(); 
				
				foreach($recs as $key=>$rs)
				{ 
					$itmgrpdet_details[$key]=array('vendor_det_id'=>$rs['vendor_det_id'], 'vendor_id'=>$rs['vendor_id'], 'item_description'=>$rs['item_description'], 'item_id'=>$rs['item_id'], 'item_amount'=>$rs['item_amount']);
					
				}
				
			}
			
			if($cid)
			{
				if(file_exists($file))
				{
					$logoUrl = $file.'?'.uniqid();
				}	
			}
			
			$itemlist = $this->getModuleComboList('item'); 
			
			$sendRs=array("vendor_id"=>$cid,"supplier_name"=>$supplier_name,"supplier_address"=>$supplier_address,"supplier_state"=>$supplier_state, 'supplier_zipcode'=>$supplier_zipcode, 'supplier_tin_cst'=>$supplier_tin_cst, 'supplier_phone'=>$supplier_phone, 'supplier_email'=>$supplier_email, 'supplier_website'=>$supplier_website, 'supplier_excise_no'=>$supplier_excise_no, 'supplier_contact_name'=>$supplier_contact_name,  'categorylist'=>$category, 'supplier_city'=>$supplier_city, 'supplier_contact_no'=>$supplier_contact_no, 'supplier_shipping_terms'=>$supplier_shipping_terms, 'supplier_payment_terms'=>$supplier_payment_terms, 'active_status'=>$active_status, 'logoUrl'=>$logoUrl, 'supplier_logo_ext'=>$supplier_logo_ext,"itemlist"=>$itemlist, "itmgrpdet_details"=>$itmgrpdet_details); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
	
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$supplier_name=$this->purifyInsertString($postArr["supplier_name"]);
		$supplier_city=$this->purifyInsertString($postArr["supplier_city"]);
		$supplier_address=$this->purifyInsertString($postArr["supplier_address"]);
		$supplier_state=$this->purifyInsertString($postArr["supplier_state"]);
		$supplier_zipcode=$this->purifyInsertString($postArr["supplier_zipcode"]);
		$supplier_phone=$this->purifyInsertString($postArr["supplier_phone"]);
		$supplier_email=$this->purifyInsertString($postArr["supplier_email"]);
		$supplier_website=$this->purifyInsertString($postArr["supplier_website"]);
		$supplier_tin_cst=$this->purifyInsertString($postArr["supplier_tin_cst"]);
		$supplier_excise_no=$this->purifyInsertString($postArr["supplier_excise_no"]);
		$supplier_contact_name=$this->purifyInsertString($postArr["supplier_contact_name"]);
		$supplier_contact_no=$this->purifyInsertString($postArr["supplier_contact_no"]);
		$supplier_shipping_terms=$this->purifyInsertString($postArr["supplier_shipping_terms"]);
		$supplier_payment_terms=$this->purifyInsertString($postArr["supplier_payment_terms"]);
		$active_status=$this->purifyInsertString($postArr["active_status"]);
		$hid_temp_del=$this->purifyInsertString($postArr["hid_temp_del"]);    
		$from=$this->purifyInsertString($postArr["from"]); 
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_vendor_master where vendor_id=:vendor_id "; 
		$bindExtCntArr=array(":vendor_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$max_ext_sql="select max(vendor_id)+1 as mid from bud_vendor_master "; 
		$rs_qry_max = $this->pdoObj->fetchSingle($max_ext_sql); 
		$ext_max_val=($rs_qry_max["mid"])?$rs_qry_max["mid"]:1;
		
		$ins=" bud_vendor_master SET supplier_name=:supplier_name,supplier_city=:supplier_city,supplier_address=:supplier_address, supplier_state=:supplier_state, supplier_zipcode=:supplier_zipcode, supplier_phone=:supplier_phone, supplier_email=:supplier_email, supplier_website=:supplier_website, supplier_tin_cst=:supplier_tin_cst, supplier_excise_no=:supplier_excise_no, supplier_contact_name=:supplier_contact_name,supplier_contact_no=:supplier_contact_no,supplier_shipping_terms=:supplier_shipping_terms,supplier_payment_terms=:supplier_payment_terms, active_status=:active_status ";
		$insBind=array(":supplier_name"=>array("value"=>$supplier_name,"type"=>"text"), ":supplier_city"=>array("value"=>$supplier_city,"type"=>"int"),
		":supplier_address"=>array("value"=>$supplier_address,"type"=>"text"), ":supplier_state"=>array("value"=>$supplier_state,"type"=>"text"),
		":supplier_zipcode"=>array("value"=>$supplier_zipcode,"type"=>"int"), ":supplier_phone"=>array("value"=>$supplier_phone,"type"=>"text"),
		":supplier_email"=>array("value"=>$supplier_email,"type"=>"text"), ":supplier_website"=>array("value"=>$supplier_website,"type"=>"int"),
		":supplier_tin_cst"=>array("value"=>$supplier_tin_cst,"type"=>"text"), ":supplier_excise_no"=>array("value"=>$supplier_excise_no,"type"=>"int"),
		":supplier_contact_name"=>array("value"=>$supplier_contact_name,"type"=>"int"),":supplier_contact_no"=>array("value"=>$supplier_contact_no,"type"=>"int"),
		":supplier_shipping_terms"=>array("value"=>$supplier_shipping_terms,"type"=>"int"),":supplier_payment_terms"=>array("value"=>$supplier_payment_terms,"type"=>"int"),
		':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ':active_status'=>array("value"=>$active_status,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_vendor_master where trim(supplier_name)=:supplier_name ";
		$bindExtChkArr=array(":supplier_name"=>array("value"=>$supplier_name,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where vendor_id=:vendor_id ";
			$insBind[":vendor_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and vendor_id<>:vendor_id ";
			$bindExtChkArr[":vendor_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Vendor updated successfully!";
		}
		else
		{
			$id = $ext_max_val;
			$strQuery="INSERT INTO $ins, vendor_id=$id, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Vendor inserted successfully!";
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
			
			 
				$hdn_itmgrp_detid = $postArr['hdn_itmgrp_detid']; 
				$txt_desc = $postArr['txt_desc'];
				$cmb_item = $postArr['cmb_item'];
				$txt_amount = $postArr['txt_amount']; 
				
				foreach($cmb_item as $cat_key=>$cat_val)
				{  
					$vendor_det_id = $this->purifyInsertString($hdn_itmgrp_detid[$cat_key]); 
					$item_description = $this->purifyInsertString($txt_desc[$cat_key]);
					$item_id = $this->purifyInsertString($cmb_item[$cat_key]);
					$item_amount = $this->purifyInsertString($txt_amount[$cat_key]);
					 
					
					if($item_id)
					{   
						
						$ins=" bud_vendor_details_master SET item_description=:item_description, item_id=:item_id, item_amount=:item_amount ";
						$insBind=array(":item_description"=>array("value"=>$item_description,"type"=>"text"),":item_id"=>array("value"=>$item_id,"type"=>"int"), ":item_amount"=>array("value"=>$item_amount,"type"=>"text")); 
						$mod = '';
						if($vendor_det_id>0) 
						{ 
							$strQuery="UPDATE $ins where vendor_det_id=:vendor_det_id";
							$insBind[":vendor_det_id"]=array("value"=>$vendor_det_id,"type"=>"int"); 
							$mod = 'up';
						}
						else
						{ 
							 
							$strQuery="INSERT INTO $ins, vendor_id=:vendor_id "; 						
							$insBind[":vendor_id"]=array("value"=>$id,"type"=>"int");  
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
							$strQuery=" delete from bud_vendor_details_master where vendor_det_id=:vendor_det_id  ";  						
							$bindArr=array( ":vendor_det_id"=>array("value"=>$del_id,"dtype"=>"int")); 
							$exec = $this->pdoObj->execute($strQuery, $bindArr);  
						}
					}
				}
				
				
				
				if(isset($_FILES['supplier_logo']))
				{
					$dir = 'public/data/vendor/';
					$this->makedirectory($dir);
					
					$file = $_FILES['supplier_logo'];
					$name = $_FILES['supplier_logo']['name'];
					
					if($name)
					{
						$supplier_logo_ext = pathinfo($name,PATHINFO_EXTENSION);
						$new_file  =$dir.$id.'.'.$supplier_logo_ext;
						if(file_exists($new_file)){ unlink($new_file);}
						if(move_uploaded_file($_FILES['supplier_logo']['tmp_name'], $dir.$id.'.'.$supplier_logo_ext))
						{
							$update = "UPDATE bud_vendor_master SET supplier_logo_ext=:supplier_logo_ext where vendor_id=:vendor_id ";
							$insUP[":vendor_id"]=array("value"=>$id,"type"=>"int"); 
							$insUP[":supplier_logo_ext"]=array("value"=>$supplier_logo_ext,"type"=>"text"); 
							$exec = $this->pdoObj->execute($update, $insUP);
						}
					}
				}
				
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		} 
		
		//if($from == 'other')
		{
			$data = $this->lastInsertData($supplier_name);
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists, 'data'=>$data);   
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$dir = 'public/data/vendor/';
			
			$strQuery=" select supplier_logo_ext from bud_vendor_master where vendor_id=:vendor_id "; 
			$bindArr=array( ":vendor_id"=>array("value"=>$id,"dtype"=>"int")); 
			$rs = $this->pdoObj->fetchSingle($strQuery, $bindArr);
			$supplier_logo_ext = $rs['supplier_logo_ext'];
			
			$file = $dir.$id.'.'.$supplier_logo_ext;
			if(file_exists($file))
			{
				unlink($file);	
			}
			
			$strQuery=" delete from bud_vendor_master where vendor_id=:vendor_id "; 
			$bindArr=array( ":vendor_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Vendor details deleted successfully'; 
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
		  	$bindArr=array( ":vendor_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or vendor_id=:vendor_id";
		  }
		   if($id == 'all')
		  {
		  	 $whereor = " or active_status != 1";
		  }
		  $sql="select vendor_id,supplier_name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_vendor_master where active_status = 1 $whereor order by supplier_name asc ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Vendor referring PO Assigned' as msg from bud_po_assign_head where vendor_id=:id)  "; 
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
		  $sql="select vendor_id as id,supplier_name as name,active_status,  case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from bud_vendor_master where active_status = 1 and supplier_name = :name order by id desc ";
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