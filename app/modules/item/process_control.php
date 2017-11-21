<?php 
	include 'class.script.php'; 
	$item = new item();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$item->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$item->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$item->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$item->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$item->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
