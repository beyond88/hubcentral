<?php

namespace HubCentral\Admin;

/**
 * Settings Handler class
 */
class Main
{

	/**
	 * Settings otpions field
	 * 
	 * @var string
	 */
	public $_optionName  = 'hubcentral_settings';

	/**
	 * Settings otpions group field
	 * 
	 * @var string
	 */
	public $_optionGroup = 'hubcentral_options_group';

	/**
	 * Settings otpions field default values
	 * 
	 * @var array
	 */
	public $_defaultOptions = array();

	/**
	 * Initial the class and its all methods
	 *
	 * @since 1.0.0
	 * @access	public
	 * @param	none
	 * @return	void
	 */
	public function __construct()
	{
		add_action('init', array($this, 'register_orders_post_type'));
		add_action('plugins_loaded', array($this, 'set_default_options'));
		add_action('admin_init', array($this, 'menu_register_settings'));
		add_action('admin_menu', array($this, 'settings_menu'));
	}

	/**
	 * Register post type		
	 * 
	 * @since	1.0.0
	 * @access 	public
	 * @param	none
	 * @return	void
	 */
	public function register_orders_post_type()
	{
		$labels = array(
			'name'               => __('Orders', 'hub-plugin'),
			'singular_name'      => __('Order', 'hub-plugin'),
			'menu_name'          => __('Orders', 'hub-plugin'),
			'name_admin_bar'     => __('Orders', 'hub-plugin'),
			'add_new'            => __('Add New', 'hub-plugin'),
			'add_new_item'       => __('Add New Order', 'hub-plugin'),
			'new_item'           => __('New Order', 'hub-plugin'),
			'edit_item'          => __('Edit Order', 'hub-plugin'),
			'view_item'          => __('View Order', 'hub-plugin'),
			'all_items'          => __('All Orders', 'hub-plugin'),
			'search_items'       => __('Search Orders', 'hub-plugin'),
			'parent_item_colon'  => __('Parent Orders:', 'hub-plugin'),
			'not_found'          => __('No orders found.', 'hub-plugin'),
			'not_found_in_trash' => __('No orders found in Trash.', 'hub-plugin')
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __('Description.', 'hub-plugin'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'order'),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title', 'editor', 'author', 'thumbnail', 'comments'),
			'show_in_rest'       => true,
		);

		register_post_type('orders', $args);
	}

	/**
	 * Apply filter with default options
	 * 
	 * @since	1.0.0
	 * @access	public
	 * @param	none
	 * @return	void
	 */
	public function set_default_options()
	{
		return apply_filters('hubcentral_default_options', $this->_defaultOptions);
	}

	/**
	 * Save the setting options		
	 * 
	 * @since	1.0.0
	 * @access 	public
	 * @param	array
	 * @return	void
	 */
	public function menu_register_settings()
	{
		add_option($this->_optionName, $this->_defaultOptions);
		register_setting($this->_optionGroup, $this->_optionName);
	}

	/**
	 * Register admin menu
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   none   
	 * @return  void
	 */
	public function settings_menu()
	{
		$parent_slug = 'edit.php?post_type=orders';

		$settings   = apply_filters('hubcentral_admin_menu', array(
			'page_title' => 'Settings',
			'menu_title' => 'Settings',
			'capability' => 'manage_options',
			'slug' => 'order-settings',
			'callback' => array($this, 'settings_page'),
		));

		$hook = add_submenu_page($parent_slug, $settings['page_title'], $settings['menu_title'], $settings['capability'], $settings['slug'], $settings['callback']);
		add_action('admin_head-' . $hook, array($this, 'enqueue_assets'));
	}

	/**
	 * Plugin page handler
	 *
	 * @since 1.0.0
	 * @access	public
	 * @param	none
	 * @return	void
	 */
	public function settings_page()
	{
		$template = __DIR__ . '/views/hubcentral-settings.php';

		if (file_exists($template)) {
			include $template;
		}
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param   none   
	 * @return  void
	 */
	public function enqueue_assets()
	{
		wp_enqueue_style('hubcentral-admin-boostrap');
		wp_enqueue_style('hubcentral-admin-style');
		wp_enqueue_script('hubcentral-admin-script');
	}
}
