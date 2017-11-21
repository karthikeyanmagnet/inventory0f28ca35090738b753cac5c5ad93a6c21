function loadVendorMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Vendor</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'vendor', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadVendordataTableList'}; 
	
	callCommonLoadFunction(passArr);  
}

function loadVendordataTableList()
{ 
	var a  = "getList";	 
	var pageParams = {action:a, module:'vendor'}; 
	
	$("#vendorMasterTbl").dataTable({
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
					checkPermssion('vendor');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateVendorMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'vendor', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadVendorMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadVendorMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'vendor'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataVendorMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataVendorMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	var cusData=jOPData.customData;  //popup for new creation from other module
	var from=cusData.from; 
	
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
		
		$('#frmVendorMaster').find("#hid_id").val(rsOp.vendor_id);
		$("#supplier_name").val(rsOp.supplier_name);
		$("#supplier_address").val(rsOp.supplier_address);
		$("#supplier_city").val(rsOp.supplier_city);
		$("#supplier_state").val(rsOp.supplier_state);
		$("#supplier_zipcode").val(rsOp.supplier_zipcode);
		$("#supplier_phone").val(rsOp.supplier_phone);
		$("#supplier_email").val(rsOp.supplier_email);
		$("#supplier_website").val(rsOp.supplier_website);
		$("#supplier_tin_cst").val(rsOp.supplier_tin_cst);
		$("#supplier_excise_no").val(rsOp.supplier_excise_no);
		$("#supplier_contact_name").val(rsOp.supplier_contact_name);
		$("#supplier_contact_no").val(rsOp.supplier_contact_no);
		$("#supplier_excise_no").val(rsOp.supplier_excise_no);
		$("#supplier_shipping_terms").val(rsOp.supplier_shipping_terms);
		$("#supplier_payment_terms").val(rsOp.supplier_payment_terms);
		$("#supplier_logo_ext").val(rsOp.supplier_logo_ext); 
		 
		
		rsOp.active_status = Number($("#hid_id").val())>0?rsOp.active_status:1; //set default active
		if(rsOp.active_status == '1')
		$("#active_status").each(function(){ this.checked = true; }); 
		
		
		var categorylist = rsOp.categorylist;
		
		var itemlist = rsOp.itemlist; 
		var itmgrpdet_details = rsOp.itmgrpdet_details; 
		
		var cnt = itmgrpdet_details.length;
		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(itemlist, function(k,v){
			var selected = '';
			//if(v.active_status == '1')
			var dispitemname=v.item_code;
			if(v.item_description!="") var dispitemname=v.item_code+' - '+v.item_description;
			option_prdline_def += "<option value='"+v.item_id+"' "+selected+" >"+dispitemname+"</option>";				  
		});
		
		$('select#cmb_item_rw').empty();
		$('select#cmb_item_rw').append(option_prdline_def);
	
		var option = "<option value='0'>Select</option>";
		
		$.each(categorylist, function(k,v){
			var selected = '';			
			if(rsOp.category_id == v.category_id) selected = 'selected';
			option += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
		});
		
		$('select#category_id').empty();
		$('select#category_id').append(option);
		
		
		
		
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
				if(rec.vendor_id )
				{
					$(tr).find('#hdn_itmgrp_detid_'+(i+1)).val(rec.vendor_det_id);
					$(tr).find('#txt_desc_'+(i+1)).val(rec.item_description); 
					$(tr).find('#txt_amount_'+(i+1)).val(rec.item_amount); 
				}
				
				
				
				var option_subcat = "<option value='0'>Select</option>";
		
				$.each(itemlist, function(k,v){
					var selected = ''; 
					if(rec.item_id == v.item_id) selected = 'selected';
					
					var dispitemname=v.item_code;
					if(v.item_description!="") var dispitemname=v.item_code+' - '+v.item_description;
			
					option_subcat += "<option value='"+v.item_id+"' "+selected+">"+dispitemname+"</option>";				  
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
		
		//$('select#category_id').val(rsOp.category_id);
		
		if(rsOp.supplier_logo_ext !='' && rsOp.logoUrl !='')
		{
				$('.viewLogo').show().click(function(){
					window.open(rsOp.logoUrl);
				});
		}
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Vendor':'New Vendor'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	if(from == 'other')
	{
		$('.SubAddEditForm').find('.modal-title').text('VENDOR CREATION'); 
		$('.SubAddEditForm, .suboverlay').show();
		$('.SubAddEditForm').addClass('mediumBox');
		$('.SubAddEditForm').find('#btnSave').attr('onclick', "createMasterEntry('vendor');");
		$('.SubAddEditForm').find("#active_status").each(function(){ this.checked = true; }); 
	}
	else 
	{
		$('.AddEditForm, .overlay').show();
		$('.AddEditForm').addClass('mediumBox');
	}
	
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
		
	
		if(from == 'other')
		{
			var strdata = {formOpData:res, customData:{name:'module', value:'vendor'}};
			putDataCreatedNewMasterEntry(strdata);
		}
		else
		{
			closeVendorDiaglog(); 
			if(res.message){ GV_show_list_page_succmsg = res.message; /*customAlert(res.message);*/  }
			else { GV_show_list_page_succmsg = "Vendor inserted successfully!"; /*customAlert('Vendor inserted successfully!'); */ }
			loadVendorMaster();
		}
    } 
}; 
	
	$('#frmVendorMaster').ajaxForm(options); 
	
}

function validateVendor()
{
	alert('test');
}

function CreateUpdateVendorMasterSave()
{
	if(jQuery.trim($('#supplier_name').val())=='') { alert('Enter Supplier'); $('#supplier_name').focus(); return false; }  
	
	var supplier_email=jQuery.trim($('#supplier_email').val());  
	if(supplier_email!='') { if(CheckEmailId(supplier_email)==false){ alert('Enter valied email for Supplier Email'); $('#supplier_email').focus(); return false; } } 
	
	
	$('#frmVendorMaster').submit();
	return false;
	
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'vendor'}
	alert(jQuery.trim($('#supplier_name').val()));
	
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmVendorMaster').serializeArray();
	var vendor_chk_status = ($('#vendor_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'vendor_chk_status', value:vendor_chk_status});
	
	console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeVendorModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeVendorDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closeVendorDeleteDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeVendorModalDialog(response)
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
			
	
	closeVendorDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadVendorMaster();
}	
	
	

function viewDeleteVendorMaster(id)
{
	$('#frmVendorDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'vendor'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteVendor', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteVendor(StrData) 
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

function deleteVendorMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'vendor'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmVendorDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeVendorDeleteDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadVendorMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 

function addVendorItemMasEntryDetailRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getVendorItemMasEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	/*$('.datepicker').datepicker({
			  autoclose: true
	});*/
	
	 
 
}
function deleteVendorItemMasEntryRow(rw)
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
function getVendorItemMasEntryTableMaxId()
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
 