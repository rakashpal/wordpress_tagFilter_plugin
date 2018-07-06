jQuery(document).ready(function(){
	var color = jQuery(".tag_filter_section li a").css("background-color");
	var hover=jQuery(".tag_filter_section li a").data('hover');
		jQuery( ".tag_filter_section li a" )
  .mouseover(function() {
   jQuery(this).css({ background: hover });
  })
  .mouseout(function() {
  jQuery(this).css({ background: color });

  });
	 
	jQuery(document).on('click','.tag_filter_section li a',function(e){
		e.preventDefault();
		var tag_id=jQuery(this).data('id');

 
		var ajaxurl=jQuery(this).data('ajaxurl');
		jQuery('.tag_filter_section li a').removeClass('activate');
		jQuery(this).addClass('activate');
	
		//alert(ajaxurl);
		var data = {
            'action': 'get_tag_post',
            'key': tag_id
        };
        
         jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
            jQuery('.update-section .update-list').html(response);
        });
	});
	
	
	
	
	
	jQuery(document).on('keyup','.srch00',function(){
		   var tag_id=jQuery('.tag_filter_section li a.activate').data('id');
		   var search_text=jQuery(this).val();
		  if(tag_id==undefined){
		  	tag_id='all';
		  }
		  var data = {
            'action': 'get_post_by_search',
            'key': tag_id,
            'search_text':search_text
        };
		  
		   jQuery.post(ajax_url, data, function(response) {
            console.log(response);
            jQuery('.update-section .update-list').html(response);
        });
			});
			
			
			
			jQuery(document).on('click','#inlineCheckbox1',function(){
			
			
				show_all_tags(jQuery(this).is(":checked"));
			});
			
			function show_all_tags(status){
				if(status==true){
					status=1
				}else{
			    status=0;
				}
		      var data = {
            'action': 'show_all_tags',
            'show':status
        };
		  
		   jQuery.post(ajax_url, data, function(response) {
            console.log(response);
            jQuery('.tag_filter_section').html(response);
        });
			}
			
			
			
		jQuery(document).on('click','#radio1,#radio2,#radio3',function(){
			  sortby=jQuery(this).data('id');
			  ids = jQuery(".tag_filter_section li a").map(function () {
			        return this.id;
			    }).get();
			    //alert(ids);
			  textcolor=jQuery(this).data('textcolor');
			  hover=jQuery(this).data('hover');

			  bgcolor=jQuery(this).data('backgroundcolor');
			
			 if(jQuery("#inlineCheckbox1").is(":checked"))
			  {
				sort_by(1,sortby);
			  }else{
			  	sort_by(0,sortby);
			  }
			});
			  function sort_by(status,sort_by){
				
		      var data = {
            'action': 'sort_by',
            'show':status,
            'ids':ids,
            'sort_by':sort_by,
			'textcolor':textcolor,
			'bgcolor':bgcolor,
			'hover':hover
        };
		  
		   jQuery.post(ajax_url, data, function(response) {
            console.log(response);
            jQuery('.tag_filter_section').html(response);
        });
			}
			  
			
			
	
});