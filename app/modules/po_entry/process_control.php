<?php 
	include 'class.script.php'; 
	$po_entry = new po_entry();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$po_entry->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$po_entry->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{ 
		echo $op=$po_entry->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$po_entry->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$po_entry->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='po_get_details')
	{
		echo $op=$po_entry->getPODetails($postArr);
	
		exit;
	}
	

?>
