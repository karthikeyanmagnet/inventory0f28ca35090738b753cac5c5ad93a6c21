<?php 
	include 'class.script.php'; 
	$expense = new expense();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$expense->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$expense->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$expense->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$expense->deleteprocess($postArr);
	
		exit;
	}
	if($action=='getLCDDetails')
	{
		echo $op=$expense->getLCDDetails($postArr);
	
		exit;
	}
	
	if($action=='upload')
	{
		echo $op=$expense->uploadprocess($postArr);
	
		exit;
	}
	if($action=='getExpDetFileDetails')
	{
		echo $op=$expense->getuploadedfile($postArr);
	
		exit;
	}
	
	if($action=='import')
	{
		echo $op=$expense->importprocess($postArr);
	
		exit;
	}
	
	if($action == 'removeTempFiles')
	{
		echo $op=$expense->removeTempFiles($postArr);
	
		exit;
	}

?>
