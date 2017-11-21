
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <!-- Exportable Table -->
      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card pull-left" style="padding-bottom:20px;">
            <div class="header">
              <h2> PURCHASE ORDER </h2>
              <div class="actionsBar" id="show_po_entry">
               <button type="button" class="btn btn-primary m-t-15 waves-effect savePOEntry" onclick="CreateUpdatePOEntryMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="loadPOEntryMaster();">CANCEL</button>
              </div>
            </div>
            <div class="body">
              <div class="tab ">
                <button class="tablinks" onclick="openTabPurchaseSUpPAssign(event, 'divTabPoEntry')" id="defaultOpen">PO entry</button>
                <button class="tablinks divBtnTabSuppAssig" onclick="openTabPurchaseSUpPAssign(event, 'divTabSuppAssig')" style="display:none;" >Supplier Assignment</button>
              </div>
              <div id="divTabPoEntry" class="tabcontent pull-left">
                <div id="showPoEntry">
                  <form role="form" id="frmPOEntryMaster">
                    <input type="hidden" name="hid_id" id="hid_id" />
                    <input type="hidden" name="hid_temp_del" id="hid_temp_del"/>
					
                    <div class="col-md-8 no-padding" style="margin-top:15px;">
                      <div class="col-md-5 no-padding-left">
                        <label>Customer Name</label>
                        <select class="form-control show-tick" id="customer_id" name="customer_id">
                        </select>
                      </div>
                       <div class="col-md-1 no-padding-left">   
						<button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('customer', this)" cmb-view="customer_id"><i class="fa fa-plus"></i></button>
					</div>
                      <div class="col-md-6 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>PO Number</label>
                            <input type="text" id="po_number" name="po_number" class="form-control" maxlength="20">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>SO Number</label>
                            <input type="text" id="so_number" name="so_number" class="form-control" maxlength="20">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>Cust PO Number</label>
                            <input type="text" id="cust_po_number" name="cust_po_number" class="form-control" maxlength="20">
                          </div>
                        </div>
                      </div>
                      
                    </div>
                    <div class="col-md-4 no-padding-left" style="margin-top:15px;">
                      <div class="form-group form-float">
                        <div class="form-line">
                          <label>Terms</label>
                          <textarea id="po_terms" name="po_terms" rows="5" class="form-control"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                    <div class="col-md-3 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>Ship Date</label>
                            <input type="date" id="ship_date" name="ship_date" class="form-control">
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-3 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>SPG Order Type</label>
                            <input type="text" id="spg_order_type" name="spg_order_type" class="form-control" maxlength="100">
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-3 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>PO Type</label>
                            <input type="text" id="po_type" name="po_type" class="form-control" maxlength="100">
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-3 no-padding-left">
                        <div class="form-group form-float">
                          <div class="form-line">
                            <label>Issuer</label>
                            <input type="text" id="issuer" name="issuer" class="form-control" maxlength="50">
                          </div>
                        </div>
                      </div>
                      </div>
                    <div class="col-md-7 no-padding">
                      <button type="button" class="btn btn-success addrow" onclick="addPOEntryMasEntryDetailRow()" ><i class="fa fa-plus"></i> Add New</button>
                    </div>
					 
                     <div class="col-md-2 no-padding"> 
							<select class="form-control" name="cmb_item_group" id="cmb_item_group" onchange="setItemGrpQty()">
								<option>Select Item Group</option> 
							</select>
                       </div>
                       <div class="col-md-1  "> 
                       <input type="text" id="txt_grp_qty" name="txt_grp_qty" class="form-control" onkeypress="return ValidateNumberKeyPress(this, event);" value="1">
                       </div>
                        <div class="col-md-1  ">     
                      <button type="button" class="btn btn-success addrow" onclick="addItemGroupToPOEntryRows()" ><i class="fa fa-plus"></i> Add Item Group</button>
                    </div>
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable item-grid" id="customFieldsPOEntry">
                      <thead>
                        <tr>
                          <th width="15%">Item Code</th>
                          <th width="25%">Description</th>
                          <th width="10%">Whse </th>
                          <th width="10%">Weight</th>
                          <th width="10%">Ordered</th>
                          <th width="13%">Unit Cost</th>
                          <th width="13%">Amount</th>
                          <th width="4%">&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="item-row" id="tempRow" style="display:none">
                          <td><input type="hidden" name="hdn_po_detid[]"  id="hdn_po_detid_rw"/>
                            <select class="form-control show-tick" name="cmb_item[]" id="cmb_item_rw">
                            </select></td>
                          <td><input type="text" class="form-control"  name="item_desc[]" id="item_desc_rw"  maxlength="100" /></td>
                          <td><input type="text" class="form-control"  name="whse_line[]" id="whse_line_rw"  maxlength="50"  /></td>
                          <td><input type="text" class="form-control poweight"  name="weight_line[]" id="weight_line_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="7"   style="text-align:right;"  onchange="calCulatePOWeight();"  /></td>
                          <td><input type="text" class="form-control poorder"  name="ordered_qty[]" id="ordered_qty_rw" onkeypress="return numbersonly(event);" maxlength="4"   style="text-align:right;" onchange="calCulatePOEntryTotal()"   /></td>
                          <td><input type="text" class="form-control pounit"  name="unit_cost[]" id="unit_cost_rw" onkeypress="return ValidateNumberKeyPress(this, event);" maxlength="9"   style="text-align:right;"  onchange="calCulatePOEntryTotal()"/></td>
                          <td><input type="text" class="form-control"  name="line_total[]" id="line_total_rw" readonly=""  style="text-align:right;"/></td>
                          <td><div style="  color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to remove items" onclick="deletePOEntryMasEntryRow(rw)"></i></div></td>
                        </tr>
                      </tbody>
                    </table>
                    <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable" >
                      <tr>
                        <td width="60%">Notes</td>
                        <td width="10%">&nbsp;</td>
                        <td width="15%">Order Total</td>
                        <td width="15%"><input type="text" id="order_total" name="order_total" class="form-control" readonly="readonly"></td>
                      </tr>
                      <tr>
                        <td rowspan="2"><textarea class="form-control" name="po_notes" id="po_notes" rows="3"></textarea></td>
                        <td>&nbsp;</td>
                        <td>Weight(LBS)</td>
                        <td><input type="text" id="total_weight_lbs" name="total_weight_lbs" class="form-control" readonly="readonly"></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>Weight(MT)</td>
                        <td><input type="text" id="total_weight_mt" name="total_weight_mt" class="form-control" readonly="readonly"></td>
                      </tr>
                    </table>
					<p>&nbsp;</p>
                    <div class="col-md-4 no-padding-left">
                      <label>Status</label>
                      <select class="form-control show-tick" id="active_status" name="active_status">
                        <option value="0" >Select</option>
                        <option value="1">Active</option>
                        <option value="2">Ordered</option>
                        <option value="3">Cancelled</option>
                        <option value="4">Shipment</option>
                        <option value="5">Closed</option>
                      </select>
                    </div>					
                  </form>
                </div>
              </div>
              <div id="divTabSuppAssig" class="tabcontent no-padding">
                <div id="showPoInternalEntry" style="display:none"> </div>
              </div>
              <style>
/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
              <script>
function openTabPurchaseSUpPAssign(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
	
	if(cityName=='divTabPoEntry') { viewPOEntry(); $('#show_po_entry').show(); }
	else if(cityName=='divTabSuppAssig') { viewPOAssignEntry(); $('#show_po_entry').hide(); }
}
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
              <!--<span onclick="viewPOEntry();">PO Entry</span> &nbsp; <span onclick="viewPOAssignEntry();">Supplier Assignment</span>-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<div class="AddEditForm largeBox" id="viewPageModal">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
      <div class="header">
        <h2 class="modal-title"> PURCHASE ORDER CREATION </h2>
        <div class="actionsBar-1">
          <button type="button" class="btn btn-primary m-t-15 waves-effect savePOEntry" onclick="CreateUpdatePOAssignMasterSave();">SUBMIT</button>
          <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closePOEntryDiaglog();">CANCEL</button>
        </div>
      </div>
      <div class="body"> </div>
    </div>
  </div>
</div>
<div class="SubAddEditForm submodalDiag">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                    CUSTOMER CREATION
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