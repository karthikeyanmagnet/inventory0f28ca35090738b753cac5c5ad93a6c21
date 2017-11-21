//=================== Item Master 
function loadItemMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Item</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'item', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadItemdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadItemdataTableList()
{
	
	var a  = "getList";	 
//        var s = $("#sort_id").val();
	var pageParams = {action:a, module:'item'}; 
	
	$("#itemMasterTbl").dataTable({
            
            "order" : [ 3 , "asc" ],
				   "processing": true,
				  "serverSide": true,
				   "bAutoWidth": false,
				   
				  "ajax":  {
						"url": "process.php",
						"type": "POST",
						"data":pageParams
					},
				"fnDrawCallback":function()
				{
					checkPermssion('item');
					console.log(this.api().page.info());
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateItemMasterList(idVal)
{
	

	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'item', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadItemMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadItemMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'item'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataItemMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataItemMasterAddEdit(StrData)
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
		
		$('#frmItemMaster').find("#hid_id").val(rsOp.item_id);
		rsOp.item_status = Number($("#hid_id").val())>0?rsOp.item_status:1; //set default active
		if(rsOp.item_status == '1')
		$("#item_status").each(function(){ this.checked = true; });
		if(rsOp.item_id)
		{
			$("#item_code").val(rsOp.item_code);
			$("#item_description").val(rsOp.item_description);
			$("#item_unit_cost").val(rsOp.item_unit_cost);
			$("#item_currency_id").val(rsOp.item_currency_id);
			$("#item_grade").val(rsOp.item_grade);
			$("#item_weight").val(rsOp.item_weight);
			$("#item_measure_id").val(rsOp.item_measure_id);
			$("#item_hts").val(rsOp.item_hts);
			$("#item_type").val(rsOp.item_type);
			$("#item_open_stock").val(rsOp.item_open_stock);
		}
		
		var categorylist = rsOp.categorylist;
	
		var option = "<option value='0'>Select</option>";
		
		$.each(categorylist, function(k,v){
			var selected = '';			
			if(rsOp.category_id == v.category_id) selected = 'selected';
			option += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
		});
		
		$('select#category_id').empty();
		$('select#category_id').append(option);
		//$('select#category_id').val(rsOp.category_id);
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Item':'New Item'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	if(from == 'other')
	{
		$('.SubAddEditForm').find('.modal-title').text('ITEM CREATION'); 
		$('.SubAddEditForm, .suboverlay').show();
		$('.SubAddEditForm').addClass('mediumBox');
		$('.SubAddEditForm').find('#btnSave').attr('onclick', "createMasterEntry('item');");
		$('.SubAddEditForm').find('.add_new_categ').hide();
		
	}
	else 
	{
		$('.AddEditForm, .overlay').show();
		$('.AddEditForm').addClass('mediumBox');
	}
	
}

function CreateUpdateItemMasterSave(from)
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'item'}
	
	if($('#category_id').val()==0) { alert('Select category'); $('#category_id').focus(); return false; } 
	if(jQuery.trim($('#item_code').val())=='') { alert('Enter Item code'); $('#item_code').focus(); return false; }
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmItemMaster').serializeArray();
	var item_chk_status = ($('#item_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'item_chk_status', value:item_chk_status});
	pageParams.push({name:'from', value:from});
	
	console.log(pageParams);
	
	var closeDiagFunc = 'closeItemModalDialog';
	
	if(from == 'other')
	{
		closeDiagFunc = 'putDataCreatedNewMasterEntry';
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:closeDiagFunc, sendCustPassVal:modParams, displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeItemDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeItemModalDialog(response)
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
			
	
	closeItemDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadItemMaster();
}	
	
	

function viewDeleteItemMaster(id)
{
	$('#frmItemDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'item'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteItem', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteItem(StrData) 
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

function deleteItemMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'item'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmItemDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeItemDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadItemMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 

var rapp_cur_selected = null;

function viewCreateMaster(type, curObj)
{
	rapp_cur_selected = curObj;
	var idVal = '';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:type, view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
	custVals["module"]=type;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCreateMasterEntry', sendCustPassVal:custVals, pageLoadContent:'.SubAddEditForm .body'};
	
	callCommonLoadFunction(passArr); 
	
}

function loadCreateMasterEntry(StrData)
{
	console.log(StrData);
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	var module=opData.module; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:module, from:'other'};  
	var putDataFunction = '';
	if(module == 'category')
	{
		putDataFunction = 'putDataCategoryMasterAddEdit';
	}
	else if(module == 'role')
	{
		putDataFunction = 'putDataRoleMasterAddEdit';
	}
	else if(module == 'customer')
	{
		putDataFunction = 'putDataCustomerMasterAddEdit';
	}
	else if(module == 'vendor')
	{
		putDataFunction = 'putDataVendorMasterAddEdit';
	}
	else if(module == 'item')
	{
		putDataFunction = 'putDataItemMasterAddEdit';
	}
		
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:putDataFunction, sendDataOnSuccess:'send', sendCustPassVal:pageParams, displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}

function cancelMasterCreation()
{
	$('.submodalDiag, .suboverlay').hide();
	$('.submodalDiag').removeClass('mediumBox');
}

function createMasterEntry(type)
{
	if(type == 'category')
	{
		CreateUpdateCategoryMasterSave('other');
	}
	else if(type == 'role')
	{
		CreateUpdateRoleMasterSave('other')
	}
	else if(type == 'customer')
	{
		CreateUpdateCustomerMasterSave('other')
	}
	else if(type == 'vendor')
	{
		CreateUpdateVendorMasterSave('other')
	}
	else if(type == 'item')
	{
		CreateUpdateItemMasterSave('other')
	}
}


function putDataCreatedNewMasterEntry(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	var cusData=jOPData.customData;  //popup for new creation from other module
	var opStatus="";
	var rc_exists = opData.rc_exists;
	if(opData.status!=undefined) opStatus=opData.status; 
	if(opStatus=='success') 
	{
		var module = cusData.value;
		
		var name = '';
		var id = '';
		
		if(module == 'category')
		{
			name = opData.data.category_name;
			id = opData.data.category_id;			
		}
		else if(module == 'role')
		{
			name = opData.data.name;
			id = opData.data.id;			
		}
		else if(module == 'customer')
		{
			name = opData.data.name;
			id = opData.data.id;			
		}
		else if(module == 'vendor')
		{
			name = opData.data.name;
			id = opData.data.id;			
		}
		else if(module == 'item')
		{
			name = opData.data.name;
			id = opData.data.id;			
		}
		
		var option = {name:name, id:id};
		setCreatedMasterEntry(option);
		cancelMasterCreation();
	}
	else
	{
		
		if(rc_exists == 'exists')
		{
			alert(opData.message);
		}
		else
		cancelMasterCreation();
	}
	
	
}

function setCreatedMasterEntry(option)
{
	var options = '<option value="'+option.id+'" selected>'+option.name+'</option>';
	var curObj = $(rapp_cur_selected).attr('cmb-view');
	//alert(curObj);
	
	$('select#'+curObj).append(options);
	$('select#'+curObj).val(option.id);
	
	//alert(option.id);
}

function updateMeasurementText()
{
	var mesaure_id = Number($('#item_measure_id').val());
	var txt = 'Weight';
	if(mesaure_id == 3) txt = 'Length';
	$('#measurment_lbl').text(txt);
}