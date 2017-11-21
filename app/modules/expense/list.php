 <div class="content-wrapper">
         <section class="content-header">
          <h1>
             Expense
		  	<div class="pull-right">
			  <a class="js-open-modal btn btn-success" href="#" data-modal-id="popup1" onclick="CreateUpdateExpenseMasterList();"><i class="fa fa-plus"></i> Create New</a>
			</div>		
            <div class="pull-right">
			  <a class="js-open-modal btn btn-warning" style="margin-top:-6px;margin-right:10px;" href="#" onclick="viewImportExpenses()"><i class="fa fa-th"></i> Upload Expenses</a>
			</div>  	 
            <div class="dropdown pull-right">
			 <span style="font-size:15px">Status</span>
			  <button class="btn btn-year dropdown-toggle" type="button" data-toggle="dropdown" style="width:150px;margin-right:10px;margin-top:-5px;" id="exp_status" stausattr="-1">All
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li onclick="viewExpenseListByStatus(this)" stausattr="0"><a>Wating for Approval</a></li>
				<li onclick="viewExpenseListByStatus(this)" stausattr="1"><a>Approved</a></li>
				<li onclick="viewExpenseListByStatus(this)" stausattr="2"><a>Rejected</a></li>
				<li onclick="viewExpenseListByStatus(this)" stausattr="-1"><a>All</a></li> 
			  </ul>
			</div>
		</h1>	
        </section>

        <section class="content">
        
          <div class="box-body white-bg">
		  <div class="alert alert-success alertSuccDivMsg"   >
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                    <div id="succDivMsg" >&nbsp;</div>
                </div>
                  <table class="table table-bordered table-striped" id="expenseMasterTbl">
                    <thead>
                      <tr>
                        <th width="6%">S.No</th>
                        <th width="17%">Submitted By</th>
                        <th width="12%">Submit. Date</th>
                        <th width="18%">Date Range</th>
						<th width="11%" style="text-align:right">Total Expenses</th>
						<th width="12%">Status</th>
						<th width="16%">Appr./Rejected By</th>
						<th width="8%" style="text-align:center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                  </table>
                </div>
        </section>
      </div>
      
      <div class="modal " id="viewPageModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Expense</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateExpenseMasterSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
  
  
  <div class="modal " id="viewDeleteModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete</h4>
        </div>
        <div class="modal-body">
        <form role="form" id="frmExpenseDeleteMaster">
						<input type="hidden" name="hid_id" id="hid_id" />
        Do you want to delete?
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalDelete" onclick="deleteExpenseMaster()"  data-dismiss="modal"><i class="fa fa-floppy-o"></i>&nbsp; Yes</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; No</button>
        </div>
      </div>
      
    </div>
  </div>
  
  
   <div class="modal " id="viewImportModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Import Expenses</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalImportSave" onclick="importExpenseFile();"><i class="fa fa-floppy-o"></i>&nbsp; Import</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
    </div>
    
      
    </div>
    
    