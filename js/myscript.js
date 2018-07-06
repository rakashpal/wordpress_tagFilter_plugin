jQuery(document).ready(function($){
	
	$(".tags-listing .tag").click(function(e){
		
		var tag_id=$(this).data('id');
		var border_color=$(this).data('bordercolor');
		var text_color=$(this).data('textcolor');
		var background_color=$(this).data('background');
		
		$(".tags-listing .tag").removeClass('selected_tag');
		$(this).addClass('selected_tag');
		
		$('.color_chooser_section').html('<h2 class="format">Select Tags Formatting</h2><div class="col-sec"><ul class="col-set-left hoc"><li>Border Color</li><li>Text Color</li><li>Section Background Color</li></ul><ul class="col-set-right hoc"><li><input type="hidden" name="tag_id" value="'+tag_id+'"><input type="color" name="border_color" value="'+border_color+'"></li><li><input type="color" name="text_color" value="'+text_color+'"></li><li><input type="color" name="background_color" value="'+background_color+'"></li></ul><div class="save-box"><input  type="submit" class="add_tag_btn" name="save" value="SAVE" /></div></div>');
	});
	
	
});