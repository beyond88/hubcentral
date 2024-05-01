<?php
/**
 * Plugin Name: HubCentral - Centralize Order Management. Empower Customer Support.
 * Description: Unify order views, update statuses, and collaborate on notes within a central hub, empowering your team for better order fulfillment.
 * Plugin URI: https://github.com/beyond88/hubcentral
 * Author: Mohiuddin Abdul Kader
 * Author URI: https://github.com/beyond88
 * Version: 1.0.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hubcentral
 * Domain Path:       /languages
 * Requires PHP:      5.6
 * Requires at least: 4.4
 * Tested up to:      6.5.2
 * @package HubCentral
 *
 * WC requires at least: 3.1
 * WC tested up to:   8.8.2
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class HubCentral {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0.0';

    /**
     * Class constructor
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

    }

    /**
     * Initializes a singleton instance
     *
     * @return \HubCentral
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'HUBCENTRAL_VERSION', self::version );
        define( 'HUBCENTRAL_FILE', __FILE__ );
        define( 'HUBCENTRAL_PATH', __DIR__ );
        define( 'HUBCENTRAL_URL', plugins_url( '', HUBCENTRAL_FILE ) );
        define( 'HUBCENTRAL_ASSETS', HUBCENTRAL_URL . '/assets' );
        define( 'HUBCENTRAL_BASENAME', plugin_basename( __FILE__ ) );
        define( 'HUBCENTRAL_PLUGIN_NAME', 'HubCentral' );
        define( 'HUBCENTRAL_MINIMUM_PHP_VERSION', '5.6.0' );
        define( 'HUBCENTRAL_MINIMUM_WP_VERSION', '4.4' );
        define( 'HUBCENTRAL_MINIMUM_WC_VERSION', '3.1' );

    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        new HubCentral\Assets();
        new HubCentral\HubCentrali18n();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new HubCentral\Ajax();
        }

        if ( is_admin() ) {
            new HubCentral\Admin();
        } else {
            new HubCentral\Frontend();
        }

        new HubCentral\API();

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new HubCentral\Installer();
        $installer->run();
    }
}

/**
 * Initializes the main plugin
 */
function hubcentral() {
    return HubCentral::init();
}

// kick-off the plugin
hubcentral();