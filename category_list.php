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
                            	<button class="btn btn-success createCategory"><i class="fa fa-plus"></i> Create</button>
                            </div>                            
                        </div>
                        <div class="body">
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Created / Updated Date</th>
                                        <th>Created / Updated By</th>
										<th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                    <tr>
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
                                    </tr>                                    
                                </tbody>
                            </table>
                      </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
        </div>
    </section><div class="AddEditForm">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    CATEGORY CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveCategory">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog">CANCEL</button>
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
</div