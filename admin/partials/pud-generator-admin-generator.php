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
global $wpdb;
$type = isset($_GET['type'])?$_GET['type']:'';
if($type !='edit')
{
  echo page_tabs('pud_generator');
}
else
{
   echo '<h1>Edit Generator</h1><div class="pud-loader"></div>';
}

$pud_max_page = intval(get_option('pud_max_page'));
$pud_default_author = intval(get_option('pud_default_author'));
$pud_page_status = get_option('pud_page_status');
$pud_page_visibility = get_option('pud_page_visibility');
$authors = get_users( array(
 'orderby' => 'nicename',
) );
$statuses = get_pud_statuses();
$visibilities = get_pud_visibilities();
$pud_page_type = 'post';
$pud_page_title = '';
$pud_content = "";
$pud_page_excerpt = "";
$pud_name = "";
$placeholders = [];
$names = [];
$id = '';
if(isset($_GET['type']) && isset($_GET['id']))
{
  $type = $_GET['type'];
  $id = $_GET['id'];
  if($type == 'edit')
  {
    $generator = $wpdb->get_row("SELECT * FROM ".PUD_GENERATOR_TABLE." where id='".$id."'" ); 
    $pud_name = $generator->name;
    $pud_page_title = esc_html(addslashes($generator->page_title));
    $pud_content = $generator->page_content;
    $pud_default_author = intval($generator->author_id);
    $pud_page_status = $generator->post_status; 
    $pud_page_excerpt = esc_html(addslashes($generator->page_excerpt)); 
    $pud_page_visibility = $generator->visibility; 
    $pud_max_page = intval($generator->max_page); 
    $pud_page_type =  $generator->post_type; 
    $placeholders_data = $wpdb->get_results(" SELECT p.* FROM ".PUD_PLACEHOLDER_TABLE." p LEFT JOIN ".PUD_GENERATOR_RELATION." gp ON p.id = gp.`placeholder_id` WHERE gp.`generator_id` = '".$id."'" ); 
    if(is_array($placeholders_data)){
        foreach ($placeholders_data as $key => $value) {
            $placeholders[] = $value->placeholder;
            $tags = $value->tags;
            $childs = [];
            $tags = explode("|", $tags);
            if(is_array($tags))
            {
              foreach ($tags as $key2 => $value2) {
                 $childs[$key2]['tag'] = $value2;
              }
            }            
            $names[$key]['id'] = $value->id;
            $names[$key]['name'] = esc_html($value->name);
            $names[$key]['placeholder'] = $value->placeholder;
            $names[$key]['show_box'] = false;
            $names[$key]['child'] = $childs;
        }
    }  
  }
  else if($type == 'page' || $type == 'post')
  {
    $post   = get_post( $id );
    if(!empty($post))
    {
      $pud_name = $pud_page_title = esc_html(addslashes($post->post_title));
      $pud_content = $post->post_content;
      $pud_default_author = intval($post->post_author);
      $pud_page_status = $post->post_status; 
      $pud_page_excerpt = esc_html(addslashes($post->post_excerpt)); 
    }
    $pud_page_type = $type;
  }
}
?>
<div class="pud-grid" ng-app="myApp" ng-controller="myCtrl"  id="MainPudWrap">
	<form method="post" id="save_generator" autocomplete="off" >
	<?php
		echo alert_message();
		?>  	
    <div class="page_combination" ng-if="page_combination != '' " ng-bind-html="page_combination" ></div>
		<p>
      &nbsp;<a href="#" class="button pud-push-right  save_generator mrg_right"    ng-click="save_generator('pending')" ><i class="fa fa-save"></i>&nbsp;<?php echo  __( 'Save' , 'pud_generator'); ?></a>
      <?php if($type =='edit') { ?>
        &nbsp;<a href="?page=pud_manage" class="button pud-push-right mrg_right"  ><i class="fa fa-backward"></i>&nbsp;<?php echo  __( 'Back' , 'pud_generator'); ?></a></p>
      <?php } ?>
      </p>
  		<div class="clear-both" >&nbsp;</div>
 		<input type="hidden" name="action" value="save_generator" />
  <div class="pud-col-8-12 pud-segment "> 
  	<table class='form-table pud-table'> 
                <tr valign='top'>
                    <th scope='row'><?php echo __( 'Name', 'pud_generator' ); ?></th>
                    <td>
                        <input type="text" class="pud-form-control" value=""  name="pud_name" id="pud_name" 
                        ng-model="pud_name"
                        /> 
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'><?php echo __( 'Page Title', 'pud_generator' ); ?></th>
                    <td>
                        <input type="text" class="pud-form-control-dyn" value=""  name="pud_page_title" id="pud_page_title" 
                        ng-model="pud_page_title"
                        />
                        <a href="#"  class="button" ng-click="addTitleBox()"  ><i class="fa fa-plus"></i>&nbsp;<?php echo  __( 'Add' , 'pud_generator'); ?></a>
                    </td>
                </tr>     
                <tr valign='top'>
                    <th scope='row'><?php echo __( 'Type', 'pud_generator' ); ?></th>
                    <td>
                        <select name="pud_page_type" size="1" id="pud_page_type" class="pud-form-control-dyn" ng-model="pud_page_type" > 
                        <option value="post" >
                          <?php echo  __( 'Post' , 'pud_generator'); ?>
                        </option>
                        <option value="page" >
                          <?php echo  __( 'Page' , 'pud_generator'); ?>
                        </option>
                      </select>
                    </td>
                </tr>               
                  
                 
      <tr valign='top'>
      <td colspan="2">
      		<?php 
          $editor_id = "pud_content";
$settings = array(
    'wpautop' => true,
    'media_buttons' => true,
    'textarea_name' => $editor_id,
    'textarea_rows' => 20,
    'tabindex' => '',
    'tabfocus_elements' => ':prev,:next', 
    'editor_css' => '', 
    'editor_class' => '',
    'teeny' => false,
    'dfw' => false,
    'tinymce' => false, // <-----
    'quicktags' => true
);
          wp_editor( $pud_content , $editor_id, $settings ); ?> 
      </td>            	
    </tr>
</table>
  	
  </div>
   <div class="pud-col-4-12 pud-segment ">
   		<div class="clear-both">
 			<a href="#"  class="button" ng-click="addBox()"  ><i class="fa fa-plus"></i>&nbsp;<?php echo  __( 'Add Content Placeholder' , 'pud_generator'); ?></a>
   			<a href="#ex1" rel="modal:open" class="button"  ><i class="fa fa-database"></i>&nbsp;<?php echo  __( 'Select Existing' , 'pud_generator'); ?></a>
   		</div>
   		<ul class="accordion">
		  <li ng-repeat="x in names" >
		  	<div class="top-accordion">
		  		<a href="#" class="title-accordion" >{{ x.name | limitTo: 60 }}{{x.name.length > 60 ? '...' : ''}} [<span class="placeholder-title"> {${{ x.placeholder }}} </span>]&nbsp;<span class="placeholder-title" ng-if="x.child.length > 0">( {{x.child.length}} )</a>
			  	<a href="#" class="tooltip" data-tooltip="<?php echo  __( 'Put Placeholder' , 'pud_generator'); ?>" ng-click="putPlaceHolder(x)" ><i class="fa fa-mouse-pointer"></i></a>
			  	<a href="#"  ng-click="addCloneBox(x)" class="tooltip" data-tooltip="<?php echo  __( 'Clone' , 'pud_generator'); ?>" ><i class="fa fa-clone"></i></a>
			  	<a href="#" class="tooltip" data-tooltip="<?php echo  __( 'Ignore' , 'pud_generator'); ?>" ng-click="removeBox(x)"  ><i class="fa fa-close"></i></a> 
			  	<a href="#" class="add_more_text tooltip" data-tooltip="<?php echo  __( 'Add Text' , 'pud_generator'); ?>" ng-click="addChildBox(x, x.child)"  ><i class="fa fa-plus"></i></a> 
			    <a class="toggle tooltip toggle_open" ng-if="!x.show_box" href="#"  data-tooltip="<?php echo  __( 'Open' , 'pud_generator'); ?>" ng-click="showChildBox(x)" ><i class="fa fa-sort-down"></i></a><a class="toggle tooltip toggle_close" href="#"  data-tooltip="<?php echo  __( 'Close' , 'pud_generator'); ?>" ng-click="hideChildBox(x)" ng-if="x.show_box" ><i class="fa fa-sort-up"></i></a>
			</div>
		    <ul class="inner" ng-if="x.show_box" >
		      <li ng-if="x.child.length == 0" ><?php echo  __( 'Opps! No text available .Click here to' , 'pud_generator'); ?><a href="#" ng-click="addChildBox(x, x.child)"><?php echo  __( 'Add' , 'pud_generator'); ?></a></li>		
		      <li ng-repeat="x2 in x.child track by $id($index)"  >
		      	<input type="text" id="p_scnt" size="20" name="p_scnt" value="{{x2.tag}}" placeholder="<?php echo  __( 'Input Value' , 'pud_generator'); ?>" class="pud-form-control-dyn" ng-model="x2.tag" ng-keypress="enterPress($event, x, x.child)" />
		      	<a href="#" class="remove-text" ng-click="removeChildBox($index, $parent.$index)" ><i class="fa fa-times"></i></a>
		      </li>		       
		    </ul>
		  </li> 
 	</ul>
 <div class="clear-both"></div>  
   	 <table class='form-table pud-table pud-top-margin'>  
     <tr valign='top'>
                    <th scope='row' colspan="2"><?php echo __( 'Page Excerpt', 'pud_generator' ); ?> 
                        <input type="text" class="pud-form-control" value=""  name="pud_page_excerpt" id="pud_page_excerpt" 
                        ng-model="pud_page_excerpt"
                        />
                        <a href="#"  class="button" ng-click="addExcerptBox()"  ><i class="fa fa-plus"></i>&nbsp;Add</a>
                    </td>
                </tr>  

                <tr valign='top'>
                    <th scope='row'><?php echo __( 'Max Page Generate', 'pud_generator' ); ?></th>
                    <td>
                        <input type="number" class="pud-form-control" value=""  min="1" step="1" ng-model="pud_max_page"
                        	name="pud_max_page">
                    </td>
                </tr>  
                <tr valign='top'>
                    <th scope='row'><?php echo __( 'Author' , 'pud_generator'); ?></th>
                    <td>
                        <select name="pud_default_author" size="1" id="pud_default_author" class="pud-form-control" ng-model="pud_default_author" >
							<?php
							if ( $authors && count( $authors ) > 0 ) {
				        		foreach ( $authors as $author ) {
				        			?>
				        			<option value="<?php echo $author->ID; ?>">
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
                    <th scope='row'><?php echo __( 'Status', 'pud_generator' ); ?></th>
                    <td>
                        <select name="pud_page_status" size="1"  class="pud-form-control" ng-model="pud_page_status" >
						<?php
						if ( is_array( $statuses ) && count( $statuses ) > 0 ) {
							foreach ( $statuses as $status => $label ) {
								?>
								<option value="<?php echo $status; ?>">
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
                    <th scope='row'><?php echo __( 'Visibility' , 'pud_generator'); ?></th>
                    <td>
                        <select name="pud_page_visibility" size="1"  class="pud-form-control" ng-model="pud_page_visibility" >
						<?php
						if ( is_array( $visibilities ) && count( $visibilities ) > 0 ) {
							foreach ( $visibilities as $visibility => $label ) {
								?>
								<option value="<?php echo $visibility; ?>">
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
  </div>
  </form>
</div>
<div id="ex1" class="modal">
  <p><strong><?php echo  __( 'Select placeholder from your list:' , 'pud_generator'); ?></strong></p>
  <table id="filter-placeholders" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
        	<th><?php echo  __( 'Id'); ?></th>
            <th><?php echo  __( 'Name'); ?></th>
            <th><?php echo  __( 'Placeholder'); ?></th>
            <th><?php echo  __( 'Tags'); ?></th>
            <th><?php echo  __( 'Action'); ?></th>
        </tr>
    </thead> 
</table>
</div>
<script>
var ContentData = '';
var TitleData = '';
var ExcerptData = '';
var Clone = 1;
var SelectBox = '';
jQuery(document).ready(function() {
	jQuery('#pud_content').bind('updateInfo keyup mousedown mousemove mouseup', function(event) {
		var range = jQuery(this).textrange();
		ContentData = range;
    SelectBox = 'pud_content';
	});
	jQuery('#pud_page_title').bind('updateInfo keyup mousedown mousemove mouseup', function(event) {
		var range = jQuery(this).textrange();
		TitleData = range;
    SelectBox = 'pud_page_title';
	});	
  jQuery('#pud_page_excerpt').bind('updateInfo keyup mousedown mousemove mouseup', function(event) {
    var range = jQuery(this).textrange();
    ExcerptData = range;
    SelectBox = 'pud_page_excerpt';
  }); 
  jQuery('#MainPudWrap').on( 'click', 'a[href="#"]', function (event){
      event.preventDefault();
   } );
});
var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope, $http, $interval, $sce) {
    $scope.page_combination = '';
    $scope.pud_id = '<?php echo $id;?>';
    $scope.pud_type = '<?php echo $type;?>';
    $scope.pud_page_type = '<?php echo $pud_page_type;?>';
    $scope.pud_page_title = '<?php echo $pud_page_title;?>';
    $scope.pud_page_excerpt = '<?php echo $pud_page_excerpt;?>';
    $scope.pud_max_page = <?php echo $pud_max_page;?>;
    $scope.pud_default_author = '<?php echo $pud_default_author;?>';
    $scope.pud_page_status = '<?php echo $pud_page_status;?>';
    $scope.pud_page_visibility = '<?php echo $pud_page_visibility;?>';
    $scope.pud_content = ''; 
    $scope.pud_name = '<?php echo $pud_name;?>';
    $scope.names = []; 
    $scope.placeholders = [];  
    <?php
    foreach ($names as $key => $value) { ?>
      $scope.names.push(<?php echo json_encode($value); ?>);
    <?php } ?>
    <?php
    foreach ($placeholders as $key => $value) { ?>
      $scope.placeholders.push(<?php echo json_encode($value); ?>);
    <?php } ?>
    $scope.removeChildBox = function( index, idx) {  
  		$scope.names[idx].child.splice(index, 1);   
    }
    $scope.addChildBox = function(x, child) { 
    	x.show_box = true;
    	child.push({'tag': ''}); 
    }
    $scope.addBox = function() {
    	if(ContentData == "")
    	{
    		alert("Please select text from content box.");
    		return false;
    	}
    	else if(typeof ContentData.text == "undefined" || ContentData.text== "")
    	{
    		alert("Please select text from content box.");
    		return false;
    	} 
    	var str = jQuery.trim( ContentData.text );
    	if(str == "")
    	{
    		alert("Please select text from content box.");
    		return false;
    	}
    	$scope.tmp_child = [{'tag': ''}];
    	placeholder = get_placeholder_key(ContentData.text);
      if($scope.placeholders.indexOf(placeholder) !== -1) {
        placeholder = placeholder+"_"+Clone;
        Clone = Clone + 1;
      }
    	name = ContentData.text;
    	$scope.names.push({id:placeholder, name:name, placeholder:placeholder, show_box:true, child: $scope.tmp_child});

      jQuery('#pud_content').textrange('replace', prepare_placeholder_key(placeholder)).trigger('updateInfo');

      $scope.placeholders.push(placeholder);
    }

    $scope.addTitleBox = function() {

    	if(TitleData == "")
    	{
    		alert("Please select text from title box.");
    		return false;
    	}
    	else if(typeof TitleData.text == "undefined" || TitleData.text== "")
    	{
    		alert("Please select text from title box.");
    		return false;
    	}
    	var str = jQuery.trim( TitleData.text );
    	if(str == "")
    	{
    		alert("Please select text from title box.");
    		return false;
    	}
    	$scope.tmp_child = [{'tag': ''}];
    	placeholder = get_placeholder_key(TitleData.text);
      if($scope.placeholders.indexOf(placeholder) !== -1) {
        placeholder = placeholder+"_"+Clone;
        Clone = Clone + 1;
      }
    	name = TitleData.text;
    	$scope.names.push({id:placeholder, name:name, placeholder:placeholder, show_box:true, child: $scope.tmp_child});

      jQuery('#pud_page_title').textrange('replace', prepare_placeholder_key(placeholder)).trigger('updateInfo');

      $scope.placeholders.push(placeholder);
    }

    $scope.addExcerptBox = function() {

      if(ExcerptData == "")
      {
        alert("Please select text from excerpt box.");
        return false;
      }
      else if(typeof ExcerptData.text == "undefined" || ExcerptData.text== "")
      {
        alert("Please select text from excerpt box.");
        return false;
      }
      var str = jQuery.trim( ExcerptData.text );
      if(str == "")
      {
        alert("Please select text from excerpt box.");
        return false;
      }
      $scope.tmp_child = [{'tag': ''}];
      placeholder = get_placeholder_key(ExcerptData.text);
      if($scope.placeholders.indexOf(placeholder) !== -1) {
        placeholder = placeholder+"_"+Clone;
        Clone = Clone + 1;
      }
      name = ExcerptData.text;
      $scope.names.push({id:placeholder, name:name, placeholder:placeholder, show_box:true, child: $scope.tmp_child});
       jQuery('#pud_page_excerpt').textrange('replace', prepare_placeholder_key(placeholder)).trigger('updateInfo');
 
      $scope.placeholders.push(placeholder);
    }

    $scope.addExistingBox = function(id, name, placeholder, tags) { 
       $scope.tmp_child = [];
       var str_array = tags.split('|');
  	   for(var i = 0; i < str_array.length; i++) {
  			var v = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
  			if(v == "")
  			{
  				continue;
  			}
        str_array[i] = decodeEntities(str_array[i]);
  		   $scope.tmp_child.push({tag:str_array[i]});
  	   }
       if($scope.placeholders.indexOf(placeholder) !== -1) {
          alert("Placeholder already exists.");
          return false;
       }

       $scope.names.push({id:id, name:name, placeholder:placeholder, show_box:true, child: $scope.tmp_child});
       $scope.placeholders.push(placeholder);
    }

    $scope.removeBox = function(item) { 
    	if(item.child.length > 0)
    	{
        var ask_confirm = 0;
        for (var i = 0;i<=item.child.length - 1; i++) {
          if(item.child[i].tag != '')
          {
            var ask_confirm = 1;
            break;
          }
        } 
    		if (ask_confirm) {
          if(confirm("Do you really want to remove it?"))
            $scope.removeMainElement(item); 
		    }
        else
        {
          $scope.removeMainElement(item);
        }
    	}
    	else
    	{
        $scope.removeMainElement(item);
    	}    	
    }

    $scope.removeMainElement = function(item) { 
        var content = jQuery('#pud_content').val(); 
        var find = prepare_placeholder_key(item.placeholder);
        var n = content.indexOf(find);
        if(n !== -1)
        {
          if(confirm("Placeholder is exist in the content box, Would like to redo with actual content?"))
          { 
              str = replaceAll(find, item.name, content);
              jQuery('#pud_content').val(str);
          } 
        }
        var index = $scope.placeholders.indexOf(item.placeholder);
        $scope.placeholders.splice(index, 1); 
        var index = $scope.names.indexOf(item);
        $scope.names.splice(index, 1);
    }



    $scope.showChildBox = function(x) { 
       x.show_box = true;
    }

    $scope.hideChildBox = function(x) { 
       x.show_box = false;
    }

    $scope.addCloneBox = function(x) {
        if(x.name == "" || x.placeholder == "")
        {
          alert("Title is empty for selected box.");
          return false;
        } 
        var str = jQuery.trim( x.name );
        if(str == "")
        {
          alert("Title is empty for selected box.");
          return false;
        }
        var str = jQuery.trim( x.placeholder );
        if(str == "")
        {
          alert("Title is empty for selected box.");
          return false;
        }
        $scope.tmp_child = [];
        for (var i = 0;i<=x.child.length - 1; i++) {
           $scope.tmp_child.push({tag:x.child[i].tag});
        }        
        placeholder = x.placeholder+"_"+Clone;
        name = x.name;
        Clone = Clone + 1;
        if($scope.placeholders.indexOf(placeholder) !== -1) {
          placeholder = placeholder+"_"+Clone;
          Clone = Clone + 1;
        }
        $scope.names.push({id:placeholder, name:name, placeholder:placeholder, show_box:true, child: $scope.tmp_child});
        $scope.placeholders.push(placeholder);
    }

    $scope.putPlaceHolder = function(x) {
        if(x.name == "" || x.placeholder == "")
        {
          alert("Placeholder is empty for selected box.");
          return false;
        } 
        var str = jQuery.trim( x.name );
        if(str == "")
        {
          alert("Placeholder is empty for selected box.");
          return false;
        }
        var str = jQuery.trim( x.placeholder );
        if(str == "")
        {
          alert("Placeholder is empty for selected box.");
          return false;
        }
        if(SelectBox == 'pud_page_title')
        { 
            jQuery('#pud_page_title').textrange('replace', prepare_placeholder_key(x.placeholder)).trigger('updateInfo');
        } 
        else if(SelectBox == 'pud_page_excerpt')
        { 
            jQuery('#pud_page_excerpt').textrange('replace', prepare_placeholder_key(x.placeholder)).trigger('updateInfo');
        }
        else 
        { 
            jQuery('#pud_content').textrange('replace', prepare_placeholder_key(x.placeholder)).trigger('updateInfo');
        }
    }


    $scope.enterPress = function(keyEvent, x, child) {
       if (keyEvent.which === 13)
        $scope.addChildBox(x, child);
    } 

    $scope.save_generator = function (generator_status) {
       show_loading_bar();
 
        $scope.pud_content = jQuery('#pud_content').val(); 
        $scope.pud_page_title = jQuery('#pud_page_title').val();
        $scope.pud_page_excerpt = jQuery('#pud_page_excerpt').val();

        var data = {
          pud_type: $scope.pud_type,
          pud_id: $scope.pud_id,
          pud_name: $scope.pud_name,
          pud_page_type: $scope.pud_page_type,
          pud_page_title: $scope.pud_page_title,
          pud_page_excerpt: $scope.pud_page_excerpt,
          pud_default_author: $scope.pud_default_author,
          pud_max_page: $scope.pud_max_page,
          pud_page_status: $scope.pud_page_status,
          pud_page_visibility: $scope.pud_page_visibility,
          pud_content: $scope.pud_content,
          names: $scope.names,
          generator_status:generator_status
        };
        var postData = JSON.stringify(data);
        $http({
          method : 'POST',
          url : ajaxurl+"?action=save_generator",
          data: postData,
          headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

        }).success(function(res){
          console.log(res);
          hide_loading_bar()
          if(res.error == 1)
          {
            alert(res.message);
          }
          else
          {
            alert(res.message);
            window.location.href='?page=pud_manage';            
          }
        }).error(function(error){
            hide_loading_bar()
            alert("Error while processing your request, please try again.");
        });
    };
     function calculate_page_combination () {
        $scope.pud_content = jQuery('#pud_content').val(); 
        var data = {
          pud_page_title: $scope.pud_page_title,
          pud_page_excerpt: $scope.pud_page_excerpt,
          pud_content: $scope.pud_content,
          names: $scope.names, 
        };
        var postData = JSON.stringify(data);
        $http({
          method : 'POST',
          url : ajaxurl+"?action=calculate_page_combination",
          data: postData,
          headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

        }).success(function(res){
          $scope.page_combination = $sce.trustAsHtml(res.message);
        }).error(function(error){
           
        });
    };
    var promise;
    $scope.start = function() {
      $scope.stop(); 
      promise = $interval(calculate_page_combination, 5000);
    };
    $scope.stop = function() {
      $interval.cancel(promise);
    };
    $scope.start();
    $scope.$on('$destroy', function() {
      $scope.stop();
    });
});
</script> 
<div id="ex4" class="modal">
  <h2 class="pud-form-title" ><?php echo  __( 'Follow below steps to create new Generator:', 'pud_generator'); ?></h2>
  <div class="pud-col-11-12"> 
    <ul class="pud-help-doc">
      <li>
        Enter the title and content into the specific input box.
      </li>
       <li>
        Select the content that you want to get replaced with the placeholder.
      </li>
       <li>
        The plugin will create a placeholder for you. You just need to provide multiple strings in the placeholder that get replaced.
      </li>
       <li>
        The plugin will show you how many pages/post will get generate based on the number of placeholder in content.
      </li>
       <li>
        You can control a number of page generation from Max page input.
      </li>
       <li>
        If you already have placeholder then select it from "Select Existing" button.
      </li>
      <li>
        Placeholder generated for Title, Content & Excerpt. Three different Add placeholder buttons provided for Title, Content & Excerpt.
      </li>
      <li>
       You can create a generator for existing post and page, please see below video.
      </li>
    </ul>
    <b>Video:</b> <a href="https://www.youtube.com/embed/_q5b3_O1CaY" target="_blank">How To Create New Generator</a>
    <br><br>
  </div>
</div>
<?php
$field = 'pud_tour_generator';
$pud_tour_generator = get_option( $field );
if ( $pud_tour_generator == 1) {
    update_option( $field, 0);
?>
<script>
jQuery('#ex4').modal();
</script> 
<?php
}
?>