<?php 
	include 'class.script.php'; 
	$vendor = new vendor();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$vendor->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$vendor->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$vendor->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$vendor->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$vendor->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
