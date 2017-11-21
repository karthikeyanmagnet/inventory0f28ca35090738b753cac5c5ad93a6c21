<?php 
	include 'class.script.php'; 
	$stock_dispatch = new stock_dispatch();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$stock_dispatch->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$stock_dispatch->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{
		echo $op=$stock_dispatch->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$stock_dispatch->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$stock_dispatch->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='getPOSODetails')
	{
		echo $op=$stock_dispatch->getPOSODetails($postArr);
	
		exit;
	}
	
	if($action == 'getProductDetails')
	{
		echo $op=$stock_dispatch->getProductDetails($postArr);
		
		exit;
	}
	
	if($action == 'getItemQuantity')
	{
		echo $op=$stock_dispatch->getItemQuantity($postArr);
		exit;
	}

?>
