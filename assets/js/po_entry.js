function loadPOEntryMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Item Group</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'po_entry', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPOEntrydataTableList'};	 
	
	callCommonLoadFunction(passArr);  
	
}

function loadPOEntrydataTableList()
{ 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'po_entry'}; 
	
	$("#pOEntryMasterTbl").dataTable({
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
					//checkPermssion('po_entry');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdatePOEntryMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'po_entry', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadPOEntryMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#rightMenuDiv'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadPOEntryMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'po_entry'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataPOEntryMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
var GV_po_entry_id = '';
function putDataPOEntryMasterAddEdit(StrData)
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
		
		$('#frmPOEntryMaster').find("#hid_id").val(rsOp.po_head_id);
		
		GV_po_entry_id = rsOp.po_head_id; 
		
		var customerlist = rsOp.customerlist;
		var itemlist = rsOp.itemlist; 
		var itemgrouplist = rsOp.itemgrouplist;
		
		var itmgrpdet_details = rsOp.itmgrpdet_details; 
		
		var cnt = itmgrpdet_details.length; 
		
		var option_subcat = "<option value='0'>Select Item group</option>";
		$.each(itemgrouplist, function(k,v){
					var selected = ''; 
					//if(rsOp.item_group_id == v.item_group_id) selected = 'selected';
					option_subcat += "<option value='"+v.item_group_id+"' "+selected+">"+v.item_group_name+"</option>";				  
				}); 
		
		$('select#cmb_item_group').empty();
		$('select#cmb_item_group').append(option_subcat);  
		
		var option_customer_def = "<option value='0'>Select</option>"; 
		$.each(customerlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_customer_def += "<option value='"+v.customer_id+"' "+selected+">"+v.company_name+"</option>";				  
		}); 

		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(itemlist, function(k,v){
			var selected = '';
			//if(v.active_status == '1')
			option_prdline_def += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'  >"+v.item_code+"</option>";				  
		});
		
		$('select#customer_id').empty();
		$('select#customer_id').append(option_customer_def);
		
		$('select#cmb_item_rw').empty();
		$('select#cmb_item_rw').append(option_prdline_def);
		
		
		
		var table = '#customFieldsPOEntry';
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
				if(rec.po_det_id )
				{
					$(tr).find('#hdn_po_detid_'+(i+1)).val(rec.po_det_id);
					$(tr).find('#item_desc_'+(i+1)).val(rec.item_desc); 
					(tr).find('#whse_line_'+(i+1)).val(rec.whse_line); 
					(tr).find('#weight_line_'+(i+1)).val(rec.weight_line); 
					(tr).find('#ordered_qty_'+(i+1)).val(rec.ordered_qty); 
					(tr).find('#unit_cost_'+(i+1)).val(rec.unit_cost); 
					(tr).find('#line_total_'+(i+1)).val(rec.line_total); 
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
		
		$('.viewSupAssignment').hide();
		 
		if(rsOp.po_head_id)
		{  
			$('.divBtnTabSuppAssig').show();
			//alert(rsOp.customer_id);
			$("#customer_id").val(rsOp.customer_id);
			$("#ship_date").val(rsOp.ship_date);
			$("#po_number").val(rsOp.po_number);
			$("#spg_order_type").val(rsOp.spg_order_type);
			$("#so_number").val(rsOp.so_number);
			$("#po_type").val(rsOp.po_type);
			$("#cust_po_number").val(rsOp.cust_po_number);
			$("#issuer").val(rsOp.issuer);
			$("#po_terms").val(rsOp.po_terms);
			$("#order_total").val(rsOp.order_total);
			$("#po_notes").val(rsOp.po_notes);
			$("#po_entry_name").val(rsOp.po_entry_name);
			$("#total_weight_lbs").val(rsOp.total_weight_lbs);
			$("#total_weight_mt").val(rsOp.total_weight_mt);
			$("#active_status").val(rsOp.active_status); 
			$('.viewSupAssignment').show();
		}
		
		 
		/*rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active   // denot delete 
		if(rsOp.status == '1')
		$("#category_status").each(function(){ this.checked = true; });*/
		
		
		
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit POEntry':'New POEntry'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	//$('.AddEditForm, .overlay').show();
	//$('.AddEditForm').addClass('mediumBox');
	
}
function viewPOEntryMasOnChangeCategory(id)
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
function addPOEntryMasEntryDetailRow()
{
	var table = '#showPoEntry #customFieldsPOEntry';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getPOEntryMasEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	/*$('.datepicker').datepicker({
			  autoclose: true
	});*/
	
	 
 
}
function deletePOEntryMasEntryRow(rw)
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
	$('#customFieldsPOEntry').find('tbody tr:not(#tempRow)').each(function(){
			addtblcnt++;
	});
	if(addtblcnt==0) addPOEntryMasEntryDetailRow();
}
function getPOEntryMasEntryTableMaxId()
{
	var table = '#customFieldsPOEntry';
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
function CreateUpdatePOEntryMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'po_entry'}
	
	if(jQuery.trim($('#customer_id').val())==0) { alert('Select Customer Name'); $('#customer_id').focus(); return false; } 
	if(jQuery.trim($('#po_number').val())=="") { alert('Enter PO Number'); $('#po_number').focus(); return false; } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPOEntryMaster').serializeArray(); 
	pageParams.push(actParams);
	pageParams.push(modParams);  
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closePOEntryModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closePOEntryDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closePOEntryDeletDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closePOEntryModalDialog(response)
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
			
	
	closePOEntryDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadPOEntryMaster();
}	
	
	

function viewDeletePOEntryMaster(id)
{
	$('#frmPOEntryDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'po_entry'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeletePOEntry', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeletePOEntry(StrData) 
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

function deletePOEntryMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'po_entry'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPOEntryDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closePOEntryDeletDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadPOEntryMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 

function calCulatePOWeight()
{
	var weigOverTotal = 0;
	$('#customFieldsPOEntry tr:not(#tempRow)').find('.poweight').each(function(){
		
		var wei = $(this).val();
		    wei = (wei)?parseFloat(wei)*1:0;
			
			weigOverTotal+=wei;
			
	});
	
	var total_weight_lbs = weigOverTotal.toFixed(3);
	var total_weight_mt = weigOverTotal*0.000453592;
	total_weight_mt = total_weight_mt.toFixed(3);
	$('#total_weight_lbs').val(total_weight_lbs);
	$('#total_weight_mt').val(total_weight_mt);
}

function calCulatePOEntryTotal()
{
	var totAmount = 0;
	$('#customFieldsPOEntry tr:not(#tempRow)').find('.poorder').each(function(){
		
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
	$('#order_total').val(order_total);
}

function viewPOAssignEntry()
{
	CreateUpdatePOAssignMasterList(0);
	
}
function viewPOEntry()
{
	$('#showPoEntry').show();
	$('#showPoInternalEntry').hide();
}
function printPOEntryMaster(id)
{
	var a  = "view";	 
	var pageParams = {action:a, module:'po_entry', view:'report_pdf', id:id};  
	var custVals = {};  
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'viewDownloadFile',sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function addItemGroupToPOEntryRows()
{
	var search_val = $('#cmb_item_group').val();
	var txt_grp_qty = $('#txt_grp_qty').val();
	if(search_val == 0)
	{
		alert('Select Item Group');
		return false;
	}
	var actParams = {name:'action', value:'getProductDetails'};  
	var modParams = {name:'module', value:'stock_dispatch'}
	var pageParams=new Array();
	pageParams.push(actParams);
	pageParams.push(modParams); 		
	pageParams.push({name:'item_group_id', value:search_val});
	pageParams.push({name:'txt_grp_qty', value:txt_grp_qty});
	var passArr={pURL:'process.php',pageParams:pageParams };
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'addPOEntriesFromItemGroup',displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
}

function addPOEntriesFromItemGroup(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	console.log(jOPData);

	if(opData.status == 'success')
	{
			if(opData.rsData!=undefined)
			{
				var rsOp=opData.rsData;
				
				//var table = '#customProductdet';
				//$(table).find('tbody tr:not(#tempRow)').remove();
				//var tempRow = $(table).find('#tempRow');
				var cnt=rsOp.length;
				
				var len = $('#customFieldsPOEntry tbody tr:not(#tempRow)').length;
				if(len == 1)
				{
					var ndid=1;
					$('#customFieldsPOEntry tbody tr:not(#tempRow)').each(function(){
																				   
						var trid=$(this).attr('id');
						ndid=parseInt(trid.replace("bill_","")); 
						 
					});
					
					if($('#cmb_item_'+ndid).val()==0)
					{
						$('#customFieldsPOEntry tbody tr:not(#tempRow)').remove();
					}
				}
				
				var table = '#showPoEntry #customFieldsPOEntry';
				var tempRow = $(table).find('#tempRow');
				
				for(i = 0; i < cnt; i++)
				{
				var rwid = getPOEntryMasEntryTableMaxId()+1;
				
				var tr = $(tempRow).html();
				tr = tr.replace(/rw/g, rwid);
				
				$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
				var rec = rsOp[i];
				var tr = $(table).find('tr#bill_'+rwid);
				$(tr).find('#cmb_item_'+rwid).val(rec.item_id);
				$(tr).find('#ordered_qty_'+rwid).val(rec.dispatch_req_qty);
				
				}
				
				
				
			}
	}
}