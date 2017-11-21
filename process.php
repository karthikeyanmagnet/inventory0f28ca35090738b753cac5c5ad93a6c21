<?php
	include 'app/rapper/class.rapper.php'; 
	$rapper = new rapper();
	$action = $_POST['action'];
	$module = $_POST['module'];
	
	if($module)
	{
		//ini_set('display_errors', 1);
		$template = $rapper->getModuleProcess($module); 
		if($template)
		include $template;
		else 
		exit('error in process page or check process control page exist');
	}
?>