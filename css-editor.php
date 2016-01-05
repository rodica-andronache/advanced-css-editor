<?php
/*
Plugin Name: Advanced CSS Editor
Plugin URI: http://www.hardeepasrani.com/
Description: It's just an experiment.
Author: Hardeep Asrani
Author URI:  http://www.hardeepasrani.com/
Version: 0.1
*/

// Add plugin options to theme customizer.
function advanced_css_editor_customizer($wp_customize) {

	// Include layout picker control.
	include('layout-picker.php');
	include('syntax-highlighter.php');

	// Add Advanceed CSS editor section to Customizer.
	$wp_customize->add_section( 'advanded_css_editor', array(
		'title'		  => 'Advanced CSS Editor',
		'priority'	   => 5,
	) );

	// Add Layout Picker setting.
	$wp_customize->add_setting( 'advanced_css_layout_picker_setting', array(
		'default'		=> 'desktop',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'advanced_css_sanitize_choices',
		'transport' => 'postMessage',
	) );

	// Add Layout Picker control.
	$wp_customize->add_control( new Advanded_CSS_Layout_Picker_Custom_Control( $wp_customize, 'advanced_css_layout_picker_setting', array(
		'label'   => 'Select Screen Size:',
		'section' => 'advanded_css_editor',
		'settings'   => 'advanced_css_layout_picker_setting',
		'choices' => array(
			'desktop' => '<span class="dashicons dashicons-desktop" title="Desktop"></span>',
			'tablet' => '<span class="dashicons dashicons-tablet" title="Tablet"></span>',
			'phone' => '<span class="dashicons dashicons-smartphone" title="Phone"></span>',
		),
		'priority' => 1
	) ) );

	$wp_customize->add_setting('advanced_css_desktop_css', array(
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( new CSS_Highlighter_Custom_Control( $wp_customize, 'advanced_css_desktop_css', array(
		'label' => __('Desktop CSS:', 'latte'),
		'section' => 'advanded_css_editor',
		'priority' => 5,
		'type' => 'textarea',
		'settings' => 'advanced_css_desktop_css'
	) ) );

	$wp_customize->add_setting('advanced_css_tablet_css', array(
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( new CSS_Highlighter_Custom_Control( $wp_customize, 'advanced_css_tablet_css', array(
		'label' => __('Tablet CSS:', 'latte'),
		'section' => 'advanded_css_editor',
		'priority' => 10,
		'type' => 'textarea',
		'settings' => 'advanced_css_tablet_css'
	) ) );

	$wp_customize->add_setting('advanced_css_phone_css', array(
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( new CSS_Highlighter_Custom_Control( $wp_customize, 'advanced_css_phone_css', array(
		'label' => __('Phone CSS:', 'latte'),
		'section' => 'advanded_css_editor',
		'priority' => 15,
		'type' => 'textarea',
		'settings' => 'advanced_css_phone_css'
	) ) );

	// Sanitize output.
	function advanced_css_sanitize_choices( $input, $setting ) {
		global $wp_customize;

		$control = $wp_customize->get_control( $setting->id );
	
		if ( array_key_exists( $input, $control->choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
}

add_action('customize_register', 'advanced_css_editor_customizer', 99);

// Reset theme mod so that Desktop is always default.
function advanced_css_remove_mod( $wp_customize ) {
	remove_theme_mod( 'advanced_css_layout_picker_setting' );
}	
add_action('customize_save_after', 'advanced_css_remove_mod', 100);

// Add scripts to Customizer screen.
function advanced_css_editor_scripts() {
	wp_enqueue_script( 'advanced_css_editor_js', plugin_dir_url( __FILE__ ) . 'js/customizer.js', array( 'jquery'), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'advanced_css_editor_scripts' );

// Add styles to Customizer screen.
function advanced_css_editor_styles() {
	wp_enqueue_style( 'advanced_css_editor_css', plugin_dir_url( __FILE__ ) . 'css/customizer.css' );
}
add_action( 'customize_controls_print_styles', 'advanced_css_editor_styles' );

function advanced_css_input() {
		$advanced_css_desktop_css = get_theme_mod('advanced_css_desktop_css');
		$advanced_css_tablet_css = get_theme_mod('advanced_css_tablet_css');
		$advanced_css_phone_css = get_theme_mod('advanced_css_phone_css');
?>
<style>
<?php echo $advanced_css_desktop_css; ?>
@media only screen and (min-device-width: 768px) and (max-device-width: 1024px)  {
<?php echo $advanced_css_tablet_css; ?>
}
@media only screen  and (min-device-width: 320px)  and (max-device-width: 667px) {
<?php echo $advanced_css_phone_css; ?>
}
</style>
<?php
}

add_action('wp_head', 'advanced_css_input');
?>