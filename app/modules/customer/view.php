<form role="form" id="frmCustomerMaster" method="post">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="module" id="module"  value="customer"/>
   <input type="hidden" name="action" id="action"  value="save"/>
    <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
  <div id="message"></div>
                	<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Customer # <span class="spnClsMandatoryField" >*</span></label>
                                <input type="text" name="vendor_code" id="vendor_code" class="form-control" maxlength="30">
                            </div>
                        </div>
                    </div>       
            		<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Company Name</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Company Logo</label>
                                <input type="file" name="company_logo" id="company_logo" class="form-control"><span class="viewLogo"  style="display:none; cursor:pointer"><strong><u>View logo</u></strong></span>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Primary Contact</label>
                                <input type="text" name="primary_contact" id="primary_contact" class="form-control" maxlength="30">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Phone</label>
                                <input type="text" name="phone_no" id="phone_no" class="form-control" maxlength="20">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Mobile</label>
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control" maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Website</label>
                                <input type="text" name="website_url" id="website_url" class="form-control" maxlength="50" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding-left">
                    	<div class="form-group form-float"> 
						<input type="checkbox" id="customer_status" name="customer_status" class="filled-in" value="1">
                    <label for="customer_status">Active</label> 
                             
                        </div>
                    </div>
                    <div class="col-md-12 no-padding-left">                
                        <div class="form-group form-float">
                            <h4>Consignee Address</h4>
                        </div>
                    </div>
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Attention</label>
                               <input type="text" name="attention_bill_addr" id="attention_bill_addr" class="form-control" maxlength="255">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Warehouse Name</label>
                                <input type="text" name="warehouse_bill_addr" id="warehouse_bill_addr" class="form-control" maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Address</label>
                                <input type="text" name="address_bill_addr" id="address_bill_addr" class="form-control" maxlength="255">
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>City</label>
                                <input type="text" name="city_bill_addr" id="city_bill_addr" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>State</label>
                               <input type="text" name="state_bill_addr" id="state_bill_addr" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>ZIP Code</label>
                                <input type="text" name="zipcode_bill_addr" id="zipcode_bill_addr" class="form-control" maxlength="20">
                            </div>
                        </div>
                    </div>
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Country</label>
                                <input type="text" name="country_bill_addr" id="country_bill_addr" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Company Phone</label>
                                <input type="text" name="company_phone_bill_addr" id="company_phone_bill_addr" class="form-control" maxlength="20">
                            </div>
                        </div>
                    </div>
					<div class="col-md-4 no-padding-left">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Company Email</label>
                                <input type="text" name="company_email_bill_addr" id="company_email_bill_addr" class="form-control" maxlength="20">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 no-padding-left">                
                        <div class="form-group form-float">
                            <h4>Delivery Address</h4>
                        </div>
                    </div>
                    <div class="col-md-12 no-padding">   
						<button type="button" class="btn btn-success addrow pull-right" onclick="addCustomerDeliveryRow()"><i class="fa fa-plus"></i> Add New</button>                   
					</div>	
                    <table width="100%" cellpadding="2" cellspacing="5" class="CustomerinputGridTable item-grid">
						<tbody>
							<tr id="tempRow" style="display:none">
								<td>
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>Attention</label><input type="hidden" name="hdn_custmer_delivery_detid[]"  id="hdn_custmer_delivery_detid_rw"/>
												<input type="text" name="attention_mail_addr[]" id="attention_mail_addr_rw" class="form-control" maxlength="255">
											</div>
										</div>
									</div>
									
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>Warehouse Name</label>
												<input type="text" name="warehouse_mail_addr[]" id="warehouse_mail_addr_rw" class="form-control" maxlength="100">
											</div>
										</div>
									</div>
									
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>Address</label>
												<input type="text" name="address_mail_addr[]" id="address_mail_addr_rw" class="form-control" maxlength="255">
											</div>
										</div>
									</div>
									
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>City</label>
												<input type="text" name="city_mail_addr[]" id="city_mail_addr_rw" class="form-control" maxlength="50">
											</div>
										</div>
									</div>
									
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>State</label>
												<input type="text" name="state_mail_addr[]" id="state_mail_addr_rw" class="form-control" maxlength="50">
											</div>
										</div>
									</div>
									
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>ZIP Code</label>
												<input type="text" name="zipcode_mail_addr[]" id="zipcode_mail_addr_rw" class="form-control" maxlength="20">
											</div>
										</div>
									</div>
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>Country</label>
												<input type="text" name="country_mail_addr[]" id="country_mail_addr_rw" class="form-control" maxlength="50">
											</div>
										</div>
									</div>
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>Company Phone</label>
												<input type="text" name="company_phone_mail_addr[]" id="company_phone_mail_addr_rw" class="form-control" maxlength="20">
											</div>
										</div>
									</div>
									<div class="col-md-4 no-padding-left">                
										<div class="form-group form-float">
											<div class="form-line">
												<label>Company Email</label>
												<input type="text" name="company_email_mail_addr[]" id="company_email_mail_addr_rw" class="form-control" maxlength="20">
											</div>
										</div>
									</div>
									<div class="col-md-12 no-padding-left" onclick="deleteCustomerDeliveryRow(rw)"><a><i class="fa fa-times"></i> Remove</a></div>
								</td>	
							</tr>  
						</tbody> 
					</table>			
                    
                    
                </form>