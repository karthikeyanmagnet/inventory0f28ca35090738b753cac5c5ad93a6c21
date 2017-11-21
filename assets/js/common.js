var jsModDir = 'public/js/';
var TemplateModDir = 'app/modules/';
var GV_show_list_page_succmsg="";
var GV_menu_permission = {};
var GV_current_menu_from_rfq = '';



function callCommonLoadFunction(passArr)
{  
 
	var pageURL=''; 
	var pageParams={};
	var pageDataType='text'; 
	var pageLoadContent='#rightMenuDiv'; 
	var pagePlsWaitMsg='Loading...Please wait...';  
	var onSuccLoadFunc='';
	var onFailLoadFunc='';
	var displayDataContent='display';
	var sendDataOnSuccess='';  
	var sendCustPassVal='';  
	var onSuccAlert='';
	var pageModule='';
	
	if(passArr.pURL!=undefined) pageURL=passArr.pURL;
	if(passArr.pageParams!=undefined) pageParams=passArr.pageParams;
	if(passArr.pageDataType!=undefined) pageDataType=passArr.pageDataType;
	if(passArr.pageLoadContent!=undefined) pageLoadContent=passArr.pageLoadContent;
	if(passArr.pagePlsWaitMsg!=undefined) pagePlsWaitMsg=passArr.pagePlsWaitMsg; 
	if(passArr.onSuccLoadFunc!=undefined) onSuccLoadFunc=passArr.onSuccLoadFunc;
	if(passArr.onFailLoadFunc!=undefined) onFailLoadFunc=passArr.onFailLoadFunc;
	if(passArr.displayDataContent!=undefined) displayDataContent=passArr.displayDataContent;
	if(passArr.sendDataOnSuccess!=undefined) sendDataOnSuccess=passArr.sendDataOnSuccess;
	if(passArr.sendCustPassVal!=undefined) sendCustPassVal=passArr.sendCustPassVal; 
	if(passArr.onSuccAlert!=undefined) onSuccAlert=passArr.onSuccAlert;
	if(passArr.pageModule!=undefined) pageModule=passArr.pageModule;
	
	if(pageURL=='' || pageURL==undefined) { customAlert('Page is empty.'); return; }
	
	
	$('#loading_div').html(pagePlsWaitMsg);
	showOverlayDiv();
	
	
	$.ajax({ 
		type : 'POST', 
		url : pageURL, 
		dataType	: pageDataType,
		data		:  pageParams,
		success : function(data)
		{ 
			HideOverlayDiv();
			
			if(displayDataContent=='display')
			{
				$(pageLoadContent).show(); 
				$(pageLoadContent).html(data); 
			}
			if(onSuccAlert=="show" || onSuccAlert=="showInPage")
			{
				var opStatus="";
				if(data.status!=undefined) opStatus=data.status; 
				 
				if(opStatus=='success') 
				{  
					if(onSuccAlert=="showInPage") GV_show_list_page_succmsg=data.message; 
					else customAlert(data.message);
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
			}
			
			if(onSuccLoadFunc!='') 
			{   
				var passDataArr={};
				passDataArr["customData"]=sendCustPassVal;
				
				var passData=sendCustPassVal;
				
				
				var SendData=')';
				if(sendDataOnSuccess=='send') 
				{
					passDataArr["formOpData"]=data; 
				}
				var appJ=JSON.stringify(passDataArr);
				 
				var sendFunc=onSuccLoadFunc+'('+appJ+')'; 
				  
				eval(sendFunc);
				
			}
			
			
		}, 
		error : function() 
		{  
			customAlert("Error while loading pages..."); 
			HideOverlayDiv();
		} 
	});
}
function loadLayout()
{
	
	var a  = "view";	 
	var pageParams = {action:a};  
	var passArr={pURL:'left_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'loadLeftMenuView'};
	
	callCommonLoadFunction(passArr);
		
}

function loadLeftMenuView()
{
	/*$('.sidebar li.main').click(function(){
			$('.sidebar-menu li.main').removeClass('active');
			$(this).addClass('active');
			
			if($(this).find('.subchild').length>0)
			{
				$(this).closest('.treeview-menu').addClass('active');
			}
		});
	
	$('.sidebar li.child a').click(function(){
												 $('.sidebar-menu li').removeClass('active');
	$(this).closest('li').addClass('active');	
	$(this).closest('li').closest('li').addClass('active');	
	});
        
	if(GV_current_menu_from_rfq)
	{
		var obj = $('li#'+GV_current_menu_from_rfq).find('a');
		$(obj).trigger('click');
		//alert($(obj).text());
		//callLeftMenuPages(obj,GV_current_menu_from_rfq);
		
		//$('ul.sidebar-menu li a.menu_link:first').trigger('click');
	}
	else
	{
		$('ul.sidebar li.leftsideopt:first').find('a').trigger('click');
	}
	
	var hei = $(window).height() - 50;
	$('ul.sidebar-menu').height(hei);*/
	
	 $('.ml-menu li').click(function(){
		$('.ml-menu li').removeClass('active');
		$('.ml-menu li a').removeClass('toggled');
		$(this).addClass('active');
		$(this).find('a').addClass('toggled');
				//$(this).closest('li').closest('li').addClass('active');		
		$('.main_menu').removeClass('active');
		$(this).closest('li.main_menu').addClass('active');		
	});
	 
	 $('.menu-toggle').on('click', function (e) {
            var $this = $(this);
            var $content = $this.next();

            if ($($this.parents('ul')[0]).hasClass('list')) {
                var $not = $(e.target).hasClass('menu-toggle') ? e.target : $(e.target).parents('.menu-toggle');

                $.each($('.menu-toggle.toggled').not($not).next(), function (i, val) {
                    if ($(val).is(':visible')) {
                        $(val).prev().toggleClass('toggled');
                        $(val).slideUp();
                    }
                });
            }

            $this.toggleClass('toggled');
            $content.slideToggle(320);
        });
	 
	 $('.main_menu:first a:first').trigger('click');
	 $('.main_menu:first').find('.ml-menu li:first').trigger('click');
	 
	 $('.main_menu:first').addClass('active');
	 
	 $('.main_menu:first').find('.ml-menu li:first').addClass('active');
	 $('.main_menu:first').find('.ml-menu li:first a').addClass('toggled');
	
}


function loadModuleJS(module, callBack) //
{
	var exec_function = callBack+'()';
	
	try{	
		eval(exec_function);
	}
	catch(e)
	{ 
		//$.ajaxSetup({ cache: true });
		
		$.getScript(jsModDir+''+module+'.js').done(function(script, textStatus){
			//$.ajaxSetup({ cache: false });
			eval(exec_function);
		});	
	}
	
}

function getModuleTemplateFile(module, view, qrystr)
{
	var loadTemplateFile = TemplateModDir+module+'/'+view+'.php';
	
	if(qrystr)
	{
		loadTemplateFile+='?'+qrystr;
	}
	
	return loadTemplateFile;
}

function createDialog(diag_params)
{
	var width = (diag_params.width)?(diag_params.width):'300';
	var height = (diag_params.height)?(diag_params.height):'150';
	var title = (diag_params.title)?(diag_params.title):'Dialog';
	var param = (diag_params.param)?(diag_params.param):'';
	var onsucessFunc = (diag_params.onsucessFunc)?(diag_params.onsucessFunc):'';
	var btn = [{
							id:"btn-ok",
							text: "OK",
							click: function() {
									closeDialogForm();
							}
					},{
							id:"btn-cancel",
							text: "Cancel",
							click: function() {
									closeDialogForm();
							}
					}]; 
	var buttons = (diag_params.buttons)?(diag_params.buttons):btn;
	$('#dialogViewer').dialog(
	{
		
		autoOpen: true,
		width: width,  
		height: height,
		modal: true,
		title: title,
		resizable: false,
		draggable: true, 
		close: function(event, ui) {    $('#dialogViewer').empty();  }, 
		buttons		: buttons
		 
	});
	var pageParams = param;  
	var onSuccLoadFunc = "loadModuleJS('"+pageParams.module+"', '"+onsucessFunc+"')";	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, pageLoadContent:'#dialogViewer', onSuccLoadFunc:onSuccLoadFunc};
	
	callCommonLoadFunction(passArr);
	
}



function closeDialogForm(){
		$('#dialogViewer').empty();
		$('#dialogViewer').dialog("close"); 
} 
function customAlert(aTxt,aType,aFocus)
{   
	alert(aTxt);	 
	
	if(aFocus){ $(aFocus).focus(); }
}
function shwAlertInPage(aTxt)
{  
	$('#succDivMsg').html(aTxt);	
	$('.alertSuccDivMsg').show();	
	//$('.modal-backdrop').hide();
}

function loadCategoryLayout()
{
	var a  = "view";	 
	var pageParams = {action:a};  
	var passArr={pURL:'left_menu.php',pageParams:pageParams, pageLoadContent:'#leftMenuDiv', onSuccLoadFunc:'loadCategoryPage'};
	
	callCommonLoadFunction(passArr);
}

function loadCategoryPage()
{
	var a  = "list";	 
	var pageParams = {action:a};  
				
	var passArr={pURL:'category_list.php',pageParams:pageParams};
	
	callCommonLoadFunction(passArr); 
	
	 
}

function topHeadTitle(titleCont)
{
	$('.breadcrumb').html(titleCont);
}

function CheckEmailId(email) {  
	email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;           
	if(!email_regex.test(email)){ return false;}
	return true;
}

function funcCallDate(thisObj)
{

	$(thisObj).find('.datepicker').focus(); 
}

function toSqlDate(date)
{
	var dt = new Date(date);
	var dt = dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
	
	return dt;
}


function ValidateNumberKeyPress(field, evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	var keychar = String.fromCharCode(charCode);

   // if (charCode > 31   && (charCode < 48 || charCode > 57) && keychar != "."  && keychar != "-" )
	 if (charCode > 31  && (charCode < 48 || charCode > 57) && keychar != "."   )
	{

		return false;

	}



	if (keychar == "." && field.value.indexOf(".") != -1) 

	{

		return false;

	}

		

	if(keychar == "-")

	{

		if (field.value.indexOf("-") != -1 /* || field.value[0] == "-" */) 

		{

			return false;

		}

		else

		{

			//save caret position

			var caretPos = getCaretPosition(field);

			if(caretPos != 0)

			{

				return false;

			}

		}

	}



	return true;

}
function numbersonly(e){

	var unicode=e.charCode? e.charCode : e.keyCode
	
	if (unicode!=13){ //if the key isn't the ENTER key (which we should allow)
	
		if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
		
			if (unicode<48||unicode>57) //if not a number
			
			return false //disable key press
		
		}
		
	}

}
 function getCaretPosition(objTextBox){



            var objTextBox = window.event.srcElement;



            var i = objTextBox.value.length;



            if (objTextBox.createTextRange){

                objCaret = document.selection.createRange().duplicate();

                while (objCaret.parentElement()==objTextBox &&

                  objCaret.move("character",1)==1) --i;

            }

            return i;

        }




 
var GV_rightSub_cont_obj={};
function callLeftMenuPages(thisObj,menuName)
{
	//console.log(GV_menu_permission);
	GV_rightSub_cont_obj=thisObj;	 
	switch(menuName) 
	{ 	 
		case 'category' : 			loadCategoryMaster(); 			break; 
		case 'item' : 		loadItemMaster(); 		break; 
		case 'userlist' : 			loadUserMaster(); 				break; 
		case 'item_group' : 			loadItemGroupMaster(); 				break;  
		case 'customer' : 			loadCustomerMaster(); 			break;  
		case 'vendor' : 	loadVendorMaster(); 		break;
		case 'user' : 		loadUserMaster(); 			break;
		case 'dashboard' : 			loadDashboardPage(); 			break;
		case 'role' : 	loadRoleMaster(); 			break;		
		case 'deduction' : 			loadDeductionMaster(); 			break;
		case 'designation' : 		loadDesignationMaster(); 		break;
		case 'employee' : 			loadEmployeeMaster(); 			break;
		case 'payroll_master' : 	loadPayrollMaster(); 			break;
		case 'salary_process' :     loadSalaryProcessMaster(1); break;
		case 'advances_details' : 	loadAdvancesDetails(); 			break;
		case 'payslip_report' : 	loadPayslipReportMaster(); 		break;
		case 'user_role' : 			loadUserRoleMaster(); 			break;
		case 'rfq':					loadRFQ();						break;
		
		case 'po_entry':			loadPOEntryMaster();			break;
		case 'stock_entry' : 			loadStockEntryMaster(); 			break;
		case 'stock_dispatch' : 		loadStockDispatchMaster(); 		break;
		case 'po_assign': 			loadPOAssignMaster(); break;
		
		case 'stock_report': 			loadStockReportLayout(); break;
		case 'create_shipment_order':			loadShipmentOrderLayout(); break;
		
	}
	
	
	
	
	
}

function loadRFQ()
{
	window.location.href = 'http://localhost/projects/rfq/public/';
}

function highlightRightMenu()
{  
	var thisObj=GV_rightSub_cont_obj;	 
	$('ul.treeview-menu li ').removeClass('active'); 
	$(thisObj).closest('li').addClass('active');
	if($(thisObj).closest('li').hasClass('subchild'))
	{
		$(thisObj).closest('li').addClass('active');
		$(thisObj).closest('li').closest('li.child').addClass('active');
	}
	
	 
	if(GV_show_list_page_succmsg!="")
	{
		shwAlertInPage(GV_show_list_page_succmsg);
		GV_show_list_page_succmsg="";	
	}
	
}
function showOverlayDiv() 
{
	$('#overlay_div').show();
	var w = $('#loading_div').width();
	var h = $('#loading_div').height();
	$('#loading_div').css ({
		left:($(document).width() - w)/2,
		top:(($(document).height() - h)/2)-25
	}); 
	
	$("#overlay_div").fadeIn();
}

function HideOverlayDiv()
{
	$('#overlay_div').hide();
}
function setLftMenuHt()
{
	/* subhu will do*/	
}

function checkPermssion(type)
{
	var str = (GV_menu_permission[type]);
	
	var chk_add = str.indexOf('2');
	
	if(chk_add==-1)
	{
		$('#rightMenuDiv').find('.act-add').remove();
	}
	
	var chk_edit = str.indexOf('3');
	
	if(chk_edit==-1)
	{
		$('#rightMenuDiv').find('.act-edit').remove();
	}
	
	var chk_delete = str.indexOf('4');
	
	if(chk_delete==-1)
	{
		$('#rightMenuDiv').find('.act-delete').remove();
	}
	
	//alert(chk_add);
	
	/*var arr = str.split(',');
	
	var 
	$.each(arr, function(k, v){
		if(v == 1)
	});*/
	
}

function rfQLeftMenuPages()
{
	
}