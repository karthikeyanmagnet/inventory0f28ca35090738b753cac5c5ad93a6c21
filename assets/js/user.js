function loadUserMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">User</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'user', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserdataTableList'};	 
	
	callCommonLoadFunction(passArr); 
	
	
}

function loadUserdataTableList()
{
	
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'user'}; 
	
	$("#userMasterTbl").dataTable({
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
					checkPermssion('user');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}
 

function CreateUpdateUserMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'user', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadUserMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'user'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataUserMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataUserMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.user_id);
		$("#employee_code").val(rsOp.employee_code);
		$("#employee_name").val(rsOp.employee_name);
		$("#user_address").val(rsOp.user_address);
		$("#user_city").val(rsOp.user_city);
		$("#user_state").val(rsOp.user_state);
		$("#user_postalcode").val(rsOp.user_postalcode);
		$("#user_phone").val(rsOp.user_phone);
		$("#user_email").val(rsOp.user_email); 
		$("#user_name").val(rsOp.user_name); 
  		
		var userroleslist = rsOp.userroleslist;   
		var option_cat = "<option value='0'>Select</option>"; 
		$.each(userroleslist, function(k,v){
			var selected = '';
			if(v.active_status == '1' || rsOp.user_role_id == v.user_role_id)
			if(rsOp.user_role_id == v.user_role_id) selected = 'selected';
			option_cat += "<option value='"+v.user_role_id+"' "+selected+">"+v.user_role_name+"</option>";				  
		}); 
		
		$('select#user_role_id').empty();
		$('select#user_role_id').append(option_cat);

		 
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		if(rsOp.status == '1')
		$("#user_status").each(function(){ this.checked = true; });
		
		if(Number(rsOp.user_id)>0)
		{   
			$("#user_password").val('****');  
			
			$('.user_password_div').hide(); 
			$('.divClsActiveStatus').show();
			 
		} 
		else
		{
			$('.user_password_div').show();
			$('.divClsActiveStatus').hide(); 
		}
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit User':'New User'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	$('.AddEditForm, .overlay').show();
	$('.AddEditForm').addClass('mediumBox');
	
}

function CreateUpdateUserMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'user'} 
	
	if(jQuery.trim($('#employee_code').val())=='') { alert('Enter Employee code'); $('#employee_code').focus(); return false; }
	if(jQuery.trim($('#employee_name').val())=='') { alert('Enter Employee name'); $('#employee_name').focus(); return false; }
	if(jQuery.trim($('#user_name').val())=='') { alert('Enter Username'); $('#user_name').focus(); return false; }
	if(jQuery.trim($('#user_password').val())=='') { alert('Enter Password'); $('#user_password').focus(); return false; }
	
	var user_email=jQuery.trim($('#user_email').val());  
	if(user_email!='') { if(CheckEmailId(user_email)==false){ alert('Enter valied email'); $('#user_email').focus(); return false; } } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserMaster').serializeArray();
	var user_chk_status = ($('#user_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'user_chk_status', value:user_chk_status});
	
	console.log(pageParams);
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeUserModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeUserDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeUserModalDialog(response)
{
	var data = response.formOpData;
	var opStatus="";
	if(data.status!=undefined) opStatus=data.status; 
	 
	if(opStatus=='success') 
	{  
		//customAlert(data.message);
		GV_show_list_page_succmsg=data.message;
		if(data.userprofile_name!='')
		{
			$('.user_profil_name').text(data.userprofile_name);
		}
		
		if(data.userprofile_email!='')
		{
			$('.userprofile_email').text(data.userprofile_email);
		}
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
			
	
	closeUserDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadUserMaster();
}	
	
	

function viewDeleteUserMaster(id)
{
	$('#frmUserDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'user'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteUser', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteUser(StrData) 
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

function deleteUserMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'user'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmUserDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeUserDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadUserMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
function viewUserProfile(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:0, action:a, module:'user', view:'view_profile'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadUserProfileAddEdit', sendCustPassVal:custVals, pageLoadContent:'#UsrProfileForm .body'};
	
	callCommonLoadFunction(passArr); 
}

function loadUserProfileAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getUserProfile";	 
	var pageParams = {id:PidVal, action:a,  module:'user'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataUserProfileAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataUserProfileAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.user_id);
		$("#employee_code").val(rsOp.employee_code);
		$("#employee_name").val(rsOp.employee_name);
		$("#user_address").val(rsOp.user_address);
		$("#user_city").val(rsOp.user_city);
		$("#user_state").val(rsOp.user_state);
		$("#user_postalcode").val(rsOp.user_postalcode);
		$("#user_phone").val(rsOp.user_phone);
		$("#user_email").val(rsOp.user_email); 
		//alert(rsOp.user_name);
		$("#user_name").val(rsOp.user_name); 
		$("#user_role").val(rsOp.user_role_name); 
		 
		/*var userroleslist = rsOp.userroleslist;   
		var option_cat = "<option value='0'>Select</option>"; 
		$.each(userroleslist, function(k,v){
			var selected = '';
			if(v.active_status == '1' || rsOp.user_role_id == v.user_role_id)
			if(rsOp.user_role_id == v.user_role_id) selected = 'selected';
			option_cat += "<option value='"+v.user_role_id+"' "+selected+">"+v.user_role_name+"</option>";				  
		}); 
		
		$('select#user_role_id').empty();
		$('select#user_role_id').append(option_cat);*/

		 
		rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active
		if(rsOp.status == '1')
		$("#user_status").each(function(){ this.checked = true; });
		
		if(rsOp.userlogoUrl!='')
		{
			$('img.user_profile_logo').attr('src', rsOp.userlogoUrl);
		}
		
		if(rsOp.companylogoUrl !='')
		{
				$('.ProfileForm .viewLogo').show().click(function(){
					window.open(rsOp.companylogoUrl);
				});
		}
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit User':'New User'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	$('.ProfileForm, .overlay').show();
	$('.ProfileForm').addClass('mediumBox');
	
	var options = { 
    target:     '#message_prof', 
    url:        'process.php', 
	method: 'POST',
	beforeSubmit: function(arr, $form, options) { 
	console.log(arr);
	console.log($form);
	console.log(options);
	
	},
    success:    function(res) { 
	
		var resp = $('#message_prof').text();
		res = JSON.parse(resp);
	
		if(res.logoURL!='')
		{
			$('img.user_profile_logo').attr('src', res.logoURL);
		}
		
		if(res.userprofile_name!='')
		{
			$('.user_profil_name').text(res.userprofile_name);
		}
		
		if(res.userprofile_email!='')
		{
			$('.userprofile_email').text(res.userprofile_email);
		}
		//alert('test');
	 	//closeUserDiaglog();
		closeFunctProfileDialog()
        alert('Profile updated successfully!'); 
    } 
}; 
	
	$('#frmUserProfileMaster').ajaxForm(options); 
	
}

function updateUserProfileMasterSave()
{ 
	var user_email=jQuery.trim($('#user_email').val()); 
	
	if(jQuery.trim($('#employee_name').val())=='') {  alert('Enter Employee name'); $('#employee_name').focus(); return false; }
	if(user_email!='') { if(CheckEmailId(user_email)==false){ alert('Enter valied email'); $('#user_email').focus(); return false; } } 
	
	$('#frmUserProfileMaster').submit();
	//closeUserDiaglog();
	
}
function closeFunctProfileDialog()
{
	$('.ProfileForm, .overlay').hide();
	$('.ProfileForm').removeClass('mediumBox');  
}