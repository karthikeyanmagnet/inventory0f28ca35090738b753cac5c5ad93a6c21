<form role="form" id="frmExpenseMaster" method="post">
  <input type="hidden" name="hid_id" id="hid_id" />
  <input type="hidden" name="hid_curr_fare_per_km_hd" id="hid_curr_fare_per_km_hd" />
  <input type="hidden" name="hid_fileup_temp_usr_fold" id="hid_fileup_temp_usr_fold" />
  <input type="hidden" name="hid_fileimport" id="hid_fileimport" />
  <input type="hidden" name="hid_import" id="hid_import"/>
  <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
  <input type="hidden" name="hid_temp_lcd_del" id="hid_temp_lcd_del"/>
<div class="content-wrapper">
         <section class="content-header">
          <h1>
             Expense Entry
			 
		</h1>	
        <div class="pull-right no-padding"  >
			  <label id="created_by"></label>
			</div>	
        </section>

        <section class="content">
          <div class="box-body white-bg">
			<table class="form-table table table-bordered table-striped" id="customFields">
				<thead>
                
					<th>S.No</th>
					<th>Category</th>
					<th>Sub Cate.</th>
					<!--<th>Description</th>-->
					<th>Date</th>
					<th>Amount</th>
					<th>File</th>
					<th>LCD</th>
					<th>EMO</th>
					<th>Details</th>
				</thead>
                <tbody>
                <tr id="tempRow" style="display:none">
                <td><label class="lbl-1">rw</label><input type="hidden" name="hdn_exp_detid[]"  id="hdn_exp_detid_rw"/></td>
					<td><select class="form-control" name="cmb_category[]" id="cmb_category_rw" onchange="viewSubCategory(rw)"></select></td>
					<td><select class="form-control" name="cmb_subcategory[]" id="cmb_subcategory_rw"></select></td>
					<!--<td><input type="text" class="form-control" /></td>-->
					<td><div class="input-group date" style="width:150px;" onclick="funcCallDate(this);" ><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  	<input type="text" class="form-control pull-right datepicker" name="txt_expense_date[]" id="txt_expense_date_rw"></div>
					</td>
					<td><input type="text" class="form-control cls_exp_det_line_amount"  name="txt_expense_amt[]" id="txt_expense_amt_rw" onkeyup="calcExpDetTotAmnt();" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="8"   style="text-align:right; width:100px" /></td>
					<td><i class="fa fa-upload" onclick="viewExpenseFileUpload(rw)"></i></td>   
					<td align="center"><a class="js-open-modal" href="#" data-modal-id="popup1" onclick="viewLCDDetails(rw)"><i class="fa fa-external-link"></i></a></td>  
					<td><input type="checkbox" name="chk_expense_emo[]" id="chk_expense_emo_rw" value="1"/></td> 
					<td><div style="width:89%; float:left" ><input type="text" class="form-control"  name="txt_expense_notes[]" id="txt_expense_notes_rw"/> </div><div style="width:10%; float:right;padding-top: 12px; color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deleteExpenseRow(rw)"></i></div></td>  
                </tr>
                
                </tbody>
				
			</table>
          </div>
		  <div class="col-md-12 no-padding">
		   <p>&nbsp;</p>
		  <div class="col-md-4">
			  <a class="btn btn-success addCF" onclick="addExpenseDetailRow()"><i class="fa fa-plus"></i> Add Expense</a>
			</div>
			 <div class="col-md-8 text-right">
		  	<label><strong>LCD</strong> - Local Conveyance Details</label> &nbsp; &nbsp;
			<label><strong>EMO</strong> - Expenses Made for Office</label>
		  </div>
			</div>
		 
		  <div class="col-md-8 no-padding">
		  	<p>&nbsp;</p>
			 
			<div class="form-group">
			
			<div class="col-md-2 no-padding">
			  		<label class="lbl-1">Cash In Hand</label>
				</div>	
				<div class="col-md-4">
			  		<input type="text" class="form-control" id="cash_hand" name="cash_hand">
				</div>	
				
				<div class="col-md-2 no-padding">
			  		<label class="lbl-1">Total Expenses</label>
				</div>	
				<div class="col-md-4">
			  		<input type="text" class="form-control" id="total_expenses" name="total_expenses" readonly="" >
				</div>	
				
				
				
			</div> 
			 
		  </div>
		  <div class="col-md-4 no-padding">
          <div class="form-group" id="status_viewer" style="display:none">
			 
				<div class="col-md-12 no-padding text-right">	
				<label class="lbl-1"><strong>Status:</strong>&nbsp;&nbsp;</label>
				<label><input name="opn_app_reject" type="radio" value="1" onclick="setExpenseStatus()"/> Approve </label>	
				<label><input name="opn_app_reject" type="radio" value="2" onclick="setExpenseStatus()" /> Reject </label>	
                <label id="reject_notes" style="display:none"><br /><textarea name="txt_reject_notes" id="txt_reject_notes"></textarea> </label>
				&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
			</div>
		  	<p>&nbsp;</p>
			<div class="form-group">
				<div class="col-md-12 no-padding text-right">			  		
					<button class="btn btn-warning btn-sm pull-right" type="button" onclick="ExpenseMasterBack()" ><i class="fa fa-times"></i> Cancel</button>
					<button class="btn btn-primary btn-sm pull-right" style="margin-right:10px;" onclick="CreateUpdateExpenseMasterSave()" type="button" id="btnSaveExpense"><i class="fa fa-floppy-o"></i> Submit</button>
				</div>	
			</div>
		  </div>
        </section>
      </div>
      </form>
      
      <div class="modal" id="viewPageModal" role="dialog">
    <div class="modal-dialog" style="width:980px;">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Local Conveyance Details</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalSave" onclick="CreateUpdateLCDSave();"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
  
   <div class="modal" id="viewUploadModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">File Upload</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnModalUploadSave" onclick="uploadExpenseFile();"><i class="fa fa-floppy-o"></i>&nbsp; Upload</button>
		  <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="resetExpenseFile();"><i class="fa fa-times"></i>&nbsp; Cancel</button>
        </div>
      </div>
      
    </div>
  </div>