 <div class="content-wrapper">

	<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                USERS 
                            </h2>
                            <div class="actionsBar">
                            	<button class="btn btn-success createUser act-add" onclick="CreateUpdateUserMasterList();"><i class="fa fa-plus"></i> Create</button>
                            </div>                            
                        </div>
                        <div class="body">
							<div class="alert alert-success alertSuccDivMsg"   >
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
								<div id="succDivMsg" >&nbsp;</div>
							</div>
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="userMasterTbl">
                                <thead>
                                    <tr>
                                        <th  width="30%">EID</th>
                                        <th width="12%">Name</th>
                                        <th width="22%">User Role</th>
                                        <th width="22%">Status</th>
										<th width="14%">Actions</th>
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
            <!-- #END# Exportable Table -->
        </div>
    </section>
        
      </div>
      
      
  
  
  
    <div class="AddEditForm modalDiag" id="viewPageModal">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                    USER CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveUser" onclick="CreateUpdateUserMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeUserDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                <form>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label>User Name</label>
                            <input type="text" id="user_name" class="form-control">
                        </div>
                    </div>
    
                    <input type="checkbox" id="remember_me_2" class="filled-in">
                    <label for="remember_me_2">Active</label>
                </form>
            </div>
        </div>
	</div>
</div>  


<div class="DeleteForm modalDiag" id="viewDeleteModal">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                    USER DELETION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect deleteUser" onclick="deleteUserMaster();">YES</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeUserDiaglog();">NO</button>
                </div>               
            </div>
            <div class="body">
             <form role="form" id="frmUserDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
                    Do you want to delete?
                    </form>
             </div>
        </div>
	</div>
</div> 

<div class="SubAddEditForm submodalDiag">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    ROLE CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect" onclick="createMasterEntry('role')">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect" onclick="cancelMasterCreation()">CANCEL</button>
                </div>               
            </div>
            <div class="body">
            </div>
        </div>
	</div>
</div>
    
 <div class="AddEditForm modalDiag" id="viewChangePasswordModal">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                    USER CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveUser" onclick="ChangeUserPassword();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeUserDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                <form>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label>User Name</label>
                            <input type="text" id="user_name" class="form-control">
                        </div>
                    </div>
    
                    <input type="checkbox" id="remember_me_2" class="filled-in">
                    <label for="remember_me_2">Active</label>
                </form>
            </div>
        </div>
	</div>
</div>