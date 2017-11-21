<!-- <script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("itemMasterTbl");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>-->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


<div class="content-wrapper">

	<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                ITEM 
                            </h2>                               
                            <div class="actionsBar">
                            	<button class="btn btn-success createItem act-add" onclick="CreateUpdateItemMasterList();"><i class="fa fa-plus"></i> Create</button>
                            </div>                            
                        </div>
                        <div class="body">
							<div class="alert alert-success alertSuccDivMsg"   >
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
								<div id="succDivMsg" >&nbsp;</div>
							</div>
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="itemMasterTbl">
                                <thead>
                                    <tr>
                                        <th width="25%">Category</th>
                                        <th width="17%">Code</th>   
                                         <th width="17%">Description</th>   
                                        <th width="22%">Created / Updated Date</th>
                                        <th width="20%">Created / Updated By</th>
										<th width="16%">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <!--<tr>
                                        <td width="30%">Cate one</td>
                                        <td width="12%" class="green-bg">Active</td>
                                        <td width="22%">16 Apr 2017</td>
                                        <td width="22%">John Williams</td>
										<td width="14%"><a><i class="fa fa-edit"></i> Edit</a></td>
                                    </tr>
                                    <tr>
                                        <td>Cate two</td>
                                        <td class="red-bg">Inactive</td>
                                        <td>05 Apr 2017</td>
                                        <td>David Smith</td>
										<td><a><i class="fa fa-edit"></i> Edit</a></td>
                                    </tr>
                                    <tr>
                                        <td>Cate three</td>
                                        <td class="green-bg">Active</td>
                                        <td>30 Mar 2017</td>
                                        <td>John Williams</td>
										<td><a><i class="fa fa-edit"></i> Edit</a></td>
                                    </tr>
                                    <tr>
                                        <td>Cate four</td>
                                        <td class="green-bg">Active</td>
                                        <td>27 Mar 2017</td>
                                        <td>Andrew Jolan</td>
										<td><a><i class="fa fa-edit"></i> Edit</a></td>
                                    </tr>
                                    <tr>
                                        <td>Cate five</td>
                                        <td class="green-bg">Active</td>
                                        <td>25 Mar 2017</td>
                                        <td>Andrew Jolan</td>
										<td><a><i class="fa fa-edit"></i> Edit</a></td>
                                    </tr>  -->                                  
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
                    ITEM CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveItem" onclick="CreateUpdateItemMasterSave();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeItemDiaglog();">CANCEL</button>
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
                    ITEM DELETION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect deleteItem" onclick="deleteItemMaster();">YES</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeItemDiaglog();">NO</button>
                </div>               
            </div>
            <div class="body">
             <form role="form" id="frmItemDeleteMaster">
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
                    CATEGORY CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect" onclick="createMasterEntry('category')">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect" onclick="cancelMasterCreation()">CANCEL</button>
                </div>               
            </div>
            <div class="body">
            </div>
        </div>
	</div>
</div>