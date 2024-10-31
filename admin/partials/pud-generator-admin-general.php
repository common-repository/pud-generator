<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.pudsoft.in/
 * @since      1.0.0
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/admin/partials
 */
?>
<?php
echo page_tabs('pud_general');
$pud_max_page = intval(get_option('pud_max_page'));
$pud_default_author = intval(get_option('pud_default_author'));
$pud_page_status = get_option('pud_page_status');
$pud_page_visibility = get_option('pud_page_visibility');
$authors = get_users( array(
 'orderby' => 'nicename',
) );
$statuses = get_pud_statuses();
$visibilities = get_pud_visibilities();
?>
<div class="pud-grid pud-top-margin">
  <div class="pud-col-8-12 pud-segment">
		<h2><?php echo  __( 'General Page' , 'pud_generator'); ?></h2>
		<div class='pud-wrap'>
            <form method='post' action='options.php'>
                <?php settings_fields( 'pud-generator-settings-group' ); ?>
                <?php do_settings_sections( 'pud-generator-settings-group' ); ?>
                <table class='form-table'>                    
                    <tr valign='top'>
                        <th scope='row'><?php echo __( 'Max Page Generate', 'pud_generator' ); ?></th>
                        <td>
                            <input type="number" class="small-text" value="<?php if($pud_max_page != NULL) { echo $pud_max_page; } else { echo "0"; } ?>"  min="1" step="1"
                            	name="pud_max_page">
                        </td>
                    </tr>  
                    <tr valign='top'>
                        <th scope='row'><?php echo __( 'Default Author' , 'pud_generator'); ?></th>
                        <td>
	                        <select name="pud_default_author" size="1" id="pud_default_author" >
								<?php
								if ( $authors && count( $authors ) > 0 ) {
					        		foreach ( $authors as $author ) {
					        			?>
					        			<option value="<?php echo $author->ID; ?>" <?php selected( $author->ID, $pud_default_author ); ?>>
					        				<?php echo $author->user_nicename; ?>
					        			</option>
					        			<?php
					        		}
					        	}
								?>	
							</select>
                        </td>
                    </tr>
                    <tr valign='top'>
                        <th scope='row'><?php echo __( 'Default Status', 'pud_generator' ); ?></th>
                        <td>
                            <select name="pud_page_status" size="1">
							<?php
							if ( is_array( $statuses ) && count( $statuses ) > 0 ) {
								foreach ( $statuses as $status => $label ) {
									?>
									<option value="<?php echo $status; ?>"<?php selected( $pud_page_status, $status ); ?>>
										<?php echo $label; ?>
									</option>
									<?php
								}
							}
							?>
						</select>
                        </td>
                    </tr>
                    <tr valign='top'>
                        <th scope='row'><?php echo __( 'Default Visibility' , 'pud_generator'); ?></th>
                        <td>
                            <select name="pud_page_visibility" size="1">
							<?php
							if ( is_array( $visibilities ) && count( $visibilities ) > 0 ) {
								foreach ( $visibilities as $visibility => $label ) {
									?>
									<option value="<?php echo $visibility; ?>"<?php selected( $pud_page_visibility, $visibility ); ?>>
										<?php echo $label; ?>
									</option>
									<?php
								}
							}
							?>
						</select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
  </div>
  <div class="pud-col-4-12 pud-segment">
  	<h3 ><span>About this Plugin:</span></h3>
<p>Generate hundreds of Pages/Post within few steps. You just need to add placeholder and remain plugin will do for you. 
</p>
    <h3> Special Features </h3>
<ul class="pud-help-doc">
<li>Create Page &amp; Post based on Dynamic Title</li>
<li>Control Number of Pages, Author, Status etc…</li>
<li>Add &amp; Edit Placeholders</li>
<li>Select text and plugin will create the placeholder and put into location</li>
<li>Duplicate Placeholder</li>
<li>Add any number of text into the placeholder</li>
<li>Automatically Create the placeholder for you</li>
<li>Ajax Flow for most of the process</li>
<li>List all existing generator with different status</li>
<li>Check Log in the popup with URL of Page/ Post</li>
<li>Save default setting</li>
<li>Use for existing page/post</li>
<li>RTL Supported</li>
</ul>

<p><strong>And many more…</strong></p>
<p><strong>See video for action:</strong></p>
  	<div class="about-plugin-sec" ><a href="#ex1"  rel="modal:open" >How to Add Placeholders</a></div>	 
  	<div  class="about-plugin-sec" ><a href="#ex2"  rel="modal:open" >How to Create Generator</a></div>	 
  	<div  class="about-plugin-sec" ><a href="#ex3"  rel="modal:open" >Bulk Page/Post Generate</a></div>	
  	<div  class="about-plugin-sec" ><a href="#ex4"  rel="modal:open" >Generate from Existing Page/Post</a></div> 
  </div>
</div>
<div id="ex1" class="modal">
	<h2 class="pud-form-title" ><?php echo  __( 'How to Add Placeholders', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
    <iframe width="440" height="315" src="https://www.youtube.com/embed/6ci2WL_zVGo" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
</div>
<div id="ex2" class="modal">
	<h2 class="pud-form-title" ><?php echo  __( 'How to Create Generator', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
  	<iframe width="440" height="315" src="https://www.youtube.com/embed/uwG6MLVygQI" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
</div>
<div id="ex3" class="modal">
	<h2 class="pud-form-title" ><?php echo  __( 'Bulk Page/Post Generate', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
    <iframe width="440" height="315" src="https://www.youtube.com/embed/QQnFCGa4f9U" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
</div>
<div id="ex4" class="modal">
	<h2 class="pud-form-title" ><?php echo  __( 'Generate from Existing Page/Post', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
    <iframe width="440" height="315" src="https://www.youtube.com/embed/_q5b3_O1CaY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
	</div>
</div>