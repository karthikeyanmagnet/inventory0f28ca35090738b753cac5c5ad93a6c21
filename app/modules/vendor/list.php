 <div class="content-wrapper">

	<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                VENDOR 
                            </h2>
                            <div class="actionsBar">
                            	<button class="btn btn-success createVendor act-add" onclick="CreateUpdateVendorMasterList();"><i class="fa fa-plus"></i> Create</button>
                            </div>                            
                        </div>
                        <div class="body">
							<div class="alert alert-success alertSuccDivMsg"   >
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
								<div id="succDivMsg" >&nbsp;</div>
							</div>
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="vendorMasterTbl">
                                <thead>
                                    <tr>
                                         <th width="30%">Supplier Name</th>
                                        <th  width="20%">Contact Person</th>
                                        <th width="20%">Contact Phone</th>
                                        <th width="13%">Status</th>
										<th width="17%">Actions</th>
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
                    VENDOR CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveVendor" onclick="CreateUpdateVendorMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeVendorDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                
            </div>
        </div>
	</div>
</div>  


<div class="DeleteForm modalDiag" id="viewDeleteModal">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                    VENDOR DELETION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect deleteVendor" onclick="deleteVendorMaster();">YES</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeVendorDeleteDiaglog();">NO</button>
                </div>               
            </div>
            <div class="body">
             <form role="form" id="frmVendorDeleteMaster">
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
               <h2 class="modal-title">
                    ITEM CREATION
                </h2>      
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect" id="btnSave">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect" onclick="cancelMasterCreation()">CANCEL</button>
                </div>               
            </div>
            <div class="body">
            </div>
        </div>
	</div>
</div>