<form role="form" id="frmItemGroupMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
  
   <SCRIPT language=Javascript>
       
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;
                  
          return true;
       }
       
    </SCRIPT>
  
  
  <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Item Code <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="item_group_name" name="item_group_name" class="form-control" maxlength="50" >
                            </div>
                        </div>
                    </div>
   <div class="col-md-8 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Group Description <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="item_group_desc" name="item_group_desc" class="form-control" >
                            </div>
                        </div>
                    </div>
  <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label> Weight <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="item_group_weight" name="item_group_weight" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" >
                            </div>
                        </div>
                    </div>
  <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label> HTC <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="item_group_htc" name="item_group_htc" class="form-control" >
                            </div>
                        </div>
                    </div>
  <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label> Unit Cost <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="item_group_unit_cost" name="item_group_unit_cost" onkeypress="return isNumberKey(event)" class="form-control" >
                            </div>
                        </div>
                    </div>
  
    
                     <div class="col-md-12 no-padding-left">                
						<table class="table table-bordered item-grid" id="customFields" width="100%" >
							<thead>
								<tr>
									<th width="31%">Category</th>
									<th width="37%">Item & Description</th>
									<th width="28%">Quantity required for a product</th>
									<th width="4%">&nbsp;</th>
								</tr>								
							</thead>
							<tbody>
								<tr class="item-row" id="tempRow" style="display:none">
									<td><input type="hidden" name="hdn_itmgrp_detid[]"  id="hdn_itmgrp_detid_rw"/>
									<select class="form-control show-tick" name="cmb_category[]" id="cmb_category_rw" onchange="viewItemGroupMasOnChangeCategory(rw)"></select>
                                    <!--<button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('category', this)" cmb-view="cmb_category_rw"><i class="fa fa-plus"></i></button>-->
									</td>
									<td><select class="form-control show-tick" name="cmb_item[]" id="cmb_item_rw"></select>  
                                    <!--<button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('item', this)" cmb-view="cmb_item_rw"><i class="fa fa-plus"></i></button>-->
									</td>
									<td>
									<input type="text" class="form-control"  name="txt_quantity[]" id="txt_quantity_rw" onkeypress="return numbersonly(event);" maxlength="4"   style="text-align:right;"   />	
									</td>
									<td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deleteItemGroupMasEntryRow(rw)"></i></div></td>
								</tr>								
							</tbody>
						</table>     
						<button type="button" class="btn btn-success addrow" onclick="addItemGroupMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>       
                    </div>
 
</form>