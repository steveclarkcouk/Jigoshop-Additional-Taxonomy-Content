<?php



/**
 * Base class
 */
if (!class_exists('Jigoshop_Additonal_Taxonomy_Content')) 
{
	class Jigoshop_Additonal_Taxonomy_Content
	{
		public $prefix;
		public $plugin_url;
		public $plugin_path;
		public $data;

		/**
		 * Constructor
		 */
		public function Jigoshop_Additonal_Taxonomy_Content() 
		{
			$this->plugin_url = plugin_dir_url(__FILE__);
			$this->plugin_path = plugin_dir_path(__FILE__);
			$this->version = "1.0";	
		}
	
		/**
		* Set Data
		*/
		public function setData($k, $v)
		{
			$this->data[$k] = $v;
		}
		
		/**
		* Get Data
		*/
		public function getData($k)
		{
		   	return $this->data[$k];
		}		

	}
}


/**
 * Admin class
 */
if (!class_exists('Jigoshop_Additonal_Taxonomy_Content_Admin')) {
class Jigoshop_Additonal_Taxonomy_Content_Admin extends Jigoshop_Additonal_Taxonomy_Content
{

	/**
	 * Constructor
	 */
	public function Jigoshop_Additonal_Taxonomy_Content_Admin() {
		parent::Jigoshop_Additonal_Taxonomy_Content();

		// Load the plugin when Jigoshop is enabled
		if ( in_array( 'jigoshop/jigoshop.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action('init', array($this, 'load_all_hooks'));
		}
	}

	/**
	 * Load the hooks
	 */
	public function load_all_hooks() {	
		// --- Add Admin Menu
		add_action( 'admin_menu', array( $this, 'add_menu') );
		// --- Include Wisywig
		add_action("admin_print_scripts", array( $this, 'js_libs'));
	}

	function js_libs() {
	    wp_enqueue_script('tiny_mce');
	}


	/**
	 * Add the menu
	 */
	public function add_menu() {
		add_submenu_page('edit.php?post_type=product', __('Additional Taxonomy Content', 'jigoshop-custom-tax'), __('Additional Taxonomy Content', 'jigoshop-custom-tax'), 'manage_options', 'jigoshop_taxonomy_settings', array($this, 'jigoshop_taxonomy_settings') );
	}

	/**
	 * Create the menu content
	 */
	public function jigoshop_taxonomy_settings() {
		// Check the user capabilities
		if (!current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		// Save the field values
		$fields_submitted = $this->data['prefix'] . 'fields_submitted';
		if ( isset($_POST[ $fields_submitted ]) && $_POST[ $fields_submitted ] == 'submitted' ) {
			foreach ($_POST as $key => $value) {
				if ( get_option( $key ) != $value ) {
					update_option( $key, $value );
				}
				else {
					add_option( $key, $value, '', 'no' );
				}
			}
			

			?><div id="setting-error-settings_updated" class="updated settings-error">
			<p><strong><?php _e('Settings saved.'); ?></strong></p>
		</div><?php
		}

		// Show the fields
		?>
		
		<?php
		wp_tiny_mce( true , // true makes the editor "teeny"
		    array(
		        "editor_selector" => "editor"
		    )
		);
		?>
		
		<div class="wrap jigoshop">
		<h2>Additional Taxonomy Content</h2>
		<form method="post" id="mainform" action="">
			
			<input type="hidden" name="<?php echo $fields_submitted; ?>" value="submitted">
			<div id="tabs-wrap">
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p>
				
				<ul class="tabs">
					<li><a href="#tab1">Categories</a></li>
					<li><a href="#tab2">Tags</a></li>
	
				</ul>
				
				<div id="tab1" class="panel">
					<table class="widefat" >
						<thead>
							<tr>
								<th colspan="2">Product Categories<br/><br/><b>Important:</b>In order for this content to render on your site you will need to make a PHP function call on your product-taxonomy.php template:<br/> <code>
								&lt;?php if(function_exists('jigoshop_additonal_taxonomy')) { jigoshop_additonal_taxonomy(); } ?&gt;</code></th>
							</tr>
						</thead>	
						<tbody>
								<?php $shop_cats = get_categories('taxonomy=product_cat&title_li=&hide_empty=0'); ?>
								<?php foreach($shop_cats as $cat) : ?>	
									<?php if(!get_option( $this->data['prefix'] . 'tax_' .  $cat->category_nicename)) : ?>
										<?php $taxonomy_description = $cat->description; ?>
									<?php else: ?>
										<?php $taxonomy_description = get_option( $this->data['prefix'] . 'tax_' .  $cat->category_nicename); ?>
									<?php endif; ?>	
									
									<tr>
										<td style="width:848px;" width="848"><strong><?php echo $cat->cat_name; ?></strong><br/>
											<textarea class="editor" style="width:848px;height:200px"  name="<?php echo $this->data['prefix']; ?>tax_<?php echo $cat->category_nicename; ?>"><?php echo html_entity_decode(wp_richedit_pre( esc_html( stripslashes ( $taxonomy_description ) ) ) ); ?></textarea>
										</td>

									</tr>
								<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div id="tab2" class="panel">
					

					
						<table class="widefat" >
							<thead>
								<tr>
									<th colspan="2">Product Tags<br/><br/><b>Important:</b>In order for this content to render on your site you will need to make a PHP function call on your product-taxonomy.php template:<br/> <code>
									&lt;?php if(function_exists('jigoshop_additonal_taxonomy')) { jigoshop_additonal_taxonomy(); } ?&gt;</code></th>
								</tr>
							</thead>	
							<tbody>
								
								<?php $shop_cats = get_categories('taxonomy=product_tag&title_li=&hide_empty=0'); ?>
								<?php foreach($shop_cats as $tag) : ?>	
									<tr>
										<td width="848"><strong><?php echo $tag->cat_name; ?></strong><br/>
											<textarea class="editor" style="width:848px;height:200px"  name="<?php echo $this->data['prefix']; ?>tax_<?php echo $tag->category_nicename; ?>"><?php echo html_entity_decode(wp_richedit_pre( esc_html( stripslashes(get_option( $this->data['prefix'] . 'tax_' .  $tag->category_nicename) ) ) ) ); ?></textarea>
										</td>

									</tr>
								<?php endforeach; ?>
								
							</tbody>
						</table>

				</div>
				
				
			</div>
			
		
			
			<script type="text/javascript">
			jQuery(function($) {
			    // Tabs
				jQuery('ul.tabs').show();
				jQuery('ul.tabs li:first').addClass('active');
				jQuery('div.panel:not(div.panel:first)').hide();
				jQuery('ul.tabs a').click(function(){
					jQuery('ul.tabs li').removeClass('active');
					jQuery(this).parent().addClass('active');
					jQuery('div.panel').hide();
					jQuery( jQuery(this).attr('href') ).show();

					jQuery.cookie('jigoshop_settings_tab_index', jQuery(this).parent().index('ul.tabs li'))

					return false;
				});

				<?php if (isset($_COOKIE['jigoshop_settings_tab_index']) && $_COOKIE['jigoshop_settings_tab_index'] > 0) : ?>

					jQuery('ul.tabs li:eq(<?php echo $_COOKIE['jigoshop_settings_tab_index']; ?>) a').click();

				<?php endif; ?>

				// Countries
				jQuery('select#jigoshop_allowed_countries').change(function(){
					if (jQuery(this).val()=="specific") {
						jQuery(this).parent().parent().next('tr.multi_select_countries').show();
					} else {
						jQuery(this).parent().parent().next('tr.multi_select_countries').hide();
					}
				}).change();

				// permalink double save hack
				$.get('<?php echo admin_url('options-permalink.php') ?>');

			});
			</script>
			
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div><?php
	}
}
}



?>