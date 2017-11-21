 <div class="content-wrapper">

	<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               INTERNAL PURCHASE ORDER 
                            </h2>
							 <!--<div class="actionsBar">
                            	<button class="btn btn-success createPOAssign act-add" onclick="CreateUpdatePOAssignMasterList();"><i class="fa fa-plus"></i> Create</button>
								
                            </div>  -->                    
                        </div>
                        <div class="body">
							<div class="alert alert-success alertSuccDivMsg"   >
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
								<div id="succDivMsg" >&nbsp;</div>
							</div>
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
            <!-- #END# Exportable Table -->
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
                  <!--  <button type="button" class="btn btn-primary m-t-15 waves-effect createInternalPO" onclick="closePOInternalAssignDiaglog();loadPOInternalAssignMaster();">Create Internal PO</button>
                    <button type="button" class="btn btn-primary m-t-15 waves-effect savePOAssign" onclick="CreateUpdatePOAssignMasterSave();">SUBMIT</button>-->
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closePOAssignDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                <form>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label>POAssign Name</label>
                            <input type="text" id="po_assign_name" class="form-control">
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
                    PURCHASE ORDER  DELETION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect deletePOAssign" onclick="deletePOAssignMaster();">YES</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closePOAssignDeletDiaglog();">NO</button>
                </div>               
            </div>
            <div class="body">
             <form role="form" id="frmPOAssignDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
                    Do you want to delete?
                    </form>
             </div>
        </div>
	</div>
</div> 