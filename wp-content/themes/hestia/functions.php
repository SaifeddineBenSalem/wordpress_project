<?php
/**
 * Hestia functions and definitions
 *
 * @package Hestia
 * @since   Hestia 1.0
 */

define( 'HESTIA_VERSION', '3.2.10' );
define( 'HESTIA_VENDOR_VERSION', '1.0.2' );
define( 'HESTIA_PHP_INCLUDE', trailingslashit( get_template_directory() ) . 'inc/' );
define( 'HESTIA_ASSETS_URL', trailingslashit( get_template_directory_uri() ) . 'assets/' );
define( 'HESTIA_CORE_DIR', HESTIA_PHP_INCLUDE . 'core/' );
define( 'HESTIA_PRODUCT_SLUG', basename( trailingslashit( get_template_directory() ) ) );

if ( ! defined( 'HESTIA_DEBUG' ) ) {
	define( 'HESTIA_DEBUG', false );
}

// Load hooks
require_once( HESTIA_PHP_INCLUDE . 'hooks/hooks.php' );

// Load Helper Globally Scoped Functions
require_once( HESTIA_PHP_INCLUDE . 'helpers/sanitize-functions.php' );
require_once( HESTIA_PHP_INCLUDE . 'helpers/layout-functions.php' );

if ( class_exists( 'WooCommerce', false ) ) {
	require_once( HESTIA_PHP_INCLUDE . 'compatibility/woocommerce/functions.php' );
}

if ( function_exists( 'max_mega_menu_is_enabled' ) ) {
	require_once( HESTIA_PHP_INCLUDE . 'compatibility/max-mega-menu/functions.php' );
}

// Load starter content
require_once( HESTIA_PHP_INCLUDE . 'compatibility/class-hestia-starter-content.php' );


/**
 * Adds notice for PHP < 5.3.29 hosts.
 */
function hestia_no_support_5_3() {
	$message = __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by upgrading PHP to the latest version.', 'hestia' );

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );
}


if ( version_compare( PHP_VERSION, '5.3.29' ) < 0 ) {
	/**
	 * Add notice for PHP upgrade.
	 */
	add_filter( 'template_include', '__return_null', 99 );
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'hestia_no_support_5_3' );

	return;
}

/**
 * Begins execution of the theme core.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function hestia_run() {

	$vendor_file = trailingslashit( get_template_directory() ) . 'vendor/composer/autoload_files.php';
	if ( is_readable( $vendor_file ) ) {
		$files = require_once $vendor_file;
		foreach ( $files as $file ) {
			if ( is_readable( $file ) ) {
				include_once $file;
			}
		}
	}
	add_filter( 'themeisle_sdk_products', 'hestia_load_sdk' );

	add_filter(
		'themesle_sdk_namespace_' . md5( get_template_directory() . '/style.css' ),
		function () {
			return 'hestia';
		}
	);

	if ( class_exists( 'Ti_White_Label', false ) ) {
		Ti_White_Label::instance( get_template_directory() . '/style.css' );
	}

	require_once HESTIA_CORE_DIR . 'class-hestia-autoloader.php';
	$autoloader = new Hestia_Autoloader();

	spl_autoload_register( array( $autoloader, 'loader' ) );

	new Hestia_Core();
}

/**
 * Loads products array.
 *
 * @param array $products All products.
 *
 * @return array Products array.
 */
function hestia_load_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}

require_once( HESTIA_CORE_DIR . 'class-hestia-autoloader.php' );

/**
 * The start of the app.
 *
 * @since   1.0.0
 */
hestia_run();

/**
 * Append theme name to the upgrade link
 * If the active theme is child theme of Hestia
 *
 * @param string $link - Current link.
 *
 * @return string $link - New upgrade link.
 * @package hestia
 * @since   1.1.75
 */
function hestia_upgrade_link( $link ) {

	$theme_name = wp_get_theme()->get_stylesheet();

	$hestia_child_themes = array(
		'orfeo',
		'fagri',
		'tiny-hestia',
		'christmas-hestia',
		'jinsy-magazine',
	);

	if ( $theme_name === 'hestia' ) {
		return $link;
	}

	if ( ! in_array( $theme_name, $hestia_child_themes, true ) ) {
		return $link;
	}

	$link = add_query_arg(
		array(
			'theme' => $theme_name,
		),
		$link
	);

	return $link;
}

add_filter( 'hestia_upgrade_link_from_child_theme_filter', 'hestia_upgrade_link' );

/**
 * Check if $no_seconds have passed since theme was activated.
 * Used to perform certain actions, like displaying upsells or add a new recommended action in About Hestia page.
 *
 * @param integer $no_seconds number of seconds.
 *
 * @return bool
 * @since  1.1.45
 * @access public
 */
function hestia_check_passed_time( $no_seconds ) {
	$activation_time = get_option( 'hestia_time_activated' );
	if ( ! empty( $activation_time ) ) {
		$current_time    = time();
		$time_difference = (int) $no_seconds;
		if ( $current_time >= $activation_time + $time_difference ) {
			return true;
		} else {
			return false;
		}
	}

	return true;
}

/**
 * Legacy code function.
 */
function hestia_setup_theme() {
	return;
}

/**
 * Minimize CSS.
 *
 * @param string $css Inline CSS.
 * @return string
 */
function hestia_minimize_css( $css ) {
	if ( empty( $css ) ) {
		return $css;
	}
	// Normalize whitespace.
	$css = preg_replace( '/\s+/', ' ', $css );
	// Remove spaces before and after comment.
	$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
	// Remove comment blocks, everything between /* and */, unless.
	// preserved with /*! ... */ or /** ... */.
	$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
	// Remove ; before }.
	$css = preg_replace( '/;(?=\s*})/', '', $css );
	// Remove space after , : ; { } */ >.
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
	// Remove space before , ; { } ( ) >.
	$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
	// Strips leading 0 on decimal values (converts 0.5px into .5px).
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
	// Strips units if value is 0 (converts 0px to 0).
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	// Converts all zeros value into short-hand.
	$css = preg_replace( '/0 0 0 0/', '0', $css );
	// Shortern 6-character hex color codes to 3-character where possible.
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
	return trim( $css );
}

/**
 * Enqueue Font Awesome
 */
function hestia_enqueue_font_awesome() {
	wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
}
add_action('wp_enqueue_scripts', 'hestia_enqueue_font_awesome');

// Register Hestia Big Title Section strings for Polylang translation
add_action('after_setup_theme', function() {
	if (function_exists('pll_register_string')) {
		pll_register_string('hestia_big_title_title', get_theme_mod('hestia_big_title_title'), 'Hestia');
		pll_register_string('hestia_big_title_text', get_theme_mod('hestia_big_title_text'), 'Hestia');
		pll_register_string('hestia_big_title_button_text', get_theme_mod('hestia_big_title_button_text'), 'Hestia');
		pll_register_string('hestia_big_title_button_link', get_theme_mod('hestia_big_title_button_link'), 'Hestia');
		pll_register_string('hestia_features_title', get_theme_mod('hestia_features_title'), 'Hestia');
		pll_register_string('hestia_features_subtitle', get_theme_mod('hestia_features_subtitle'), 'Hestia');
		// Register each feature item for translation
		$features_content = get_theme_mod('hestia_features_content');
		if ($features_content) {
			$features = json_decode($features_content);
			if (is_array($features)) {
				foreach ($features as $i => $feature) {
					if (!empty($feature->title)) {
						pll_register_string("hestia_feature_title_$i", $feature->title, 'Hestia Features');
					}
					if (!empty($feature->text)) {
						pll_register_string("hestia_feature_text_$i", $feature->text, 'Hestia Features');
					}
				}
			}
		}
		pll_register_string('hestia_subscribe_title', get_theme_mod('hestia_subscribe_title', __('Subscribe to our Newsletter', 'hestia')), 'Hestia');
		pll_register_string('hestia_subscribe_subtitle', get_theme_mod('hestia_subscribe_subtitle'), 'Hestia');
		pll_register_string('hestia_testimonials_title', get_theme_mod('hestia_testimonials_title', __('Testimonials', 'hestia')), 'Hestia');
		pll_register_string('hestia_testimonials_subtitle', get_theme_mod('hestia_testimonials_subtitle'), 'Hestia');
		// Register each testimonial item for translation
		$testimonials_content = get_theme_mod('hestia_testimonials_content');
		if ($testimonials_content) {
			$testimonials = json_decode($testimonials_content);
			if (is_array($testimonials)) {
				foreach ($testimonials as $i => $testimonial) {
					if (!empty($testimonial->title)) {
						pll_register_string("hestia_testimonial_title_$i", $testimonial->title, 'Hestia Testimonials');
					}
					if (!empty($testimonial->text)) {
						pll_register_string("hestia_testimonial_text_$i", $testimonial->text, 'Hestia Testimonials');
					}
				}
			}
		}
		pll_register_string('hestia_ribbon_text', get_theme_mod('hestia_ribbon_text', __('Ribbon', 'hestia')), 'Hestia');
		pll_register_string('hestia_ribbon_button_text', get_theme_mod('hestia_ribbon_button_text'), 'Hestia');
		pll_register_string('hestia_ribbon_button_url', get_theme_mod('hestia_ribbon_button_url'), 'Hestia');
	}
});
