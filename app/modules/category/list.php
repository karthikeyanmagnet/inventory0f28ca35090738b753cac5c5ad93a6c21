 <div class="content-wrapper">

	<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                CATEGORY 
                            </h2>
                            <div class="actionsBar">
                            	<button class="btn btn-success createCategory act-add" onclick="CreateUpdateCategoryMasterList();"><i class="fa fa-plus"></i> Create</button>
                            </div>                            
                        </div>
                        <div class="body">
							<div class="alert alert-success alertSuccDivMsg"   >
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
								<div id="succDivMsg" >&nbsp;</div>
							</div>
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="categoryMasterTbl">
                                <thead>
                                    <tr>
                                        <th  width="30%">Category</th>
                                        <th width="12%">Status</th>
                                        <th width="22%">Created / Updated Date</th>
                                        <th width="22%">Created / Updated By</th>
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
                    CATEGORY CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveCategory" onclick="CreateUpdateCategoryMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeCategoryDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                <form>
                    <div class="form-group form-float">
                        <div class="form-line">
                        	<label>Category Name</label>
                            <input type="text" id="category_name" class="form-control">
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
                    CATEGORY DELETION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect deleteCategory" onclick="deleteCategoryMaster();">YES</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeCategoryDiaglog();">NO</button>
                </div>               
            </div>
            <div class="body">
             <form role="form" id="frmCategoryDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
                    Do you want to delete?
                    </form>
             </div>
        </div>
	</div>
</div> 