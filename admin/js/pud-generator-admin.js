jQuery(document).ready(function() {
	var table2 = jQuery('#list-generator').DataTable({
		"aaSorting": [[0, 'desc']],
        "ajax": ajaxurl+"?action=load_generator",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": '<a href="#" data-tooltip="Start Generation" class="clone_generator clickable tooltip" data-action="generate" ><i class="fa fa-clone"  ></i></a>&nbsp;<a href="#" data-tooltip="Edit Generator" class="edit_generator clickable tooltip" data-action="edit" ><i class="fa fa-edit"  ></i></a>&nbsp;<a href="#" data-tooltip="Delete Generator" class="delete_generator tooltip clickable" data-action="delete" ><i class="fa fa-trash"></i></a>'
        }
        ],
        "drawCallback": function( settings ) {
	        jQuery('.tooltip').darkTooltip();
	    }
    });
	var table1 = jQuery('#list-placeholders').DataTable({
		"aaSorting": [[0, 'desc']],
        "ajax": ajaxurl+"?action=load_placeholder",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": '<a href="#" class="edit_placeholder clickable tooltip" data-action="edit"  data-tooltip="Edit Placeholder"  ><i class="fa fa-edit"  ></i></a>&nbsp;<a href="#" class="delete_placeholder clickable tooltip"  data-tooltip="Delete Placeholder"  data-action="delete" ><i class="fa fa-trash"></i></a>'
        }
        ],
        "drawCallback": function( settings ) {
	        jQuery('.tooltip').darkTooltip();
	    }
    });

    var table3 = jQuery('#filter-placeholders').DataTable({
    	"aaSorting": [[0, 'desc']],
        "ajax": ajaxurl+"?action=load_placeholder",
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": '<a href="#" class="select_placeholder clickable tooltip" data-action="edit"  data-tooltip="Select Placeholder"  ><i class="fa fa-check-circle"  ></i>&nbsp;Select</a>'
        }
        ],
        "drawCallback": function( settings ) {
	        jQuery('.tooltip').darkTooltip();
	    }
    });
    jQuery("#editplaceholder").submit(function(e){ 
    	 show_loading_bar();
	     e.preventDefault();
	     var input_data = jQuery(this).serialize();                 
	     jQuery.ajax({
	       type: "POST",
	       url: ajaxurl,
	       dataType: "json",
	       data: input_data,
	       success: function(alrt){
	       		hide_loading_bar()
	            if(alrt.error == 1)
	            {
	            	alert(alrt.message);
	            }
	            else
	            {
	            	table1.ajax.reload();
	            	jQuery(".pud-alert").show();
	            	jQuery(".pud-alert-msg").html(alrt.message);
	            	jQuery("#editplaceholder").find("input[type=text], textarea").val("");
	            	jQuery.modal.close();
	            }
	  	   },
	  	   error: function(alrt){
	  	   		hide_loading_bar()
	            alert("Error while processing your request, please try again.")
	  	   }
	  });
	});
    jQuery("#addplaceholder").submit(function(e){ 
    	 show_loading_bar();
	     e.preventDefault();
	     var input_data = jQuery(this).serialize();                 
	     jQuery.ajax({
	       type: "POST",
	       url: ajaxurl,
	       dataType: "json",
	       data: input_data,
	       success: function(alrt){
	       		hide_loading_bar()
	            if(alrt.error == 1)
	            {
	            	alert(alrt.message);
	            }
	            else
	            {
	            	table1.ajax.reload();
	            	jQuery(".pud-alert").show();
	            	jQuery(".pud-alert-msg").html(alrt.message);
	            	jQuery("#addplaceholder").find("input[type=text], textarea").val("");
	            	jQuery.modal.close();
	            }
	  	   },
	  	   error: function(alrt){
	  	   		hide_loading_bar()
	            alert("Error while processing your request, please try again.")
	  	   }
	  });
	});	
	jQuery('.refresh_generator').click(function(e){ 
	 	table2.ajax.reload();	   
	});
	jQuery('.refresh_placeholder').click(function(e){ 
	 	table1.ajax.reload();	   
	});
    jQuery('#list-placeholders tbody').on( 'click', '.clickable', function (event){
	 		var data = table1.row( jQuery(this).parents('tr') ).data();
			event.preventDefault();
			this.blur(); 
	 		action = jQuery(this).attr("data-action");
	 		if(action == 'delete')
	 		{
	 			var r = confirm("Do you really want to delete?");
				if (r == true) {
				   delete_placeholder(data[0]);
				} 
	 		}
	 		else
	 		{
	 			jQuery("#edit_id").val(data[0]);
	 			jQuery("#edit_name").val(data[1]);
	 			jQuery("#edit_placeholder").val(data[2]);
	 			var str = data[3];
				var str_array = str.split('|');
				var str_html = "";
				for(var i = 0; i < str_array.length; i++) {
				   str_html = str_html+'<div class="con"><input name="tags[]" value="'+str_array[i]+'" type="text" id="tags" class="pud-form-control-dyn" /><a class="btnRemove" href="javascript:void(0);" ><i class="fa fa-close"></i></a></div>';
				}
	 			jQuery(".edit-tag-section").html(str_html); 
				jQuery("#ex2").modal();	
	 		}	  
	});

    jQuery('#filter-placeholders tbody').on( 'click', '.clickable', function (event){
	 		var data = table3.row( jQuery(this).parents('tr') ).data();
			event.preventDefault();
			this.blur(); 
	 		action = jQuery(this).attr("data-action");
			jQuery.modal.close();			
		    var scope = angular.element(document.getElementById("MainPudWrap")).scope();
		    scope.$apply(function () {
		    	scope.addExistingBox(data[0], data[1], data[2], data[3]);
		    });
	});

	jQuery('#list-generator tbody').on( 'click', '.clickable', function (event){
	 		var data = table2.row( jQuery(this).parents('tr') ).data();
			event.preventDefault();
			this.blur(); 
	 		action = jQuery(this).attr("data-action");
	 		if(action == 'delete')
	 		{
	 			var r = confirm("Do you really want to delete?");
				if (r == true) {
				   delete_generator(data[0]);
				} 
	 		}
	 		else if(action == 'edit')
	 		{
	 			 window.location.href='?page=pud_generator&type=edit&id='+data[0];
	 		}
	 		else if(action == 'generate')
	 		{
	 			var r = confirm("Do you really want to start generation process? If yes then don't referesh the page untile process get completed");
				if (r == true) {
				   start_generator(data[0]);
				   jQuery("#log-generator-modal").modal();
				   jQuery("#log-generator-section").html('<div id="log-loading-bar" >Loading....</div><div id="log-generator-row" ></div>');
				   
				   setTimeout(function(){ load_generator_log(data[0], 0, 1); }, 2000);
				}
	 		}	  
	});
	function start_generator(id)
	{
		//show_loading_bar(); 
		input_data = {id:id, action:"start_generator"};
		jQuery.ajax({
	       type: "POST",
	       url: ajaxurl,
	       dataType: "json",
	       data: input_data,
	       success: function(alrt){
	       		//hide_loading_bar()
	            if(alrt.error == 1)
	            {
	            	alert(alrt.message);
	            }
	            else
	            {
	            	table2.ajax.reload();
	            	jQuery(".pud-alert").show();
	            	jQuery(".pud-alert-msg").html(alrt.message);           	
	            }
	  	   },
	  	   error: function(alrt){
	  	   		//hide_loading_bar()
	           // alert("Error while processing your request, please try again.")
	  	   }
	  });	
	}
	function delete_generator(id)
	{
		show_loading_bar(); 
		input_data = {id:id, action:"delete_generator"};
		jQuery.ajax({
	       type: "POST",
	       url: ajaxurl,
	       dataType: "json",
	       data: input_data,
	       success: function(alrt){
	       		hide_loading_bar()
	            if(alrt.error == 1)
	            {
	            	alert(alrt.message);
	            }
	            else
	            {
	            	table2.ajax.reload();
	            	jQuery(".pud-alert").show();
	            	jQuery(".pud-alert-msg").html(alrt.message);           	
	            }
	  	   },
	  	   error: function(alrt){
	  	   		hide_loading_bar()
	            alert("Error while processing your request, please try again.")
	  	   }
	  });	
	}

	function delete_placeholder(id)
	{
		show_loading_bar(); 
		input_data = {id:id, action:"delete_placeholder"};
		jQuery.ajax({
	       type: "POST",
	       url: ajaxurl,
	       dataType: "json",
	       data: input_data,
	       success: function(alrt){
	       		hide_loading_bar()
	            if(alrt.error == 1)
	            {
	            	alert(alrt.message);
	            }
	            else
	            {
	            	table1.ajax.reload();
	            	jQuery(".pud-alert").show();
	            	jQuery(".pud-alert-msg").html(alrt.message);           	
	            }
	  	   },
	  	   error: function(alrt){
	  	   		hide_loading_bar()
	            alert("Error while processing your request, please try again.")
	  	   }
	  });	
	}	 

	// Open modal in AJAX callback
	jQuery('#list-generator tbody').on( 'click', '.generator_log', function (event){
		var data = table2.row( jQuery(this).parents('tr') ).data();
		event.preventDefault();
		this.blur(); 
	    jQuery("#log-generator-modal").modal();
	    jQuery("#log-generator-section").html('<div id="log-loading-bar" >Loading....</div><div id="log-generator-row" ></div>');
	    load_generator_log(data[0], 0, 1);
	});

	function load_generator_log(id, last_id, page)
	{
		//show_loading_bar(); 
		input_data = {id:id, action:"load_generator_log", last_id:last_id, page:page};
		jQuery.ajax({
	       type: "POST",
	       url: ajaxurl,
	       dataType: "json",
	       data: input_data,
	       success: function(alrt){
	       		//hide_loading_bar();
	       		jQuery("#log-loading-bar").hide();
	            if(alrt.error == 0)
	            { 
	            	jQuery("#log-generator-row").append(alrt.message);
	            }
	            last_id = alrt.last_id;
	         	if(alrt.load_more && jQuery('#log-generator-modal').is(':visible'))
	         	{
	         		setTimeout(function(){ load_generator_log(id, last_id, page+1); }, 2000);	
	         	}	            
	         	if(page > 1 && alrt.message != '')
	         	{
	         		jQuery("#pud-no-records").hide();
	         	}
	         	jQuery("time.timeago").timeago();
	  	   },
	  	   error: function(alrt){
	  	   		//hide_loading_bar()
	           // alert("Error while processing your request, please try again.")
	  	   }
	  });	
	}
	jQuery("time.timeago").timeago();
	jQuery('.tooltip').darkTooltip();
});
function show_loading_bar()
{
	if(jQuery(".pud-loader").length)
	{
		jQuery(".pud-alert").hide();
    	jQuery(".pud-loader").css("display", "block");
	}
}
function hide_loading_bar()
{
	if(jQuery(".pud-loader").length)
	{
    	jQuery(".pud-loader").css("display", "none");
	}
}

function create_unique_key(name, placeholder)
{
	text = jQuery("#"+name).val();
	text = get_placeholder_key(text);
	jQuery("#"+placeholder).val(text);
}

function get_placeholder_key(text)
{
	text = jQuery.trim(text.toLowerCase());
	text = text.replace(/[^a-zA-Z0-9\- ]/g, "")
	text = text.replace(/ /g, "-");
	text = text.substr(0, 30);
	return text;
}

function prepare_placeholder_key(placeholder)
{
	text = "{$"+placeholder+"}"; 
	return text;
}
function replaceAll(find, replace,str) { 
  str = str.split(find).join(replace);
  return str; 
}
function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}