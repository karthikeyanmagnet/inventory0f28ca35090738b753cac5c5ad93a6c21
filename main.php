<?php 
session_start();

$sessionVal = session_id(); 
if($_SESSION['sess_inventory_key']=="" or ($_SESSION['sess_inventory_key']!=$sessionVal) )
{  
?>
<script>location.href="login.php";</script>
<?php
}

?> 
<?php include('header.php') ?>
<div id="leftMenuDiv"   >
<?php include('sidebar.php') ?>
</div>
<div id="centerBodyDiv">
<?php // include('category_list.php'); ?>
</div>
<div id="rightMenuDiv" align="left"></div> 
<div id="dialogViewer"></div>
<?php include('footer.php'); ?>
