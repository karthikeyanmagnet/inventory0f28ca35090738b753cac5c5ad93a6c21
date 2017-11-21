function loadItemGroupMaster()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Item Group</li>';
	topHeadTitle(titleCont);
	
	
	var a  = "view";	 
	var pageParams = {action:a, module:'item_group', view:'list'};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadItemGroupdataTableList'};	 
	
	callCommonLoadFunction(passArr);  
	
}

function loadItemGroupdataTableList()
{ 
	
	var a  = "getList";	 
	var pageParams = {action:a, module:'item_group'}; 
	
	$("#itemGroupMasterTbl").dataTable({
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
					checkPermssion('item_group');
				}
				  
	 }); 
	
	
	highlightRightMenu();
}

function CreateUpdateItemGroupMasterList(idVal)
{
	if(idVal==undefined) idVal='';
	var a  = "view";	 
	var pageParams = {id:idVal, action:a, module:'item_group', view:'view'};  
	var custVals = {};  
	custVals["id"]=idVal;
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'loadItemGroupMasterAddEdit', sendCustPassVal:custVals, pageLoadContent:'#viewPageModal .body'};
	
	callCommonLoadFunction(passArr); 
		
}
function loadItemGroupMasterAddEdit(StrData)
{
	 
	var jOPData=StrData; 
	var opData=jOPData.customData; 
	
	var PidVal=opData.id; 
	
	var a  = "getSingeView";	 
	var pageParams = {id:PidVal, action:a,  module:'item_group'};  
				
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'putDataItemGroupMasterAddEdit', sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
}
function putDataItemGroupMasterAddEdit(StrData)
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
		
		$("#hid_id").val(rsOp.item_group_id);
		
		var categorylist = rsOp.categorylist;
		var itemlist = rsOp.itemlist; 
		
		var itmgrpdet_details = rsOp.itmgrpdet_details; 
		
		var cnt = itmgrpdet_details.length;
		
		
		var option_parprd_def = "<option value='0'>Select</option>"; 
		$.each(categorylist, function(k,v){ 
			var selected = '';
			if(v.active_status == '1')
			option_parprd_def += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
		}); 

		
		var option_prdline_def = "<option value='0'>Select</option>"; 
		$.each(itemlist, function(k,v){
			var selected = '';
			//if(v.active_status == '1') 
			var dispitemname=v.item_code;
			if(v.item_description!="") var dispitemname=v.item_code+' - '+v.item_description;
			option_prdline_def += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'  >"+dispitemname+"</option>";				  
		});
		
		$('select#cmb_category_rw').empty();
		$('select#cmb_category_rw').append(option_parprd_def);
		
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
				if(rec.item_group_id )
				{
					$(tr).find('#hdn_itmgrp_detid_'+(i+1)).val(rec.item_group_det_id);
					$(tr).find('#txt_quantity_'+(i+1)).val(rec.item_quantity); 
				}
				
				var option_cat = "<option value='0'>Select</option>";
		
				$.each(categorylist, function(k,v){
					var selected = '';
					if(v.active_status == '1' || rec.category_id == v.category_id)
					if(rec.category_id == v.category_id) selected = 'selected';
					option_cat += "<option value='"+v.category_id+"' "+selected+">"+v.category_name+"</option>";				  
				}); 
		
				
				var option_subcat = "<option value='0'>Select</option>";
		
				$.each(itemlist, function(k,v){
					var selected = ''; 
					if(rec.item_id == v.item_id) selected = 'selected';
					var dispitemname=v.item_code;
					if(v.item_description!="") dispitemname=v.item_code+' - '+v.item_description;
					
					option_subcat += "<option value='"+v.item_id+"' "+selected+" parprdid='"+v.category_id+"'>"+dispitemname+"</option>";				  
				}); 
		
				
				$('select#cmb_category_'+(i+1)).empty();
				$('select#cmb_category_'+(i+1)).append(option_cat);
				
				$('select#cmb_item_'+(i+1)).empty();
				$('select#cmb_item_'+(i+1)).append(option_subcat);
				 
			}
			else
			{
			 
				$('select#cmb_category_'+(i+1)).empty();
				$('select#cmb_category_'+(i+1)).append(option_parprd_def);
				
				$('select#cmb_item_'+(i+1)).empty();
				$('select#cmb_item_'+(i+1)).append(option_prdline_def);  
			}
		} 
		
		
		
		$("#item_group_name").val(rsOp.item_group_name);
                $("#item_group_desc").val(rsOp.item_group_desc);
                $("#item_group_weight").val(rsOp.item_group_weight);
                $("#item_group_htc").val(rsOp.item_group_htc);
                $("#item_group_unit_cost").val(rsOp.item_group_unit_cost);
		 
		/*rsOp.status = Number($("#hid_id").val())>0?rsOp.status:1; //set default active   // denot delete 
		if(rsOp.status == '1')
		$("#category_status").each(function(){ this.checked = true; });*/
		
		
		
		
		
	} 
	var modal_title = Number($("#hid_id").val())>0?'Edit ItemGroup':'New ItemGroup'; 
	$('#viewPageModal').find('.modal-title').text(modal_title); 
	//$('#viewPageModal').modal({  show:true, backdrop:false });
	$('.AddEditForm, .overlay').show();
	$('.AddEditForm').addClass('mediumBox');
	
}
function viewItemGroupMasOnChangeCategory(id)
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
function addItemGroupMasEntryDetailRow()
{
	var table = '#customFields';
	var tempRow = $(table).find('#tempRow');
	
	var rwid = getItemGroupMasEntryTableMaxId()+1;
	
	var tr = $(tempRow).html();
	tr = tr.replace(/rw/g, rwid);
	
	$(table).append("<tr id='bill_"+rwid+"'>"+tr+"</tr>");
	
	/*$('.datepicker').datepicker({
			  autoclose: true
	});*/
	
	 
 
}
function deleteItemGroupMasEntryRow(rw)
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
function getItemGroupMasEntryTableMaxId()
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
function CreateUpdateItemGroupMasterSave()
{
	var a  = "save";	 
	var actParams = {name:'action', value:'save'};  
	var modParams = {name:'module', value:'item_group'}
	
	if(jQuery.trim($('#item_group_name').val())=='') { alert('Enter Item Group'); $('#item_group_name').focus(); return false; } 
	
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmItemGroupMaster').serializeArray();
	var item_group_chk_status = ($('#item_group_status').is(':checked')?1:0);
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'item_group_chk_status', value:item_group_chk_status});
	
	console.log(pageParams); 
	
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'closeItemGroupModalDialog', displayDataContent:'', sendDataOnSuccess:'send',  pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
		
}

function closeItemGroupDiaglog()
{
	$('.largeBox, .overlay').hide();
	$('.largeBox').removeClass('mediumBox');
}
function closeItemGroupDeletDiaglog()
{
	$('.modalDiag, .overlay').hide();
	$('.modalDiag').removeClass('mediumBox');
}

function closeItemGroupModalDialog(response)
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
			
	
	closeItemGroupDiaglog();
	
	//$('#viewPageModal').removeBackdrop();
	loadItemGroupMaster();
}	
	
	

function viewDeleteItemGroupMaster(id)
{
	$('#frmItemGroupDeleteMaster').find("#hid_id").val(id);
	var actParams = {name:'action', value:'deleteRestrict'};  
	var modParams = {name:'module', value:'item_group'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=[]
	
	pageParams.push(actParams);
	pageParams.push(modParams);
	pageParams.push({name:'hid_id',value:id});
 
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadDeleteItemGroup', displayDataContent:'',  sendDataOnSuccess:'send',pageDataType:'json'}; 
	callCommonLoadFunction(passArr); 
	
}

function loadDeleteItemGroup(StrData) 
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

function deleteItemGroupMaster()
{
	var actParams = {name:'action', value:'delete'};  
	var modParams = {name:'module', value:'item_group'}
				
	var passArr={pURL:'process.php',pageParams:pageParams };
	
	var pageParams=$('#frmItemGroupDeleteMaster').serializeArray();
	
	pageParams.push(actParams);
	pageParams.push(modParams); 
	closeItemGroupDeletDiaglog(); 
	//$('#viewDeleteModal').modal('hide');
	var passArr={pURL:'process.php',pageParams:pageParams, onSuccLoadFunc:'loadItemGroupMaster', displayDataContent:'', onSuccAlert:'showInPage', pageDataType:'json'};  
	callCommonLoadFunction(passArr); 
		
} 
 
