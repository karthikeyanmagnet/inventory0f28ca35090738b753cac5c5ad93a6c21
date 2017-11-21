<?php 
	include 'class.script.php'; 
	$item_group = new item_group();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$item_group->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$item_group->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{ 
		echo $op=$item_group->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$item_group->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$item_group->deleteRestrition($postArr);
	
		exit;
	}
	
	

?>
