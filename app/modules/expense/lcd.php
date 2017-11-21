<form role="form" id="frmLCDDetails" method="post">
<table class="table table-bordered table-striped" id="tbl_lcd_det" width="100%">
		<thead>
			<tr>
				<th width="13%">Date</th>
				<th width="18%">Place of Origin</th>
				<th width="18%">Place of Destination</th>
				<th width="10%">Distance </th>
				<th width="15%">Fare @ <span class="spn_cls_fare_per_km" >0.00</span> Rs/Km</th>
				<th width="13%">Food Expenses</th>
				<th width="16%">Total</th>
			</tr>
		</thead>
		<tbody>
			<tr id="tempRow" style="display:none">
				<td><div class="input-group date" style="width:150px;" onclick="funcCallDate(this);" ><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  	<input type="text" class="form-control datepicker" id="txt_lcd_date_rw" name="txt_lcd_date[]"/></div><input type="hidden" class="form-control" id="hdn_lcd_id_rw" name="hdn_lcd_id[]" /></td>
				<td><input type="text" class="form-control" id="txt_lcd_place_orgin_rw" name="txt_lcd_place_orgin[]" maxlength="50"  /></td>
				<td><input type="text" class="form-control" id="txt_lcd_place_dest_rw" name="txt_lcd_place_dest[]" maxlength="50" /></td>
				<td><input type="text" class="form-control calfaredistance" id="txt_lcd_distance_rw" name="txt_lcd_distance[]" maxlength="6" onkeypress="return ValidateNumberKeyPress(this, event);" style="width:70px;" /></td>
				<td><input type="text" class="form-control" id="txt_lcd_fare_rw" name="txt_lcd_fare[]" readonly="" maxlength="8" onkeypress="return ValidateNumberKeyPress(this, event);"  style="text-align:right" style="width:70px;"  />
                <input type="hidden" class="form-control" id="hdn_lcd_fare_perkm_rw" name="txt_lcd_fare_perkm[]" style="width:80px;" /></td>
				<td><input type="text" class="form-control calfaretotal" id="txt_lcd_food_exp_rw" name="txt_lcd_food_exp[]" maxlength="8" onkeypress="return ValidateNumberKeyPress(this, event);" style="text-align:right" /></td>
				<td><div style="width:89%; float:left" ><input type="text" class="form-control" id="txt_lcd_subtotal_rw" name="txt_lcd_subtotal[]" readonly=""  style="text-align:right" /></div><div style="width:10%; float:right;padding-top: 12px; color: #FF0000;" align="right" ><i class="fa fa-trash-o" style="cursor:pointer;" title="Click to clear values"  onclick="emptyLCDRow(rw)"></i></div></td>				
			</tr>
            </tbody>
            <tfoot>
            	<tr>
				<td colspan="6" align="right">Total</td>
				<td align="right"><span id="spn_lcd_total">0</span><input type="hidden" class="form-control" id="hdn_lcd_total" name="hdn_lcd_total" /></td>
			</tr>
            </tfoot>
		
	</table>
    </form>