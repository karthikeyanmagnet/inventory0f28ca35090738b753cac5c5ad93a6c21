<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">    
				<div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User">
                </div>            
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">John Doe</div>
                    <div class="email">john.doe@example.com</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);" class="profileView"><i class="material-icons">person</i>Profile</a></li>                            
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
                    <li <?php if($menuFocus=='dashboard.php') { ?> class="active" <?php } ?>>
                        <a href="dashboard.php">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>                   
                    <li  class="active">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-cog"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="active">
                                <a href="category_list.php">Category</a>
                            </li>                            
                            <li <?php if($menuFocus=='item_list.php') { ?> class="active" <?php } ?>>
                                <a href="item_list.php">Item</a>
                            </li>
                            <li <?php if($menuFocus=='item_group_list.php') { ?> class="active" <?php } ?>>
                                <a href="item_group_list.php">Item Group</a>
                            </li>
                            <li <?php if($menuFocus=='customer_list.php') { ?> class="active" <?php } ?>>
                                <a href="customer_list.php">Customer</a>
                            </li>
                            <li <?php if($menuFocus=='vendor_list.php') { ?> class="active" <?php } ?>>
                                <a href="vendor_list.php">Vendor</a>
                            </li>
                            <li <?php if($menuFocus=='user_list.php') { ?> class="active" <?php } ?>>
                                <a href="user_list.php">User</a>
                            </li>  
                            <li <?php if($menuFocus=='role_list.php') { ?> class="active" <?php } ?>>
                                <a href="role_list.php">Roles</a>
                            </li>                     
                            <li <?php if($menuFocus=='permission_list.php') { ?> class="active" <?php } ?>>
                            	<a href="permission_list.php">Role Permission</a>
                            </li>     
                        </ul>
                    </li>
                    <li <?php if($menuFocus=='purchase_order_list.php') { ?> class="active" <?php } ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-file-text"></i>
                            <span>Purchase Orders</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($menuFocus=='purchase_order_list.php') { ?> class="active" <?php } ?>>
                                <a href="purchase_order_list.php">Entry</a>
                            </li>                            
                            <li>
                                <a href="#">Supplier Assignment</a>
                            </li>                                                    
                        </ul>
                    </li>
                    <li <?php if($menuFocus=='stock_entry_list.php' || $menuFocus=='stock_dispatch_list.php') { ?> class="active" <?php } ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-slack"></i>
                            <span>Inventory</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($menuFocus=='stock_entry_list.php') { ?> class="active" <?php } ?>>
                                <a href="stock_entry_list.php">Stock Entry</a>
                            </li>                            
                            <li <?php if($menuFocus=='stock_dispatch_list.php') { ?> class="active" <?php } ?>>
                                <a href="stock_dispatch_list.php">Stock Dispatch</a>
                            </li> 
                            <li>
                                <a href="#">Stock Bill</a>
                            </li>                                                    
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-ship"></i>
                            <span>Sales & Shipments</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="#">Create Shipment Order</a>
                            </li>                            
                            <li>
                                <a href="#">Create Invoice and Close PO</a>
                            </li> 
                            <li>
                                <a href="#">Track Payments of Initial PO</a>
                            </li>                                                    
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="fa fa-cube"></i>
                            <span>Assets</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="#">Assets Management</a>
                            </li>                                                                                                            
                        </ul>
                    </li>				
                </ul>
            </div>
            <!-- #Menu -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>