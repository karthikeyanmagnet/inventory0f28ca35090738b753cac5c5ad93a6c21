<?php
	include  dirname(realpath('..')).'/rapper/class.rapper.php'; 
	include 'class.script.php'; 
	$user_role = new user_role();
	$postArr = $_POST;
	$modules = $user_role->getMenuModuleList();
	$id=$user_role->purifyInsertString($postArr["id"]); 
	$module_chk_actions = array();
	if($id>0)
	{
		$rs=$user_role->getSingleView($postArr);
		$rsData = $rs['rsData'];
		$user_mod_actions = $rsData['user_mod_actions'];
		$roles = $rsData['roles'];
		
		foreach($user_mod_actions as $action)
		{
			$module_id = $action['module_id'];
			$mod_action = $action['module_actions'];
			$module_chk_actions[$module_id] = $mod_action;
		}
		
		//print_r($module_actions);
		
	}
	else
	{
		$roles = $user_role->getModuleComboList('role');
	}
?>

<form role="form" id="frmUserRoleMaster">
  <input type="hidden" name="hid_id" id="hid_id" value="<?=$id;?>" />
  <div class="content-wrapper">
    <!--<section class="content-header">
      
    </section>-->
    <section class="content">
      <div class="box-body white-bg">
	  
	   <div class="form-group">
          <h3> Roles Permission </h3>
		  
		  </div>
        <div class="form-group">
          <label>Role <span class="spnClsMandatoryField" >*</span></label>
          <select class="form-control show-tick" id="user_role_id" name="user_role_id">
		  <?php 
		  	if($id>0)
			{
				foreach($roles as $rol)
				{ 
					if($rol['user_role_id'] == $rsData['user_role_id'])
					{
						echo '<option value="'.$rol['user_role_id'].'"  >'.$rol['user_role_name'].'</option>';
					}  
				}
			}
			else
			{		
		  ?>
            <option value="0" >Select</option>
            <?php
				foreach($roles as $rol)
				{  
					$selected = ($rol['user_role_id'] == $rsData['user_role_id'])?'selected':'';
					echo '<option value="'.$rol['user_role_id'].'" '.$selected.'>'.$rol['user_role_name'].'</option>'; 
				}
			}
			?>
          </select>
          <button type="button" class="btn btn-success" style="margin:25px 0 0 -20px;text-align:center" onclick="viewCreateMaster('role', this)" cmb-view="user_role_id"><i class="fa fa-plus"></i></button>
        </div>
        <table width="100%" cellpadding="2" cellspacing="5" class="inputGridTable-1" id="customFields">
          <thead>
          <th>Module</th>
            <th>Sub Module</th>
            <th>Access</th>
            <th>Add</th>
            <th>Edit</th>
            <th>Delete</th>
            </thead>
          <tbody>
            <? 
 $parent = '';
 $icr = 0;
 foreach($modules as $mod) { 
 $icr++;
 $chkAction = explode(',', $module_chk_actions[$mod['module_id']]);
 
 ?>
            <tr>
              <td><?  
	if($parent!=$mod['main_module_name']) {
	$parent = $mod['main_module_name'];
	echo $mod['main_module_name'];
	
	if($mod['sub_module_id']>0)
	{
		echo '</td><td></td><td>';
		echo '<input type="checkbox"  name="hdn_module_parent_'.$mod['module_id'].'" id="hdn_module_parent_'.$mod['module_id'].'" class="filled-in chk_module_head" value="'.$mod['main_module_id'].'"><label for="hdn_module_parent_'.$mod['module_id'].'" >&nbsp;</label>';
		//echo '<input type="checkbox" name="hdn_module_parent_'.$mod['module_id'].'" class="chk_module_head" value="'.$mod['main_module_id'].'">';
		echo '</td>';
		echo '<td colspan=3></td>';
		echo '</tr>';
		echo '<tr><td>'; 
	}
	 
	}
	
	$module_actions = explode(',', $mod['module_actions']);
	
	
?>
              </td>
              <td><?=$mod['sub_module_name'];?></td>
              <td><? if(in_array(1,$module_actions)) { ?>
                <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="chk_submodules1_<?=$icr;?>" value="1" class="chk_module_sub sub_access filled-in" parent="<?=$mod['main_module_id'];?>" sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>"  <? if(in_array(1,$chkAction)) { echo "checked"; } ?>/>
                <label for="chk_submodules1_<?=$icr;?>" >&nbsp;</label>
                <? } ?></td>
              <td><? if(in_array(2,$module_actions)) { ?>
                <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="chk_submodules2_<?=$icr;?>" value="2" class="chk_module_sub filled-in" parent="<?=$mod['main_module_id'];?>"  sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>" <? if(in_array(2,$chkAction)) { echo "checked"; } ?>/>
                <label for="chk_submodules2_<?=$icr;?>" ></label>
                <? } ?></td>
              <td><? if(in_array(3,$module_actions)) { ?>
                <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="chk_submodules3_<?=$icr;?>" value="3" class="chk_module_sub filled-in" parent="<?=$mod['main_module_id'];?>"  sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>" <? if(in_array(3,$chkAction)) { echo "checked"; } ?>/>
                <label for="chk_submodules3_<?=$icr;?>" ></label>
                <? } ?></td>
              <td><? if(in_array(4,$module_actions)) { ?>
                <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="chk_submodules4_<?=$icr;?>" value="4" class="chk_module_sub filled-in" parent="<?=$mod['main_module_id'];?>"  sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>" <? if(in_array(4,$chkAction)) { echo "checked"; } ?>/>
                <label for="chk_submodules4_<?=$icr;?>" ></label>
                <? } ?></td>
            </tr>
            <? } ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-4 no-padding pull-right">
        <p>&nbsp;</p>
        <div class="form-group">
          <div class="col-md-12 no-padding text-right">
            <button class="btn btn-warning btn-sm pull-right" onclick="closeUserRoleMaster()" type="button"><i class="fa fa-times"></i> Cancel</button>
            <button class="btn btn-primary btn-sm pull-right" style="margin-right:10px;" onclick="CreateUpdateUserRoleMasterSave()" type="button"><i class="fa fa-floppy-o"></i> Submit</button>
          </div>
        </div>
      </div>
    </section>
  </div>
</form>
<div class="SubAddEditForm submodalDiag">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    ROLE CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect" onclick="createMasterEntry('role')">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect" onclick="cancelMasterCreation()">CANCEL</button>
                </div>               
            </div>
            <div class="body">
            </div>
        </div>
	</div>
</div>
