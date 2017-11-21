function loadPOInternalAssignMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Item Group</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'po_internal_assign', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPOInternalAssigndataTableList'};	 
	
	callCommonLoadFunction(passArr);  
	
}

function loadPOInternalAssigndataTableList()
{ 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'po_internal_assign', 'po_assign_head':GV_po_assign_head}; 
	
	$("#POInternalAssignMasterTbl").dataTable({
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
					//checkPermssion('po_internal_assign');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdatePOInternalAssignMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'po_internal_assign', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPOInternalAssignMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadPOInternalAssignMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'po_internal_assign'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataPOInternalAssignMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataPOInternalAssignMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.po_internal_assign_head_id);
		
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
				if(rec.po_internal_assign_det_id )
				{
					$(tr).find('#hdn_po_detid_'+(i+1)).val(rec.po_internal_assign_det_id);
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
		
		
		if(rsOp.po_internal_assign_head_id)
		{
			$("#vendor_id").val(rsOp.vendor_id);
			$("#received_date").val(rsOp.received_date);
			$("#assigned_date").val(rsOp.assigned_date);
			$("#po_internal_assign_terms").val(rsOp.po_internal_assign_terms);
			$("#po_internal_assign_remarks").val(rsOp.po_internal_assign_remarks);
			
			$("#internal_po_number").val(rsOp.internal_po_number);
			$("#po_number").val(rsOp.po_number);
			$("#po_internal_assign_status").val(rsOp.po_internal_assign_status);
			$("#po_internal_assign_type").val(rsOp.po_internal_assign_type);
			$("#po_internal_assign_ext").val(rsOp.po_internal_assign_ext);
			$("#po_total").val(rsOp.po_total);
			$("#po_grant_total").val(rsOp.po_grant_total);
		}
		
		 
		/*rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active   // denot delete 
		if(rsOp.status == '1')
		$("#category_status").each(function(){ this.checked = true; });*/
		
		
		if(rsOp.po_internal_assign_ext !='' && rsOp.po_internal_file !='')
		{
				$('.viewLogo').show().click(function(){
					window.open(rsOp.po_internal_file);
				});
		}
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit POInternalAssign':'New POInternalAssign'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	$('.AddEditForm, .overlay').show();
	$('.AddEditForm').addClass('mediumBox');
	
	var options = { 
    target:     '#message', 
    url:        'process.php', 
	method: 'POST',
	dataType	: 'json',
	beforeSubmit: function(arr, $form, options) { 
	console.log(arr);
	console.log($form);
	console.log(options);
	
	},
    success:    function(res) { 
	 	closePOInternalAssignDiaglog(); 
		if(res.message){ alert(res.message); }
		else { alert('PO Internal details inserted successfully!');  }
		loadPOInternalAssignMaster();
    } 
}; 
	
	$('#frmPOInternalAssignMaster').ajaxForm(options); 

	
}
function viewPOInternalAssignMasOnChangeCategory(id)
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
function addPOInternalAssignMasEntryDetailRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getPOInternalAssignMasEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	/*$('.datepicker').datepicker({
			  autoclose: true
	});*/
	
	 
 
}
function deletePOInternalAssignMasEntryRow(rw)
{
	var con = confirm('Are you sure want to delete?');
	if(!con) return false;
	
	var tr = $('tr#bill_'+rw);
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
	$('#customFields').find('tbody tr:not(#tempRow)').each(function(){
			addtblcnt++;
	});
	if(addtblcnt==0) addPOInternalAssignMasEntryDetailRow();
}
function getPOInternalAssignMasEntryTableMaxId()
{
	var table = '#customFields';
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
function CreateUpdatePOInternalAssignMasterSave()
{	
	if(jQuery.trim($('#po_number').val())=="") { alert('Enter PO Number'); $('#po_number').focus(); return false; } 
	if(jQuery.trim($('#internal_po_number').val())==0) { alert('Enter Internal PO Number'); $('#internal_po_number').focus(); return false; } 
	if(jQuery.trim($('#vendor_id').val())==0) { alert('Select Assigned To'); $('#vendor_id').focus(); return false; } 
	var item_checked = false;
	if($('input[name="chk_po_internal_assign[]"]:checked').length>0) item_checked = true;
	if(item_checked == false)
	{
		alert('Atleast check any item');
		return false;
	}
	var _itemSelected = 0;
	var qunatityItem = 0;
	
	$('input[name="chk_po_internal_assign[]"]:checked').each(function(){
		
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
				if(itm>0)
				{
					_itemSelected = 1;
				}
			}
		});
	
	if(_itemSelected == 1)
		{
			alert('Enter Item details');
			return false;
		}
		if(qunatityItem == 1)
		{
			alert('Enter Item quantity details');
			return false;
		}
	
	$('#hdn_po_assign_head_id').val(GV_po_assign_head);
	$('#frmPOInternalAssignMaster').submit();
	return false;
	
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'po_internal_assign'}
	
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPOInternalAssignMaster').serializeArray(); 
	pageParams.push(actParams);
	pageParams.push(modParams);  
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closePOInternalAssignModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closePOInternalAssignDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closePOInternalAssignDeletDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closePOInternalAssignModalDialog(response)
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
			
	
	closePOInternalAssignDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadPOInternalAssignMaster();
}	
	
	

function viewDeletePOInternalAssignMaster(id)
{
	$('#frmPOInternalAssignDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'po_internal_assign'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeletePOInternalAssign', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeletePOInternalAssign(StrData) 
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

function deletePOInternalAssignMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'po_internal_assign'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPOInternalAssignDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closePOInternalAssignDeletDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPOInternalAssignMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 


function calCulatePOInternalAssignTotal()
{
	var totAmount = 0;
	$('#customFields tr:not(#tempRow)').find('.poorder').each(function(){
		
		var ord = $(this).val();
			ord = (ord)?ord:0;
		var tr = $(this).closest('tr');
		var untprice = $(tr).find('input[name="unit_cost[]"]').val();
		    untprice = (untprice)?parseFloat(untprice)*1:0;
		var subtot      = (ord*untprice);
			totAmount+=subtot;
		 $(tr).find('input[name="line_total[]"]').val(subtot.toFixed(2));
			
	});
	
	var order_total = totAmount.toFixed(2);
	$('#po_total').val(order_total);
	calCulatePOTax();
}

function gotoBackPOSupplier()
{
	CreateUpdatePOAssignMasterList(GV_po_assign_head);
}