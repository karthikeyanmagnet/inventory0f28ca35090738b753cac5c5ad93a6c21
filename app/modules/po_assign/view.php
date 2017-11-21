 <div class="content-wrapper">

	<section class="content" style="margin:0px">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="card po-create">
            <div class="header">
                <h2 class="modal-title">&nbsp;
                    
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect createInternalPO" style="display:none" onclick="closePOInternalAssignDiaglog();loadPOInternalAssignMaster();">Create Internal PO</button>
                    <button type="button" class="btn btn-primary m-t-15 waves-effect savePOAssign" onclick="CreateUpdatePOAssignMasterSave();">SUBMIT</button>
                     <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="loadPOEntryMaster();">CANCEL</button> 
                </div>               
            </div>
            <div class="body">
            <form role="form" id="frmPOAssignMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="hid_temp_del" id="hid_temp_del"/> 
    <input type="hidden" name="hid_po_orderid" id="hid_po_orderid"/> 
                	<div class="col-md-12 no-padding">         
										<div class="col-md-3 no-padding-left">                
											<label>Vendor Name</label>
											<select class="form-control show-tick" id="vendor_id" name="vendor_id">												
											</select>
										</div>    
                                         <div class="col-md-1">   
						<button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('vendor', this)" cmb-view="vendor_id"><i class="fa fa-plus"></i></button>
					</div>   
										<div class="col-md-2 no-padding-left"> 
                                               
											               
                                                <label>Internal PO Number</label>
                                                           
                                                <select name="posname" class="form-control">
                                                    <option > 3PL/M  </option>
                                                    <option > 3PL/L  </option>
                                                    <option > 3PL/G  </option>
                                                    <option > 3PL/R  </option>
                                                    
                                                </select>
                                        </div>
                            
                            <div class="col-md-4 no-padding-left"> 
                                               
											               
                                                <label>Internal PO Number</label>
                                                           
                                                <input type="text" id="internal_po_number" name="internal_po_number" class="form-control" maxlength="30">
                                        </div>
										                                          
									</div>                  
					<div class="col-md-12 no-padding">   
						<!--<button type="button" class="btn btn-success addrow" onclick="addPOAssignMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>  -->             
					</div>	
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customFields"> 
						<thead>
							<tr>
								<th width="5%">&nbsp;</th>
                                <th width="12%">Item Code</th>
                                <th width="20%">Description</th>
                                <th width="10%">PO Qty</th>
                                <th width="10%">Qty Avl</th>
                                <th width="10%">Qty Req</th>
                                <th width="11%">Vendor Unit Price</th>
                                <th width="11%">Total Price</th> 
                                <th width="10%">Taxes</th>
                                <!--<th width="4%">&nbsp;</th>-->
							</tr>
						</thead>	
						<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><input name="chk_po_assign_rw" type="checkbox" class="po_assign_chkbx" value="1" id="chk_rw" onclick="calCulatePOAssignTotal()" style="opacity: 1;position: relative;left: 0px;"></td>
                                    <td><input type="hidden" name="hdn_po_detid[]"  id="hdn_po_detid_rw"/>
									<select class="form-control show-tick" name="cmb_item[]" id="cmb_item_rw"></select></td>
									<td><input type="text" class="form-control"  name="item_desc[]" id="item_desc_rw"  maxlength="100" /></td>
									<td><input type="text" class="form-control"  name="po_qty[]" id="po_qty_rw"  maxlength="50"  /></td>
									<td><input type="text" class="form-control poweight"  name="qty_avaliable[]" id="qty_avaliable_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="7"   style="text-align:right;"   /></td>
									<td><input type="text" class="form-control poorder"  name="req_qty[]" id="req_qty_rw" onkeypress="return numbersonly(event);" maxlength="4"   style="text-align:right;" onchange="calCulatePOAssignTotal()"   /></td>
									<td><input type="text" class="form-control pounit"  name="unit_cost[]" id="unit_cost_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="9"   style="text-align:right;"  onchange="calCulatePOAssignTotal()"/></td>
									<td><input type="text" class="form-control"  name="line_total[]" id="line_total_rw" readonly=""  style="text-align:right;"/></td>
                                    <td><input type="text" class="form-control potax"  name="item_tax[]" id="item_tax_rw"   style="text-align:right;" onchange="calCulatePOAssignTotal()" /></td>
									<!--<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deletePOAssignMasEntryRow(rw)"></i></div></td>-->
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
												<td width="30%">Shipment terms</td>
												<td width="30%">Remarks</td>
												<td width="30%">Delivery Date (on or before)</td>
												<td width="10%"><input type="date" id="delivery_date_before" name="delivery_date_before" class="form-control" ></td>
											</tr> 
											<tr>
												<td rowspan="3"><textarea class="form-control" rows="3" name="po_assign_terms" id="po_assign_terms"></textarea></td>
												<td rowspan="3"><textarea class="form-control" rows="3" name="po_assign_remarks" id="po_assign_remarks"></textarea></td>
												<td>Delivery Date (after)</td>
												<td><input type="date" id="delivery_date_after" name="delivery_date_after" class="form-control"></td>
											</tr> 
											<tr> 
												<td>Status</td>
												<td><select class="form-control show-tick" id="active_status" name="active_status">
                        <option value="0" >Select</option>
                        <option value="1">Active</option>
                        <option value="2">Ordered</option>
                        <option value="3">Cancelled</option>
                        <option value="4">Shipment</option>
                        <option value="5">Closed</option>
                      </select></td>
											</tr>
										</table>
                                      
                   
</form>
</div>
</div>
</div>
</div>

<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="card">
                        <div class="header">
                            <h2>
                                PURCHASE ORDER 
                            </h2>
							 <div class="actionsBar">
                            	<!--<button class="btn btn-success createPOAssign act-add" onclick="CreateUpdatePOAssignMasterList();"><i class="fa fa-plus"></i> Create</button>-->
								
                            </div>                      
                        </div>
                        <div class="body">
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="pOAssignMasterTbl" style="width:100%" >
                                <thead>
                                    <tr>
                                       <th>Internal PO #</th>
                                        <th>Created Date</th>
                                        <th>Assign To</th>
                                        <th>Total Amount</th>
										<th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>                             
                                </tbody>
                            </table>
                      </div>
                    </div>
                </div>
            </div>
</div>
</section>
</div>

