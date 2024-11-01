<?php require_once(__DIR__.'/functions.php');

/**
@brief Show (not exactly) a hidden field.
@param array $field Array of field options.
@param optional_mixed $value.
@since 1.1
@see $custom_field.
*/
function tf_show_hidden_field($field, $value=null) { ?>
	<input type="hidden" name="<?php echo $field['id']; ?>" value="<?php echo $value; ?>" />
<?php }
tf_add_custom_field('hidden', 'tf_show_hidden_field', null);
/**
@brief Show a checkbox field.
@param array $field Array of field options.
@param optional_boolean $value A boolean value.
@since 1.1
@see $custom_field.
*/
function tf_show_checkbox_field($field, $value=false) { ?>
	<input type="checkbox" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); ?> value="true" <?php if($value) echo 'checked '; ?>
	class="tf-checkbox-field" id="<?php echo $field['id']; ?>" />
<?php }
tf_add_custom_field('checkbox', 'tf_show_checkbox_field', 'tf_get_boolean');
/**
@brief Show a checkbox group.
@param array $field Array of field options.
@param optional_array $options Array of options.
@since 1.1
@see $custom_field.
*/
function tf_show_multi_check_field($field, $options=array()) { ?>
	<div class="tf-checkbox-group" id="<?php echo $field['id']; ?>">
	<?php if($field['purpose']) $field['options']=
		tf_get_relationship_items($field['options'], $field['purpose']);
	foreach($field['options'] as $value => $text): ?>
		<div class="tf-checkbox-wrap">
			<input type="checkbox" name="<?php echo $field['id']; ?>[]" 
				<?php tf_readonly_or_required($field); ?> value="<?php echo $value; ?>"
				<?php if(is_array($options) && in_array($value, $options)) echo 'checked '; ?>
				class="tf-checkbox-field" id="<?php echo $value; ?>" />
			<label for="<?php echo $value; ?>"><?php echo $text; ?></label>
		</div>
	<?php endforeach; ?>
	</div>
<?php }
tf_add_custom_field('multi_check', 'tf_show_multi_check_field', 'tf_get_array');
/**
@brief Show an alternative field.
@param array $field Array of field options.
@param optional_string $options Checked option.
@since 1.1
@see $custom_field.
*/
function tf_show_alternative_field($field, $option=null) { ?>
	<div class="tf-alternative-group" id="<?php echo $field['id']; ?>">
	<?php if($field['purpose']) $field['options']=
		tf_get_relationship_items($field['options'], $field['purpose']);
	foreach($field['options'] as $value => $text): ?>
		<div class="tf-alternative-field">
			<input type="radio" name="<?php echo $field['id']; ?>"
				<?php tf_readonly_or_required($field); ?> id="<?php echo $value; ?>"
				value="<?php echo $value; ?>" <?php if($value==$option) echo 'checked '; ?> />
			<label for="<?php echo $value; ?>"><?php echo $text; ?></label>
		</div>
	<?php endforeach; ?>
	</div>
<?php }
tf_add_custom_field('alternative', 'tf_show_alternative_field', null);
/**
@brief Show a text field.
@param array $field Array of field options.
@param optional_string $value A text value.
@since 1.1
@see $custom_field.
*/
function tf_show_text_field($field, $value='') { 
	if(!$field['purpose']) $field['purpose']='text'; ?>
	<input type="<?php echo $field['purpose']; ?>" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); ?> value="<?php echo $value; ?>"
	class="tf-text-field" id="<?php echo $field['id']; ?>" />
<?php }
tf_add_custom_field('text', 'tf_show_text_field', null);
/**
@brief Show an analogic range field.
@param array $field Array of field options.
@param optional_numeric $value A numeric value.
@since 1.1
@see $custom_field.
*/
function tf_show_range_field($field, $value=0) { ?>
	<input type="range" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); tf_min_max_and_step($field); ?> value="<?php echo $value; ?>"
	class="tf-range-field" id="<?php echo $field['id']; ?>" />
<?php }
/* @see tf_show_range_field(). */
tf_add_custom_field('range', 'tf_show_range_field', null);
/**
@brief Show a number field.
@param array $field Array of field options.
@param optional_numeric $value A numeric value.
@since 1.1
@see $custom_field.
*/
function tf_show_number_field($field, $value=0) { ?>
	<input type="number" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); tf_min_max_and_step($field); ?> value="<?php echo $value; ?>"
	class="tf-number-field" id="<?php echo $field['id']; ?>" />
<?php }
tf_add_custom_field('number', 'tf_show_number_field', 'tf_get_number');
/**
@brief Show a color picker.
@param array $field Array of field options.
@param string $value Like #XXX or #XXXXXX.
@since 1.1
@see $custom_field.
*/
function tf_show_color_field($field, $value='#000') { ?>
	<input type="color" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); ?> value="<?php echo $value; ?>"
	class="tf-color-field" id="<?php echo $field['id']; ?>" />
<?php }
tf_add_custom_field('color', 'tf_show_color_field', null);
/**
@brief Show a time, week, month, date or any specific time field.
@param array $field Array of field options.
@param mixed $value A specific time value.
@since 1.1
@see $custom_field.
*/
function tf_show_time_field($field, $value=null) { 
	if(!$field['purpose']) $field['purpose']='time'; ?>
	<input type="<?php echo $field['purpose']; ?>" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); ?> value="<?php echo $value; ?>"
	class="tf-time-field" id="<?php echo $field['id']; ?>" />
<?php }
tf_add_custom_field('time', 'tf_show_time_field', null);
/**
@brief Show a file field.
@param array $field Array of field options.
@since 1.1
@see $custom_field.
*/
function tf_show_file_field($field, $value=null) { ?>
	<input type="file" name="<?php echo $field['id']; ?>"
	<?php tf_readonly_or_required($field); if($field['purpose']) echo 'accept="'.$field['purpose'].'" '; ?>
	class="tf-file-field" id="<?php echo $field['id']; ?>" />
<?php }
tf_add_custom_field('file', 'tf_show_file_field', null);
/**
@brief Show a generic option list field.
@param array $field Array of field options.
@param optional_mixed $option A reference for the field value.
@since 1.1
@see $custom_field.
*/
function tf_show_select_field($field, $option=null) { ?>
	<select name="<?php echo $field['id']; ?>" class="tf-select-field"
	id="<?php echo $field['id']; ?>" <?php tf_readonly_or_required($field); ?>>
	<?php if($field['purpose']) $field['options']=
		tf_get_relationship_items($field['options'], $field['purpose']);
	foreach($field['options'] as $value => $text): ?>
		<option value="<?php echo $value; ?>"
		<?php if($value==$option) echo 'selected '; ?>>
		<?php echo $text; ?></option>
	<?php endforeach; ?>
	</select>
<?php }
tf_add_custom_field('select', 'tf_show_select_field', null);
/**
@brief Show a multiple options list field.
@param array $field Array of field options.
@param optional_array $options Array of options.
@since 1.1
@see $custom_field.
*/
function tf_show_multi_select_field($field, $options=array()) { ?>
	<select name="<?php echo $field['id']; ?>" class="tf-select-field"
	id="<?php echo $field['id']; ?>" <?php tf_readonly_or_required($field); ?> multiple>
	<?php if($field['purpose']) $field['options']=
		tf_get_relationship_items($field['options'], $field['purpose']);
	foreach($field['options'] as $value => $text): ?>
		<option value="<?php echo $value; ?>"
		<?php if(is_array($options) && in_array($value, $options)) echo 'selected '; ?>>
		<?php echo $text; ?></option>
	<?php endforeach; ?>
	</select>
<?php }
tf_add_custom_field('multi_select', 'tf_show_multi_select_field', 'tf_get_array');
/**
@brief Show a reset field for a custom form.
@param optional_array $field Array of field options.
@since 1.2
@see $custom_field.
*/
function tf_show_reset_field($field=null) { ?>
	<input type="reset" class="tf-reset-field" 
	<?php if($field['id']) echo 'id="'.$field['id'].'" '; ?>
	<?php if($field['value']) echo 'value="'.$field['value'].'" '; ?> />
<?php }
tf_add_custom_field('reset', 'tf_show_reset_field', null);
/**
@brief Show an action button.
@param array $field Array of field options.
@since 1.1
@see $custom_field.
*/
function tf_show_action_button($field) { ?>
	<input type="button" class="tf-action-button" id="<?php echo $field['id']; ?>"
	onclick="<?php echo $field['purpose']; ?>" value="<?php echo $field['value']; ?>" />
<?php }
tf_add_custom_field('action', 'tf_show_action_button', null);
/**
@brief Show a submit field for a custom form.
@param optional_array $field Array of field options.
@since 1.2
@see $custom_field.
*/
function tf_show_submit_field($field=null) { ?>
	<input type="<?php echo ($field['purpose']? 'image': 'submit'); ?>"
	class="<?php if($field['purpose']) echo 'tf-image-field '; ?>tf-submit-field"
	<?php if($field['purpose']) echo 'src="'.$field['purpose'].'" ';
	if($field['id']) echo 'name="'.$field['id'].'" id="'.$field['id'].'" ';
	if($field['value']) echo 'value="'.$field['value'].'" '; ?> />
<?php }
tf_add_custom_field('submit', 'tf_show_submit_field', null);
/**
@brief Show a image-URL input field.
@param array $field Array of field options.
	If @b value is set, it shows a button for viewing the image.
@param optional_string $value Image URL.
@since 1.3
@see $custom_field.
*/
function tf_show_image_field($field, $value=null) { ?>
	<div class="tf-image-field">
		<img src="<?php echo $value? $value: plugins_url('no-image.png', __FILE__); ?>" 
			class="tf-field-image" id="<?php echo 'tf-image-'.$field['id']; ?>" />
		<div>
			<?php $text_field=$field; 
			$text_field['type']='text'; $text_field['purpose']='url';
			tf_show_text_field($text_field, $value);
			if($field['value']) {
				wp_enqueue_script('tricks-forms', plugins_url('script.js', __FILE__));
				$action_button=array('purpose' => "tf_change_image_field('{$field['id']}')", 'value' => $field['value']);
				tf_show_action_button($action_button);
			} ?>
		</div>
	</div>
<?php }
tf_add_custom_field('image', 'tf_show_image_field', null);

?>