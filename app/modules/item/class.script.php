<?php	

class item extends rapper
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{
//            if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
//            {
//                $orderby=" order by name asc ";
//                print_r($orderby);exit();
//            }
            
            
            
            $sortby=(($postArr['order'][0][dir])=="asc")?"asc":"desc";            
            $orderby=($postArr['order'][0][column]);
            
            //$orderby=($orderby=0)?" order by cmas.category_name ":($orderby=1)?" order by name ":"";
            
            if($orderby==0){if($sortby=="asc"){$orderby=" order by cmas.category_name asc";}else{$orderby=" order by cmas.category_name desc";}}
            else if($orderby==1){if($sortby=="asc"){$orderby=" order by name asc";}else{$orderby=" order by name desc";}}
            else{$orderby=$_SESSION["orderbyLast"];}
            //print_r($orderby);exit();
                        
            //$orderby=$orderby+" "+$sortby+" ";
            
            //print_r($orderby);exit();
            
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
                            $where = ' where (cat.item_code like :search_str or category_name like :search_str)';
                            $bindArr=array(':search_str'=>array("value"=>$query_val,"type"=>"text"));
		  }
		  
		  $tot_sql="select count(*) as cnt from bud_item_master cat left join bud_category_master cmas on cmas.category_id = cat.category_id $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  $sql="select item_id,item_code,cat.item_description,if(coalesce(usr_upd.user_name,'')!='', usr_upd.user_name, usr_crt.user_name) as updated_by, if(coalesce(usr_upd.user_name,'')!='', date_format(cat.lastmodifiedon, '%d %b %Y'), date_format(cat.createdon, '%d %b %Y')) as updated_on, cmas.category_name, item_code as name  from bud_item_master cat left join bud_category_master cmas on cmas.category_id = cat.category_id left join bud_user_master usr_crt on cat.createdby = usr_crt.user_id left join bud_user_master usr_upd on cat.lastmodifiedby = usr_upd.user_id $where $orderby ";
                  
                  $_SESSION["orderbyLast"]=$orderby;
                  
                  
                  
//        " CASE WHEN ASCII(SUBSTRING(name,1)) BETWEEN 48 AND 57 THEN         
//              
//              CAST(name AS UNSIGNED)
//        
//              WHEN ASCII(SUBSTRING(name,2)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,1)
//              WHEN ASCII(SUBSTRING(name,3)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,2)
//              WHEN ASCII(SUBSTRING(name,4)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,3)
//              WHEN ASCII(SUBSTRING(name,5)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,4)
//              WHEN ASCII(SUBSTRING(name,6)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,5)
//              WHEN ASCII(SUBSTRING(name,7)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,6)
//              WHEN ASCII(SUBSTRING(name,8)) BETWEEN 48 AND 57 THEN
//                   SUBSTRING(name,1,7)
//         END  , 
//         CASE WHEN ASCII(SUBSTRING(name,1)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,1) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,2)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,2) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,3)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,3) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,4)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,4) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,5)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,5) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,6)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,6) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,7)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,7) AS UNSIGNED)
//              WHEN ASCII(SUBSTRING(name,8)) BETWEEN 48 AND 57 THEN
//                   CAST(SUBSTRING(name,8) AS UNSIGNED)
//         END    "; 
//                  
                  
                  //, cmas.category_name asc
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["item_id"]);
				$cname=$this->purifyString($rs["item_code"]);
				$category_name=$this->purifyString($rs["category_name"]);
				$cstatus_desc=$this->purifyString($rs["active_status_desc"]);
				$item_display_order = $this->purifyString($rs["item_display_order"]);
				$updated_by=$this->purifyString($rs["updated_by"]);
				$updated_on=$this->purifyString($rs["updated_on"]);
                                $item_description=$this->purifyString($rs["item_description"]);
				
				
				//$sendRs[$rsCnt]=array("item_id"=>$cid,"item_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc); //$PageSno+1,
				$sendRs[$rsCnt]=array($category_name,$cname,$item_description, $updated_on, $updated_by, '<span class="edit act-edit" data-modal-id="popup1" onclick="CreateUpdateItemMasterList('.$cid.');"><i class="fa fa-edit"></i> Update </span> <span class="delete act-delete"  onclick="viewDeleteItemMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span>');
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
			
			$sql="select item_id,item_code, item_description, category_id, item_unit_cost, item_currency_id, item_grade, item_weight, item_measure_id, item_hts, item_type, item_open_stock, item_status from bud_item_master where item_id=:item_id";
			$bindArr=array(":item_id"=>array("value"=>$getcid,"type"=>"int")); 
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["item_id"]);
			$category_id=$this->purifyString($recs["category_id"]);
		
			$item_code=$this->purifyString($recs["item_code"]);
			$item_description=$this->purifyString($recs["item_description"]);
			$item_unit_cost=$this->purifyString($recs["item_unit_cost"]);
			$item_currency_id = $this->purifyString($recs["item_currency_id"]);
			$item_grade = $this->purifyString($recs["item_grade"]);
			$item_weight = $this->purifyString($recs["item_weight"]);
			$item_measure_id = $this->purifyString($recs["item_measure_id"]);
			$item_hts = $this->purifyString($recs["item_hts"]);
			$item_type = $this->purifyString($recs["item_type"]);
			$item_open_stock = $this->purifyString($recs["item_open_stock"]);
			$item_status = $this->purifyString($recs["item_status"]);
			
			$category = $this->getModuleComboList('category', $category_id);
			$sendRs=array("item_id"=>$cid,"item_code"=>$item_code,"item_description"=>$item_description,"item_unit_cost"=>$item_unit_cost, 'item_currency_id'=>$item_currency_id, 'item_hts'=>$item_hts, 'item_grade'=>$item_grade, 'item_weight'=>$item_weight, 'item_measure_id'=>$item_measure_id, 'item_type'=>$item_type, 'item_open_stock'=>$item_open_stock,  'categorylist'=>$category, 'category_id'=>$category_id, 'item_status'=>$item_status); 
			
			$sendArr=array('rsData'=>$sendRs,'status'=>'success');  
			
			return json_encode($sendArr);
	}
	
	public function saveprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$item_code=$this->purifyInsertString($postArr["item_code"]);
		$category_id=$this->purifyInsertString($postArr["category_id"]);
		$item_description=$this->purifyInsertString($postArr["item_description"]);
		$item_unit_cost=$this->purifyInsertString($postArr["item_unit_cost"]);
		$item_currency_id=$this->purifyInsertString($postArr["item_currency_id"]);
		$item_grade=$this->purifyInsertString($postArr["item_grade"]);
		$item_weight=$this->purifyInsertString($postArr["item_weight"]);
		$item_measure_id=$this->purifyInsertString($postArr["item_measure_id"]);
		$item_hts=$this->purifyInsertString($postArr["item_hts"]);
		$item_type=$this->purifyInsertString($postArr["item_type"]);
		$item_open_stock=$this->purifyInsertString($postArr["item_open_stock"]);
		$from=$this->purifyInsertString($postArr["from"]); 
		$item_status=$this->purifyInsertString($postArr["item_chk_status"]);
		
		$cnt_ext_sql="select count(*) as ext_cnt from bud_item_master where item_id=:item_id "; 
		$bindExtCntArr=array(":item_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" bud_item_master SET item_code=:item_code,category_id=:category_id,item_description=:item_description, item_unit_cost=:item_unit_cost, item_currency_id=:item_currency_id, item_grade=:item_grade, item_weight=:item_weight, item_measure_id=:item_measure_id, item_hts=:item_hts, item_type=:item_type, item_open_stock=:item_open_stock, item_status=:item_status";
		$insBind=array(":item_code"=>array("value"=>$item_code,"type"=>"text"), ":category_id"=>array("value"=>$category_id,"type"=>"int"),
		":item_description"=>array("value"=>$item_description,"type"=>"text"), ":item_unit_cost"=>array("value"=>$item_unit_cost,"type"=>"text"),
		":item_currency_id"=>array("value"=>$item_currency_id,"type"=>"int"), ":item_grade"=>array("value"=>$item_grade,"type"=>"text"),
		":item_weight"=>array("value"=>$item_weight,"type"=>"text"), ":item_measure_id"=>array("value"=>$item_measure_id,"type"=>"int"),
		":item_hts"=>array("value"=>$item_hts,"type"=>"text"), ":item_type"=>array("value"=>$item_type,"type"=>"int"),
		":item_open_stock"=>array("value"=>$item_open_stock,"type"=>"int"),
		':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int"), ':item_status'=>array("value"=>$item_status,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from bud_item_master where trim(item_code)=:item_code ";
		$bindExtChkArr=array(":item_code"=>array("value"=>$item_code,"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where item_id=:item_id ";
			$insBind[":item_id"]=array("value"=>$id,"type"=>"text"); 
			
			$sql_ext_chk .= " and item_id<>:item_id ";
			$bindExtChkArr[":item_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Item updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(),createdby=:sess_user_id "; 
			$opmsg="Item inserted successfully!";
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
			$data = $this->lastInsertData($item_code);
		}
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists, 'data'=>$data);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
			$id=$this->purifyInsertString($postArr["hid_id"]);
			
			$bindArr=array(); 
			
			$strQuery=" delete from bud_item_master where item_id=:item_id "; 
			$bindArr=array( ":item_id"=>array("value"=>$id,"dtype"=>"int")); 
			
			$exec = $this->pdoObj->execute($strQuery, $bindArr);
			
			$opStatus='failure';
			$opMessage='failure';
			if($exec)
			{
				$opStatus='success';
				$opMessage='Item details deleted successfully'; 
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
		  	$bindArr=array( ":item_id"=>array("value"=>$id,"dtype"=>"int")); 
			$whereor = " or item_id=:item_id";
		  }
		 
		  $sql="select item_id,item_code, category_id, item_description from bud_item_master where item_status = 1 $whereor  ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Item referring Item group' as msg from bud_item_group_details_master where item_id=:id) union all (select count(*) as ext_cnt, 'Item linked to Vendor master' as msg from bud_vendor_details_master where item_id=:id) union all (select count(*) as ext_cnt, 'Item linked to Stock entry' as msg from bud_stock_details where item_id=:id) union all (select count(*) as ext_cnt, 'Item linked to Stock dispatch' as msg from bud_stock_dispatch_details where item_id=:id) union all (select count(*) as ext_cnt, 'Item linked to PO entry' as msg from bud_po_details where item_id=:id) union all (select count(*) as ext_cnt, 'Item linked to PO assigned' as msg from bud_po_assign_details where item_id=:id) "; 
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
		 $sql="select item_id as id,concat(item_code,'',if((item_description='' || item_description=null), '',concat(' - ','',item_description))) as name, category_id, item_description from bud_item_master where item_code = :name order by id desc ";
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