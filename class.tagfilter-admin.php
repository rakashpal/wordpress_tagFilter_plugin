<?php

class TagFilter_Admin{
	
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
       add_action( 'admin_menu', array('TagFilter_Admin','wpdocs_register_my_custom_menu_page') );
        add_action( 'admin_enqueue_scripts', array('TagFilter_Admin','load_admin_styles' ));
        add_action( 'add_meta_boxes', array( 'TagFilter_Admin', 'add_metabox'  ));
         add_action( 'save_post', array( 'TagFilter_Admin', 'save_metabox' ), 10, 2 );
	 }
	  
	  /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
	 public static function save_metabox($post_id, $post){
	     
	     $nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
	      $nonce_action = 'custom_nonce_action';
	      
      

	      
	     if ( ! isset( $nonce_name ) ) {
	     	return;
            }
            
            // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
        
         // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        
         // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        
      global $wpdb;
          $pivot_table= $wpdb->prefix.'filter_post';
           $wpdb->query( 'DELETE  FROM '.$pivot_table.' WHERE post_id = '.$post_id.'');
	 	  if(isset($_POST['tag_names']) && !empty($_POST['tag_names'])){
		  	
		  
	 	  foreach($_POST['tag_names'] as $value){
		  	
		  
			
		   
         $data = array(
                'post_id'            => $post_id,
                'filter_id'          =>$value, 
                );               
            $format = array(
                '%d',
                '%d',
            );
            
	          $success=$wpdb->insert( $pivot_table, $data, $format );
			
			}
		}
		
	 }
	 /**
	 * 
	 * 
	 * @return
	 */
	 public static function add_metabox(){
		 
		 add_meta_box(
            'taxonomy_meta_box',
            __( 'Taxonomy', 'tagfilter' ),
            array('TagFilter_Admin', 'render_metabox' ),
            'post',
            'side',
            'default'
        );
		
	 }//end add metabox
	 
	 /**
	 * 
	 * 
	 * @return
	 */
	 public static function render_metabox($post){
	 	
	 	?>
	 	
         <?php
         global $wpdb;
         $pivot_table= $wpdb->prefix.'filter_post';
          $table_name = $wpdb->prefix .'fitlertag'; 
          $results = $wpdb->get_results( 'SELECT * FROM '.$table_name  );
          $selected_tag = $wpdb->get_results( 'SELECT * FROM '.$pivot_table.' where post_id='.$post->ID  ); 
          wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
           if($results){
		foreach($results as $key => $value)
		{
			?>
			<input type='checkbox' name='tag_names[]' value='<?php echo $value->id; ?>'
			<?php if($selected_tag)
			{
				foreach($selected_tag as $k => $v){
				
				if($v->filter_id == $value->id){
					echo 'checked';
				}
					}
			}?>
			  /><?php echo  $value->name; ?><br/>
			
			<?php
		}
   }   
         ?>
  
     

	 	<?php
	 }
	 
	 /**
	 * 
	 * 
	 * @return
	 */
	 public static function load_admin_styles(){
		wp_enqueue_style('admin-styles', plugin_dir_url( __FILE__ ).'/css/admin_style.css');
		wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'js/myscript.js');
	 }
	 
	 /**
	 * 
	 * 
	 * @return
	 */
	public static function wpdocs_register_my_custom_menu_page() {
	 	  add_menu_page( 
        __( 'Taxonomy', 'tagfilter' ),
        'Taxonomy',
        'manage_options',
        'filtertag',
        array('TagFilter_Admin','my_custom_menu_page'),
        '',
        20
    ); 
	 	
	}
	/**
 * Display a custom menu page
 */
 public static function my_custom_menu_page(){
  //esc_html_e( '<h3>Add Tags</h3>', 'tagfilter' ); 
  global $wpdb;
  $table_name = $wpdb->prefix .'fitlertag'; 
  if(isset($_POST['save']))
  {
		extract($_POST);
		$wpdb->query($wpdb->prepare("UPDATE $table_name SET border_color='$border_color', text_color='$text_color', background_color='$background_color' WHERE id=$tag_id"));
  }
  
  
  if(isset($_POST['add'])){
     
    
        $data = array(
                'name'            => $_POST['tag_name'],
                'border_color'    =>'#000000',
                'text_color'      =>'#ffffff',
                'background_color'=>'#000000');
                
            $format = array(
                '%s',
                '%s',
                '%s',
                '%s'
            );
            
	          $success=$wpdb->insert( $table_name, $data, $format );
	          
            if($success){
            //echo 'data has been save' ; 
}
		
  }
  ?>

  
  <div class="section-tag">
	  <div class="top-seciton1">
	   <h1>Add Tags</h1>
	  </div>
	  <div class="tags-lots">
	  <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
	  <label for="tagname"><b>Tag Name</b></label><br/>
    <div class="inputs-d">
    <input type="text" class="insec" placeholder="Tag Name" name="tag_name" required id="tagname">
    <input  type="submit" class="add_tag_btn" name="add" value="ADD" />
    </div>
    
	  </form>
	  <div class="tags-listing">
	  
	  
  <?php
   

    $results = $wpdb->get_results( 'SELECT * FROM '.$table_name  ); 
  
   if($results){
		foreach($results as $key => $value)
		{
			echo "<span data-id='".$value->id."'  data-bordercolor='".$value->border_color."' 
			data-textcolor='".$value->text_color."' data-background='".$value->background_color."' class='tag'>".$value->name."<a  href='#' class='cross'>X</a></span>";
		}
   }   
  

	
  ?>
 
    </div><!-- tags-listing-->
      </div>
      
      
	    <form class="color_chooser_section" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
	    <?php if(isset($_POST['save'])): ?>
	   <?php  extract($_POST); ?>
	    <h2 class="format">Select Tags Formatting</h2>
<div class="col-sec">
   <ul class="col-set-left hoc">
      <li>Border Color</li>
      <li>Text Color</li>
      <li>Section Background Color</li>
   </ul>
   <ul class="col-set-right hoc">
      <li><input type="hidden" name="tag_id" value="<?php echo $tag_id; ?>">
      <input type="color" name="border_color" value="<?php echo $border_color; ?>"></li>
      <li><input type="color" name="text_color" value="<?php echo $text_color; ?>"></li>
      <li><input type="color" name="background_color" value="<?php echo  $background_color; ?>"></li>
   </ul>
   <div class="save-box"><input  type="submit" class="add_tag_btn" name="save" value="SAVE" /></div>
</div>
	    <?php endif; ?>
	    </form>
  
  </div>
  
  
 <?php 
	}
}	