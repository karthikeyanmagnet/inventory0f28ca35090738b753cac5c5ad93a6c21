<form role="form" id="frmItemMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  
            		<div class="col-md-5 no-padding-left">                
						<label>Category <span class="spnClsMandatoryField" >*</span></label>
						<select class="form-control show-tick" id="category_id" name="category_id">
							<option value="0">Select</option>
							
						</select>
                    </div>
                    <div class="col-md-1">   
						<button type="button" class="btn btn-success add_new_categ" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('category', this)" cmb-view="category_id"><i class="fa fa-plus"></i></button>
					</div>
					<div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Item Code <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" id="item_code" name="item_code" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Description</label>
                                <input type="text" id="item_description" name="item_description" class="form-control" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Unit Cost </label>
                                <input type="text" id="item_unit_cost" name="item_unit_cost" class="form-control" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="9">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
						<label>Currency </label>
						<select class="form-control show-tick" id="item_currency_id" name="item_currency_id">
							<option value="0">Select</option>
							<option value="1">USD</option>
							<option value="2">INR</option>
                            <option value="3">Ft</option>
						</select>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Grade </label>
                                <input type="text" id="item_grade" name="item_grade" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Weight </label>
                                <input type="text" id="item_weight" name="item_weight" class="form-control" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="7">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
						<label>Measurement </label>
						<select class="form-control show-tick" id="item_measure_id" name="item_measure_id">
							<option value="0">Select</option>
							<option value="1">LBS</option>
							<option value="2">KGS</option>
						</select>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>HTS </label>
                                <input type="text" id="item_hts" name="item_hts" class="form-control" maxlength="50" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding-left">                
						<label>Type</label>
						<select class="form-control show-tick" id="item_type" name="item_type">
							<option value="0">Select</option>
							<option value="1">Inventory</option>
							<option value="2">Fixed Asset</option>
						</select>
                    </div>
					<div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Opening Stock</label>
                                <input type="text" id="item_open_stock" name="item_open_stock" class="form-control" onkeypress="return numbersonly(event);" maxlength="5" >
                            </div>
                        </div>
                    </div>                    
  
 
</form>