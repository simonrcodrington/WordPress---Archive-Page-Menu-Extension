<?php
/*
Plugin Name: Archive Page Menu Extension
Plugin URI: http://simoncodrington.com.au/plugins/archive-page
Description: Adds a new metabox item in WordPress's menu builder (Appearance -> Menus). Allows quick access to add your post types archive pages instead of having to rely on custom links. Will display all post types with an archive that are public
Version: 1.0.0
Author: Simon Codrington
Author URI: http://simoncodrington.com.au
Text Domain: archive-page-menu-extension
Domain Path: /languages
*/

class el_archive_pages_menu{
	
	private static $instance = null;
	
	//constructor
	public function __construct(){
		add_action('admin_init', array($this, 'add_meta_box'));
	}
	
	//get singleton instance
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	//add metabox to the nav builder
	public function add_meta_box(){
			
		add_meta_box(
			'el_archive_page_menu_metabox',
			__('Archive Pages', 'archive-pages-to-menu'),
			array($this, 'display_meta_box'),
			'nav-menus',
			'side',
			'low'
		);
	}
	
	//displays a metabox that will let users link directly to post type archives
	public function display_meta_box(){
		
		?>
		<div id="posttype-archive-pages" class="posttypediv">
			<div id="tabs-panel-archive-pages" class="tabs-panel tabs-panel-active">
				
				
				<?php
				//loop through all registered content types that have 'has-archive' enabled 
				$post_types = get_post_types(array('has_archive' => true));
				if($post_types){ ?>
				<p>These will link your users directly to your post type archives (if the post type allows)</p>
					<ul id="archive-pages" class="categorychecklist form-no-clear">
						<!--Custom -->
						<?php
						$counter = -1; //negative index to match WP
						foreach($post_types as $post_type){
							$post_type_obj = get_post_type_object($post_type);
							$post_type_archive_url = get_post_type_archive_link($post_type);
							$post_type_name = $post_type_obj->labels->singular_name;
							?>
							<li>
								<label class="menu-item-title">
									<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $counter; ?>][menu-item-object-id]" value="-1"/>Archive Page: <?php echo $post_type_name; ?>
								</label>
								<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $counter; ?>][menu-item-type]" value="custom"/>
								<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $counter; ?>][menu-item-title]" value="<?php echo $post_type_name; ?>"/>
								<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $counter; ?>][menu-item-url]" value="<?php echo $post_type_archive_url; ?>"/>
								<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $counter; ?>][menu-item-classes]"/>
							</li>
							<?php
							$counter--;
						}?>
					</ul>
				<?php }
				else{?>		
					<p>There don't appear to be any public post types with archive pages enabled.</p>
				<?php } ?>
			</div>
			<p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo admin_url('nav-menus.php?page-tab=all&selectall=1#posttype-archive-pages'); ?>" class="select-all"> <?php _e('Select All', 'archive-pages-to-menu' ); ?></a>
				</span>
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e('Add to Menu', 'archive-pages-to-menu') ?>" name="add-post-type-menu-item" id="submit-posttype-archive-pages">
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}
	
}
$el_archive_pages_menu = el_archive_pages_menu::getInstance();
?>