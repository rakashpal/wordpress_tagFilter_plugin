<?php

class TagFilter{
	private static $temp_path='';
	private static $initiated = false;
	 
	/**
	* 
	* 
	* @return
	*/

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
	
	
	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
		add_action( 'wp_enqueue_scripts', array('TagFilter','load_admin_styles' ));
		add_action( 'wp_head', array('TagFilter','load_wp_head_data' ));
		add_shortcode( 'taxonomy_filter',array('TagFilter','taxonomy_filter_func' ));
		add_action('wp_ajax_get_tag_post', array('TagFilter','cvf_getPosts'));
        add_action('wp_ajax_nopriv_get_tag_post', array('TagFilter','cvf_getPosts')); 
        add_action('wp_ajax_get_post_by_search', array('TagFilter','cvf_get_post_by_search'));
        add_action('wp_ajax_nopriv_get_post_by_search', array('TagFilter','cvf_get_post_by_search')); 
        add_action('wp_ajax_show_all_tags', array('TagFilter','cvf_show_all_tags'));
        add_action('wp_ajax_nopriv_show_all_tags', array('TagFilter','cvf_show_all_tags')); 
     
            add_action('wp_ajax_sort_by', array('TagFilter','cvf_sort_by'));
        add_action('wp_ajax_nopriv_sort_by', array('TagFilter','cvf_sort_by'));
	 }
	 /**
	 * 
	 * 
	 * @return
	 */ 
	 public static function cvf_sort_by(){
	  extract($_POST);
	 $ids=$_POST["ids"];	 	
	 	 global $wpdb;
	 	$pivot_table= $wpdb->prefix.'filter_post';
    $table_name = $wpdb->prefix .'fitlertag'; 
      $table_name1 = $wpdb->prefix .'posts'; 
    
      if($sort_by=='A'){
			$S_type='ASC';
			}elseif($sort_by=='Z'){
				$S_type='DESC';
			}	
      if($show){
       	
      $results = $wpdb->get_results( 'SELECT distinct t1.*, ifnull(count(t2.filter_id),0) as total  FROM '.$table_name.' t1  left JOIN '.$pivot_table.' t2 ON t1.id=t2.filter_id  where t1.id  in ('.implode(',',$ids).') group by t1.id order by t1.name '.$S_type);
	 // $results = $wpdb->get_results( 'SELECT  * FROM '.$table_name.' order by name '.$S_type);
      }else{
	  	
	  $results = $wpdb->get_results( 'SELECT distinct t1.*, ifnull(count(t2.filter_id),0) as total  FROM '.$table_name.' t1  left JOIN '.$pivot_table.' t2 ON t1.id=t2.filter_id  where t1.id  in ('.implode(',',$ids).') group by t1.id order by t1.name '.$S_type);
	 
		}
		
		if($results):
		 foreach($results as $key=> $value):?>
   <li><a  data-border="<?php echo $value->border_color;?>" data-hover="<?php echo $_POST["hover"];?>" id="<?php echo $value->id; ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-id="<?php echo $value->id; ?>" href="javascript:void(0);" style="background:<?php echo $_POST["bgcolor"];?>;color:<?php echo $_POST["textcolor"];?>;border-bottom:2px solid <?php echo $value->border_color;?>"><?php echo $value->name;if($sort_by=='count'){ echo "    ".$value->total;} ?></a></li>
   <?php endforeach; endif; 
	  
	  exit(0);
	 }
	 /**
	 * 
	 * 
	 * @return
	 */
	 public static function cvf_show_all_tags()
	 {
	 	extract($_POST);
	 	
	 	 global $wpdb;
	 	$pivot_table= $wpdb->prefix.'filter_post';
    $table_name = $wpdb->prefix .'fitlertag'; 
      $table_name1 = $wpdb->prefix .'posts';  
      if($show){
      		
	  $results = $wpdb->get_results( 'SELECT  * FROM '.$table_name.'');
      }else{
	  	
	  $results = $wpdb->get_results( 'SELECT distinct t1.* FROM '.$table_name.' t1 INNER JOIN '.$pivot_table.' t2 ON t1.id=t2.filter_id');
		}
		if($results):
		 foreach($results as $key=> $value):?>
   <li><a  data-border="<?php echo $value->border_color;?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-id="<?php echo $value->id; ?>" href="javascript:void(0);" style="background:<?php echo $value->background_color;?>;color:<?php echo $value->text_color;?>;border-bottom:2px solid <?php echo $value->border_color;?>"><?php echo $value->name; ?></a></li>
   <?php endforeach; endif; 
	 	exit(0);
	 }
	 
	 /**
	 * 
	 * 
	 * @return
	 */
	 public static function cvf_get_post_by_search(){
	    extract($_POST);
	   global $wpdb;
	 	$pivot_table= $wpdb->prefix.'filter_post';
        $table_name = $wpdb->prefix .'posts'; 
        if($key=='all'){
			 $results = $wpdb->get_results( 'SELECT distinct t1.* FROM '.$table_name.' t1 inner join '.$pivot_table.' t2 on t1.id=t2.post_id where  t1.post_title LIKE "'.$search_text.'%" OR t1.post_title LIKE "%'.$search_text.'" OR t1.post_title LIKE "%'.$search_text.'%"' );
		}else{
			
		
        $results = $wpdb->get_results( 'SELECT distinct t1.* FROM '.$table_name.' t1 inner join '.$pivot_table.' t2 on t1.id=t2.post_id where filter_id='.$key.' AND ( t1.post_title LIKE "'.$search_text.'%" OR t1.post_title LIKE "%'.$search_text.'" OR t1.post_title LIKE "%'.$search_text.'%")' );
		}  
       
		  foreach($results as $custompost) {
 $custompost_ids[] = $custompost->ID;
}

if(empty($custompost_ids)){
 echo "<li>No post Found</li>";
 exit(0);
}
$args = array(
'post_type' => 'post',
'posts_per_page' => -1,
'post__in'=> $custompost_ids

);



$query1 = new WP_Query($args);
  $dir   = get_template_directory();
   
    $allfiles=TagFilter::dirToArray($dir);
	
	

 array_walk_recursive($allfiles, array(__CLASS__,'test_print'));
	
		   
  if($query1->have_posts()):    
  while($query1->have_posts()):$query1->the_post();
    
    if(self::$temp_path){
 
	require (self::$temp_path);
   }else {
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
		endwhile;
		wp_reset_postdata();
		 else:
  echo "No post found";
  endif;
	  exit(0);
	
	
	 }
	 
	 /**
	 * 
	 * 
	 * @return
	 */
	 public static function load_wp_head_data(){
	echo "<script> var ajax_url='".admin_url( 'admin-ajax.php' )."'</script>";
	 }
	/**
	* 
	* 
	* @return
	*/ 
	 public static function cvf_getPosts(){
	 	
	  extract($_POST);
	   global $wpdb,$post;
	 	$pivot_table= $wpdb->prefix.'filter_post';
        $table_name = $wpdb->prefix .'posts'; 
        $results = $wpdb->get_results( 'SELECT * FROM '.$table_name.' t1 inner join '.$pivot_table.' t2 on t1.id=t2.post_id where filter_id='.$key  );
        
         

		  foreach($results as $custompost) {
 $custompost_ids[] = $custompost->ID;
}

if(empty($custompost_ids)){
 echo "<li>No post Found</li>";
 exit(0);
}

$args = array(
'post_type' => 'post',
'posts_per_page' => -1,
'post__in'=> $custompost_ids
);



 $posts = new WP_Query($args);

  $dir   = get_template_directory();
   
    $allfiles=TagFilter::dirToArray($dir);
	
	

 array_walk_recursive($allfiles, array(__CLASS__,'test_print'));
	
		   
        if($posts->have_posts()):    
  while($posts->have_posts()):$posts->the_post();
    
    if(self::$temp_path){
 
	require (self::$temp_path);
   }else {
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
  

		  
        ?>
         <?php /*if($results): ?>
         <?php foreach($results as $k=>$vv): ?>
         <li>
		  <div class="row">
		     <div class="cols-3">
		     <?php 
		     $src = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail_size' );
$url = $src[0];
		     ?>
			    <img src="<?php if($url){ echo $url;}else{ echo plugin_dir_url( __FILE__ ).'images/vga.png';} ?>">
			 </div>
			 <div class="cols-9">
			 
			    <h4><?php echo $vv->post_title; ?></h4>
				<p><?php echo $vv->post_content; ?>  <a href="<?php the_permalink($vv->ID);?>">Continue...</a></p>

			 </div>
			 </div>
		  </li>
		  <?php endforeach; ?>
		  <?php else:
		  echo '<li><div class="row"><h4>No post in this Tag</h4></div></li>';
		  endif; */ ?>
        <?php
        
	  exit(0);
	 }
	 
	  /**
	 * 
	 * 
	 * @return
	 */
	 public static function load_admin_styles(){
		wp_enqueue_style('admin-styles', plugin_dir_url( __FILE__ ).'/css/style.css');
		wp_enqueue_style('admin-styles-font', plugin_dir_url( __FILE__ ).'/css/font-awesome/css/font-awesome.css');
		wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'js/filter_script.js');
		
	 }
	 
	 /**
	 * 
	 * @param undefined $atts
	 * 
	 * @return
	 */
	 public static function taxonomy_filter_func($atts){

	 	$atts = shortcode_atts(
		array(
			'color' => '#ffffff',
			'tags_id' => 'default bar',
			'font_size'=>'15px',
			'hover_color'=>'#000000',
			'background'=> '#000000',
			'bordercolor'=> '#cccccc',
			 'tags'=>'all',
		), $atts, 'taxonomy_filter' );
 		$tags=$atts['tags'];
	 	 global $wpdb;
	 	$pivot_table= $wpdb->prefix.'filter_post';
    $table_name = $wpdb->prefix .'fitlertag'; 
      $table_name1 = $wpdb->prefix .'posts';  
          //$results = $wpdb->get_results( 'SELECT distinct t1.* FROM '.$table_name.' t1 INNER JOIN '.$pivot_table.' t2 ON t1.id=t2.filter_id  WHERE t1.id  IN ('.$tags.')');
$results = $wpdb->get_results( 'SELECT distinct t1.* FROM '.$table_name.' t1 WHERE t1.id  IN ('.$tags.')');
           $allPosts = $wpdb->get_results( 'SELECT distinct t2.* FROM '.$table_name1.' t1 inner join '.$pivot_table.' t2 on t1.id=t2.post_id group by t2.post_id' ,OBJECT  );
		
          foreach($allPosts as $custompost) {
 $custompost_ids[] = $custompost->post_id;
}
//print_r($custompost_ids);
$args = array(
'post_type' => 'post',
'posts_per_page' => -1,
'post__in'=> $custompost_ids
);

 $posts = new WP_Query($args);
  $dir   = get_template_directory();
   
    $allfiles=TagFilter::dirToArray($dir);
	
	

  array_walk_recursive($allfiles, array(__CLASS__,'test_print'));
	
	
	      require( TAGFILTER__PLUGIN_DIR . '/templates/tpl_filter.php' );  
	 }


	public static function test_print($item, $key)
{   
  if($item =='content.php'){
    self::$temp_path= $key  ;
	}
	
}
	 
public static function dirToArray($dir) { 
  
   $result = array(); 

   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
		 
            $result[$dir . DIRECTORY_SEPARATOR . $value] = TagFilter::dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[$dir . DIRECTORY_SEPARATOR . $value] = $value; 
         } 
      } 
   } 
   
   return $result; 
}
	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		if ( version_compare( $GLOBALS['wp_version'], TAGFILTER__MINIMUM_WP_VERSION, '<' ) ) {
			load_plugin_textdomain( 'tagfilter' );
			
			$message = 'This plugin is not compatible with current wordpress version.Please update Current Wordrpess Version';

			TagFilter::bail_on_activation( $message );
		}
		
		global $wpdb;
     $charset_collate = $wpdb->get_charset_collate();
     $table_name = $wpdb->prefix . 'fitlertag';
     $pivot_table= $wpdb->prefix.'filter_post';
      $table_name1 = $wpdb->prefix .'posts';

    #Check to see if the table exists already, if not, then create it

    if($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) 
    {

        $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255)   NOT NULL,
		border_color varchar(10)  NULL,
		text_color varchar(10) NULL,
		background_color varchar(10) NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
    }
		
		if($wpdb->get_var( "show tables like '$pivot_table'" ) != $pivot_table) 
    {
		$sql1="CREATE TABLE $pivot_table (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id int(11) NOT NULL,
        filter_id int(11) NOT NULL,
        UNIQUE KEY id (id)

  
)$charset_collate;";


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );
		}
	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {
		
	}
	
	
		private static function bail_on_activation( $message, $deactivate = true ) {
?>
<!doctype html>
<html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<style>
* {
	text-align: center;
	margin: 0;
	padding: 0;
	font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
}
p {
	margin-top: 1em;
	font-size: 18px;
}
</style>
<body>
<p><?php echo esc_html( $message ); ?></p>
</body>
</html>
<?php
		exit;
	}
	
	
}	