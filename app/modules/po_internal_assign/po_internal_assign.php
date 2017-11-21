<form role="form" id="frmPOInternalAssignMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="hid_temp_del" id="hid_temp_del"/> 
  <input type="hidden" name="module" id="module"  value="po_internal_assign"/>
   <input type="hidden" name="action" id="action"  value="save"/>
   <input type="hidden" name="hdn_po_assign_head_id" id="hdn_po_assign_head_id"  value=""/>
  <div id="message"></div>
  <div class="col-md-12 no-padding">         
                        <div class="col-md-2 no-padding-left">                
							<label>PO Number</label>
						</div>	
						<div class="col-md-4">                
							<label><input type="text" id="po_number" name="po_number" class="form-control" maxlength="30"></label>
                        </div>       
                        <div class="col-md-2 no-padding-left">                
							<label>Internal PO Number</label>
						</div>	
						<div class="col-md-4">                
							<label><input type="text" id="internal_po_number" name="internal_po_number" class="form-control" maxlength="30"></label>
                        </div>
					</div>
					<div class="col-md-12 no-padding">         	
						<div class="col-md-2 no-padding-left">                
							<label>Received Date</label>
						</div>
						<div class="col-md-4">                	
							<label><input type="date" id="received_date" name="received_date" class="form-control" ></label>
                        </div>
						<div class="col-md-2 no-padding-left">                
							<label>Assigned To</label>
						</div>
						<div class="col-md-4">                	
							<label><select class="form-control show-tick" id="vendor_id" name="vendor_id">												
											</select></label>
                        </div>
					</div>
					<div class="col-md-12 no-padding">         	
						<div class="col-md-2 no-padding-left">                
							<label>Assigned Date</label>
						</div>
						<div class="col-md-4">                	
							<label><input type="date" id="assigned_date" name="assigned_date" class="form-control" ></label>
                        </div>                           
                    </div> 
                	                  
					<div class="col-md-12 no-padding">   
						<button type="button" class="btn btn-success addrow" onclick="addPOEntryMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>               
					</div>	
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customFields"> 
						<thead>
							<tr>
								<th width="5%">&nbsp;</th>
                                <th width="12%">Item Code</th>
                                <th width="20%">Description</th>
                                <th width="10%">PO Qty</th>
                                <th width="10%">Qty Req</th>
                                <th width="11%">Vendor Unit Price</th>
                                <th width="11%">Total Price</th> 
                                <th width="10%">Taxes</th>
                                <th width="4%">&nbsp;</th>
							</tr>
						</thead>	
						<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><div class="demo-radio-button" style="width:10px;"><input name="chk_po_internal_assign[]" type="checkbox" class="with-gap" value="1" id="chk_rw"><label for="chk_rw">&nbsp;</label></div></td>
                                    <td><input type="hidden" name="hdn_po_detid[]"  id="hdn_po_detid_rw"/>
									<select class="form-control show-tick" name="cmb_item[]" id="cmb_item_rw"></select></td>
									<td><input type="text" class="form-control"  name="item_desc[]" id="item_desc_rw"  maxlength="100" /></td>
									<td><input type="text" class="form-control"  name="po_qty[]" id="po_qty_rw"  maxlength="50"  /></td>
									<td><input type="text" class="form-control poorder"  name="req_qty[]" id="req_qty_rw" onkeypress="return numbersonly(event);" maxlength="4"   style="text-align:right;" onchange="calCulatePOInternalAssignTotal()"   /></td>
									<td><input type="text" class="form-control pounit"  name="unit_cost[]" id="unit_cost_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="9"   style="text-align:right;"  onchange="calCulatePOInternalAssignTotal()"/></td>
									<td><input type="text" class="form-control"  name="line_total[]" id="line_total_rw" readonly=""  style="text-align:right;"/></td>
                                    <td><input type="text" class="form-control potax"  name="item_tax[]" id="item_tax_rw"   style="text-align:right;" onchange="calCulatePOInternalAssignTotal()"/></td>
									<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deletePOInternalAssignMasEntryRow(rw)"></i></div></td>
								</tr>								
							</tbody>
							
						  
						</table>						
                        <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable ">
						<tbody>
							<tr class="item-row">
								<td width="50%">&nbsp;</td>
								<td width="10%">Total</td>
								<td width="23%"><input type="text" id="po_total" name="po_total" class="form-control" readonly="readonly"></td>
							</tr> 
							<tr class="item-row">
								<td>&nbsp;</td>
								<td>Grant Total</td>
								<td ><input type="text" id="po_grant_total" name="po_grant_total" class="form-control" readonly="readonly"></td>
							</tr> 							 
						</tbody> 
					</table>
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable" >                     
							<tr>
								<td width="30%">Shipments</td>
								<td width="30%">Remarks</td>
								<td width="20%">Documents Type</td>
								<td width="20%">File Upload</td>
							</tr> 
							<tr>
								<td><textarea class="form-control" rows="3"  name="po_internal_assign_terms" id="po_internal_assign_terms"></textarea></td>
								<td><textarea class="form-control" rows="3"  name="po_internal_assign_remarks" id="po_internal_assign_remarks"></textarea></td>
								<td><select class="form-control show-tick" id="po_internal_assign_type" name="po_internal_assign_type">
									<option value="0">Select</option>
									<option value="1">Sales Order</option>
									<option value="2">Bill</option>
									<option value="3">Others</option>
								</select></td>
								<td><input type="file" class="form-control" name="file_internal_po" id="file_internal_po" /><span class="viewLogo" style="display:none; cursor:pointer"><strong><u>View</u></strong></span></td>
							</tr> 
							                     
						</table>
                        
                        <div class="col-md-4 no-padding-left">                
						<label>Status</label>
						<select class="form-control show-tick" id="po_internal_assign_status" name="po_internal_assign_status">
							<option  value="0">Select</option>
							<option value="1">Created</option>
							<option value="2">Sent</option>
							<option value="3">Completed</option>
							<option value="4">Cancelled</option>
						</select>
                    </div>
						
                   
</form>