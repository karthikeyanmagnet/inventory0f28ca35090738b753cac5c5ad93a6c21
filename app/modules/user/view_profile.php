<form role="form" id="frmUserProfileMaster" method="post">
   <input type="hidden" name="module" id="module"  value="user"/>
   <input type="hidden" name="action" id="action"  value="update_profile"/>
   <div id="message_prof"></div>
                	<div class="col-md-12 no-padding">                
						<div class="col-md-6">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Name</label>
                                <input type="text" id="employee_name" name="employee_name" class="form-control" >
                            </div>
                        </div>
						</div>
						<div class="col-md-6">                
						<div class="form-group form-float">
                            <div class="form-line">
                                <label>Email</label>
                                <input type="text" id="user_email" name="user_email" class="form-control" >
                            </div>
                        </div>
						</div>
                    </div> 
					<div class="col-md-12 no-padding">                
						<div class="col-md-6">                
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label>Username</label>
								 <input type="text" id="user_name" name="user_name" class="form-control" readonly="" > 
                            </div>
                        </div>
						</div>
						<div class="col-md-2">                
							<img src="images/user.png" width="48" height="48" class="user_profile_logo" />
						</div>
						<div class="col-md-4">                
						<div class="form-group form-float">
                            <div class="form-line">
                                <label>Profile Image</label>
                                <input type="file" class="form-control" name="user_logo" id="user_logo">
                            </div>
                        </div>
						</div>
                    </div>
					<div class="col-md-12 no-padding">                
						<div class="col-md-6">                
							<div class="form-group form-float">
								<div class="form-line">
									<label>Role</label> 
									<input type="text" id="user_role" name="user_role" class="form-control" readonly="" > 
								</div>
							</div>
						</div>
						<div class="col-md-6">                
							<div class="form-group form-float">
								<div class="form-line">
									<label>Contact No</label>
									<input type="text" name="user_phone" id="user_phone" class="form-control" value="9934589340">
								</div>
							</div>
						</div>
                    </div>     
					<div class="col-md-12 no-padding">                
						<div class="col-md-6">                
							<div class="form-group form-float">
								<div class="form-line">
									<label>Company Logo</label>
									<input type="file" class="form-control" name="company_logo" id="company_logo"> <span class="viewLogo" style="display:none; cursor:pointer"><strong><u>View logo</u></strong></span>
								</div>
							</div>
						</div>
                    </div>
                </form>
