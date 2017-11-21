<?php 
	include  dirname(realpath('..')).'/rapper/class.rapper.php';  //ini_set('display_errors',1);
	include 'class.script.php'; 
	$modobj = new stock_report();  
	
	$postArr=$_POST;
	
	$get_categoryid=$modobj->purifyInsertString($postArr["category_id"]);
	$get_cmbitemid=$modobj->purifyInsertString($postArr["cmb_item_id"]);
	$get_datefrom=$modobj->purifyInsertString($postArr["date_from"]);
	$get_dateto=$modobj->purifyInsertString($postArr["date_to"]);
	
	
	if($get_datefrom!="" and $get_dateto=="") { $get_dateto=$get_datefrom; }
	else if($get_datefrom=="" and $get_dateto!="") { $get_datefrom=$get_dateto; }
	
	if($get_datefrom=="" ) { $get_datefrom=date('d-m-Y'); }
	if($get_dateto=="") { $get_dateto=date('d-m-Y'); }
 

	$categorylist = $modobj->getModuleComboList('category');
	$itemlist = $modobj->getModuleComboList('item');   
	
	//print_r($categorylist);
	
	$pass_start_date = date('Y-m-d', strtotime($get_datefrom));
	$pass_end_date = date('Y-m-d', strtotime($get_dateto));
	
	/*while (strtotime($pass_start_date) <= strtotime($pass_end_date)) {
		echo "$pass_start_date";
		$pass_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($pass_start_date)));
	}*/
	
	 
 
 	$dispItm=array();
	$dispDateArr=array();
 	//for($dti=1; $dti<=30; $dti++)
	while(strtotime($pass_start_date) <= strtotime($pass_end_date))  
	{
		$putdate=$pass_start_date;
		
		$pass_start_date = date ("Y-m-d", strtotime("+1 days", strtotime($pass_start_date)));
		
		$dispDateArr[]=$putdate;
		
		$bindPassDateArr=array( ":pass_date"=>array("value"=>$putdate,"dtype"=>"text"));
		$bindPassDateOnlyArr=array( ":pass_date"=>array("value"=>$putdate,"dtype"=>"text"));
		
		$extFilt="";
		if($get_categoryid)
		{
			$extFilt.=" and itm.category_id=:category_id";
			$bindPassDateArr[":category_id"]=array("value"=>$get_categoryid,"dtype"=>"text");
		}
		if($get_cmbitemid)
		{
			$extFilt.=" and itm.item_id=:item_id";
			$bindPassDateArr[":item_id"]=array("value"=>$get_cmbitemid,"dtype"=>"text");
		}
		
		//$itms_sql="select itm.item_id, itm.item_code, itm.item_description, if(date(itm.createdon)<=:pass_date,itm.item_open_stock,0) as item_open_stock, itm.createdon from bud_item_master as itm order by itm.item_code, itm.item_description"; 
		
		$itms_sql=" select item_id,  item_code, item_description, sum(sum_received_qty) -sum(sum_dispatch_qty) as sum_opening_qty, category_name     from ( select itm.item_id, itm.item_code, itm.item_description, if(date(itm.createdon)<=:pass_date,itm.item_open_stock,0) as sum_received_qty, 0 as sum_dispatch_qty, cat.category_name  from bud_item_master as itm left join bud_category_master as cat on itm.category_id=cat.category_id where 1 {$extFilt}
 
 union all
 
 select   ssub.item_id, '' as item_code, '' as item_desc,  sum(ssub.received_qty) as sum_received_qty, 0 as sum_dispatch_qty, '' as category_name from bud_stock_details as ssub left join bud_stock_head as shd on ssub.stock_head_id=shd.stock_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id left join bud_item_master as itm on ssub.item_id=itm.item_id where ssub.stock_status=1 and (ssub.received_date<:pass_date or ph.ship_date<:pass_date) {$extFilt} group by ssub.item_id 
 
 union all
 
 select ssub.item_id, '' as item_code, '' as item_description, 0 as sum_received_qty, sum(ssub.dispatch_qty) as sum_dispatch_qty, '' as category_name from bud_stock_dispatch_details as ssub left join bud_stock_dispatch_head as shd on ssub.stock_dispatch_head_id =shd.stock_dispatch_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id left join bud_item_master as itm on ssub.item_id=itm.item_id where ssub.stock_dispatch_status =1 and (ssub.dispatch_date<:pass_date or ph.ship_date<:pass_date) {$extFilt} group by ssub.item_id
 
 ) as dd group by item_id order by category_name, item_code, item_description";
		$recs_itm = $modobj->pdoObj->fetchMultiple($itms_sql, $bindPassDateArr);  
		//echo $itms_sql.json_encode($bindPassDateArr);
		
		//============ Stock ENTRY 
		$stock_in_sql="select ssub.item_id,  sum(ssub.received_qty) as sum_received_qty from bud_stock_details as ssub left join bud_stock_head as shd on ssub.stock_head_id=shd.stock_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id where ssub.stock_status=1 and (ssub.received_date=:pass_date or ph.ship_date=:pass_date) group by ssub.item_id; ";
		$recs_stock_in = $modobj->pdoObj->fetchMultiple($stock_in_sql, $bindPassDateOnlyArr); 
		
		$stock_in_arr=array();
		foreach($recs_stock_in as $stock_in_lp)
		{
			$stock_in_arr[$stock_in_lp["item_id"]]=$stock_in_lp["sum_received_qty"];
		}
		
		
		
		//============ Stock DISPATCH 
		$stock_dis_sql="select ssub.item_id, sum(ssub.dispatch_qty) as sum_dispatch_qty from bud_stock_dispatch_details as ssub left join bud_stock_dispatch_head as shd on ssub.stock_dispatch_head_id =shd.stock_dispatch_head_id left join bud_po_head as ph on shd.po_head_id=ph.po_head_id where ssub.stock_dispatch_status =1 and (ssub.dispatch_date=:pass_date or ph.ship_date=:pass_date) group by ssub.item_id; ";
		$recs_stock_dis = $modobj->pdoObj->fetchMultiple($stock_dis_sql, $bindPassDateOnlyArr); 
		
		$stock_dis_arr=array();
		foreach($recs_stock_dis as $stock_dis_lp)
		{
			$stock_dis_arr[$stock_dis_lp["item_id"]]=$stock_dis_lp["sum_dispatch_qty"];
		} 
		
		
		$lpisno=0;
		foreach($recs_itm as $rs_lp_itm)
		{
			$lpisno++;
			$lp_itmstk_item_id=$rs_lp_itm["item_id"];
			$lp_itmstk_item_code=$rs_lp_itm["item_code"];
			$lp_itmstk_item_description=$rs_lp_itm["item_description"];
			$lp_itmstk_category_name=$rs_lp_itm["category_name"];
			
			$lp_itmstk_item_open_stock=intval($rs_lp_itm["sum_opening_qty"]);
			$lp_itmstk_received=intval($stock_in_arr[$lp_itmstk_item_id]);
			$lp_itmstk_dispatched=intval($stock_dis_arr[$lp_itmstk_item_id]);
			$lp_itmstk_item_close_stock=($lp_itmstk_item_open_stock+$lp_itmstk_received-$lp_itmstk_dispatched);
			
			$dispItm[$lp_itmstk_item_id]['item_id']=$lp_itmstk_item_id;
			$dispItm[$lp_itmstk_item_id]['category_name']=$lp_itmstk_category_name;
			$dispItm[$lp_itmstk_item_id]['item_code']=$lp_itmstk_item_code; 
			$dispItm[$lp_itmstk_item_id]['item_desc']=$lp_itmstk_item_description; 
			$dispItm[$lp_itmstk_item_id]['details'][$putdate]=array('item_open_stock'=>$lp_itmstk_item_open_stock, 'item_received'=>$lp_itmstk_received, 'item_dispatched'=>$lp_itmstk_dispatched, 'item_close_stock'=>$lp_itmstk_item_close_stock );
			
			 
		} 
		 
	}
	
	/*echo '<pre>';
	print_r($dispItm);
	echo '</pre>';*/
 ?>
 
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <!-- Exportable Table -->
      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card pull-left" style="padding-bottom:20px;">
            <div class="header">
              <h2> Stock report </h2> 
            </div>
            <div class="body"> 
              <div id="divTabPoEntry" class="tabcontent pull-left">
                <div id="showPoEntry">
                  <form role="form" id="frmStockReportView">  
				  <div class="col-md-12 no-padding"  >
                    <div class="col-md-7 no-padding"  >
						<div class="col-md-6 no-padding-left">
                        	<h4><label>Filters</label> </h4>
                      	</div>
						<div class="col-md-6 no-padding-left">
                        	<h4><label>Date range</label> </h4>
                      	</div>
                      <div class="col-md-6 no-padding-left">
                        <label>Category</label> 
                        <select class="form-control show-tick" id="category_id" name="category_id" onchange="stockReportCategoryOnChange()" >
						<option value="0"></option> 
						<?php foreach($categorylist as $categorydata){ ?>
						<option value="<?php echo  $categorydata["category_id"];?>" <?php if($get_categoryid==$categorydata["category_id"]) echo 'selected'; ?> ><?php echo  $categorydata["category_name"];?></option>
						<?php } ?>
                        </select>
                      </div>
                      <div class="col-md-12 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>Date from </label> 
                            <input type="text" id="date_from" name="date_from" class="form-control datepicker" value="<?php echo $get_datefrom;?>"   >
                          </div>
                        </div>
                      </div>
					  <div class="col-md-6 no-padding-left">
                        <label>Item code</label>
						
                        <select class="form-control show-tick" id="cmb_item_id" name="cmb_item_id">
						<option value="0"></option>
						<?php foreach($itemlist as $itemdata){ ?>
						<option value="<?php echo  $itemdata["item_id"];?>"  <?php if($get_cmbitemid== $itemdata["item_id"]) echo 'selected'; ?> atr_catid="<?php echo  $itemdata["category_id"];?>"><?php echo  $itemdata["item_code"];?></option>
						<?php } ?>
                        </select>
                      </div>
                      <div class="col-md-12 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>Date to </label>
                            <input type="text" id="date_to" name="date_to" class="form-control datepicker" value="<?php echo $get_dateto;?>"   >
                          </div>
                        </div>
                      </div> 
                       
                       
                    </div>
                    <div class="col-md-5 no-padding-left" style="margin-top:15px;">
						<div class="col-md-12 no-padding-left"><div class="form-group form-float"><label>&nbsp;</label></div></div>
						<div class="col-md-12 no-padding-left"><div class="form-group form-float"><label>&nbsp;</label></div></div>
						 <div class="col-md-12 no-padding-left"><div class="form-group form-float"><label>&nbsp;</label></div></div>
						 
                       <div class="col-md-12 no-padding-left">
					    
								<button type="button" class="btn btn-success act-add" onclick="stockReportGenerateOp();"><i class="fa fa-plus"></i> Generate</button>
								<button type="button" class="btn btn-warning  " onclick="stockReportCancel();">CANCEL</button>
								<button type="button" class="btn btn-primary editCategory" onclick="stockReportGenerateExcel();"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                
				</div>
                    </div>
					</div>
                     <div class="col-md-12 no-padding-left">
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customFieldsPOEntry">
                      <thead>
                        <tr>
						<td>Category&nbsp;</td>
						<td>Item Code&nbsp;</td>
						<td>Item Description&nbsp;</td>
						<?php
							foreach($dispDateArr as $dispDateVal)
							{
						 ?>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0">
							  <tr>
								<td colspan="4" align="center" ><?php echo $modobj->convertDate($dispDateVal);?></td> 
							  </tr>
							  <tr>
								<td>Open&nbsp;</td>
								<td>Received&nbsp;</td>
								<td>Dispatched&nbsp;</td>
								<td>Closing&nbsp;</td>
							  </tr>
							</table>
						</td>
						<?php } ?>
                      </thead>
                      <tbody>
					  <?php 
							foreach($dispItm as $dispItmKey=>$dispItmVal)
							{ 
						  ?>
						  <tr class="item-row" >
						<td><?php echo $dispItmVal["category_name"];?>&nbsp;</td>
						<td><?php echo $dispItmVal["item_code"];?>&nbsp;</td>
						<td><?php echo $dispItmVal["item_desc"];?>&nbsp;</td>
						<?php
							foreach($dispDateArr as $dispDateVal)
							{
							
								$lpd_open=$dispItmVal["details"][$dispDateVal]['item_open_stock'];
								$lpd_received=$dispItmVal["details"][$dispDateVal]['item_received'];
								$lpd_dispatched=$dispItmVal["details"][$dispDateVal]['item_dispatched'];
								$lpd_closed=$dispItmVal["details"][$dispDateVal]['item_close_stock'];
						 ?>
						<td>
							<table width="100%"   cellspacing="0" cellpadding="0"> 
							  <tr>
								<td><?php echo $lpd_open;?>&nbsp;</td>
								<td><?php echo $lpd_received;?>&nbsp;</td>
								<td><?php echo $lpd_dispatched;?>&nbsp;</td>
								<td><?php echo $lpd_closed;?>&nbsp;</td>
							  </tr>
							</table>
						</td>
						<?php } ?>
						  </tr>
	  <?php } ?>
                         
                      </tbody>
                    </table>
					</div> 
                     					
                  </form>
                </div>
              </div>
               
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
 

 <table width="100%" border="1" cellspacing="0" cellpadding="0" style="display:none;" >
  <tr>
    <td>Category&nbsp;</td>
    <td>Item Code&nbsp;</td>
    <td>Item Description&nbsp;</td>
	<?php
		foreach($dispDateArr as $dispDateVal)
		{
	 ?>
    <td>
		<table width="100%" border="1" cellspacing="0" cellpadding="0">
		  <tr>
			<td colspan="4" ><?php echo $dispDateVal;?></td> 
		  </tr>
		  <tr>
			<td>Open&nbsp;</td>
			<td>Received&nbsp;</td>
			<td>Dispatched&nbsp;</td>
			<td>Closing&nbsp;</td>
		  </tr>
		</table>
	</td>
	<?php } ?>
	  </tr>
	  <?php 
		foreach($dispItm as $dispItmKey=>$dispItmVal)
		{ 
	  ?>
	  <tr>
    <td><?php echo $dispItmVal["item_id"];?>&nbsp;</td>
    <td><?php echo $dispItmVal["item_code"];?>&nbsp;</td>
    <td><?php echo $dispItmVal["item_desc"];?>&nbsp;</td>
	<?php
		foreach($dispDateArr as $dispDateVal)
		{
		
			$lpd_open=$dispItmVal["details"][$dispDateVal]['item_open_stock'];
			$lpd_received=$dispItmVal["details"][$dispDateVal]['item_received'];
			$lpd_dispatched=$dispItmVal["details"][$dispDateVal]['item_dispatched'];
			$lpd_closed=$dispItmVal["details"][$dispDateVal]['item_close_stock'];
	 ?>
    <td>
		<table width="100%" border="1" cellspacing="0" cellpadding="0"> 
		  <tr>
			<td><?php echo $lpd_open;?>&nbsp;</td>
			<td><?php echo $lpd_received;?>&nbsp;</td>
			<td><?php echo $lpd_dispatched;?>&nbsp;</td>
			<td><?php echo $lpd_closed;?>&nbsp;</td>
		  </tr>
		</table>
	</td>
	<?php } ?>
	  </tr>
	  <?php } ?>
</table>
