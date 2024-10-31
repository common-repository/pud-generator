<?php
/**
 * @link       http://www.pudsoft.in/
 * @since      1.0.0
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/admin/partials
 */
?>
<?php
echo page_tabs('pud_placeholder');
?>
<div class="pud-grid pud-top-margin">
  <div class="pud-col-11-12 pud-segment">  
  		<?php
		echo alert_message();
		?>  	
		<p>&nbsp;<a href="#" class="button pud-push-right refresh_placeholder tooltip " data-tooltip="Refresh Table" ><i class="fa fa-refresh"></i>&nbsp;</a>&nbsp;<a href="#ex1" class="button pud-push-right mrg_right" rel="modal:open" ><i class="fa fa-plus"></i>&nbsp;<?php echo  __( 'Add Placeholder', 'pud_generator'); ?></a></p>
  		<div class="clear-both" >&nbsp;</div>
		<table id="list-placeholders" class="display" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th><?php echo  __( 'Id', 'pud_generator'); ?></th>
		            <th><?php echo  __( 'Name', 'pud_generator'); ?></th>
		            <th><?php echo  __( 'Placeholder', 'pud_generator'); ?></th>
		            <th><?php echo  __( 'Tags', 'pud_generator'); ?></th>
		            <th><?php echo  __( 'Created date', 'pud_generator'); ?></th>
		            <th><?php echo  __( 'Updated date', 'pud_generator'); ?></th>
		            <th><?php echo  __( 'Action', 'pud_generator'); ?></th>
		        </tr>
		    </thead>
		    <tfoot>
		        <tr>
		        	<th><?php echo  __( 'Id', 'pud_generator'); ?></th>
					<th><?php echo  __( 'Name', 'pud_generator'); ?></th>
					<th><?php echo  __( 'Placeholder', 'pud_generator'); ?></th>
					<th><?php echo  __( 'Tags', 'pud_generator'); ?></th>
					<th><?php echo  __( 'Created date', 'pud_generator'); ?></th>
					<th><?php echo  __( 'Updated date', 'pud_generator'); ?></th>
					<th><?php echo  __( 'Action', 'pud_generator'); ?></th>
		        </tr>
		    </tfoot>
		</table>
  </div>
</div>
<!-- Modal HTML embedded directly into document -->
<div id="ex1" class="modal">
	<h2 class="pud-form-title" ><?php echo  __( 'Add Placeholder', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12 pud-segment"> 
   <form method="post" id="addplaceholder" autocomplete="off" >
		  <div class="pud-form-group">
		    <label for="name"><?php echo  __( 'Name', 'pud_generator'); ?> *</label>
		    <input name="name" id="name" type="text" class="pud-form-control" placeholder="Enter name of placeholder" required onkeypress="create_unique_key('name', 'placeholder')" onblur="create_unique_key('name', 'placeholder')">
		  </div>
		  <div class="pud-form-group">
		     <label for="to"><?php echo  __( 'Placeholder', 'pud_generator'); ?> *</label>
		    <input name="placeholder" id="placeholder" type="text" class="pud-form-control" placeholder="Placeholder Key" required readonly maxlength="40">
		  </div>
		  <div class="pud-form-group">
		    <label for="to"><?php echo  __( 'Tags', 'pud_generator'); ?> * <a href="javascript:void(0);" id="btnAdd" ><i class="fa fa-plus"></i></a></label>
		    <div class="add-tag-section">
		    	<input name="tags[]" type="text" id="tags" class="pud-form-control-dyn" /> 
		    </div>
		    
		  </div>
		  <div class="pud-form-group">
		 		<input type="hidden" name="action" value="add_place_holder" class="button" />
		  		<input type="submit">
		   </div>
		</form>
	</div>
</div>
<div id="ex2" class="modal">
	<h2 class="pud-form-title" ><?php echo  __( 'Edit Placeholder', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12 pud-segment"> 
   <form method="post" id="editplaceholder" autocomplete="off" >
		  <div class="pud-form-group">
		    <label for="name"><?php echo  __( 'Name', 'pud_generator'); ?> *</label>
		    <input name="name" id="edit_name" type="text" class="pud-form-control" placeholder="Enter name of placeholder" required >
		  </div>
		  <div class="pud-form-group">
		     <label for="to"><?php echo  __( 'Placeholder', 'pud_generator'); ?> *</label>
		    <input name="placeholder" id="edit_placeholder" type="text" class="pud-form-control" placeholder="Placeholder Key" required readonly maxlength="40">
		  </div>
		  <div class="pud-form-group">
		    <label for="to"><?php echo  __( 'Tags', 'pud_generator'); ?> *<a href="javascript:void(0);" id="btnEdit" ><i class="fa fa-plus"></i></a></label>
		    <div class="edit-tag-section">
		    	<input name="tags[]" type="text" id="tags" class="pud-form-control-dyn" /> 
		    </div>
		  </div>
		  <div class="pud-form-group">
		 		<input type="hidden" name="action" value="edit_place_holder"  />
		 		<input type="hidden" name="id" id="edit_id" value="" class="button" />
		  		<input type="submit">
		   </div>
		</form>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("#btnAdd").click(function() {
    jQuery(".add-tag-section").append('<div class="con"><input type="text" name="tags[]" id="tags" class="pud-form-control-dyn" value="" />' + '<a class="btnRemove" href="javascript:void(0);" ><i class="fa fa-close"></i></a></div>');
  });
  jQuery("#btnEdit").click(function() {
    jQuery(".edit-tag-section").append('<div class="con"><input type="text" name="tags[]" id="tags" class="pud-form-control-dyn" value="" />' + '<a class="btnRemove" href="javascript:void(0);" ><i class="fa fa-close"></i></a></div>');
  });
  // delegating event here
  jQuery('body').on('click','.btnRemove',function() {
    jQuery(this).parent('div.con').remove()

  });
});
</script>
<div id="ex4" class="modal">
  <h2 class="pud-form-title" ><?php echo  __( 'How to Add Placeholders:', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
    <ul class="pud-help-doc">
      <li>
        All placeholders are listed here with data & different actions. 
      </li>
      <li>
        These placeholders are get used while creating a generator. You just need to select it and all data populate automatically.
      </li>
      <li>
         You can use HTML tag as well in the Tags, it'll get replaced.
      </li>
      <li>
         You can add any number of tag with each placeholder.
      </li>
    </ul>
    <b>Video:</b> <a href="https://www.youtube.com/embed/6ci2WL_zVGo" target="_blank">See in Action</a>
    <br><br>
  </div>
</div>
<?php
$field = 'pud_tour_placeholder';
$pud_tour_placeholder = get_option( $field );
if ( $pud_tour_placeholder == 1) {
    update_option( $field, 0);
?>
<script>
jQuery('#ex4').modal();
</script> 
<?php
}
?>