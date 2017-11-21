<?php 
	$maindir = dirname(realpath('..'));
	include  $maindir.'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	
	$dashboard = new dashboard();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	/* year drop down list */
	$shwFromYear=$dashboard->shwYearDropDownFrom;
	$drpDwnYearList=array();
	for($dd_y=$shwFromYear;$dd_y<=date('Y');$dd_y++){ $drpDwnYearList[]=$dd_y;} 
	rsort($drpDwnYearList);
	
	/*$cmbbx_year=$dashboard->purifyInsertString($postArr["v_cmbbx_year"]);
	if($cmbbx_year==""){ $cmbbx_year=date('Y'); } */
	$cmbbx_year=date('Y');
	 
?>
<div class="content-wrapper"> 
        <section class="content-header">
          <h1>
             Dashboard
			 <div class="dropdown pull-right">
			 <span style="font-size:15px">Year</span>
			  <button id="dd_year" class="btn btn-year dropdown-toggle" type="button" data-toggle="dropdown" style="width:150px;margin-right:10px;margin-top:-5px;"><?=$cmbbx_year;?>
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  <?php foreach($drpDwnYearList as $drpDwnYearVal){ ?>
				<li onclick="callDahboardeportChangecombo('<?=$drpDwnYearVal;?>',this,'dd_year');" ><a href="#"><?=$drpDwnYearVal;?></a></li><?php } ?> 
			  </ul>
			</div>
		</h1>	
        </section>

        <section class="content">
          <div class="row">
            <div class="col-lg-4 col-xs-6">
			  	<div class="dashboard-box-1">
					<h4><div class="dropdown pull-left">
					  <button id="dd_pie_yr_duraion" class="btn btn-year dropdown-toggle" type="button" data-toggle="dropdown">Current Month
					  <span class="caret"></span></button>
					  <ul class="dropdown-menu">
					  <li><a onclick="callDahboardeportChangecombo('cy',this,'dd_pie_yr_duraion');" href="#">Current Year</a></li>
						<li><a onclick="callDahboardeportChangecombo('cm',this,'dd_pie_yr_duraion');" href="#">Current Month</a></li>
						<li><a onclick="callDahboardeportChangecombo('q1',this,'dd_pie_yr_duraion');" href="#">1st Quarter</a></li>
						<li><a onclick="callDahboardeportChangecombo('q2',this,'dd_pie_yr_duraion');" href="#">2nd Quarter</a></li>
						<li><a onclick="callDahboardeportChangecombo('q3',this,'dd_pie_yr_duraion');" href="#">3rd Quarter</a></li>
						<li><a onclick="callDahboardeportChangecombo('q4',this,'dd_pie_yr_duraion');" href="#">4th Quarter</a></li> 
					  </ul>
					</div> 
					<span><font color="#8e44ad"><i class="fa fa-inr"></i> <label id="pie_bud_inramount" >0.00</label></font> / <font color="#e74c3c"><i class="fa fa-inr"></i> <label id="pie_exp_inramount" >0.00</label></font> </span></h4>
					<div id="div_pie_chart" style="min-width: 310px; height: 400px; max-width: 600px; margin:0 auto"></div>		
				</div>
            </div>
            <div class="col-lg-8 col-xs-6 left-padding-none">
				<div class="dashboard-box-2">
					<h4>
					<div class="dropdown pull-left" >
			  <button id="dd_bar_exp_type" class="btn btn-month dropdown-toggle" type="button" data-toggle="dropdown">Expenses by Month
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
				<li ><a onclick="callDahboardeportChangecombo('exp_month',this,'dd_bar_exp_type');" href="#" >Expenses by Month</a></li>
				<li ><a onclick="callDahboardeportChangecombo('exp_category',this,'dd_bar_exp_type');" href="#">Expenses by Category</a></li>
			  </ul>
			</div> 
			
			<div class="dropdown pull-left" style="display:none;" id="divCatMonthOpn" >
			  <button id="dd_bar_yr_duraion" class="btn btn-month dropdown-toggle" type="button" data-toggle="dropdown">Current Month
			  <span class="caret"></span></button>
			  <ul class="dropdown-menu">
					   <li><a onclick="callDahboardeportChangecombo('cy',this,'dd_bar_yr_duraion');" href="#">Current Year</a></li>
						<li><a onclick="callDahboardeportChangecombo('cm',this,'dd_bar_yr_duraion');" href="#">Current Month</a></li>
						<li><a onclick="callDahboardeportChangecombo('q1',this,'dd_bar_yr_duraion');" href="#">1st Quarter</a></li>
						<li><a onclick="callDahboardeportChangecombo('q2',this,'dd_bar_yr_duraion');" href="#">2nd Quarter</a></li>
						<li><a onclick="callDahboardeportChangecombo('q3',this,'dd_bar_yr_duraion');" href="#">3rd Quarter</a></li>
						<li><a onclick="callDahboardeportChangecombo('q4',this,'dd_bar_yr_duraion');" href="#">4th Quarter</a></li> 
					  </ul>
			</div>
			
			<span><font color="#8e44ad"><i class="fa fa-inr"></i> <label id="bar_bud_inramount" >0.00</label></font> / <font color="#e74c3c"><i class="fa fa-inr"></i> <label id="bar_exp_inramount" >0.00</label></font> </span></h4>
              		<div id="div_bar_chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
				</div>			  
				
            </div>
          </div>
        </section>
      </div>    