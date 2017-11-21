function loadPOAssignMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Item Group</li>';
	topHeadTitle(titleCont);
	
	GV_po_assign_head = '';
	GV_po_entry_id=''; 
	
	var a  = "view";	 
	var pageParams = {action:a, module:'po_assign', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPOAssigndataTableList'};	 
	
	callCommonLoadFunction(passArr);  
	
}

function loadPOAssigndataTableList()
{ 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'po_assign', 'gv_po_head':GV_po_entry_id}; 
	
	$("#pOAssignMasterTbl").dataTable({
				   "processing": true,
				  "serverSide": true,
				   "bAutoWidth": false,
				    "bSort": false,
				  "ajax":  {
						"url": "process.php",
						"type": "POST",
						"data":pageParams
					},
				"fnDrawCallback":function()
				{
					//checkPermssion('po_assign');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


var Gv_edit_from_poassign = '';
function EditPOAssignMasterList(idVal, opt)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'po_assign', view:'edit'};  
	var custVals = {};  
	custVals["id"]=idVal;
	
	if(opt == 1)
	{
		Gv_edit_from_poassign = 'view';		
	}
	
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadeditPOAssignMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}

function loadeditPOAssignMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'po_assign'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'editPutDataPOAssignMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function CreateUpdatePOAssignMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'po_assign', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPOAssignMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#showPoInternalEntry'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadPOAssignMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'po_assign','gv_po_head':GV_po_entry_id};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataPOAssignMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function editPutDataPOAssignMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
	if(opStatus!='success') 
	{  
		if(opData.message!=undefined)
		{
			customAlert(opData.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	} 
	
	if(opData.rsData!=undefined)
	{
		var rsOp=opData.rsData;
		
		$('.AddEditForm').find("#hid_id").val(rsOp.po_assign_head_id);
		
		$('.AddEditForm').find("#hid_po_orderid").val(GV_po_entry_id);
		
		if(Number(rsOp.po_assign_head_id)>0)
		{
			GV_po_assign_head = rsOp.po_assign_head_id;
		}
		
		var customerlist = rsOp.customerlist;
		var itemlist = rsOp.itemlist; 
		
		var itmgrpdet_details = rsOp.itmgrpdet_details; 
		
		var cnt = itmgrpdet_details.length; 
		
		var option_customer_def = "<option value='0'>Select</option>"; 
		$.each(customerlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_customer_def += "<option value='"+v.vendor_id+"' "+selected+">"+v.supplier_name+"</option>";				  
		}); 

		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(itemlist, function(k,v){
			var selected = '';
			//if(v.active_status == '1')
			option_prdline_def += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'  >"+v.item_code+"</option>";				  
		});
		
		$('.AddEditForm').find('select#vendor_id').empty();
		$('.AddEditForm').find('select#vendor_id').append(option_customer_def);
		
		$('.AddEditForm').find('select#cmb_item_rw').empty();
		$('.AddEditForm').find('select#cmb_item_rw').append(option_prdline_def);
		
		
		
		var table = '#customFieldsEdit';
		var tempRow = $(table).find('#tempRow');
		
		if(cnt<1) cnt = 1;
		for(i = 0; i < cnt; i++)
		{
			var tr = $(tempRow).html();
			tr = tr.replace(/rw/g, i+1);
			
			$(table).append("<tr id='bill_"+(i+1)+"'>"+tr+"</tr>");
			
			var tr = $(table).find('tr#bill_'+(i+1)); 
			
			var rec = itmgrpdet_details[i];
			
			if(rec)
			{
				if(rec.po_assign_det_id )
				{
					$(tr).find('#hdn_po_detid_'+(i+1)).val(rec.po_assign_det_id);
					$(tr).find('#item_desc_'+(i+1)).val(rec.item_desc); 
					$(tr).find('#po_qty_'+(i+1)).val(rec.po_qty); 
					$(tr).find('#qty_avaliable_'+(i+1)).val(rec.qty_avaliable); 
					$(tr).find('#req_qty_'+(i+1)).val(rec.req_qty); 
					$(tr).find('#unit_cost_'+(i+1)).val(rec.unit_cost); 
					$(tr).find('#line_total_'+(i+1)).val(rec.line_total); 
					$(tr).find('#item_tax_'+(i+1)).val(rec.item_tax); 
					$(tr).find('#chk_'+(i+1)).each(function(){
						this.checked = true;
					});
				}
				
				 
		
				
				var option_subcat = "<option value='0'>Select</option>";
		
				$.each(itemlist, function(k,v){
					var selected = ''; 
					if(rec.item_id == v.item_id) selected = 'selected';
					option_subcat += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'>"+v.item_code+"</option>";				  
				});  
				 
				
				$('.AddEditForm').find('select#cmb_item_'+(i+1)).empty();
				$('.AddEditForm').find('select#cmb_item_'+(i+1)).append(option_subcat);
				 
			}
			else
			{ 
				
				$('.AddEditForm').find('select#cmb_item_'+(i+1)).empty();
				$('.AddEditForm').find('select#cmb_item_'+(i+1)).append(option_prdline_def);  
			}
		} 
		
		
		if(rsOp.po_assign_head_id)
		{
			$('.AddEditForm').find("#vendor_id").val(rsOp.vendor_id);
			$('.AddEditForm').find("#delivery_date_before").val(rsOp.delivery_date_before);
			$('.AddEditForm').find("#delivery_date_after").val(rsOp.delivery_date_after);
			$('.AddEditForm').find("#po_assign_terms").val(rsOp.po_assign_terms);
			$('.AddEditForm').find("#po_assign_remarks").val(rsOp.po_assign_remarks);
			$('.AddEditForm').find("#internal_po_number").val(rsOp.internal_po_number);
			$('.AddEditForm').find("#po_total").val(rsOp.po_total);
			$('.AddEditForm').find("#po_grant_total").val(rsOp.po_grant_total);
			$('.AddEditForm').find("#active_status").val(rsOp.active_status);
		}
		
		if(Gv_edit_from_poassign == 'view')
		{
			$('.view_detete_poassig').hide();
			  
		}
		
		 
		/*rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active   // denot delete 
		if(rsOp.status == '1')
		$("#category_status").each(function(){ this.checked = true; });*/
		
		
		
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit POAssign':'New POAssign'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	$('.AddEditForm, .overlay').show();
	$('.AddEditForm').addClass('mediumBox');
	$('body').scrollTop(5);
	
}



var GV_po_assign_head = '';
function putDataPOAssignMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
	if(opStatus!='success') 
	{  
		if(opData.message!=undefined)
		{
			customAlert(opData.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	} 
	
	if(opData.rsData!=undefined)
	{
		var rsOp=opData.rsData;
		
		$('#frmPOAssignMaster').find("#hid_id").val(rsOp.po_assign_head_id);
		
		$("#hid_po_orderid").val(GV_po_entry_id);
		
		if(Number(rsOp.po_assign_head_id)>0)
		{
			GV_po_assign_head = rsOp.po_assign_head_id;
			$('.createInternalPO').show();
		}
		
		var customerlist = rsOp.customerlist;
		var itemlist = rsOp.itemlist; 
		
		var itmgrpdet_details = rsOp.itmgrpdet_details; 
		
		var cnt = itmgrpdet_details.length; 
		
		var option_customer_def = "<option value='0'>Select</option>"; 
		$.each(customerlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_customer_def += "<option value='"+v.vendor_id+"' "+selected+">"+v.supplier_name+"</option>";				  
		}); 

		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(itemlist, function(k,v){
			var selected = '';
			//if(v.active_status == '1')
			option_prdline_def += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'  >"+v.item_code+"</option>";				  
		});
		
		$('select#vendor_id').empty();
		$('select#vendor_id').append(option_customer_def);
		
		$('select#cmb_item_rw').empty();
		$('select#cmb_item_rw').append(option_prdline_def);
		
		
		
		var table = '#customFields';
		var tempRow = $(table).find('#tempRow');
		
		if(cnt<1) cnt = 1;
		for(i = 0; i < cnt; i++)
		{
			var tr = $(tempRow).html();
			tr = tr.replace(/rw/g, i+1);
			
			$(table).append("<tr id='bill_"+(i+1)+"'>"+tr+"</tr>");
			
			var tr = $(table).find('tr#bill_'+(i+1)); 
			
			var rec = itmgrpdet_details[i];
			
			if(rec)
			{
				if(rec.item_id )
				{
					$(tr).find('#hdn_po_detid_'+(i+1)).val(rec.po_assign_det_id);
					$(tr).find('#item_desc_'+(i+1)).val(rec.item_desc); 
					$(tr).find('#po_qty_'+(i+1)).val(rec.po_qty); 
					$(tr).find('#qty_avaliable_'+(i+1)).val(rec.qty_avaliable); 
					$(tr).find('#req_qty_'+(i+1)).val(rec.req_qty); 
					$(tr).find('#unit_cost_'+(i+1)).val(rec.unit_cost); 
					$(tr).find('#line_total_'+(i+1)).val(rec.line_total); 
					$(tr).find('#item_tax_'+(i+1)).val(rec.item_tax); 
					$(tr).find('#chk_'+(i+1)).each(function(){
						this.checked = true;
					});
				}
				
				 
		
				
				var option_subcat = "<option value='0'>Select</option>";
		
				$.each(itemlist, function(k,v){
					var selected = ''; 
					if(rec.item_id == v.item_id) selected = 'selected';
					option_subcat += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'>"+v.item_code+"</option>";				  
				});  
				 
				
				$('select#cmb_item_'+(i+1)).empty();
				$('select#cmb_item_'+(i+1)).append(option_subcat);
				 
			}
			else
			{ 
				
				$('select#cmb_item_'+(i+1)).empty();
				$('select#cmb_item_'+(i+1)).append(option_prdline_def);  
			}
		} 
		
		
		if(rsOp.po_assign_head_id)
		{
			$("#vendor_id").val(rsOp.vendor_id);
			$("#delivery_date_before").val(rsOp.delivery_date_before);
			$("#delivery_date_after").val(rsOp.delivery_date_after);
			$("#po_assign_terms").val(rsOp.po_assign_terms);
			$("#po_assign_remarks").val(rsOp.po_assign_remarks);
		}
		
		 
		/*rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active   // denot delete 
		if(rsOp.status == '1')
		$("#category_status").each(function(){ this.checked = true; });*/
		
		
		
		
		
	} 
	//var modal_title = Number($("#hid_id").val())>0?'Edit POAssign':'New POAssign'; 
	//$('#viewPageModal').find('.modal-title').text(modal_title); 
	
	$('#showPoEntry').hide();
	$('#showPoInternalEntry').show();
	loadPOAssigndataTableList();
	
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	//$('.AddEditForm, .overlay').show();
	//$('.AddEditForm').addClass('mediumBox');
	
}
function viewPOAssignMasOnChangeCategory(id)
{
	var parprdid = $('select#cmb_category_'+(id)).val();
	$('select#cmb_item_'+(id)).val(0);
	$('select#cmb_item_'+(id)).val(0);
	
	$('select#cmb_item_'+(id)).find('option').hide(); 
	$('select#cmb_item_'+(id)).find('option').each(function(){
		if(parprdid == $(this).attr('parprdid') || $(this).val()==0)
		$(this).show();
	}); 
	 
} 
function addPOAssignMasEntryDetailRow()
{
	var table = '#showPoInternalEntry #customFields';
	if($('#hid_from_ae').val() == 'edit')
	{
		table = '#customFieldsEdit';
	}
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getPOAssignMasEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	/*$('.datepicker').datepicker({
			  autoclose: true
	});*/
	
	 
 
}
function chkvalidtoDeletePOassign()
{
	
}
function deletePOAssignMasEntryRow(rw)
{
	var len = $('#customFieldsEdit tbody tr:not(#tempRow)').length;
	if(len == 1)
	{
		alert('Atleast one item');
		return false;	
	}
	
	var con = confirm('Are you sure want to delete?');
	if(!con) return false;
	
	var tr = $('#customFieldsEdit tr#bill_'+rw);
	var del_val = $(tr).find('#hdn_po_detid_'+rw).val();
	var hid_temp_del = $('#hid_temp_del').val();
	
	if(del_val!='')
	{
		if(hid_temp_del!='')
		{
			del_val = hid_temp_del+','+del_val;
		}
	}
	
	//alert(del_val);
	
	$('#hid_temp_del').val(del_val); 
	
	$(tr).remove(); 
	 
	var addtblcnt = 0;
	$('#customFieldsEdit').find('tbody tr:not(#tempRow)').each(function(){
			addtblcnt++;
	});
	if(addtblcnt==0) addPOAssignMasEntryDetailRow();
	calCulatePOAssignTotal();
}
function getPOAssignMasEntryTableMaxId()
{
	var table = '#customFields';
	if($('#hid_from_ae').val() == 'edit')
	{
		container = 'customFieldsEdit';
	}
	var tempRow = $(table).find('#tempRow');
	var trid = 0;
	$(table).find('tbody tr:not(#tempRow)').each(function(){
			var id = $(this).attr('id');
			id = id.replace('bill_','');
			id = Number(id);
			if(trid<id) trid = id;
	});
	
	return trid;
}
function CreateUpdatePOAssignMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'po_assign'}
	var container = 'showPoInternalEntry';
	var form_name = 'frmPOAssignMaster';
	if($('#hid_from_ae').val() == 'edit')
	{
		container = 'frmPOAssignEditMaster';
		form_name = 'frmPOAssignEditMaster';
	}
	
	if(jQuery.trim($('#'+form_name+' #vendor_id').val())==0) { alert('Select Vendor'); $('#vendor_id').focus(); return false; } 
	var item_checked = false;
	if($('#'+form_name+' input[type=checkbox].po_assign_chkbx:checked').length>0 || $('#hid_from_ae').val() == 'edit') item_checked = true;
	if(item_checked == false)
	{
		alert('Atleast check any item');
		return false;
	}
	var _itemSelected = 0;
	var qunatityItem = 0;
	
	$('#'+form_name+' input[type=checkbox].po_assign_chkbx:checked').each(function(){
		
			var val = Number($(this).val());
			if(val>0)
			{
				_itemSelected = 1;	
				
				var tr = $(this).closest('tr');
				var qty = $(tr).find('input[name="req_qty[]"]').val();
				var itm = $(tr).find('select[name="cmb_item[]"]').val();
				if(qty=='')
				{
					qunatityItem = 1;
				}
				if(Number(itm)>0)
				{
					_itemSelected = 1;
				}
			}
		});
	if($('#hid_from_ae').val() == 'edit')
	{
			qunatityItem = 0;
			_itemSelected = 1;
	}
	if(_itemSelected == 0)
		{
			alert('Enter Item details');
			return false;
		}
		if(qunatityItem == 1)
		{
			alert('Enter Item quantity details');
			return false;
		}
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#'+form_name).serializeArray(); 
	pageParams.push(actParams);
	pageParams.push(modParams);  
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'refreshPOAsssignTable', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		 
}

function refreshPOAsssignTable(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	var opStatus='failure';
	if(opData.status!=undefined) opStatus=opData.status;
	
	if(opStatus!='success') 
	{  
		if(opData.message!=undefined)
		{
			customAlert(opData.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	} 
	else
	{
		alert('Details updated successfully');
	}
	
	if($('#hid_from_ae').val() == 'edit')
	{
		closePOAssignDiaglog();
	}
	viewPOAssignEntry();
}

function closePOAssignDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closePOAssignDeletDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closePOAssignModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		GV_show_list_page_succmsg=data.message;
	}
	else
	{
		if(data.message!=undefined)
		{
			customAlert(data.message); 
		}
		else
		{ 
			customAlert('Something went wrong!'); 
		}
		return 
	}				
			
	
	closePOAssignDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadPOAssignMaster();
}	
	
	

function viewDeletePOAssignMaster(id)
{
	$('#frmPOAssignDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'po_assign'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeletePOAssign', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeletePOAssign(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 

	if(opData.status == 'success')
	{
		$('.DeleteForm, .overlay').show();
		$('.DeleteForm').addClass('mediumBox');	
	}
	else
	alert(opData.message);
	
	//
	//$('#viewDeleteModal').modal({  show:true, backdrop:false });
}

function deletePOAssignMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'po_assign'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPOAssignDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closePOAssignDeletDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPOAssignMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 


function calCulatePOAssignTotal()
{
	var totAmount = 0, table = 'customFields', container = 'frmPOAssignMaster';
	if($('#hid_from_ae').val() == 'edit')
	{
		table = 'customFieldsEdit';
		container = 'frmPOAssignEditMaster';
	}
	$('#'+table+' tr:not(#tempRow)').find('.poorder').each(function(){
		
		var ord = $(this).val();
			ord = (ord)?ord:0;
		var tr = $(this).closest('tr');
		var hdn_po_detid =  Number($(tr).find('input[name="hdn_po_detid[]"]').val());
		if($(tr).find('input[type=checkbox].po_assign_chkbx').is(':checked') || hdn_po_detid>0)
		{
		var untprice = $(tr).find('input[name="unit_cost[]"]').val();
		    untprice = (untprice)?parseFloat(untprice)*1:0;
		var subtot      = (ord*untprice);
			totAmount+=subtot;
		 $(tr).find('input[name="line_total[]"]').val(subtot.toFixed(2));
		}
		else
		{
			var subtot = 0;
			$(tr).find('input[name="line_total[]"]').val(subtot.toFixed(2));
		}
			
	});
	var order_total = totAmount.toFixed(2);
	$('#'+container).find('#po_total').val(order_total);
	calCulatePOTax();
}
function calCulatePOTax()
{
	var weigOverTotal = 0, table = 'customFields',container = 'frmPOAssignMaster';
	if($('#hid_from_ae').val() == 'edit')
	{
		table = 'customFieldsEdit';
		container = 'frmPOAssignEditMaster';
	}
	$('#'+table+' tr:not(#tempRow)').find('.potax').each(function(){
		
		var tr = $(this).closest('tr');
		if($(tr).find('input[type=checkbox].po_assign_chkbx').is(':checked'))
		{
		var wei = $(this).val();
		    wei = (wei)?parseFloat(wei)*1:0;
			
			weigOverTotal+=wei;
		}
			
	});
	var total = parseFloat($('#'+container).find('#po_total').val())*1;
	var total_weight_lbs = (weigOverTotal + total).toFixed(2);
	$('#'+container).find('#po_grant_total').val(total_weight_lbs);
}
function printPOAssignMaster(id)
{
	var a  = "view";	 
	var pageParams = {action:a, module:'po_assign', view:'report_pdf', id:id};  
	var custVals = {};  
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'viewDownloadFile',sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}