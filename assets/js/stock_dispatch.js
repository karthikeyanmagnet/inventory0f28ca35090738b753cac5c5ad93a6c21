function loadStockDispatchMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Stock Dispatch</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'stock_dispatch', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockDispatchdataTableList'}; 
	
	callCommonLoadFunction(passArr);  
}

function loadStockDispatchdataTableList()
{ 
	var a  = "getList";	 
	var pageParams = {action:a, module:'stock_dispatch'}; 
	
	$("#stock_dispatchMasterTbl").dataTable({
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
					checkPermssion('stock_dispatch');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateStockDispatchMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'stock_dispatch', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadStockDispatchMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadStockDispatchMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'stock_dispatch'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataStockDispatchMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataStockDispatchMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.stock_dispatch_head_id);
		$("#hdn_po_head_id").val(rsOp.po_head_id);
		
		if(rsOp.stock_dispatch_head_id)
		{
			$('.viewaddmode').hide();
		}
		
		
		/*rsOp.active_status = Number($("#hid_id").val())>0?rsOp.active_status:1; //set default active
		if(rsOp.active_status == '1')
		$("#active_status").each(function(){ this.checked = true; }); */
		
		
		
		
		var itemlist = rsOp.itemlist; 
		var itemgrouplist = rsOp.itemgrouplist;
		var itmgrpdet_details = rsOp.itmgrpdet_details; 
		
		var cnt = itmgrpdet_details.length;
		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(itemlist, function(k,v){
			var selected = '';
			//if(v.active_status == '1')
			option_prdline_def += "<option value='"+v.item_id+"' "+selected+" >"+v.item_code+"</option>";				  
		});
		
		$('select#cmb_item_rw').empty();
		$('select#cmb_item_rw').append(option_prdline_def);
		
		$.each(itemgrouplist, function(k,v){
					var selected = ''; 
					if(rsOp.item_group_id == v.item_group_id) selected = 'selected';
					option_subcat += "<option value='"+v.item_group_id+"' "+selected+">"+v.item_group_name+"</option>";				  
				}); 
		
		$('select#cmb_item_group').empty();
		$('select#cmb_item_group').append(option_subcat);  
	
		
		if(Number(rsOp.po_head_id)>0)
		{
				$('input[type=radio][name=chk_stock_dispatch]').each(function(){
					if($(this).val() == 2)
					{
						this.checked = true;
					}
				});
				var table = '#customPoDet';
				$(table).find('tbody tr:not(#tempRow)').remove();
				var tempRow = $(table).find('#tempRow');
				//var cnt=rsOp.length;
				//if(cnt<1) cnt = 1;
				for(i = 0; i < cnt; i++)
				{
					var tr = $(tempRow).html();
					tr = tr.replace(/rw/g, i+1);
					
					$(table).append("<tr id='pstock_"+(i+1)+"'>"+tr+"</tr>");
					
					var tr = $(table).find('tr#pstock_'+(i+1)); 
					
					var rec = itmgrpdet_details[i];
					console.log(rec);
					if(rec)
					{
						if(rec.po_det_id )
						{
							$(tr).find('#hdn_po_det_id_'+(i+1)).val(rec.po_det_id);
							$(tr).find('#hdn_stock_dispatch_det_id_'+(i+1)).val(rec.stock_dispatch_det_id);
							$(tr).find('#txt_item_'+(i+1)).val(rec.item_code); 
							$(tr).find('#txt_disp_qty_'+(i+1)).val(rec.dispatch_qty); 
							$(tr).find('#hdn_item_id_'+(i+1)).val(rec.item_id); 
							$(tr).find('#cmb_poitem_status_'+(i+1)).val(rec.stock_dispatch_status); 
							
							$(tr).find('#txt_ava_qty_'+(i+1)).text(rec.avability); 
							var ava = Number(rec.avability);
							var disp = Number(rec.dispatch_qty);
							var remin = ava - disp;
							$(tr).find('#txt_remaing_qty_'+(i+1)).text(remin); 
							
							
						}
						
						
						
						
						 
					}
					
				} 
		 		
			
		}
		else if(Number(rsOp.item_group_id)>0)
		{
			$('input[type=radio][name=chk_stock_dispatch]').each(function(){
				if($(this).val() == 3)
				{
					this.checked = true;
				}
			});
			
			var table = '#customProductdet';
			var tempRow = $(table).find('#tempRow');
			for(i = 0; i < cnt; i++)
			{
				var tr = $(tempRow).html();
				tr = tr.replace(/rw/g, i+1);
				
				$(table).append("<tr id='prdstock_"+(i+1)+"'>"+tr+"</tr>");
					
				var tr = $(table).find('tr#prdstock_'+(i+1)); 
				
				var rec = itmgrpdet_details[i];
			
				if(rec)
				{
					if(rec.stock_dispatch_det_id )
					{
						$(tr).find('#hdn_itmgrp_detid_'+(i+1)).val(rec.stock_dispatch_det_id);						
						$(tr).find('#txt_prd_item_'+(i+1)).val(rec.item_code); 
						$(tr).find('#hdn_prd_item_id_'+(i+1)).val(rec.item_id); 
						$(tr).find('#txt_prd_disp_qty_'+(i+1)).val(rec.dispatch_qty); 
						
						
						$(tr).find('#txt_ava_qty_'+(i+1)).text(rec.avability); 
						var ava = Number(rec.avability);
						var disp = Number(rec.dispatch_qty);
						var remin = ava - disp;
						$(tr).find('#txt_remaing_qty_'+(i+1)).text(remin); 
						
					}
				}
			}
			
		}
		else
		{
		$('input[type=radio][name=chk_stock_dispatch]').each(function(){
			if($(this).val() == 1)
			{
				this.checked = true;
			}
		});
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
				if(rec.stock_dispatch_det_id )
				{
					$(tr).find('#hdn_itmgrp_detid_'+(i+1)).val(rec.stock_dispatch_det_id);
					$(tr).find('#txt_dispatch_date_'+(i+1)).val(rec.dispatch_date); 
					$(tr).find('#txt_po_'+(i+1)).val(rec.po_number); 
					$(tr).find('#txt_qty_'+(i+1)).val(rec.dispatch_qty); 
					
					$(tr).find('#txt_ava_qty_'+(i+1)).text(rec.avability); 
					var ava = Number(rec.avability);
					var disp = Number(rec.dispatch_qty);
					var remin = ava - disp;
					$(tr).find('#txt_remaing_qty_'+(i+1)).text(remin); 
					
				}
				
				
				
				var option_subcat = "<option value='0'>Select</option>";
		
				$.each(itemlist, function(k,v){
					var selected = ''; 
					if(rec.item_id == v.item_id) selected = 'selected';
					option_subcat += "<option value='"+v.item_id+"' "+selected+">"+v.item_code+"</option>";				  
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
		
		}
		
		//$('select#category_id').val(rsOp.category_id);
		
		
	} 
	 viewByStockDispatchType();
	 var FromEndDate = new Date();
	 $('.datepicker').datepicker({
			  autoclose: true,
			  endDate: FromEndDate
	});
	var modal_title = Number($("#hid_id").val())>0?'Edit StockDispatch':'New StockDispatch'; 
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
	 	closeStockDispatchDiaglog(); 
		if(res.message){ GV_show_list_page_succmsg = res.message; /*customAlert(res.message);*/ }
		else { GV_show_list_page_succmsg = "Stock Dispatch inserted successfully!"; /*customAlert('Stock Dispatch inserted successfully!'); */ alert();  }
		loadStockDispatchMaster();
    } 
}; 
	
	$('#frmStockDispatchMaster').ajaxForm(options); 
	
}

function validateStockDispatch()
{
	alert('test');
}

function CreateUpdateStockDispatchMasterSave()
{
	/*if(jQuery.trim($('#supplier_name').val())=='') { alert('Enter Supplier'); $('#supplier_name').focus(); return false; }  
	
	var supplier_email=jQuery.trim($('#supplier_email').val());  
	if(supplier_email!='') { if(CheckEmailId(supplier_email)==false){ alert('Enter valied email for Supplier Email'); $('#supplier_email').focus(); return false; } } 
	
	*/
	var _itemSelected = 0;
	var qunatityItem = 0;
	var dispatchItem = 0;
	var statusItem = 0;
	var chkval = Number($('input[name=chk_stock_dispatch]:checked').val());
	if(chkval == 1)
	{
		$('#customFields').find('.cmbitem_list').each(function(){
		
			var val = Number($(this).val());
			if(val>0)
			{
				_itemSelected = 1;	
				
				var tr = $(this).closest('tr');
				var qty = $(tr).find('input[name="txt_qty[]"]').val();
				var rec_date = $(tr).find('input[name="txt_dispatch_date[]"]').val();
				if(qty=='')
				{
					qunatityItem = 1;
				}
				if(rec_date=='')
				{
					dispatchItem = 1;
				}
			}
		});
	}
	if(chkval == 2)
	{
		$('#customPoDet').find('.cmbitem_list').each(function(){
		
			var val = Number($(this).val());
			if(val>0)
			{
				_itemSelected = 1;	
				
				var tr = $(this).closest('tr');
				var qty = $(tr).find('input[name="txt_disp_qty[]"]').val();
				var stat = $(tr).find('select[name="cmb_poitem_status[]"]').val();
				if(qty=='')
				{
					qunatityItem = 1;
				}
				if(stat==0)
				{
					statusItem = 1;
				}
			}
		});
	}
	
	if(chkval == 3)
	{
		$('#customProductdet').find('.cmbitem_list').each(function(){
		
			var val = Number($(this).val());
			if(val>0)
			{
				_itemSelected = 1;	
				
				var tr = $(this).closest('tr');
				var qty = $(tr).find('input[name="txt_disp_qty[]"]').val();
				
				if(qty=='')
				{
					qunatityItem = 1;
				}
				
			}
		});
	}
	
	if(_itemSelected == 0)
	{
		alert('Enter Item Details');
		return false;
	}
	
	if(chkval == 1)
	{
		if(qunatityItem == 1)
		{
			alert('Enter Item quantity details');
			return false;
		}
		if(dispatchItem == 1)
		{
			alert('Enter Item dispatch date details');
			return false;
		}
		
	}
	if(chkval == 2)
	{
		if(qunatityItem == 1)
		{
			alert('Enter Item quantity details');
			return false;
		}
		if(statusItem == 1)
		{
			alert('Enter Item status details');
			return false;
		}
	}
	
	if(chkval == 3)
	{
		var cmb_item_group = $('#cmb_item_group').val();
		if(cmb_item_group>0)
		{	
			if(qunatityItem == 1)
			{
				alert('Enter Item quantity details');
				return false;
			}
		}
		else
		{
			alert('Select Item Group');
			return false;
		}
	}
	
	$('#frmStockDispatchMaster').submit();
	return false;
	
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'stock_dispatch'}
	alert(jQuery.trim($('#supplier_name').val()));
	
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmStockDispatchMaster').serializeArray();
	var stock_dispatch_chk_status = ($('#stock_dispatch_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'stock_dispatch_chk_status', value:stock_dispatch_chk_status});
	
	console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeStockDispatchModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeStockDispatchDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closeStockDispatchDeleteDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeStockDispatchModalDialog(response)
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
			
	
	closeStockDispatchDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadStockDispatchMaster();
}	
	
	

function viewDeleteStockDispatchMaster(id)
{
	$('#frmStockDispatchDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'stock_dispatch'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteStockDispatch', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteStockDispatch(StrData) 
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

function deleteStockDispatchMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'stock_dispatch'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmStockDispatchDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeStockDispatchDeleteDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadStockDispatchMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 

function addStockDispatchItemMasEntryDetailRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getStockDispatchItemMasEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	$('.datepicker').datepicker({
			  autoclose: true
	});
	
	/*$('.datepicker').datepicker({
			  autoclose: true
	});*/
	
	 
 
}
function deleteStockDispatchItemMasEntryRow(rw)
{
	var con = confirm('Are you sure want to delete?');
	if(!con) return false;
	
	var tr = $('tr#bill_'+rw);
	var del_val = $(tr).find('#hdn_itmgrp_detid_'+rw).val();
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
	if(addtblcnt==0) addItemGroupMasEntryDetailRow();
}
function getStockDispatchItemMasEntryTableMaxId()
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

function viewByStockDispatchType()
{
	var chk_val = $('input[type=radio][name=chk_stock_dispatch]:checked').val();
	$('.stock_option1,.stock_option2,.stock_option3').hide();
	if(chk_val == 1)
	{
		$('.stock_option1').show();
		
	}
	else if(chk_val == 2)
	{
		$('.stock_option2').show();
	}
	else if(chk_val == 3)
	{
		$('.stock_option3').show();
	}
	
}

function searchByViewPOSODetailsDispatch()
{
	var search_val = $('#po_so_number').val();
	var actParams = {name:'action', value:'getPOSODetails'};  
	var modParams = {name:'module', value:'stock_dispatch'}
	var pageParams=new Array();
	pageParams.push(actParams);
	pageParams.push(modParams); 		
	pageParams.push({name:'po_so_number', value:search_val});
	var passArr={pURL:'process.php',pageParams:pageParams };
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDispatchPOSODetails',displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
}
function loadDispatchPOSODetails(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	console.log(jOPData);

	if(opData.status == 'success')
	{
			if(opData.rsData!=undefined)
			{
				var rsOp=opData.rsData;
				
				var hdn_po_head_id = '';
				var table = '#customPoDet';
				$(table).find('tbody tr:not(#tempRow)').remove();
				var tempRow = $(table).find('#tempRow');
				var cnt=rsOp.length;
				//if(cnt<1) cnt = 1;
				for(i = 0; i < cnt; i++)
				{
					var tr = $(tempRow).html();
					tr = tr.replace(/rw/g, i+1);
					
					$(table).append("<tr id='pstock_"+(i+1)+"'>"+tr+"</tr>");
					
					var tr = $(table).find('tr#pstock_'+(i+1)); 
					
					var rec = rsOp[i];
					console.log(rec);
					if(rec)
					{
						if(rec.po_det_id )
						{
							$(tr).find('#hdn_po_det_id_'+(i+1)).val(rec.po_det_id);
							$(tr).find('#hdn_stock_dispatch_det_id_'+(i+1)).val(rec.stock_dispatch_det_id);
							$(tr).find('#txt_item_'+(i+1)).val(rec.item_desc); 
							
							$(tr).find('#txt_disp_qty_'+(i+1)).val(rec.ordered_qty); 
							$(tr).find('#hdn_item_id_'+(i+1)).val(rec.item_id); 
							if( rec.po_head_id ) hdn_po_head_id = rec.po_head_id;
							$(tr).find('#txt_ava_qty_'+(i+1)).text(rec.avability); 
							var ava = Number(rec.avability);
							var disp = Number(rec.ordered_qty);
							var remin = ava - disp;
							$(tr).find('#txt_remaing_qty_'+(i+1)).text(remin); 
							
						}
						
						
						
						
						 
					}
					
				} 
		 		$('#hdn_po_head_id').val(hdn_po_head_id);
			}
				
	}
	else
	alert(opData.message);
	
	//
	//$('#viewDeleteModal').modal({  show:true, backdrop:false });
}
function searchByViewProductDetails()
{
	var search_val = $('#cmb_item_group').val();
	var txt_grp_qty = $('#txt_grp_qty').val();
	var actParams = {name:'action', value:'getProductDetails'};  
	var modParams = {name:'module', value:'stock_dispatch'}
	var pageParams=new Array();
	pageParams.push(actParams);
	pageParams.push(modParams); 		
	pageParams.push({name:'item_group_id', value:search_val});
	pageParams.push({name:'txt_grp_qty', value:txt_grp_qty});
	var passArr={pURL:'process.php',pageParams:pageParams };
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadProductItemDetails',displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
}

function loadProductItemDetails(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	console.log(jOPData);

	if(opData.status == 'success')
	{
			if(opData.rsData!=undefined)
			{
				var rsOp=opData.rsData;
				
				var hdn_po_head_id = '';
				var table = '#customProductdet';
				$(table).find('tbody tr:not(#tempRow)').remove();
				var tempRow = $(table).find('#tempRow');
				var cnt=rsOp.length;
				//if(cnt<1) cnt = 1;
				for(i = 0; i < cnt; i++)
				{
					var tr = $(tempRow).html();
					tr = tr.replace(/rw/g, i+1);
					
					$(table).append("<tr id='prdstock_"+(i+1)+"'>"+tr+"</tr>");
					
					var tr = $(table).find('tr#prdstock_'+(i+1)); 
					
					var rec = rsOp[i];
					console.log(rec);
					if(rec)
					{
						if(rec.item_id )
						{
							$(tr).find('#txt_prd_item_'+(i+1)).val(rec.item_code); 
							$(tr).find('#txt_disp_qty_'+(i+1)).val(rec.ordered_qty); 
							$(tr).find('#hdn_prd_item_id_'+(i+1)).val(rec.item_id); 
							$(tr).find('#hdn_itm_qty_req_'+(i+1)).val(rec.item_quantity);
							$(tr).find('#txt_prd_disp_qty_'+(i+1)).val(rec.dispatch_req_qty);
							
							
							if( rec.po_head_id ) hdn_po_head_id = rec.po_head_id;
							
							
							
							$(tr).find('#txt_ava_qty_'+(i+1)).text(rec.avability); 
							var ava = Number(rec.avability);
							var disp = isNaN(Number(rec.dispatch_req_qty))?0:Number(rec.dispatch_req_qty);
							//alert(disp);
							var remin = ava - disp;
							$(tr).find('#txt_remaing_qty_'+(i+1)).text(remin); 
							
						}
						
						
						
						
						 
					}
					
				} 
		 		$('#hdn_po_head_id').val(hdn_po_head_id);
			}
				
	}
	else
	alert(opData.message);
	
	//
	//$('#viewDeleteModal').modal({  show:true, backdrop:false });
}
var curItmObj = '';
function getItemQuantityDetails(obj)
{ 	
	curItmObj = obj;
	var item_id = $(obj).val();
	var pageParams = new Array();
	pageParams.push({name:'item_id', value:item_id});
	pageParams.push({name:'module', value:'stock_dispatch'});
	pageParams.push({name:'action', value:'getItemQuantity'});
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadItemQuantityDetails',displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
	
}

function loadItemQuantityDetails(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	console.log(jOPData);
	
	var tr = $(curItmObj).closest('tr');
	$(tr).find('span[name="txt_ava_qty[]"]').text(0);

	if(opData.status == 'success')
	{
		if(opData.availabily!="")
		{
			var tr = $(curItmObj).closest('tr');
			$(tr).find('span[name="txt_ava_qty[]"]').text(opData.availabily);
		}
	}
	
}

function putItemRemainingQty(obj)
{
	var tr = $(obj).closest('tr');
	var availbty = $(tr).find('span[name="txt_ava_qty[]"]').text();
	var disptc = $(obj).val();
	var remingqty = Number(availbty) - Number(disptc);
	$(tr).find('span[name="txt_remaing_qty[]"]').text(remingqty);
}

function setDispatchQuantityItem(obj)
{
	var txt_qty = $(obj).val();
	
	$('#customProductdet').find('.cmbitem_list').each(function(){
		
			var val = Number($(this).val());
			if(val>0)
			{
				var tr = $(this).closest('tr');
				var qty = $(tr).find('input[name="hdn_itm_qty_req[]"]').val();
				var total = Number(txt_qty) * Number(qty);
				$(tr).find('input[name="txt_prd_disp_qty[]"]').val(total).trigger('change');
				
				
			}
		});
	
}

function setItemGrpQty()
{
	$('#txt_grp_qty').val(1)
}