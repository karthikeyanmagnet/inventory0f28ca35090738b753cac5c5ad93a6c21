<?php 
	include  dirname(realpath('..')).'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	
	$expense = new expense();
	$sess_log_employee_id = $expense->sess_userid;
	$user_previlage = $expense->getUserPervilageSettings();
	$shwOnlyEmp="";
	if(!$user_previlage['view_to_others'])  // $sess_logintype==2 
	{
		$shwOnlyEmp="shwOnlyEmp";
	}
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	$frm_date=date("d-m-Y",strtotime("-1 month"));;
	$to_date=date('d-m-Y');
	 
?>
<form role="form" id="frmBudgetReport" method="post">
<?php 
 
	
	//============================ Below PHP code: praga small dog will put it in class file 
	
	//=========== Employee list
	/*$sqlEmpl="select um.user_id, um.user_display_name from bud_user_master as um order by um.user_display_name";
	$bindEmplArr=array();
	$recempl = $expense->pdoObj->fetchMultiple($sqlEmpl, ''); 
	
	 
	$emplArr=array(); 
	foreach($recempl as $empl_val)
	{
		$m_user_id=$empl_val['user_id']; 
		$m_user_display_name=$expense->purifyString($empl_val['user_display_name']); 
		
		$emplArr[] = array('user_id'=>$m_user_id, 'user_display_name'=>$m_user_display_name);  
	}*/ 
	
	$emplArr = $expense->getEmployeeList();
	
	
	//=========== Category list
	$sqlCats="select cat.category_id, cat.category_name from bud_category_master as cat where cat.active_status=1 order by cat.category_name";
	$bindCatsArr=array();
	$reccats = $expense->pdoObj->fetchMultiple($sqlCats, ''); 
	
	 
	$catArr=array(); 
	foreach($reccats as $cat_sub)
	{
		$m_cat_id=$cat_sub['category_id']; 
		$m_cat_name=$expense->purifyString($cat_sub['category_name']); 
		
		$catArr[] = array('category_id'=>$m_cat_id, 'category_name'=>$m_cat_name);  
	} 
	
	//=========== sub category list
	
	$sqlSubCats="select scat.category_id, scat.subcategory_id, scat.subcategory_name from bud_subcategory_master as scat where scat.active_status=1 order by scat.subcategory_name";
	$bindSubCatsArr=array();
	$recsubcats = $expense->pdoObj->fetchMultiple($sqlSubCats, ''); 
 
	$subcatArr=array(); 
	foreach($recsubcats as $cat_sub)
	{ 
		$m_cat_id=$cat_sub['category_id']; 
		$m_subcat_id=$cat_sub['subcategory_id']; 
		$m_subcat_name=$expense->purifyString($cat_sub['subcategory_name']); 
		 
		$subcatArr[] = array('subcategory_id'=>$m_subcat_id, 'subcategory_name'=>$m_subcat_name, 'category_id'=>$m_cat_id);  
	} 
	 
	 
	
	//echo '<pre>';
	//print_r($expDisp); 
	
?>   
<input type="hidden" name="cmbbx_year" id="cmbbx_year" value="<?=$cmbbx_year;?>"  />   <div class="content-wrapper">
         <section class="content-header">
		 
		 <div class="box-body white-bg">
		  	<div class="table-responsive">
			
			<div class="col-md-12">
				<div class="col-md-2">
					Employee
				</div>
				<div class="col-md-2">
					<select name="cmb_employee" id="cmb_employee"  class="form-control" >
					<option value="0" ></option>
					<?php
						foreach($emplArr as $empVal){
						if($shwOnlyEmp=="shwOnlyEmp" and $empVal['user_id']==$sess_log_employee_id){ $_selected = 'selected'; }
					?>
					<option value="<?=$empVal["user_id"];?>" <?=$_selected;?>><?=$empVal["user_display_name"];?></option>
					<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
					Category
				</div>
				<div class="col-md-2">
					<select name="cmb_category" id="cmb_category" class="form-control" >
					<option value="0" ></option>
					<?php
						foreach($catArr as $catVal){
					?>
					<option value="<?=$catVal["category_id"];?>" ><?=$catVal["category_name"];?></option>
					<?php } ?>
					</select>   
				</div>
				<div class="col-md-2">
					Sub Category
				</div>
				<div class="col-md-2">
					<select name="cmb_subcategory" id="cmb_subcategory" class="form-control" >
					<option value="0" ></option>
					<?php
						foreach($subcatArr as $subcatVal){
					?>
					<option value="<?=$subcatVal["subcategory_id"];?>" ><?=$subcatVal["subcategory_name"];?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-12">
			&nbsp;
			</div>
			
			<div class="col-md-12">
				<div class="col-md-2">
					Date From
				</div>
				<div class="col-md-2">
					<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);" ><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  	<input type="text" class="form-control pull-right datepicker" name="txt_expense_fromdate" id="txt_expense_fromdate" value="<?=$frm_date;?>" ></div>
				</div>
				<div class="col-md-2">
					Date To
				</div>
				<div class="col-md-2">
					<div class="input-group date" style="width:150px;" onclick="funcCallDate(this);" ><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  	<input type="text" class="form-control pull-right datepicker" name="txt_expense_todate" id="txt_expense_todate" value="<?=$to_date;?>"></div>
				</div>
				<div class="col-md-2">
					<button class="btn btn-primary btn-sm " type="button" onclick="expenseReportSearchData()" ><i class="fa fa-floppy-o"></i> Search</button>
				</div>
				<div class="col-md-2">
					<button class="btn btn-warning btn-sm " type="button" onclick="expenseReportClearData()" ><i class="fa fa-times"></i> Clear</button>
				</div>
			</div>
			
			
			</div>
			</div><br />
			
          <h1>
             Expense Report	 
			<div class="pull-right">
			  <a class="js-open-modal btn btn-warning" style="margin-top:-6px;margin-right:10px;" onclick="exportExpenseReportToPDF();" ><i class="fa fa-file-pdf-o"></i> Export to PDF</a>
			</div>
			<div class="pull-right">
			  <a class="js-open-modal btn btn-warning" style="margin-top:-6px;margin-right:10px;" onclick="exportExpenseReportToXLS();" ><i class="fa fa-th"></i> Export to Excel</a>
			</div> 
		</h1>	
        </section> 
		 

        <section class="content">
		

			 <div class="box-body white-bg">
		  	<div class="table-responsive " id="exp_rep_data" > 
		 
          </div>
				</div> 
        </section>
      </div>
    </div> 
	</form>