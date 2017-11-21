<form role="form" id="frmStockEntryMaster" method="post" name="frmStockEntryMaster" enctype="multipart/form-data">
  <input type="hidden" name="hid_id" id="hid_id" />
   <input type="hidden" name="module" id="module"  value="stock_entry"/>
   <input type="hidden" name="action" id="action"  value="save"/>
     <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
  <div id="message"></div>
                	<div class="col-md-12 no-padding demo-radio-button viewaddmode">
						<input name="chk_stock_entry" type="radio" class="with-gap" value="1" id="chk_stock_entry1"  onclick="viewByStockEntryType()"><label for="chk_stock_entry1"> By Entering Item details</label>
						<input name="chk_stock_entry" type="radio" class="with-gap" value="2" id="chk_stock_entry2" onclick="viewByStockEntryType()"><label for="chk_stock_entry2" > By Purchase Order or Sales Order</label>
						<p>&nbsp;</p>
					</div>
                    
                    <div class="col-md-12 no-padding-left stock_option1">                
						<table class="inputGridTable item-grid" id="customFields" width="100%" >
							<thead>
								<tr>
									<th width="21%">Item</th>									
									<th width="28%">Qty</th>
                                    <th width="27%">Received Date</th>
                                    <th width="27%">PO#</th>
									<th width="4%">&nbsp;</th>
								</tr>								
							</thead>
							<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_itmgrp_detid[]"  id="hdn_itmgrp_detid_rw"/>
									<select class="form-control show-tick cmbitem_list" name="cmb_item[]" id="cmb_item_rw" onchange="checkStockEntryExist(this)"></select>
									</td>
									<td>  <input type="text" class="form-control"  name="txt_qty[]" id="txt_qty_rw" onkeypress="return ValidateNumberKeyPress(this, event);" />
									</td>
                                    <td>  <input type="text" class="form-control datepicker"  name="txt_received_date[]" id="txt_received_date_rw"  />
									</td>
									<td>
									<input type="text" class="form-control"  name="txt_po[]" id="txt_po_rw"  maxlength="30"   style="text-align:left;"   />	
									</td>
									<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deleteStockEntryItemMasEntryRow(rw)"></i></div></td>
								</tr>								
							</tbody>
						</table>     
						<button type="button" class="btn btn-success addrow" onclick="addStockEntryItemMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>       
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
							<button class="btn btn-info" type="button" style="margin-top:25px" onclick="searchByViewPOSODetails();">Search</button>
						</div>
						<div class="col-md-12">
							<h4>PO Details</h4>
						</div><input type="hidden" name="hdn_po_head_id"  id="hdn_po_head_id"/>
						<table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customPoDet">
						<thead>
							<tr>
								<th width="50%">Item </th>
								<th width="15%">Qty</th>
								<th width="15%">Status </th>                                
							</tr>
						</thead>	
						<tbody>
                        <tr class="item-row_po" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_po_det_id[]"  id="hdn_po_det_id_rw"/>
                                    <input type="hidden" name="hdn_stock_det_id[]"  id="hdn_stock_det_id_rw"/>
                                    <input type="hidden" name="hdn_item_id[]"  id="hdn_item_id_rw" class="cmbitem_list"/>
									<input type="text" class="form-control"  name="txt_item[]" id="txt_item_rw" readonly="readonly"/>
									</td>
									<td>  <input type="text" class="form-control"  name="txt_rec_qty[]" id="txt_rec_qty_rw" onkeypress="return ValidateNumberKeyPress(this, event);" />
									</td>
                                    <td>  <select class="form-control" id="cmb_poitem_status_rw" name="cmb_poitem_status[]">
									<option value="0">Select</option>
									<option value="1">Stock</option>
									<option value="2">Pending</option>
								</select>
									</td>
								</tr>								
							
						</tbody> 
						</table>						
					</div>
</form>