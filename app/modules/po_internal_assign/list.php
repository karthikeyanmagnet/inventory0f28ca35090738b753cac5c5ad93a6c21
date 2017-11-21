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
							 <div class="actionsBar">
                            	<button class="btn btn-success createPOInternalAssign act-add" onclick="CreateUpdatePOInternalAssignMasterList();"><i class="fa fa-plus"></i> Create</button>
                                
                                <button class="btn btn-success backPO" onclick="gotoBackPOSupplier();"><i class="fa fa-arrow-left"></i> Back</button>
								
                            </div>                      
                        </div>
                        <div class="body">
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="POInternalAssignMasterTbl" style="width:100%" >
                                <thead>
                                    <tr>
                                   
                                       <th>PO #</th>
                                        <th>Created Date</th>
                                        <th>Assign To</th>
                                        <th>Total Price</th>
										<th>Excepted Date</th>
										<th>Status</th>
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
                    <button type="button" class="btn btn-primary m-t-15 waves-effect savePOInternalAssign" onclick="CreateUpdatePOInternalAssignMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closePOInternalAssignDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                <form>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label>POInternalAssign Name</label>
                            <input type="text" id="po_internal_assign_name" class="form-control">
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
                    <button type="button" class="btn btn-primary m-t-15 waves-effect deletePOInternalAssign" onclick="deletePOInternalAssignMaster();">YES</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closePOInternalAssignDeletDiaglog();">NO</button>
                </div>               
            </div>
            <div class="body">
             <form role="form" id="frmPOInternalAssignDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
                    Do you want to delete?
                    </form>
             </div>
        </div>
	</div>
</div> 