<?php 
	include  dirname(realpath('..')).'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	
	$expense = new expense();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	 
?>
<form role="form" id="frmBudgetReport" method="post">
<?php 
 

	$data = $expense->getExpenseReportData($postArr);
	
	$expDisp = $data['expDisp'];
	
?>   
	<?php 
			foreach($expDisp as $expLpKey=>$expLpVal)
			{
		?>
                  <table id="budget_allocation" class="table table-bordered table-striped table-responsive" style="overflow:auto">
				<thead> 
					<th colspan="5">Employee name : <?=$expLpKey;?></th>
					</thead>
				<tr valign="top">	
					<td><label class="lbl-1">&nbsp;</label></td>
					<td><label class="lbl-1"><strong>Date</strong></label></td>
					<td><label class="lbl-1"><strong>Category</strong></label></td>
					<td><label class="lbl-1"><strong>Sub Category</strong></label></td>
					<td><label class="lbl-1"><strong>Expenses</strong></label></td>  
				</tr>
				<?php 
			foreach($expLpVal["data"] as $expDataVal)
			{
		?>
				<tr valign="top">	
					<td>&nbsp;</td>
					<td ><?=$expDataVal["exp_date"];?></td>
					<td ><?=$expDataVal["cat_name"];?></td>
					<td ><?=$expDataVal["subcat_name"];?></td>
					<td align="right"><?php echo number_format($expDataVal["exp_amount"],2);?></td>  
				</tr>
				<?php } ?>
				<tr valign="top">	
					<td>&nbsp;</td>
					<td >&nbsp;</td>
					<td >&nbsp;</td>
					<td ><label class="lbl-1"><strong>Total</strong></label></td>
					<td align="right"><label class="lbl-1"><strong><?php echo number_format($expLpVal["total"],2);?></strong></label></td>  
				</tr> 
			</table>
			<?php } ?> 