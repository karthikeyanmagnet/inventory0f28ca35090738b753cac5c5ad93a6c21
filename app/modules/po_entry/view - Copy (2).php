 <div class="content-wrapper">
	<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                PURCHASE ORDER 
                            </h2>
							 <div class="actionsBar">
                            	<button class="btn btn-success createPOAssign act-add" onclick="CreateUpdatePOAssignMasterList();"><i class="fa fa-plus"></i> Create</button>
								
                            </div>                      
                        </div>
                        <div class="body">
               
<span onclick="viewPOEntry();">PO Entry</span> &nbsp; <span onclick="viewPOAssignEntry();">Supplier Assignment</span>
<div id="showPoEntry">
<form role="form" id="frmPOEntryMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="hid_temp_del" id="hid_temp_del"/> 
  <div class="header">
                <h2 class="modal-title">
                    &nbsp;
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect savePOEntry" onclick="CreateUpdatePOEntryMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="loadPOEntryMaster();">CANCEL</button>
                </div>               
            </div>
                	<div class="col-md-8 no-padding">         
                        <div class="col-md-6 no-padding-left">                
							<label>Customer Name</label>
							<select class="form-control show-tick" id="customer_id" name="customer_id"> 
							</select>
                        </div>       
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>Ship Date</label>
                                    <input type="date" id="ship_date" name="ship_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>PO Number</label>
                                    <input type="text" id="po_number" name="po_number" class="form-control" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>SPG Order Type</label>
                                    <input type="text" id="spg_order_type" name="spg_order_type" class="form-control" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>SO Number</label>
                                    <input type="text" id="so_number" name="so_number" class="form-control" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>PO Type</label>
                                    <input type="text" id="po_type" name="po_type" class="form-control" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>Cust PO Number</label>
                                    <input type="text" id="cust_po_number" name="cust_po_number" class="form-control" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 no-padding-left">                
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label>Issuer</label>
                                     <input type="text" id="issuer" name="issuer" class="form-control" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Terms</label>
                                <textarea id="po_terms" name="po_terms" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>     
					<div class="col-md-12 no-padding">   
						<button type="button" class="btn btn-success addrow" onclick="addPOEntryMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>               
					</div>	
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customFieldsPOEntry"> 
						<thead>
							<tr>
								<th width="15%">Item Code</th>
								<th width="25%">Description</th>
								<th width="10%">Whse </th>
								<th width="10%">Weight</th>
								<th width="10%">Ordered</th>
								<th width="13%">Unit Cost</th>
								<th width="13%">Amount</th>
								<th width="4%">&nbsp;</th>
							</tr>
						</thead>	
						<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_po_detid[]"  id="hdn_po_detid_rw"/>
									<select class="form-control show-tick" name="cmb_item[]" id="cmb_item_rw"></select></td>
									<td><input type="text" class="form-control"  name="item_desc[]" id="item_desc_rw"  maxlength="100" /></td>
									<td><input type="text" class="form-control"  name="whse_line[]" id="whse_line_rw"  maxlength="50"  /></td>
									<td><input type="text" class="form-control poweight"  name="weight_line[]" id="weight_line_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="7"   style="text-align:right;"  onchange="calCulatePOWeight();"  /></td>
									<td><input type="text" class="form-control poorder"  name="ordered_qty[]" id="ordered_qty_rw" onkeypress="return numbersonly(event);" maxlength="4"   style="text-align:right;" onchange="calCulatePOEntryTotal()"   /></td>
									<td><input type="text" class="form-control pounit"  name="unit_cost[]" id="unit_cost_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="9"   style="text-align:right;"  onchange="calCulatePOEntryTotal()"/></td>
									<td><input type="text" class="form-control"  name="line_total[]" id="line_total_rw" readonly=""  style="text-align:right;"/></td>
									<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deletePOEntryMasEntryRow(rw)"></i></div></td>
								</tr>								
							</tbody>
							
						  
						</table>						
						<table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable" >                     
							<tr>
								<td width="60%">Notes</td>
								<td width="10%">&nbsp;</td>
								<td width="15%">Order Total</td>
								<td width="15%"><input type="text" id="order_total" name="order_total" class="form-control" readonly="readonly"></td>
							</tr> 
							<tr>
								<td rowspan="2"><textarea class="form-control" name="po_notes" id="po_notes" rows="3"></textarea></td>
								<td>&nbsp;</td>
					
								<td>Weight(LBS)</td>
								<td><input type="text" id="total_weight_lbs" name="total_weight_lbs" class="form-control" readonly="readonly"></td>
							</tr> 
							<tr>
								<td>&nbsp;</td>
								<td>Weight(MT)</td>
								<td><input type="text" id="total_weight_mt" name="total_weight_mt" class="form-control" readonly="readonly"></td>
							</tr>                        
						</table>
                    <div class="col-md-4 no-padding-left">                
						<label>Status</label>
						<select class="form-control show-tick" id="active_status" name="active_status">
							<option value="0" >Select</option>
							<option value="1">Active</option>
							<option value="2">Ordered</option>
							<option value="3">Cancelled</option>
							<option value="4">Shipment</option>
							<option value="5">Closed</option>
						</select>
                    </div> 
 
</form>

</div>
<div id="showPoInternalEntry" style="display:none">

</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>
<div class="AddEditForm largeBox" id="viewPageModal">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                    PURCHASE ORDER CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect savePOEntry" onclick="CreateUpdatePOAssignMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closePOEntryDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
              
            </div>
        </div>
	</div>
</div>