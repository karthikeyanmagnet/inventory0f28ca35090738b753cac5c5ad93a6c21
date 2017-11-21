<?php 
	include 'class.script.php'; 
	$category = new subcategory();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$category->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$category->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$category->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$category->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$category->deleteRestrition($postArr);
	
		exit;
	}

?>
