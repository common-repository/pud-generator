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
echo page_tabs('pud_manage');
?>
<div class="pud-grid pud-top-margin">
  <div class="pud-col-11-12 pud-segment">  
        <?php
        echo alert_message();
        ?>                   
        <p>&nbsp;<a href="#" class="button pud-push-right refresh_generator tooltip"   data-tooltip="Refresh Table" ><i class="fa fa-refresh"></i>&nbsp;</a>&nbsp;<a href="?page=pud_generator" class="button pud-push-right mrg_right" ><i class="fa fa-plus"></i>&nbsp;<?php echo  __( 'Add Generator', 'pud_generator'); ?></a></p>
        <div class="clear-both" >&nbsp;</div>
        <table id="list-generator" class="display dt-head-left" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><?php echo  __( 'Id', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Name', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Page Title', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Page Combination', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Max Page', 'pud_generator'); ?></th><th><?php echo  __( 'Type', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Author', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Status', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Visibility', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Generator Status', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Created date', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Updated date', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Action', 'pud_generator'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php echo  __( 'Id', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Name', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Page Title', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Page Combination', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Max Page', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Type', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Author', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Status', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Visibility', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Generator Status', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Created date', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Updated date', 'pud_generator'); ?></th>
                    <th><?php echo  __( 'Action', 'pud_generator'); ?></th>
                </tr>
            </tfoot>
        </table>
  </div>
</div>
<div id="log-generator-modal" class="modal ">
    <div id="log-generator-section"><div id="log-loading-bar" >Loading....</div><div id="log-generator-row" ></div></div>
</div>
<div id="ex4" class="modal">
  <h2 class="pud-form-title" ><?php echo  __( 'Steps to Generate Bulk Page/Post:', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
    <ul class="pud-help-doc">
      <li>
        All Generator are listed here with data & different actions. 
      </li>
      <li>
        Mainly three actions are provided here, Edit, Start Generation & Delete also based on the status you can see the log of generated records.
      </li>
      <li>
         Once you start the generation process, the popup will get visible. Don't refresh the page until you see processing complete message.
      </li> 
      <li>
       You can easily manage each generator from the interface.
      </li>
    </ul>
    <b>Video:</b> <a href="https://www.youtube.com/embed/QQnFCGa4f9U" target="_blank">See in Action</a>
    <br><br>
  </div>
</div>

<?php
$field = 'pud_tour_manage';
$pud_tour_manage = get_option( $field );
if ( $pud_tour_manage == 1) {
    update_option( $field, 0);
?>
<script>
jQuery('#ex4').modal();
</script> 
<?php
}
?>