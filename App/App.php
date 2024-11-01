<?php

namespace WPBlocksHub;

/**
 * Primary core controller
 **/
class App {
	
	private static $instance = null;
	
	public $Config;
	public $Model;
	public $View;
  public $Controller;
		
	/**
	 * @return App , Singleton
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	private function __construct() {
	}

	private function __clone() {
	}
	
	/**
	 * Run the theme
	 **/
	public function run() {

		// Load default config
		$this->Config = require_once plugin_dir_path( WPBH_PLUGIN_FILE ) . 'App/Config.php';
		
		add_action( 'plugins_loaded', function() {
			// Translation support
			load_plugin_textdomain( 'wp-blocks-hub', false, dirname( plugin_basename( WPBH_PLUGIN_FILE ) ) . '/languages/' );
		});
		
		// Load core classes
		$this->_dispatch();
		
	}
	
	/**
	 * Load and instantiate all application
	 * classes neccessary for this theme
	 **/
	private function _dispatch() {
		
		$this->Model = new \stdClass();
		$this->View = new \stdClass();
		$this->Controller = new \stdClass();
		
		// Autoload models
		$this->_load_modules( 'Model', '/' );

		// Init View
		$this->View = new \WPBlocksHub\View\View();

		// Load controllers manually
		$controllers = [
			'FS',
			'Init',
			'SVG',
			'Assets',
			'Backend',
			'Blocks',
			'BackendHub',
			'BackendHubMyBlocks',
			'BackendSettings',
			'CustomPostTypes',
			'REST',
			'GutenbergSettings',
			'ElementorSettings',
			'Woocommerce',
			'Frontend',
			'SCSS',
		];

		$this->_load_controllers( $controllers );
				
	}
	
	/**
	 * Autoload core modules in a specific directory
	 *
	 * @param string
	 * @param string
	 * @param bool
	 **/
	private function _load_modules( $layer, $dir = '/' ) {

		$directory 	= $this->Config['plugin_path'] . '/App/' . $layer . $dir;
		$handle    	= opendir( $directory );

    if( count( glob( "$directory/*" )) === 0 ) {
      return false;
    }

		while ( false !== ( $file = readdir( $handle ) ) ) {
			
			if ( is_file( $directory . $file ) ) {
				// Figure out class name from file name
				$class = str_replace( '.php', '', $file );
				
				// Avoid recursion
				if ( $class !== get_class( $this ) ) {
					$classPath = "\\WPBlocksHub\\{$layer}\\{$class}";
					$this->$layer->$class = new $classPath();
				}
				
			}
		}
		
	}

	/**
	 * Autoload controllers in specific order
	 */
	private function _load_controllers( $list ) {

		$directory 	= $this->Config['plugin_path'] . '/App/Controller/';

		foreach( $list as $controller_name ) {
			if ( is_file( $directory . $controller_name . '.php' ) ) {
				$class = $controller_name;
				// Avoid recursion
				if ( $class !== get_class( $this ) ) {
					$classPath = "\\WPBlocksHub\\Controller\\{$class}";
					$this->Controller->$class = new $classPath();
				}
			}
		}

	}
		
}
?>