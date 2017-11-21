<div class="ProfileForm" id="UsrProfileForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    USER PROFILE
                </h2>        
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveProfile" onclick="updateUserProfileMasterSave()" >SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeProfileDialog" onclick="closeFunctProfileDialog();">CANCEL</button>
                </div>           
            </div>
            <div class="body">
                
            </div>
        </div>
	</div>
</div>
<!-- Jquery Core Js -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="assets/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="assets/plugins/node-waves/waves.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>	
    <script src="assets/plugins/chartjs/Chart.bundle.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/admin.js"></script>
	<script src="assets/js/bootstrap-datepicker.js?ver=1.0.0.7"></script>
	<script src="assets/js/pages/charts/chartjs.js"></script>
    <script src="assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>
    
     <script type="text/javascript" src="assets/js/jquery.form.js?ver=1.0.0.7"></script>
    
    <script type="text/javascript" src="assets/js/common.js?ver=1.0.0.7"></script>
    <script type="text/javascript" src="assets/js/category.js?ver=1.0.0.8"></script>
     <script type="text/javascript" src="assets/js/item.js?ver=1.0.0.10"></script>
     <script type="text/javascript" src="assets/js/vendor.js?ver=1.0.0.7"></script>
      <script type="text/javascript" src="assets/js/role.js?ver=1.0.0.8"></script>
      <script type="text/javascript" src="assets/js/user_role.js?ver=1.0.0.7"></script>
      <script type="text/javascript" src="assets/js/item_group.js?ver=1.0.0.7"></script>
	<script type="text/javascript" src="assets/js/customer.js?ver=1.0.0.10"></script>
	<script type="text/javascript" src="assets/js/user.js?ver=1.0.0.7"></script>
	
	<script type="text/javascript" src="assets/js/po_entry.js?ver=1.0.0.8"></script>
	<script type="text/javascript" src="assets/js/stock_entry.js?ver=1.0.0.10"></script>
    <script type="text/javascript" src="assets/js/stock_dispatch.js?ver=1.0.0.8"></script>
    <script type="text/javascript" src="assets/js/po_assign.js?ver=1.0.0.8"></script>
    <script type="text/javascript" src="assets/js/internal_po_assign.js?ver=1.0.0.7"></script>
	
	<script type="text/javascript" src="assets/js/stock_report.js?ver=1.0.0.8"></script>
    <script type="text/javascript" src="assets/js/shipment.js?ver=1.0.0.8"></script>


    <!-- Demo Js -->
    <script src="assets/js/demo.js"></script>
    <script>
   GV_menu_permission = (<?=$menujson;?>);
	   GV_menu_permission = eval(GV_menu_permission);
	 
		//console.log(GV_menu_permission);
	/*$('.profileView').click(function(){
		$('.ProfileForm, .overlay').show();
		$('.ProfileForm').addClass('largeBox');
	})
	$('.saveProfile, .closeProfileDialog').click(function(){
		$('.ProfileForm, .overlay').hide();
		$('.ProfileForm').removeClass('largeBox');
	})*/
    </script>	
</body>
</html>


