<?php session_start(); ?>
<?php
include 'app/rapper/class.rapper.php'; 
	$rapper = new rapper();
	$modules = $rapper->getMenuModulePrevilegeList();
	
	$leftside_menu = array();
	$menu_actions = array();
	$view_menu_actions = array();
	$menu_list = array();
	foreach($modules as $mod)
	{
		$leftside_menu[$mod['main_module_name']] = $mod['main_actions'];
		$leftside_menu[$mod['sub_module_name']] = $mod['sub_actions'];
		
		$menu_actions[$mod['js_call_identify']] = ($mod['sub_actions'])?$mod['sub_actions']:$mod['main_actions'];
		$module_actions = explode(',',$menu_actions[$mod['js_call_identify']]);
		$view = (in_array(1,$module_actions))?1:0;
		if($mod['main_module_name'])$view_menu_actions[$mod['main_module_name']] = $view;
		if($mod['sub_module_name'])$view_menu_actions[$mod['sub_module_name']] = $view; 
		$menu_list[$mod['main_module_name']][] =  array('name'=>$mod['sub_module_name'], 'action'=>$view, 'js_call'=>$mod['js_call_identify']);
	}
	
	//print_r($view_menu_actions);
	//print_r($menu_list);
	$sessLogo=$_SESSION['sess_user_logo'];
	$sessUserImgLogo="images/user.png";
	if($sessLogo)
	{
		$sessUserChkImgLogo="public/data/user/profile/".$_SESSION['sess_user_logo'];
		if(file_exists($sessUserChkImgLogo))
		{
			$sessUserImgLogo=$sessUserChkImgLogo;
		}
	} 
?>	
	
<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">    
				<div class="image">
                    <img src="<?php echo $sessUserImgLogo;?>" width="48" height="48" alt="User" class="user_profile_logo">
                </div>            
                <div class="info-container">
                    <div class="name user_profil_name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['sess_log_employee_name']; ?></div>
                    <div class="email user_profil_email"><?php echo $_SESSION['sess_log_employee_email']; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);" class="profileView" onclick="viewUserProfile();"><i class="material-icons">person</i>Profile</a></li>                            
                            <li role="seperator" class="divider"></li>
                            <li><a href="logout.php"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">NAVIGATIONS</li>
                    <?php if($view_menu_actions['Dashboard'] == 1) { ?>         
                    <li  class="main_menu">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-cog"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="ml-menu">
                        <?php foreach($menu_list['Masters'] as $menu ) { 
							if($menu['action'] == 1) {
						?>
                         	<li onclick="callLeftMenuPages(this,'<?=$menu['js_call'];?>');">
                               <a style="cursor:pointer" ><?php echo $menu['name']; ?></a>
                            </li>
                        <?php } } ?>
                            <!--<li onclick="loadRoleMaster();">
                                <a >Roles</a>
                            </li>                     
                            <li onclick="loadUserRoleMaster();">
                            	<a>Role Permission</a>
                            </li>    
							<li onclick="callLeftMenuPages(this,'po_entry');">
                               <a style="cursor:pointer" >PO Entry</a>
                            </li>
                            
                            <li onclick="callLeftMenuPages(this,'po_assign');">
                               <a style="cursor:pointer" >Supplier Assignment</a>
                            </li> -->  
							
                        </ul>
                    </li>
                    
                    <?php } ?>
                    
                    <li class="main_menu">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-slack"></i>
                            <span>Inventory</span>
                        </a>
                        <ul class="ml-menu">
                        <?php foreach($menu_list['Inventory'] as $menu ) { 
							if($menu['action'] == 1) {
						?>
                         	<li onclick="callLeftMenuPages(this,'<?=$menu['js_call'];?>');">
                               <a style="cursor:pointer" ><?php echo $menu['name']; ?></a>
                            </li>
                        <?php } } ?>
                            
                            <li onclick="callLeftMenuPages(this,'stock_report');">
                               <a style="cursor:pointer" >Stock Report</a>
                            </li> 
                            <!--<li onclick="loadStockEntryMaster();">
                                <a>Stock Entry</a>
                            </li>                            
                         <li  onclick="loadStockDispatchMaster();">
                                <a>Stock Dispatch</a>
                            </li> -->
                            <!--<li>
                                <a href="#">Stock Bill</a>
                            </li>                  -->                                  
                        </ul>
                    </li>
					<li class="main_menu">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-file-text"></i>
                            <span>Purchase Orders</span>
                        </a>
                        <ul class="ml-menu">
                        <?php foreach($menu_list['Purchase Orders'] as $menu ) { 
							if($menu['action'] == 1) {
						?>
                         	<li onclick="callLeftMenuPages(this,'<?=$menu['js_call'];?>');">
                               <a style="cursor:pointer" ><?php echo $menu['name']; ?></a>
                            </li>
                        <?php } } ?>
                            <!--<li onclick="loadStockEntryMaster();">
                                <a>Stock Entry</a>
                            </li>                            
                         <li  onclick="loadStockDispatchMaster();">
                                <a>Stock Dispatch</a>
                            </li> -->
                            <!--<li>
                                <a href="#">Stock Bill</a>
                            </li>                  -->                                  
                        </ul>
                    </li>
                    
                    <li  class="main_menu">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-ship"></i>
                            <span>Sales & Shipments</span>
                        </a>
                        <ul class="ml-menu">
                            <li onclick="callLeftMenuPages(this,'create_shipment_order');">
                               <a style="cursor:pointer">Create Shipment Order</a>
                            </li>
                        <?php foreach($menu_list['Sales & Shipments'] as $menu ) { 
							if($menu['action'] == 1) {
						?>
                         	<li onclick="callLeftMenuPages(this,'<?=$menu['js_call'];?>');">
                               <a style="cursor:pointer" ><?php echo $menu['name']; ?></a>
                            </li>
                        <?php } } ?>                                    
                            
                       
                                                            
                        
                        </ul>
                    </li>
                    				
                </ul>
            </div>
            <!-- #Menu -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>