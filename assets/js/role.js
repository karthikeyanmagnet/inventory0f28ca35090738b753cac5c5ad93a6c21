//=================== Role Master 
function loadRoleMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Role</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'role', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadRoledataTableList'}; 
	
	callCommonLoadFunction(passArr);  
	
}

function loadRoledataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'role'}; 
	
	$("#roleMasterTbl").dataTable({
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
					checkPermssion('role');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

 

function CreateUpdateRoleMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'role', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadRoleMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadRoleMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'role'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataRoleMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataRoleMasterAddEdit(StrData)
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
		
		$('#frmRoleMaster').find("#hid_id").val(rsOp.user_role_id);
		$("#role_name").val(rsOp.user_role_name);
		$("#role_display_order").val(rsOp.role_display_order);
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		if(rsOp.status == '1')
		$("#role_status").each(function(){ this.checked = true; });
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Role':'New Role'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	
	if(from == 'other')
	{
		$('.SubAddEditForm, .suboverlay').show();
		$('.SubAddEditForm').addClass('mediumBox');
	}
	else 
	{
		$('.AddEditForm, .overlay').show();
		$('.AddEditForm').addClass('mediumBox');
	}
}

function CreateUpdateRoleMasterSave(from)
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'role'}
	
	if(jQuery.trim($('#role_name').val())=='') { alert('Enter Role'); $('#role_name').focus(); return false; }  
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmRoleMaster').serializeArray();
	var role_chk_status = ($('#role_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'role_chk_status', value:role_chk_status});
	pageParams.push({name:'from', value:from});
	
	console.log(pageParams);
	
	var closeDiagFunc = 'closeRoleModalDialog';
	
	if(from == 'other')
	{
		closeDiagFunc = 'putDataCreatedNewMasterEntry';
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:closeDiagFunc, sendCustPassVal:modParams, displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeRoleDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeRoleModalDialog(response)
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
			
	
	closeRoleDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadRoleMaster();
}	
	
	

function viewDeleteRoleMaster(id)
{
	$('#frmRoleDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'role'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteRole', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteRole(StrData) 
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

function deleteRoleMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'role'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmRoleDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeRoleDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadRoleMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 