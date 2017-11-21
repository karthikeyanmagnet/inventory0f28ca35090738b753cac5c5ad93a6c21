function loadShipmentOrderLayout()
{
	
	var a  = "view";	 
	var pageParams = {action:a, module:'shipment', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadSalesShipmentdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
	
}

function loadSalesShipmentdataTableList()
{ 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'shipment'}; 
	
	$("#SalesShipmentMasterTbl").dataTable({
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


function CreateUpdateShipmentList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {shipment_id:idVal, action:a, module:'shipment', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadShipmentAddEdit', sendCustPassVal:custVals, pageLoadContent:'#rightMenuDiv'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadShipmentAddEdit(StrData)
{
	 $("#CreateInvoiceShipmentView").show();
         $("#CreateUpdateShipmentSave").hide();
         
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "po_get_details";	 
	var pageParams = {id:PidVal, action:a,  module:'shipment'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataShipmentAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
        $("#customer_id").parent().parent().hide();
}


function CreateUpdateShipmentList1(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {customer_id:idVal, action:a, module:'shipment', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadShipmentAddEdit1', sendCustPassVal:custVals, pageLoadContent:'#rightMenuDiv'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadShipmentAddEdit1(StrData)
{
	 $("#CreateInvoiceShipmentView").show();
         $("#CreateUpdateShipmentSave").hide();
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "po_get_details";	 
	var pageParams = {id:PidVal, action:a,  module:'shipment'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataShipmentAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
       
}
var GV_po_entry_id = '';
function putDataShipmentAddEdit(StrData)
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
		console.log(rsOp);
		
		$('#frmPOEntryMaster').find("#hid_id").val(rsOp.shipment_order_id);
                
		
		GV_po_entry_id = rsOp.po_head_id; 
		
		var customerlist = rsOp.customerlist;
		
		var option_customer_def = "<option value='0'>Select</option>"; 
		$.each(customerlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_customer_def += "<option value='"+v.customer_id+"' "+selected+">"+v.company_name+"</option>";				  
		}); 

		
		
		$('select#customer_id').empty();
		$('select#customer_id').append(option_customer_def);
		//$('select#customer_id').attr('disabled',true);
		
		var table = '#customFieldsPOEntry';
		var tempRow = $(table).find('#tempRow');
		var po_details = rsOp.po_details
		var cnt = po_details.length;
		$('#customFieldsPOEntry th:eq(0)').remove();
		//alert(cnt);
		
		if(cnt<1) cnt = 1;
		for(i = 0; i < cnt; i++)
		{
			
			
			var podet = po_details[i];
			console.log(i);
			console.log(podet);
			
			var str = '<tr id="bill_'+i+'">';
			 // str += '<td width="10%">&nbsp;</td>';
			  str += '<td width="15%">'+podet.po_number+'</td>';
			  str += '<td width="15%">'+podet.ship_date+'</td>';
			  str += '<td width="15%">'+podet.order_total+'</td>';
			  str += '<td width="30%">'+podet.po_number+'</td>'; //dupt ware house
			  str += '<td width="15%">&nbsp;</td>';
			str += '</tr>';
			str += '<tr>';
			  str += '<td colspan="5"><table width="100%" cellpadding="0" cellspacing="0">';
				str += '  <thead>';
				str += '	<tr>';
				str += '	  <th>&nbsp;</th>';
				str += '	  <th>Item</th>';
				str += '	  <th>Description</th>';
				str += '	  <th>Qty</th>';
				str += '	  <th>Unit Cost</th>';
				str += '	  <th>Total Amount</th>';
				str += '	</tr>';
				str += '  </thead>';
				str += '  <tbody>';
				var itemdet = podet.itmgrpdet_details;
				$.each(itemdet, function(k,rec){
			
				if(rec)
				{
					if(rec.po_det_id )
					{
						str += '	<tr>';
						/*str += '	  <td width="10%"><div class="demo-radio-button" style="width:30px;">';
						str += '		  <input name="item_det_id[]" id="chk_item_'+(rec.po_det_id)+'"  type="checkbox" class="with-gap" value="'+rec.po_det_id+'">';
						str += '		  <label for="chk_item_'+(rec.po_det_id)+'" >&nbsp;</label>';
						str += '		</div></td>';*/
						str += '	  <td width="15%">'+rec.item_code+'</td>';
						str += '	  <td width="15%">'+rec.item_desc+'</td>';
						str += '	  <td width="15%">'+rec.ordered_qty+'</td>';
						str += '	  <td width="30%">'+rec.unit_cost+'</td>';
						str += '	  <td width="15%">'+rec.line_total+'</td>';
						str += '	</tr>';
					}
				}
				
				});
				str += '  </tbody>';
				str += '</table></td>';
			str += '</tr>';
			
			console.log(str);

			$(table).append(str);
			
		}
			
			
			
		 
			if(rsOp.customer_id)
			{  
				
				$("#customer_id").val(rsOp.customer_id);
				
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
function CreateUpdateShipmentSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'shipment'}
	
	if(jQuery.trim($('#customer_id').val())==0) { alert('Select Customer Name'); $('#customer_id').focus(); return false; } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmPOEntryMaster').serializeArray(); 
	pageParams.push(actParams);
	pageParams.push(modParams);  
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeShipmentModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeShipmentModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		
                CreateInvoiceShipmentView(data.shipment_order_id);
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
			
	
	//loadShipmentOrderLayout();
}	

function loadCustomerShipment()
{
	var idVal = $('#customer_id').val();
	var a  = "view";	 
	var pageParams = {customer_id:idVal, action:a, module:'shipment', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCustomerShipmentAddEdit', sendCustPassVal:custVals, pageLoadContent:'#rightMenuDiv'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadCustomerShipmentAddEdit(StrData)
{
    
     $("#CreateInvoiceShipmentView").hide();
         $("#CreateUpdateShipmentSave").show();
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "po_get_details";	 
	var pageParams = {customer_id:PidVal, action:a,  module:'shipment'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataViewShipmentAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function putDataViewShipmentAddEdit(StrData)
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
		console.log(rsOp);
		
		$('#frmPOEntryMaster').find("#hid_id").val(rsOp.po_head_id);
		
		GV_po_entry_id = rsOp.po_head_id; 
		
		var customerlist = rsOp.customerlist;
		
		var option_customer_def = "<option value='0'>Select</option>"; 
		$.each(customerlist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_customer_def += "<option value='"+v.customer_id+"' "+selected+">"+v.company_name+"</option>";				  
		}); 

		
		
		$('select#customer_id').empty();
		$('select#customer_id').append(option_customer_def);
		//$('select#customer_id').attr('disabled', true);
		
		var table = '#customFieldsPOEntry';
		var tempRow = $(table).find('#tempRow');
		var po_details = rsOp.po_details
		var cnt = po_details.length;
		
		
		
		if(cnt<1) cnt = 1;
		for(i = 0; i < cnt; i++)
		{
			
			
			var podet = po_details[i];
			console.log(i);
			console.log(podet);
			
			var str = '<tr id="bill_'+i+'">';
			  str += '<td width="10%">&nbsp;</td>';
			  str += '<td width="15%">'+podet.po_number+'</td>';
			  str += '<td width="15%">'+podet.ship_date+'</td>';
			  str += '<td width="15%">'+podet.order_total+'</td>';
			  str += '<td width="30%">'+podet.po_number+'</td>'; //dupt ware house
			  str += '<td width="15%">&nbsp;</td>';
			str += '</tr>';
			str += '<tr>';
			  str += '<td colspan="6"><table width="100%" cellpadding="0" cellspacing="0">';
				str += '  <thead>';
				str += '	<tr>';
				str += '	  <th>&nbsp;</th>';
				str += '	  <th>Item</th>';
				str += '	  <th>Description</th>';
				str += '	  <th>Qty</th>';
				str += '	  <th>Unit Cost</th>';
				str += '	  <th>Total Amount</th>';
				str += '	</tr>';
				str += '  </thead>';
				str += '  <tbody>';
				var itemdet = podet.itmgrpdet_details;
				$.each(itemdet, function(k,rec){
			
				if(rec)
				{
					if(rec.po_det_id )
					{
						str += '	<tr>';
						str += '	  <td width="10%"><div class="demo-radio-button" style="width:30px;">';
						str += '		  <input name="item_det_id[]" id="chk_item_'+(rec.po_det_id)+'"  type="checkbox" class="with-gap" value="'+rec.po_det_id+'">';
						str += '		  <label for="chk_item_'+(rec.po_det_id)+'" >&nbsp;</label>';
						str += '		</div></td>';
						str += '	  <td width="15%">'+rec.item_code+'</td>';
						str += '	  <td width="15%">'+rec.item_desc+'</td>';
						str += '	  <td width="15%">'+rec.ordered_qty+'</td>';
						str += '	  <td width="30%">'+rec.unit_cost+'</td>';
						str += '	  <td width="15%">'+rec.line_total+'</td>';
						str += '	</tr>';
					}
				}
				
				});
				str += '  </tbody>';
				str += '</table></td>';
			str += '</tr>';
			
			console.log(str);

			$(table).append(str);
			
		}
			
			
			
		 
			if(rsOp.customer_id)
			{  
				
				$("#customer_id").val(rsOp.customer_id);
				
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

function CreateInvoiceShipmentView(shipment_order_id)
{
	
	var a  = "view";	 
	var pageParams = {shipment_order_id:shipment_order_id, action:a, module:'shipment', view:'shipment_invoice'};  
	var custVals = {};  
	custVals["id"]=shipment_order_id;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadShipmentInvoiceAddEdit', sendCustPassVal:custVals, pageLoadContent:'#rightMenuDiv'};
	
	callCommonLoadFunction(passArr); 
}

function openTabPurchaseSUpPAssign(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        //tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
	
	if(cityName=='divTabPoEntry') viewPOEntry();
	else if(cityName=='divTabSuppAssig') viewPOAssignEntry();
}

function loadShipmentInvoiceAddEdit()
{
	document.getElementById("defaultOpen").click();
}

function CreateUpdateShipmentInvoicePackSave()
{
	var a  = "invoice_pack_save";	 
	var actParams = {name:'action', value:'update_shipment_order'};  
	var modParams = {name:'module', value:'shipment'}
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#update_shipment_order').serializeArray(); 
	pageParams.push(actParams);
	pageParams.push(modParams);  
	console.log(pageParams);
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'createUpdateShipmentInvoicePackSaveModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 	
}

function createUpdateShipmentInvoicePackSaveModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		customAlert('Updated Successfully '); 
               
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
			
	
	//loadShipmentOrderLayout();
}

function CreateInvoicePDF()
{
	$("#export_invoice_pdf").submit();	
}