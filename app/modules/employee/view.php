<form role="form" id="frmEmployeeMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
		  <label>ID</label>
		  <input type="text" class="form-control" id="employee_id" name="employee_id" readonly="" placeholder="Automatically generated">
		</div>
  <div class="form-group">
    <label>Name</label>
    <input type="text" class="form-control" id="employee_name" name="employee_name" placeholder="Enter Name" maxlength="50">
  </div>
  <div class="form-group">
		  <label>Date of joining</label>
		  <div class="input-group date" style="width:150px;" onclick="funcCallDate(this);"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  	<input type="text" class="form-control pull-right datepicker" name="employee_doj" id="employee_doj"></div>
		</div>
		
<div class="form-group">
		  <label>Designation</label>
		  <select class="form-control" name="designation_id" id="designation_id">
		  </select>
		</div>	
  <div class="form-group">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="employee_status" name="employee_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>