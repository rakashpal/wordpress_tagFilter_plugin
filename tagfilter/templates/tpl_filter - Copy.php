

<div class="container-sfilter">
<div class="left-side-bar">
   <label>Search</label>
   <input type="text" class="srch00">
   <div class="filter-div">
      <strong>Or filter by tag</strong><br>
	  <span>reset all filters</span>
	  <img class="arow" src="rightarrow.png">
   </div>
   <div class="clear"></div>
   <div class="check-b">      
                        <input id="inlineCheckbox1" value="option1" type="checkbox">
                        <label for="inlineCheckbox1"> Show all tags </label>                    
   </div>
   <div class="radio-b">
      <strong>Sort By:</strong><br>
	   <div class="radio-btn-li"><input type="radio" name="radio" class="sorted_by" data-id="count" id="radio1"><label for="radio1"> Count</label> </div>
	   <div class="radio-btn-li"><input type="radio" name="radio" class="sorted_by" data-id="A" id="radio2"><label for="radio2"> A-Z</label> </div>
	   <div class="radio-btn-li"><input type="radio" name="radio"  class="sorted_by"  data-id="Z"id="radio3"><label for="radio3"> Z-A</label> </div>
   </div>
</div>
<div class="right-content">
   <ul class="link-list-5r tag_filter_section">
   <?php if($results):
   foreach($results as $key=> $value):?>
   <li><a  data-border="<?php echo $value->border_color;?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-id="<?php echo $value->id; ?>" href="javascript:void(0);" style="background:<?php echo $value->background_color;?>;color:<?php echo $value->text_color;?>;border-bottom:2px solid <?php echo $value->border_color;?>"><?php echo $value->name; ?></a></li>
   <?php endforeach; endif; ?>
  
   </ul>
   
</div>
</div>
<div class="clear"></div>
	<section class="update-section">
	<div class="container-sfilter">
	<h2>Blogs</h2>
	   <ul class="update-list">
	    <?php if($allPosts): ?>
         <?php foreach($allPosts as $k=>$vv): ?>
         <li>
		  <div class="row">
		     <div class="cols-3">
		     <?php 
		     $src = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail_size' );
$url = $src[0];
		     ?>
			    <img src="<?php if($url){ echo $url;}else{ echo plugin_dir_url( __DIR__ ).'images/vga.png';} ?>">
			 </div>
			 <div class="cols-9">
			 
			    <h4><?php echo $vv->post_title; ?></h4>
				<p><?php echo $vv->post_content; ?>  <a href="<?php the_permalink($vv->ID);?>">Continue...</a></p>

			 </div>
			 </div>
		  </li>
		  <?php endforeach; ?>
		  <?php endif; ?>
	     
		  
	   </ul>
	</div>	  
	</section>
	

