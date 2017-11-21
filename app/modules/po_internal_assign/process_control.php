<?php 
	include 'class.script.php'; 
	$po_internal_assign = new po_internal_assign();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$po_internal_assign->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$po_internal_assign->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{ 
		echo $op=$po_internal_assign->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$po_internal_assign->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$po_internal_assign->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
