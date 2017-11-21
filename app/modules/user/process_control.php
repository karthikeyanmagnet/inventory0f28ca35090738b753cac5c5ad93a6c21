<?php 
	include 'class.script.php'; 
	$user = new user();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$user->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$user->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$user->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$user->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$user->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getUserProfile')
	{
		echo $op=$user->getUserProfile($postArr);
	
		exit;
	}
	if($action=='update_profile')
	{
		echo $op=$user->updateUserProfile($postArr);
	
		exit;
	}
	
	

?>
