<?php 
	include 'class.script.php'; 
	$role = new role();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$role->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$role->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$role->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$role->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$role->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
