function loadStockReportLayout()
{
	var titleCont= ' <li> Masters </li>';
        titleCont += '<li class="active">Stock report</li>';
	topHeadTitle(titleCont);
	
	
	calldStockReportLayout();
	
}
function calldStockReportLayout()
{ 
    $(".page-loader-wrapper").show();
	var category_id = $('#category_id').val(); 
	var cmb_item_id = $('#cmb_item_id').val();
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
        var datefilter = $('#datefilter').val();
	//alert(category_id);
	
	var a  = "view";	 
	var pageParams = {action:a, module:'stock_report', view:'view', category_id:category_id, cmb_item_id:cmb_item_id, date_from:date_from, date_to:date_to,datefilter:datefilter};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'onSuccessStockReportLayout', pageLoadContent:'#rightMenuDiv'};	 
	
	callCommonLoadFunction(passArr);  
	
}

function calldStockReportLayoutPage(page)
{ 
    
    $(".page-loader-wrapper").show();
	var category_id = $('#category_id').val(); 
	var cmb_item_id = $('#cmb_item_id').val();
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
        var datefilter = $('#datefilter').val();
	//alert(category_id);
	
	var a  = "view";	 
	var pageParams = {action:a, module:'stock_report', view:'view', category_id:category_id, cmb_item_id:cmb_item_id, date_from:date_from, date_to:date_to,datefilter:datefilter,page:page};  
	
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;	
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'onSuccessStockReportLayout', pageLoadContent:'#rightMenuDiv'};	 
	
	callCommonLoadFunction(passArr);  
	
}
function onSuccessStockReportLayout()
{
	$('.datepicker').datepicker({
			  autoclose: true
	});
	 
	stockReportCategoryOnChange('onloadCall');
        $(".page-loader-wrapper").hide();
	  
}
function stockReportCategoryOnChange(callType)
{
	var category_id = $('select#category_id').val();
	var cmb_item_id = $('select#cmb_item_id').val();	
	
	$('select#cmb_item_id').val(0); 
	
	if(category_id>0)
	{
		$('select#cmb_item_id').find('option').hide(); 
		$('select#cmb_item_id').find('option').each(function(){
			if(category_id == $(this).attr('atr_catid') || $(this).val()==0)
			$(this).show();
		});
	}
	else
	{
		$('select#cmb_item_id').find('option').show(); 	
	}
	
	if(callType=='onloadCall')
	{ 
		$('select#cmb_item_id').val(cmb_item_id); 
	}
}
function stockReportGenerateOp()
{
	calldStockReportLayout();
}
function stockReportCancel()
{
	$('#category_id').val(''); 
	$('#cmb_item_id').val(''); 
	$('#date_from').val(''); 
	$('#date_to').val(''); 
	loadStockReportLayout();
}
function stockReportGenerateExcel()
{
	downloadExcelStockReportEntryData();
}

function downloadExcelStockReportEntryData()
 {
	 var a  = "view";	 
	 var category_id = $('#category_id').val(); 
	var cmb_item_id = $('#cmb_item_id').val();
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
	//alert(category_id);
	
	var a  = "view";	 
	var pageParams = {action:a, module:'stock_report', view:'report_xls', category_id:category_id, cmb_item_id:cmb_item_id, date_from:date_from, date_to:date_to}; 
	var custVals = {};  
 
	var loadTemplateFile = getModuleTemplateFile(pageParams.module, pageParams.view); //build.php;		
	var passArr={pURL:loadTemplateFile,pageParams:pageParams, onSuccLoadFunc:'viewDownloadFile',sendDataOnSuccess:'send', displayDataContent:'', pageDataType:'json'};
	
	callCommonLoadFunction(passArr); 
 }
 
 function viewDownloadFile(StrData)
{
	//console.log(StrData);
	var jOPData=StrData; 
	var opData=jOPData.formOpData; 
	
	if(opData.status == 'success')
	{
		if(opData.file)
		{
			window.open(opData.file)
		}
	}
}