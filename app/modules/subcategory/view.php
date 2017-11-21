<form role="form" id="frmSubCategoryMaster">
  <input type="hidden" name="hid_id" id="hid_id" />
  <div class="form-group">
    <label>Name</label>
    <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" placeholder="Enter Name"  maxlength="50">
  </div>
  <div class="form-group">
    <label>Main Category</label>
    <select class="form-control" id="category_id" name="category_id">
    </select>
  </div>
  <div class="form-group">
    <label>Display Order</label>
    <input type="text" class="form-control" id="subcategory_display_order" name="subcategory_display_order" placeholder="Enter Display Order" maxlength="50" onkeypress="return ValidateNumberKeyPress(this, event);">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="subcategory_status" name="subcategory_status">
      <option value="1" selected>Active</option>
      <option value="2" >Inactive</option>
    </select>
  </div>
</form>