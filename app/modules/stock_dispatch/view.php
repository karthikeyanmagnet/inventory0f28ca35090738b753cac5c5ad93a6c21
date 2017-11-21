<form role="form" id="frmStockDispatchMaster" method="post" name="frmStockDispatchMaster" enctype="multipart/form-data">
  <input type="hidden" name="hid_id" id="hid_id" />
   <input type="hidden" name="module" id="module"  value="stock_dispatch"/>
   <input type="hidden" name="action" id="action"  value="save"/>
     <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
  <div id="message"></div>
                	<div class="col-md-12 no-padding demo-radio-button viewaddmode">
						<input name="chk_stock_dispatch" type="radio" class="with-gap" value="1" id="chk_stock_dispatch1"  onclick="viewByStockDispatchType()"><label for="chk_stock_dispatch1"> Dispatch by Entering Item Details</label>
						<input name="chk_stock_dispatch" type="radio" class="with-gap" value="2" id="chk_stock_dispatch2" onclick="viewByStockDispatchType()"><label for="chk_stock_dispatch2" > Dispatch for a Purchase Order</label>
                        <input name="chk_stock_dispatch" type="radio" class="with-gap" value="3" id="chk_stock_dispatch3" onclick="viewByStockDispatchType()"><label for="chk_stock_dispatch3" > Dispatch by Item Group</label>
                        
                       <p>&nbsp;</p>
					</div>
                    
                    <div class="col-md-12 no-padding-left stock_option1">                
						<table class="inputGridTable item-grid" id="customFields" width="100%" >
							<thead>
								<tr>
									<th width="21%">Item</th>									
									<th width="10%"> Available Qty</th>
                                    <th width="10%"> Dispatch Qty</th>
                  					<th width="10%"> Dispatch Date</th>
									<th width="20%">PO#</th>
									<th width="10%">Remaining Qty</th>
                                    <th width="4%"></th>

								</tr>								
							</thead>
							<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_itmgrp_detid[]"  id="hdn_itmgrp_detid_rw"/>
									<select class="form-control show-tick cmbitem_list" name="cmb_item[]" id="cmb_item_rw" onchange="getItemQuantityDetails(this);"></select>
									</td>
                                    <td><span name="txt_ava_qty[]" id="txt_ava_qty_rw"></span></td>
									<td>  <input type="text" class="form-control"  name="txt_qty[]" id="txt_qty_rw" onkeypress="return ValidateNumberKeyPress(this, event);" onchange="putItemRemainingQty(this);" />
                                    <td>  <input type="text" class="form-control datepicker"  name="txt_dispatch_date[]" id="txt_dispatch_date_rw" />
									</td>
									<td>
									<input type="text" class="form-control"  name="txt_po[]" id="txt_po_rw"  maxlength="30"   style="text-align:left;"   />	
									</td>
                                    <td><span name="txt_remaing_qty[]" id="txt_remaing_qty_rw"></span></td>
									<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deleteStockDispatchItemMasEntryRow(rw)"></i></div></td>
								</tr>								
							</tbody>
						</table>     
						<button type="button" class="btn btn-success addrow" onclick="addStockDispatchItemMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>       
                    </div>
                    
                    <div class="col-md-12 no-padding stock_option2">
						<div class="col-md-4 viewaddmode">
							<div class="form-group form-float">
								<div class="form-line">
									<label>PO or SO Number</label>
									<input type="text" id="po_so_number" class="form-control" name="po_so_number">
								</div>
							</div>
						</div>
						<div class="col-md-4 viewaddmode">
							<label>&nbsp;</label>
							<button class="btn btn-info" type="button" style="margin-top:25px" onclick="searchByViewPOSODetailsDispatch();">Search</button>
						</div>
						<div class="col-md-12">
							<h4>PO Details</h4>
						</div><input type="hidden" name="hdn_po_head_id"  id="hdn_po_head_id"/>
						<table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customPoDet">
						<thead>
							<tr>
								<th width="50%">Item </th>
								<th width="10%"> Available Qty</th>
                                <th width="10%"> Dispatch Qty</th>
                                <th width="10%">Remaining Qty</th>
								<th width="15%">Status </th>                                
							</tr>
						</thead>	
						<tbody>
                        <tr class="item-row_po" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_po_det_id[]"  id="hdn_po_det_id_rw"/>
                                    <input type="hidden" name="hdn_stock_dispatch_det_id[]"  id="hdn_stock_dispatch_det_id_rw"/>
                                    <input type="hidden" name="hdn_item_id[]"  id="hdn_item_id_rw" class="cmbitem_list"/>
									<input type="text" class="form-control"  name="txt_item[]" id="txt_item_rw" readonly="readonly"/>
									</td>
                                     <td><span name="txt_ava_qty[]" id="txt_ava_qty_rw"></span></td>
									<td>  <input type="text" class="form-control"  name="txt_disp_qty[]" id="txt_disp_qty_rw" onkeypress="return ValidateNumberKeyPress(this, event);" onchange="putItemRemainingQty(this);" />
									</td>
                                    
                                    <td><span name="txt_remaing_qty[]" id="txt_remaing_qty_rw"></span></td>
                                    <td>  <select class="form-control" id="cmb_poitem_status_rw" name="cmb_poitem_status[]">
									<option value="0">Select</option>
									<option value="1">Dispatch</option>
									<option value="2">Pending</option>
								</select>
									</td>
								</tr>								
							
						</tbody> 
						</table>						
					</div>
                    
                    <div class="col-md-12 no-padding stock_option3">
						<div class="col-md-4 viewaddmode">
							<label>Items Group Name</label>
							<select class="form-control" name="cmb_item_group" id="cmb_item_group" onchange="setItemGrpQty()">
								<option>Select</option>
							
							</select>
						</div>
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<label>Quantity</label>
									<input type="text" id="txt_grp_qty" name="txt_grp_qty" class="form-control" onkeypress="return ValidateNumberKeyPress(this, event);" onkeyup="setDispatchQuantityItem(this);">
								</div>
							</div>
						</div>
                        <div class="col-md-4 viewaddmode">
							<label>&nbsp;</label>
							<button class="btn btn-info" type="button" style="margin-top:25px" onclick="searchByViewProductDetails();">Search</button>
						</div>
						<table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customProductdet">
						<thead>
							<tr>
								<th width="55%">Item </th>
								<th width="15%">Available Qty</th>
								<th width="15%">Dispatch Qty</th>
								<th width="15%">Remaining Qty</th>
							</tr>
						</thead>	
						<tbody>
                        <tr class="item-row_prd" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_prd_po_det_id[]"  id="hdn_prd_po_det_id_rw"/>
                                    <input type="hidden" name="hdn_prd_stock_dispatch_det_id[]"  id="hdn_prd_stock_dispatch_det_id_rw"/>
                                    <input type="hidden" name="hdn_prd_item_id[]"  id="hdn_prd_item_id_rw" class="cmbitem_list"/>
									<input type="text" class="form-control"  name="txt_prd_item[]" id="txt_prd_item_rw" readonly="readonly"/>
									</td>
                                    <td><span name="txt_ava_qty[]" id="txt_ava_qty_rw"></span>
									</td>
									<td>  <input type="text" class="form-control"  name="txt_prd_disp_qty[]" id="txt_prd_disp_qty_rw" onkeypress="return ValidateNumberKeyPress(this, event);" onchange="putItemRemainingQty(this);" />
									</td>
                                     <td><span name="txt_remaing_qty[]" id="txt_remaing_qty_rw"></span>
                                     <input type="hidden" name="hdn_itm_qty_req[]" id="hdn_itm_qty_req_rw" />
									</td>
								</tr>								
							
							  
						</tbody> 
						</table>						
					</div>
</form>