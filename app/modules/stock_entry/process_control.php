<?php 
	include 'class.script.php'; 
	$stock_entry = new stock_entry();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$stock_entry->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$stock_entry->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$stock_entry->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$stock_entry->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$stock_entry->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getPOSODetails')
	{
		echo $op=$stock_entry->getPOSODetails($postArr);
	
		exit;
	}

?>
