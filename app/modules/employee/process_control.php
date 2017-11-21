<?php 
	include 'class.script.php'; 
	$employee = new employee();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$employee->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$employee->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$employee->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$employee->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$employee->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
