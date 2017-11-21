<?php 
	include 'class.script.php'; 
	$shipment = new shipment();
	
	$action=$_POST["action"];
	$postArr=$_POST;
	
	if($action=='getList')
	{
		echo $op=$shipment->listview($postArr);
	
		exit;
	}
	if($action=='getSingeView')
	{
		echo $op=$shipment->getSingleView($postArr);
	
		exit;
	}
	if($action=='save')
	{ 
		echo $op=$shipment->saveprocess($postArr);
	
		exit;
	}
	if($action=='delete')
	{
		echo $op=$shipment->deleteprocess($postArr);
	
		exit;
	}
	if($action=='deleteRestrict')
	{
		echo $op=$po_entry->deleteRestrition($postArr);
	
		exit;
	}
	if($action=='po_get_details')
	{
		echo $op=$shipment->getPODetails($postArr);
	
		exit;
	}
	if($action=='invoice_pack_save')
	{
		echo $op=$shipment->saveInvoicePackDetails($postArr);
	
		exit;
	}
        
        if($action=='update_shipment_order')
	{
		echo $op=$shipment->updateShipmentOrder($postArr);
	
		exit;
	}
	

?>
