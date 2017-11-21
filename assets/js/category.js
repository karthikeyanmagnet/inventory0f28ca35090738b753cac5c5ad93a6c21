function loadCategoryMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Category</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'category', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCategorydataTableList'};	 
	
	callCommonLoadFunction(passArr);  
}

function loadCategorydataTableList()
{ 
	var a  = "getList";	 
	var pageParams = {action:a, module:'category'}; 
	
	$("#categoryMasterTbl").dataTable({
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
					checkPermssion('category');
				},
				"rowCallback": function( row, data, index ) {
					/*if ( data.grade == "A" ) {
					  $('td:eq(4)', row).html( '<b>A</b>' );
					}*/
					if(data[1].toLowerCase() == 'active')
					{
						$(row).find('td:eq(1)').addClass('green-bg')
					}
					else
					{
						$(row).find('td:eq(1)').addClass('red-bg')
					}
				}
				  
	 }); 
	
	
	highlightRightMenu();
}


function CreateUpdateCategoryMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'category', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadCategoryMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadCategoryMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'category'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataCategoryMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataCategoryMasterAddEdit(StrData)
{
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	var cusData=jOPData.customData;  //popup for new creation from other module
	var from=cusData.from; 
	
	console.log(StrData);
	
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
		
		$('#frmCategoryMaster').find("#hid_id").val(rsOp.category_id);
		$("#category_name").val(rsOp.category_name);
		 
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		if(rsOp.status == '1')
		$("#category_status").each(function(){ this.checked = true; });
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit Category':'New Category'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	//alert(from);
	if(from == 'other')
	{
		$('.SubAddEditForm').find('.modal-title').text('CATEGORY CREATION'); 
		$('.SubAddEditForm, .suboverlay').show();
		$('.SubAddEditForm').addClass('mediumBox');
		$('.SubAddEditForm').find('#btnSave').attr('onclick', "createMasterEntry('category');");
		
	}
	else 
	{
		$('.AddEditForm, .overlay').show();
		$('.AddEditForm').addClass('mediumBox');
	}
	
}

function CreateUpdateCategoryMasterSave(from)
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'category'}
	
	if(jQuery.trim($('#category_name').val())=='') { alert('Enter Category'); $('#category_name').focus(); return false; } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCategoryMaster').serializeArray();
	var category_chk_status = ($('#category_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'category_chk_status', value:category_chk_status});
	pageParams.push({name:'from', value:from});
	
	console.log(pageParams);
	
	var closeDiagFunc = 'closeCategoryModalDialog';
	
	if(from == 'other')
	{
		closeDiagFunc = 'putDataCreatedNewMasterEntry';
	}
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:closeDiagFunc, sendCustPassVal:modParams, displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeCategoryDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeCategoryModalDialog(response)
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
			
	
	closeCategoryDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadCategoryMaster();
}	
	
	

function viewDeleteCategoryMaster(id)
{
	$('#frmCategoryDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'category'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteCategory', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteCategory(StrData) 
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

function deleteCategoryMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'category'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmCategoryDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeCategoryDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadCategoryMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 