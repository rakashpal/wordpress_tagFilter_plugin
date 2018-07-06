<style>
.tag_filter_section li a.activate, .tag_filter_section li a.activate:focus, .tag_filter_section li a:focus{
background:<?php echo $atts['hover_color']." !important";?>
}
</style>

<div class="container-sfilter">
<div class="border-over">
<div class="left-side-bar">
   <span class="srch-tex">Search </span><div class="check-b">      
                        <input id="inlineCheckbox1" value="option1" type="checkbox">
                        <label for="inlineCheckbox1"> Show all tags </label>                    
   </div>
   <input type="text" class="srch00">
   <div class="filter-div">
      <strong>Or filter by tag</strong><br>
	  <span>reset all filters</span>
	  <img class="arow" src="<?php echo plugin_dir_url( __DIR__ )?>templates/rightarrow.png">
   </div>
   <div class="clear"></div> 
   <div class="radio-b">
      <strong>Sort By:</strong>
	   <div class="radio-btn-li"><input type="radio" name="radio" class="sorted_by" data-hover="<?php echo  $atts['hover_color']?>" data-id="count" data-textcolor="<?php echo  $atts['color']?>" data-backgroundcolor="<?php echo $atts['background']?>" id="radio1"><label for="radio1"> Count</label> </div>
	   <div class="radio-btn-li"><input type="radio" name="radio" class="sorted_by" data-id="A" data-textcolor="<?php echo  $atts['color']?>" data-backgroundcolor="<?php echo $atts['background']?>" id="radio2"><label for="radio2"> A-Z</label> </div>
	   <div class="radio-btn-li"><input type="radio" name="radio"  class="sorted_by"  data-id="Z" data-textcolor="<?php echo  $atts['color']?>" data-backgroundcolor="<?php echo $atts['background']?>" id="radio3"><label for="radio3"> Z-A</label> </div>
   </div>
</div>
<div class="right-content">

   <ul class="link-list-5r tag_filter_section">
   <?php 

   if($results):

   foreach($results as $key=> $value):?>
   <li><a  data-border="<?php echo $atts['bordercolor'];?>" id="<?php echo $value->id; ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-id="<?php echo $value->id; ?>" href="javascript:void(0);" style="background:<?php echo $atts['background']?>;color:<?php echo  $atts['color']?>;border-bottom:2px solid <?php echo $atts['bordercolor']?>"><?php echo $value->name; ?></a></li>
   <?php endforeach; endif; ?>
  
   </ul>
   <div class="clear"></div>
   
</div> </div>
<?php


  echo '<section class="update-section">
	   <div class="update-list">';
      if($posts->have_posts()):  
  while($posts->have_posts()):$posts->the_post();
 
   if(self::$temp_path){
	require (self::$temp_path);
   }else{
   	?>
      
         <li>
		  <div class="row">
		     <div class="cols-3">
		     <?php 
		    
		     ?>
			    <img src="<?php if( get_the_post_thumbnail_url('full')){ echo  get_the_post_thumbnail_url('full');}else{ echo plugin_dir_url( __DIR__ ).'images/vga.png';} ?>">
			 </div>
			 <div class="cols-9">
			 
			    <h4><?php the_title(); ?></h4>
				<p><?php echo the_excerpt(); ?>  <a href="<?php the_permalink();?>">Continue...</a></p>

			 </div>
			 </div>
		  </li>
		 
	     <?php

   }
 //  require (''.get_template_directory(). '/template-parts/post/content.php');
  //get_template_part( 'template-parts/post/content', get_post_format() );
   
  endwhile;
  else:
  echo "No post found";
  endif;
  echo '</div></section>';

      
         ?>
         <!--
<section class="update-section">
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
		  
	</section>-->
</div>

	
	

