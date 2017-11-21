<?php
	$maindir = 'app';
	include  $maindir.'/rapper/class.rapper.php'; 
	$rapper = new rapper;
	$table = "bud_shipment_invoice_items";
	$sql_qry = "show columns from $table";
	$recs = $rapper->pdoObj->fetchMultiple($sql_qry, $bindArr); 
	
	foreach($recs as $col)
	{
		echo '$'.$col['Field'].' = $this->purifyInsertString($postArr["'.$col['Field'].'"]);';
		echo "<br>";
	}
	echo '$ins=" '.$table.' SET ';
	foreach($recs as $col)
	{
		echo $col['Field'].'=:'.$col['Field'].',';
	}
	
	echo "<br>";
	
	foreach($recs as $col)
	{
		echo strpos('int',$col['Type']);
		$type = strpos('int',$col['Type'])>0?'int':'text';
		echo '":'.$col['Field'].'"=>array("value"=>$'.$col['Field'].',"type"=>"'.$type.'"),';
	}
	
	echo "<br>";
?>	