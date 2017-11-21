<form role="form" id="frmVendorMaster" method="post">
  <input type="hidden" name="hid_id" id="hid_id" />
   <input type="hidden" name="module" id="module"  value="vendor"/>
   <input type="hidden" name="action" id="action"  value="save"/>
   <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
  <div id="message"></div>
                	<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Supplier Name <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="supplier_name" name="supplier_name" class="form-control" maxlength="70" >
                            </div>
                        </div>
                    </div>       
            		<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Address</label>
                                <input type="text" id="supplier_address" name="supplier_address" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>City</label>
                                <input type="text" id="supplier_city" name="supplier_city" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
					
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>State</label>
                                <input type="text" id="supplier_state" name="supplier_state" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>ZIP Code</label>
                                <input type="text" id="supplier_zipcode" name="supplier_zipcode" class="form-control"  maxlength="20">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Phone </label>
                                <input type="text" id="supplier_phone" name="supplier_phone" class="form-control"  maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Email</label>
                                <input type="text" id="supplier_email" name="supplier_email" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Website</label>
                                <input type="text" id="supplier_website" name="supplier_website" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Logo</label>
                                <input type="file" id="supplier_logo" name="supplier_logo" class="form-control"> <span class="viewLogo" style="display:none; cursor:pointer"><strong><u>View logo</u></strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>TIN / CST#</label>
                                <input type="text" id="supplier_tin_cst" name="supplier_tin_cst" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Excise No</label>
                                <input type="text" id="supplier_excise_no" name="supplier_excise_no" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Contact Person</label>
                                <input type="text" id="supplier_contact_name" name="supplier_contact_name" class="form-control"  maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Contact Number</label>
                                <input type="text" id="supplier_contact_no" name="supplier_contact_no" class="form-control"  maxlength="20">
                            </div>
                        </div>
                    </div>                    
                    <div class="col-md-12 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Shipping Terms</label>
                                <textarea id="supplier_shipping_terms" name="supplier_shipping_terms" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Payment Terms</label>
                                <textarea id="supplier_payment_terms" name="supplier_payment_terms" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 no-padding-left">
                    	<div class="form-group form-float">
                            <input type="checkbox" id="active_status" name="active_status" class="filled-in" value="1">
                            <label for="active_status">Active</label>
                        </div>
                    </div> 
                    
                    <div class="col-md-12 no-padding-left">                
						<table class="table table-bordered item-grid" id="customFields" width="100%" >
							<thead>
								<tr>
									<th width="31%">Item</th>
									<!--<th width="37%">Description</th>-->
									<th width="28%">Unit Price</th>
									<th width="4%">&nbsp;</th>
								</tr>								
							</thead>
							<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_itmgrp_detid[]"  id="hdn_itmgrp_detid_rw"/>
									<select class="form-control show-tick" name="cmb_item[]" id="cmb_item_rw"></select>
                                     <button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('item', this)" cmb-view="cmb_item_rw"><i class="fa fa-plus"></i></button>
									</td>
									<!--<td>  <input type="text" class="form-control"  name="txt_desc[]" id="txt_desc_rw"  />
									</td>-->
									<td>
									<input type="text" class="form-control"  name="txt_amount[]" id="txt_amount_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="7"   style="text-align:right;"   />	
									</td>
									<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deleteVendorItemMasEntryRow(rw)"></i></div></td>
								</tr>								
							</tbody>
						</table>     
						<button type="button" class="btn btn-success addrow" onclick="addVendorItemMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>       
                    </div>
</form>