<form role="form" id="frmUserMaster">
  <input type="hidden" name="hid_id" id="hid_id" /> 
                	<div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>EID <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" name="employee_code" id="employee_code" class="form-control" maxlength="20" >
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Name <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" name="employee_name" id="employee_name" class="form-control" maxlength="50" >
                            </div>
                        </div>
                    </div>     
					
					<div class="col-md-12 no-padding-left">     
					<div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Username <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" class="form-control" id="user_name" name="user_name"    maxlength="20">
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line user_password_div">
                                <label>Password <span class="spnClsMandatoryField" >*</span></label>
                                  <input type="password" class="form-control" id="user_password" name="user_password"   maxlength="20">
                            </div>
                        </div>
                    </div> 
					</div>
					
					
					  
            		<div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Address</label>
                                <input type="text" name="user_address" id="user_address" class="form-control" maxlength="255" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>City</label>
                                <input type="text" name="user_city" id="user_city" class="form-control" maxlength="50" >
                            </div>
                        </div>
                    </div>
					
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>State</label>
                                <input type="text" name="user_state" id="user_state" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Postal Code</label>
                                <input type="text" name="user_postalcode" id="user_postalcode" class="form-control" maxlength="20" >
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Phone </label>
                                <input type="text" name="user_phone" id="user_phone" class="form-control" maxlength="20" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Email</label>
                                <input type="text" name="user_email" id="user_email" class="form-control" maxlength="50" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 no-padding-left">                
						<label>User Role</label>
						<select class="form-control show-tick" name="user_role_id" id="user_role_id"> 
						</select>
                    </div>
                    <div class="col-md-1 no-padding-left">   
						<button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('role', this)" cmb-view="user_role_id"><i class="fa fa-plus"></i></button>
					</div>
                    <div class="col-md-6 no-padding-left">
                    	<div class="form-group form-float">
						
						<input type="checkbox" id="user_status" class="filled-in" name="user_status">
                    <label for="user_status">Active</label> 
                             
                        </div>
                    </div>  
 
</form>