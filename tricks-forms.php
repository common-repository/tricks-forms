<?php /** 
Plugin Name: Tricks & Forms
Description: Custom posts, profiles and options fields and custom forms.
Tags: custom fields, custom forms, custom profile, custom options.
Version: 1.3
Author: Alfredo Barbosa
Author URI: br.linkedin.com/pub/alfredo-barbosa/90/866/a9a/
*/

require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/custom-fields.php');

/**
@var array $custom_form.
@details Each custom form is an array with the following elements:
	- string @b id CSS ID.
	- string @b title If set, show a heading.
	- string @b type Form method, like @b get or @b post (default).
	- string @b purpose Submiting URL.
	- array @b fields Array of fields.
	.
@see $custom_field.
*/
$custom_form;

/**
@brief Show a custom form detailed by $form.
@param array $form Array of forms fields and other options.
@since 1.2
@see $custom_form, tf_show_field_wrap().
*/
function tf_show_custom_form($form) { ?>
	<div class="tf-form-box" id="<?php echo $form['id']; ?>">
	<?php if($form['title']): ?>
		<h4 class="tf-form-heading"><?php echo $form['title']; ?></h4>
	<?php endif; ?>
		<form class="tf-custom-form"
			method="<?php echo $form['type']? $form['type']: 'post'; ?>"
			action="<?php echo $form['purpose']; ?>">
		<?php foreach($form['fields'] as $field)
			tf_show_field_wrap($field); ?>
		</form>
	</div>
<?php }
/**
@var array $tf_custom_boxes.
@brief Array of custom meta boxes.
@details Each custom box is an array with the following elements:
	- string @b id and @b title Same as $custom_form.
	- string or array @b type Post type or array of post types.
	- string @b context and @b priority Box @b context and @priority attributes.
		<http://codex.wordpress.org/Function_Reference/add_meta_box>
	- array @b fields Same as $custom_form.
	.
@since 1.1
@see $custom_field, $custom_form.
*/
$tf_custom_boxes=array();
/**
@brief Add a custom post meta box.
@param array $custom_box Custom post meta box to be added.
@since 1.1
@see $tf_custom_boxes.
*/
function tf_add_custom_box($custom_box) {
	global $tf_custom_boxes;
	$tf_custom_boxes[]=$custom_box;
}
/**
@brief Add some or all fields from $base to $derivate type.
@param string $derivate Derivate type, which you want to inherit from $base.
@param string or array $box Box or boxes you want $derivate to inherit.
@param optional_string $base Base type, which you want $derivate to inherit from.
@since 1.2
*/
function tf_inherit_type($derivate, $box, $base=null) {
	global $tf_custom_boxes;
	$box=tf_get_array($box);
	//real change to the global
	foreach($tf_custom_boxes as &$custom_box) {
		$custom_box['type']=tf_get_array($custom_box['type']);
		if(!$box || in_array($custom_box['id'], $box))
			if(!$base || in_array($base, $custom_box['type']))
				$custom_box['type'][]=$derivate;
	}
}
/**
@brief Show an array of fields in a custom box.
@param object $post Used to get field values.
@param array $fields Array of custom fields.
@since 1.1
@see tf_show_field_wrap().
*/
function tf_show_custom_box($post, $fields) {
	//since it's called by add_meta_box()
	if($fields['args']) $fields=$fields['args']; ?>
	<div class="tf-custom-box">
	<?php foreach($fields as $field)
		tf_show_field_wrap($field, get_post_meta($post->ID, $field['id'], true)); ?>
	</div>
<?php }
/**
@brief Init all custom meta boxes.
@since 1.1
@see $tf_custom_boxes, tf_show_custom_box().
*/
function tf_custom_boxes_function() {
	global $tf_custom_boxes;
	//for each meta box
	foreach($tf_custom_boxes as $custom_box) {
		//set defaults
		if(!$custom_box['type']) $custom_box['type']='post';
		if(!$custom_box['context']) $custom_box['context']='normal';
		if(!$custom_box['priority']) $custom_box['priority']='default';
		//turn string into array
		$custom_box['type']=tf_get_array($custom_box['type']);
		//now we're ready
		foreach($custom_box['type'] as $type)
			add_meta_box($custom_box['id'], $custom_box['title'], 
				'tf_show_custom_box', $type, $custom_box['context'], 
				$custom_box['priority'], $custom_box['fields']);
	}
}
add_action('add_meta_boxes', 'tf_custom_boxes_function');
/**
@brief Save all custom field values.
@param string $post_id ID of the post to be saved.
@since 1.1
@see $tf_custom_boxes, tf_save_custom_field().
*/
function tf_save_custom_data($post_id) {
	global $tf_custom_boxes;
	foreach($tf_custom_boxes as $custom_box) {
		$custom_box['type']=tf_get_array($custom_box['type']);
		foreach($custom_box['type'] as $type)
			if($type==get_post_type($post_id))
				foreach($custom_box['fields'] as $field) {
					$value=tf_get_form_value($field['id']);
					$value=tf_save_custom_field($field['type'], $value);
					update_post_meta($post_id, $field['id'], $value);
				}
	}	
}
add_action('save_post', 'tf_save_custom_data');
/**
@var array $tf_profile_boxes 
@brief Array of custom profile data.
@details Each profile box is an array with the following elements:
	- string @b id and @b title Same as $custom_form.
	- optional string @b type Permission users need to have this profile data.
		<http://codex.wordpress.org/Roles_and_Capabilities>
	- array @b fields Same as $custom_form.
	.
@since 1.1
@see $custom_field, $custom_form.
*/
$tf_profile_boxes=array();
/**
@brief Add a custom profile data box.
@param array $profile_box Array of custom profile data.
@since 1.1
@see $tf_profile_boxes.
*/
function tf_add_profile_box($profile_box) {
	global $tf_profile_boxes;
	$tf_profile_boxes[]=$profile_box;
}
/**
@brief Show all custom profile data.
@param object $user Used to get field values.
@since 1.1
@see $tf_profile_boxes, tf_show_field_wrap().
*/
function tf_show_profile_boxes($user) {
	global $tf_profile_boxes; 
	foreach($tf_profile_boxes as $profile_box)
		//if either type is not set or this user can do that
		if(!$profile_box['type'] || user_can($user, $profile_box['type'])): ?>
		<div class="tf-profile-box" id="<?php echo $profile_box['id']; ?>">
			<h3><?php echo $profile_box['title']; ?></h3>
			<div class="tf-custom-box">
			<?php foreach($profile_box['fields'] as $field)
				tf_show_field_wrap($field, get_user_meta($user->ID, $field['id'], true)); ?>
			</div>
		</div>
		<?php endif;
}
add_action('show_user_profile', 'tf_show_profile_boxes');
add_action('edit_user_profile', 'tf_show_profile_boxes');
/**
@brief Save all custom profile data.
@param string $user_id The user to be saved.
@since 1.1
@see $tf_profile_boxes, tf_save_custom_field().
*/
function tf_save_profile_data($user_id) {
	global $tf_profile_boxes;
	foreach($tf_profile_boxes as $profile_box)
		foreach($profile_box['fields'] as $field) {
			$value=tf_get_form_value($field['id']);
			$value=tf_save_custom_field($field['type'], $value);
			update_user_meta($user_id, $field['id'], $value);
		}	
}
add_action('personal_options_update', 'tf_save_profile_data');
add_action('edit_user_profile_update', 'tf_save_profile_data');
/**
@var array $custom_options
@brief Array of custom options screens.
@details Each options screen is an array with the following elements:
	- string @b id and @b title Same as $custom_form.
	- string @b type Permission users need to access this data.
	- optional string @b menu If set, this options screen is a submenu of @b menu.
	- array @b sections Array of sections, whereof each section is an array with the following elements:
		- string @b id and @b title Same as $custom_form.
		- array @b options Array of options fields, same as $custom_form.
		.
	.
@since 1.1
*/
$tf_custom_options=array();
/**
@brief Add a custom options screen.
@param array @options_screen A whole options screen.
@since 1.1
@see $tf_custom_options.
*/
function tf_add_options_screen($options_screen) {
	global $tf_custom_options;
	$tf_custom_options[]=$options_screen;
}
/**
@brief Show a custom field in an options screen.
@since 1.1
@see tf_show_custom_field(), tf_show_field_description().
*/
function tf_show_option_field($field) {
	tf_show_custom_field($field, get_option($field['id']));
	tf_show_field_description($field);
}
/**
@brief A default options screen callback function.
@param array $options_screen The options screen to be shown.
@return A default options screen callback function.
@since 1.1
@see $tf_custom_options.
*/
function tf_show_options_screen($options_screen) {
	return function() use($options_screen) { ?><div class="wrap">
		<h2><?php echo $options_screen['title']; ?></h2>
		<form method="post" action="options.php"> 
		
			<?php settings_fields($options_screen['id']);
			do_settings_sections($options_screen['id']); ?>
		
			<?php submit_button(); ?>
		</form>
	</div><?php };
}
/**
@brief Init a whole options set or menu.
@since 1.1
*/
function tf_init_custom_options() {
	global $tf_custom_options;
	foreach($tf_custom_options as $options_screen) {
		if($options_screen['sections']) foreach($options_screen['sections'] as $options_section) {
			add_settings_section($options_section['id'], $options_section['title'], false, $options_screen['id']);
			if($options_section['options']) foreach($options_section['options'] as $options_setting) {
				add_settings_field($options_setting['id'], $options_setting['text'], 'tf_show_option_field', 
					$options_screen['id'], $options_section['id'], $options_setting);
				register_setting($options_screen['id'], $options_setting['id']);
			}
		}
		$type=($options_screen['type']? $options_screen['type']: 'read');
		if($options_screen['menu']) {
			add_submenu_page($options_screen['menu'], 
				$options_screen['title'], $options_screen['title'], $type, 
				$options_screen['id'], tf_show_options_screen($options_screen));
			remove_submenu_page($options_screen['menu'], $options_screen['menu']);
		} else add_menu_page($options_screen['title'], $options_screen['title'], $type, 
			$options_screen['id'], tf_show_options_screen($options_screen));
	}
}
add_action('admin_menu', 'tf_init_custom_options');

?>