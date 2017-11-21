function loadCustomerMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Customer</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'customer', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerdataTableList'};	 
	
	callCommonLoadFunction(passArr); 
	
	
}

function loadCustomerdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'customer'}; 
	
	$("#customerMasterTbl").dataTable({
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
					checkPermssion('customer');
				}
				  
	 }); 
	
	
	highlightRightMenu();
} 
function CreateUpdateCustomerMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'customer', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadCustomerMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'customer'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataCustomerMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataCustomerMasterAddEdit(StrData)
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
		
		$('#frmCustomerMaster').find("#hid_id").val(rsOp.customer_id);
		
		$("#vendor_code").val(rsOp.vendor_code);
		$("#company_name").val(rsOp.company_name);
		$("#primary_contact").val(rsOp.primary_contact);
		$("#phone_no").val(rsOp.phone_no);
		$("#mobile_no").val(rsOp.mobile_no);
		$("#website_url").val(rsOp.website_url);
		
		$("#attention_mail_addr").val(rsOp.attention_mail_addr);
		$("#warehouse_mail_addr").val(rsOp.warehouse_mail_addr);
		$("#address_mail_addr").val(rsOp.address_mail_addr);
		$("#city_mail_addr").val(rsOp.city_mail_addr);
		$("#state_mail_addr").val(rsOp.state_mail_addr);
		$("#zipcode_mail_addr").val(rsOp.zipcode_mail_addr);
		$("#country_mail_addr").val(rsOp.country_mail_addr);
		$("#company_phone_mail_addr").val(rsOp.company_phone_mail_addr);
		$("#company_email_mail_addr").val(rsOp.company_email_mail_addr);
		
		$("#attention_bill_addr").val(rsOp.attention_bill_addr);
		$("#warehouse_bill_addr").val(rsOp.warehouse_bill_addr);
		$("#address_bill_addr").val(rsOp.address_bill_addr);
		$("#city_bill_addr").val(rsOp.city_bill_addr);
		$("#state_bill_addr").val(rsOp.state_bill_addr);
		$("#zipcode_bill_addr").val(rsOp.zipcode_bill_addr);
		$("#country_bill_addr").val(rsOp.country_bill_addr);
		$("#company_phone_bill_addr").val(rsOp.company_phone_bill_addr);
		$("#company_email_bill_addr").val(rsOp.company_email_bill_addr);
		$("#company_logo_ext").val(rsOp.company_logo_ext);
		
		
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		if(rsOp.status == '1')
		$("#customer_status").each(function(){ this.checked = true; }); 
		var details = rsOp.details; 
		var table = 'table.CustomerinputGridTable';
		var tempRow = $(table).find('#tempRow');
		
		var cnt = details.length;
		
		if(cnt<1) cnt = 1;
		for(i = 0; i < cnt; i++)
		{
			var tr = $(tempRow).html();
			tr = tr.replace(/rw/g, i+1);
			
			$(table).append("<tr id='bill_"+(i+1)+"'>"+tr+"</tr>");
			
			var tr = $(table).find('tr#bill_'+(i+1)); 
			
			var rec = details[i];
			
			if(rec)
			{
				if(rec.customer_delivery_id )
				{
					$(tr).find('#hdn_custmer_delivery_detid_'+(i+1)).val(rec.customer_delivery_id);
					$(tr).find('#attention_mail_addr_'+(i+1)).val(rec.attention_mail_addr); 
					$(tr).find('#warehouse_mail_addr_'+(i+1)).val(rec.warehouse_mail_addr); 
					$(tr).find('#address_mail_addr_'+(i+1)).val(rec.address_mail_addr); 
					$(tr).find('#city_mail_addr_'+(i+1)).val(rec.city_mail_addr); 
					$(tr).find('#state_mail_addr_'+(i+1)).val(rec.state_mail_addr); 
					$(tr).find('#zipcode_mail_addr_'+(i+1)).val(rec.zipcode_mail_addr); 
					$(tr).find('#country_mail_addr_'+(i+1)).val(rec.country_mail_addr); 
					$(tr).find('#company_phone_mail_addr_'+(i+1)).val(rec.company_phone_mail_addr); 
					$(tr).find('#company_email_mail_addr_'+(i+1)).val(rec.company_email_mail_addr); 
				}
			}
		}
		 
		if(rsOp.company_logo_ext !='' && rsOp.logoUrl !='') 
		{
				$('.viewLogo').show().click(function(){
					window.open(rsOp.logoUrl);
				});
		}
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Customer':'New Customer'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	if(from == 'other')
	{
		$('.SubAddEditForm').find('.modal-title').text('CUSTOMER CREATION'); 
		$('.SubAddEditForm, .suboverlay').show();
		$('.SubAddEditForm').addClass('mediumBox');
		$('.SubAddEditForm').find('#btnSave').attr('onclick', "createMasterEntry('customer');");
		
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
	beforeSubmit: function(formData, formObject, formOptions) { 
	formData.push({name:'from', value:from});
	console.log(formData);
	console.log(formObject);
	console.log(formOptions);
	
	},
    success:    function(res) { 
	
		var closeDiagFunc = 'closeCustomerModalDialog';
	
		if(from == 'other')
		{
			var strdata = {formOpData:res, customData:{name:'module', value:'customer'}};
			putDataCreatedNewMasterEntry(strdata);
		}
		else
		{
		 
			closeCustomerDiaglog();
			if(res.message){ GV_show_list_page_succmsg = res.message; /*customAlert(res.message);*/ }
			else { GV_show_list_page_succmsg = "Customer inserted successfully!"; /*customAlert('Customer inserted successfully!'); */ }
			loadCustomerMaster();
		}
    } 
}; 
	
	$('#frmCustomerMaster').ajaxForm(options); 
	
	
}

function CreateUpdateCustomerMasterSave(from)
{
	if(jQuery.trim($('#vendor_code').val())=='') { alert('Enter Customer'); $('#vendor_code').focus(); return false; }  
	
	var company_email_mail_addr=jQuery.trim($('#company_email_mail_addr').val());  
	if(company_email_mail_addr!='') { if(CheckEmailId(company_email_mail_addr)==false){ alert('Enter valied email for Mailing Address Company Email'); $('#company_email_mail_addr').focus(); return false; } } 
	
	var company_email_bill_addr=jQuery.trim($('#company_email_bill_addr').val());  
	if(company_email_bill_addr!='') { if(CheckEmailId(company_email_bill_addr)==false){ alert('Enter valied email for Billing Address Company Email'); $('#company_email_bill_addr').focus(); return false; } } 
	
	
	$('#frmCustomerMaster').submit();
	return false;
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'customer'}
	
	
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCustomerMaster').serializeArray();
	var customer_chk_status = ($('#customer_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'customer_chk_status', value:customer_chk_status});
	pageParams.push({name:'from', value:from});
	
	console.log(pageParams);
	var closeDiagFunc = 'closeCustomerModalDialog';
	
	if(from == 'other')
	{
		closeDiagFunc = 'putDataCreatedNewMasterEntry';
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:closeDiagFunc, sendCustPassVal:modParams, displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeCustomerDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closeCustomerDeleteDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeCustomerModalDialog(response)
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
			
	
	closeCustomerDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadCustomerMaster();
}	
	
	

function viewDeleteCustomerMaster(id)
{
	$('#frmCustomerDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'customer'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteCustomer', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteCustomer(StrData) 
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 

	if(opData.status == 'success')
	{
		$('.DeleteForm, .overlay').show();
		$('.DeleteForm').addClass('mediumBox');	
		$('.DeleteForm').find('span#customer_del_message').text(opData.message);
	}
	else
	alert(opData.message);
	
	//
	//$('#viewDeleteModal').modal({  show:true, backdrop:false });
}

function deleteCustomerMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'customer'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCustomerDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeCustomerDeleteDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadCustomerMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 

function addCustomerDeliveryRow()
{
	var table = 'table.CustomerinputGridTable';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getCustomerDeliveryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
}

function getCustomerDeliveryTableMaxId()
{
	var table = 'table.CustomerinputGridTable';
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
function deleteCustomerDeliveryRow(rw)
{
	var con = confirm('Are you sure want to delete?');
	if(!con) return false;
	
	var tr = $('tr#bill_'+rw);
	var del_val = $(tr).find('#hdn_custmer_delivery_detid_'+rw).val();
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
	var table = 'table.CustomerinputGridTable';
	$(table).find('tbody tr:not(#tempRow)').each(function(){
			addtblcnt++;
	});
	if(addtblcnt==0) addCustomerDeliveryRow();
}