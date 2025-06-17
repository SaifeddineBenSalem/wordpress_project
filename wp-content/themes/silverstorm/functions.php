<?php
// Minimal functions.php for menu location testing
add_action('after_setup_theme', function() {
    $languages = array('en', 'de');
    $menus = array();
    foreach ($languages as $lang) {
        $lang_uc = ucfirst($lang);
        $menus["header-menu-{$lang}"] = "Header Menu - {$lang_uc}";
        $menus["footer-menu-{$lang}"] = "Footer Menu - {$lang_uc}";
        $menus["header-secondary-menu-{$lang}"] = "Header secondary menu {$lang_uc}";
        $menus["footer-secondary-menu-{$lang}"] = "Footer secondary menu {$lang_uc}";
        $menus["in-page-menu-{$lang}"] = "In Page Menu {$lang_uc}";
    }
    // Also add the default locations for backward compatibility
    $menus['header-menu'] = 'Header Menu';
    $menus['footer-menu'] = 'Footer Menu';
    $menus['header-secondary-menu'] = 'Header secondary menu';
    $menus['footer-secondary-menu'] = 'Footer secondary menu';
    $menus['in-page-menu'] = 'In Page Menu';
    register_nav_menus($menus);
});

/**
 *
 * Sets up theme defaults and registers support for various WordPress features.
 *
 */

if ( ! defined( 'SILVERSTORM_THEME_REQUIRED_PHP_VERSION' ) ) {
	define( 'SILVERSTORM_THEME_REQUIRED_PHP_VERSION', '5.6.0' );
}

add_action( 'after_switch_theme', 'silverstorm_check_php_version' );

function silverstorm_check_php_version() {
	// Compare versions.
	if ( version_compare( phpversion(), SILVERSTORM_THEME_REQUIRED_PHP_VERSION, '<' ) ) :
		// Theme not activated info message.
		add_action( 'admin_notices', 'silverstorm_php_version_notice' );

		// Switch back to previous theme.
		switch_theme( get_option( 'theme_switched' ) );

		return false;
	endif;
}

function silverstorm_php_version_notice() {
	?>
    <div class="notice notice-alt notice-error notice-large">
        <h4><?php esc_html_e( 'Silverstorm theme activation failed!', 'silverstorm' ); ?></h4>
        <p>
			<?php printf( esc_html__( 'You need to update your PHP version to use the %s.', 'silverstorm' ),
				' <strong>Silverstorm</strong>' ); ?>
            <br/>
			<?php printf( esc_html__( 'Current php version is: %1$s and the mininum required version is %2$s',
				'silverstorm' ),
				"<strong>" . esc_html(phpversion()) . "</strong>",
				"<strong>" . esc_html(SILVERSTORM_THEME_REQUIRED_PHP_VERSION) . "</strong>" );
			?>

        </p>
    </div>
	<?php
}

if ( version_compare( phpversion(), SILVERSTORM_THEME_REQUIRED_PHP_VERSION, '>=' ) ) {
	require_once get_template_directory() . "/inc/functions.php";
} else {
	add_action( 'admin_notices', 'silverstorm_php_version_notice' );
}

add_filter( 'body_class', function ($classes) {
	$classes[] = 'colibri-theme-' . get_stylesheet();
	return $classes;
});

add_action('admin_init', function() {
    if (function_exists('pll_register_string')) {
        $polylang_options = get_option('polylang', []);
        if (!isset($polylang_options['nav_menus']) || $polylang_options['nav_menus'] != 1) {
            $polylang_options['nav_menus'] = 1;
            update_option('polylang', $polylang_options);
        }
    }
});



