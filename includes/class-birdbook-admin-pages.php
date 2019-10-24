<?php

namespace birdbook;


class BirdBookAdminPages
{

	public function __construct()
	{
		add_action('admin_menu', array($this, 'register_submenu_page'), 99);
		add_action('admin_init', array($this, 'settings_init'));
	}

	function register_submenu_page()
	{
		// add_menu_page( page_title, menu_title, capability, menu_slug, function, icon_url, position )
		$menu_page = add_menu_page(
			'Bird Book',
			'Bird Book',
			'manage_options',
			'birdbook-books-submenu-page',
			function() { $this->birdbook_books_submenu_page_callback('book'); }
		);
 

		add_action('admin_print_styles' , array($this, 'add_stylesheets'));
		add_action('admin_print_scripts' , array($this, 'add_scripts'));
		add_submenu_page(
			'birdbook-books-submenu-page',
			'Bird',
			'Bird',
			'manage_options',
			'birdbook-birds-settings',
			function() { $this->birdbook_books_submenu_page_callback('birds'); }
		);
		// add_submenu_page( parent_slug, page_title, menu_title, capability, menu_slug, function )
		add_submenu_page(
			'birdbook-books-submenu-page',
			'Locations',
			'Locations',
			'manage_options',
			'birdbook-bird-locations-settings',
			function() { $this->birdbook_books_submenu_page_callback('location'); }
		);

		add_submenu_page(
			'birdbook-books-submenu-page',
			'Bird Filters',
			'Bird Filters',
			'manage_options',
			'birdbook-bird-filters-settings',
			function() { $this->birdbook_books_submenu_page_callback('filter'); }
		);

	

		add_submenu_page(
			'birdbook-books-submenu-page',
			'Gallery',
			'Gallery',
			'manage_options',
			'birdbook-gallery-filters-settings',
			function() { $this->birdbook_books_submenu_page_callback('gallery'); }
		);

// add_submenu_page( parent_slug, page_title, menu_title, capability, menu_slug, function )

		// add_submenu_page( parent_slug, page_title, menu_title, capability, menu_slug, function )

	}

	function birdbook_books_submenu_page_callback($view)
	{
		include_once __DIR__ . '/../views/'.$view.'.php';
	}
	function add_stylesheets()
	{
		wp_enqueue_style('birdbook_stylesheet', plugin_dir_url(__FILE__) . '../css/book.css');
		wp_enqueue_style('semantic_stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css');
	}

	function add_scripts()
	{
		wp_enqueue_script('birdbook_script', plugin_dir_url(__FILE__) . '../js/book.js');
		wp_enqueue_script('jquery');

		wp_enqueue_script('semantic_script',  'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js');
	}

	public function settings_init()
	{
		register_setting('birdbook', 'birdbook_options');

		// register a new section in the "wporg" page
		add_settings_section(
			'birdbook_bird_filters_section',
			__('Google book api settings.', 'birdbook'),
			array($this, 'birdbook_bird_filters_section_callback'),
			'birdbook-google-api-settings'
		);

		// register a new field in the "wporg_section_developers" section, inside the "wporg" page
		add_settings_field(
			'birdbook_google_book_api_key', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Google Book API key', 'birdbook'),
			array($this, 'birdbook_bird_filters_key_field_callback'),
			'birdbook-google-api-settings',
			'birdbook_bird_filters_section',
			[
				'label_for' => 'birdbook_google_book_api_key',
				'class' => 'birdbook-api-settings-field',
				'birdbook_custom_data' => 'custom',
			]
		);
	}

	public function birdbook_bird_filters_key_field_callback($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('birdbook_options');
		// output the field
		?>
		<input id="<?php echo esc_attr($args['label_for']); ?>" name="birdbook_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo $options[esc_attr($args['label_for'])] ?>" />
	<?php
		}



		public function birdbook_bird_filters_section_callback()
		{
			?>
		<p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Enter the google api key here.', 'birdbook'); ?></p>
	<?php
		}

		public function submenupage_callback()
		{
			// check user capabilities
			if (!current_user_can('manage_options')) {
				return;
			}

			// add error/update messages

			// check if the user have submitted the settings
			// wordpress will add the "settings-updated" $_GET parameter to the url
			if (isset($_GET['settings-updated'])) {
				// add settings saved message with the class of "updated"
				add_settings_error('birdbook_messages', 'birdbook_message', __('Settings Saved', 'wporg'), 'updated');
			}

			// show error/update messages
			settings_errors('birdbook_messages');
			?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
						// output security fields for the registered setting "wporg"
						settings_fields('birdbook');
						// output setting sections and their fields
						// (sections are registered for "wporg", each field is registered to a specific section)
						do_settings_sections('birdbook-google-api-settings');
						// output save settings button
						submit_button('Save Settings');
						?>
			</form>
		</div>
<?php
	}
}
