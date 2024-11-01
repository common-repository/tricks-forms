<?php

/**
@var array $custom_field.
@details Each custom field is an array with the following elements:
	- string @b id CSS and PHP ID.
	- string @b text Text used in the field label.
	- string @b type Attribute and field type in: 
		@b hidden, @b checkbox, @b multi_check, @b alternative, @b text, @b range, @b number, 
		@b color, @b time, @b file, @b select, @b multi_select, @b reset, @b action, @b submit and @b image.
	- optional boolean @b readonly & @b required Set it to @b true for the field to be @b readonly and @b required respectively.
	- optional numeric @b min, @b max & @b step Used on numeric fields to set @b min, @b max & @b step values respectively.
	- string @b purpose Its meaning is relative to the field @b type:
		- If @b type is @b text, @b purpose is the field type: @b email, @b url, @b password, @b search or @b text (default).
		- If @b type is @b time, @b purpose is the field type: @b week, @b month, @b date or @b time (default).
		- If @b type is @b file, @b purpose is the expected file extension.
		- If @b type is	@b multi_check, @b alternative, @b select or multi_select, 
			@b purpose is whether the options type is @b taxonomy or @b post.
		- If @b type is @b submit, @b purpose is the image URL (optional).
		.
	- array @b options An array of options, whereof the keys are their values and the elements are their texts.
	- optional mixed @b value Used on some fields as default values or button texts.
	- optional string @b description Text used in the field description.
	- optional numeric @b span Field's relative width, like 1/2, 3/4, etc.
	.
*/
$custom_field;
/** 
@brief Enqueue default styles anywhere you want.
@since 1.2
*/
function tf_init_styles() {
	wp_enqueue_style('barbosa-tricks-forms', plugins_url('style.css', __FILE__));
}
add_action('admin_head', 'tf_init_styles');
/** 
@var array $tf_custom_fields 
@brief An array of custom fields.
@details Each custom field is an array, 
	which keys are the field types and elements are:
	- @b show Function to show the field.
	- @b save Function to sanitize the value.
	. 
@since 1.1
*/
$tf_custom_fields=array();
/**
@brief Show a custom field without label or description.
@param array $field An array of custom field options.
@param optional_mixed $value If set, the field value.
@since 1.1
@see $custom_field, $tf_custom_fields.
*/
function tf_show_custom_field($field, $value=null) {
	global $tf_custom_fields;
	$tf_custom_fields[$field['type']]['show']($field, $value);
}
/**
@brief Sanitizes a field value.
@param string $type Must match a $custom_fields element key.
@param mixed $value Value to be sanitized.
@return The sanitized value.
@since 1.1
@see $tf_custom_fields.
*/
function tf_save_custom_field($type, $value) {
	global $tf_custom_fields;
	$aux=$tf_custom_fields[$type]['save'];
	return $aux? $aux($value): $value;
}
/**
@brief Add a custom field, overriding previous ones.
@param string $type Key to custom @b show and @b save methods.
@param callback $show Function to show the field.
@param optional_callback $save Function to sanitize the value.
@since 1.1
@see $tf_custom_fields.
*/
function tf_add_custom_field($type, $show, $save=null) {
	global $tf_custom_fields;
	$tf_custom_fields[$type]=array();
	$tf_custom_fields[$type]['show']=$show;
	$tf_custom_fields[$type]['save']=$save;
}
/**
@brief If text is set, show the field label.
@param array $field Checking for @b id and @b text.
@since 1.1
@see $custom_field.
*/
function tf_show_field_label($field) { 
	if($field['text']): ?>
		<label class="tf-field-label" for="<?php echo $field['id']; ?>"><?php echo $field['text']; ?></label>
	<?php endif;
}
/**
@brief If it's set, show the field description.
@param array $field Checking for @b description.
@since 1.1
@see $custom_field.
*/
function tf_show_field_description($field) {
	if($field['description']): ?>
		<span class="description tf-field-description"><?php echo $field['description']; ?></span>
	<?php endif;
}
/**
@brief If used inside tf_show_field_wrap(), 
	which is usually used inside block elements,
	it'll force a table like structure.
@param array $field Checking for @b span.
@since 1.1
@see $custom_field.
*/
function tf_column_span($field) {
	if($field['span']) echo 'style="width: ', ($field['span']*100), '%" ';
}
/**
@brief Show a label, an input-output element and a description, within a div.
@param array $field.
@param optional_mixed $value.
@since 1.1
@see $custom_field, tf_show_field_label(), tf_show_custom_field(), 
	tf_show_field_description(), tf_column_span().
*/
function tf_show_field_wrap($field, $value=null) { ?>
	<div class="tf-field-wrap" <?php tf_column_span($field); ?>>
		<?php tf_show_field_label($field);
		tf_show_custom_field($field, $value); 
		tf_show_field_description($field); ?>
	</div>
<?php }
/**
@brief If used inside a field show function,
	set it to readonly or required accordingly to $field.
@param array $field Checking for @b readonly and @b required.
@since 1.1
@see $custom_field.
*/
function tf_readonly_or_required($field) {
	if($field['readonly']) echo 'readonly ';
	if($field['required']) echo 'required ';
}
/**
@brief If used inside a field show function,
	set min, max and step attributes accordingly $field.
@param array $field Checking for @b min, @b max and @b step.
@since 1.1
@see $custom_field.
*/
function tf_min_max_and_step($field) {
	if(isset($field['min'])) echo 'min="'.$field['min'].'" ';
	if(isset($field['max'])) echo 'max="'.$field['max'].'" ';
	if(isset($field['step'])) echo 'step="'.$field['step'].'" ';
}
/**
@brief Get true or false.
@param boolean $value.
@since 1.2
*/
function tf_get_boolean($value) {
	return $value? true: false;
}
/** 
@brief If $value is numeric, return it; otherwise, return zero.
@return A numeric value.
@since 1.2
*/
function tf_get_number($value) {
	return is_numeric($value)? $value: 0;
}
/**
@brief Ensure $value is an array.
@return If $value is an array, return it; otherwise, return an array containing $value.
@since 1.2
*/
function tf_get_array($value) {
	return is_array($value)? $value: ($value? array($value): array());
}
/**
@brief Get a form value.
@param string $id The key.
@param optional_string $method Like @b get or @b post.
@return The value.
@since 1.1
*/
function tf_get_form_value($id, $method='post') {
	if($method=='get') return $_GET[$id];
	else return $_POST[$id];
}
/**
@brief Get all items of $type.
@param string $options A default or custom type.
@param optional_string $type Like @b taxonomy (default) or @b post.
@return An array which keys are terms or posts IDs and
	values are terms or posts names or titles respectively.
@since 1.2
*/
function tf_get_relationship_items($options, $type='taxonomy') {
	$aux=array();
	if($type=='post') {
		$posts=get_posts(array('post_type' => $options, 'posts_per_page' => -1));
		foreach($posts as $post) $aux[$post->ID]=$post->post_title;
	} else {
		$terms=get_terms($options);
		foreach($terms as $term) $aux[$term->term_id]=$term->name;
	}
	return $aux;
}
/**
@brief Get all default and custom meta data.
@param string $id Object ID.
@param string $type Like @b user or @b post (default).
@return An array which keys are meta keys and values are meta data.
@since 1.1
*/
function tf_get_meta_data($id, $type='post') {
	$data=($type=='user'? 
		get_user_meta($id): get_post_meta($id));
	foreach($data as $key => $value)
		$data[$key]=$value[0];
	unset($data['_edit_lock']);
	unset($data['_edit_last']);
	return $data;
}

?>