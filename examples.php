<?php 

$fields=array(
	array(
		'id' => 'checkbox',
		'text' => 'Checkbox',
		'type' => 'checkbox',
		'span' => 1/3,
		'description' => 'Just a checkbox.',
	),
	array(
		'id' => 'multi_check',
		'text' => 'Multi Check',
		'type' => 'multi_check',
		'options' => array(
			'check1' => 'Check 1',
			'check2' => 'Check 2',
			'check3' => 'Check 3',
			'check4' => 'Check 4',
		),
		'span' => 1/3,
		'description' => 'Just a multi check.',
	),
	array(
		'id' => 'alternative',
		'text' => 'Alternative',
		'type' => 'alternative',
		'options' => array(
			'yes' => 'Yes',
			'no' => 'No',
		),
		'span' => 1/3,
		'description' => 'Just an alternative field.',
	),
	array(
		'id' => 'email',
		'text' => 'Email',
		'type' => 'text',
		'purpose' => 'email',
		'span' => 1/4,
		'description' => 'Fill it with an email.',
	),
	array(
		'id' => 'url',
		'text' => 'URL',
		'type' => 'text',
		'purpose' => 'url',
		'span' => 1/4,
		'description' => 'Fill it with an URL.',
	),
	array(
		'id' => 'password',
		'text' => 'Password',
		'type' => 'text',
		'purpose' => 'password',
		'span' => 1/4,
		'description' => 'Fill it with a password.',
	),
	array(
		'id' => 'text',
		'text' => 'Text',
		'type' => 'text',
		//'purpose' => 'text',
		'span' => 1/4,
		'description' => 'Fill it with a text.',
	),
	array(
		'id' => 'range',
		'text' => 'Range',
		'type' => 'range',
		'span' => 1/2,
	),
	array(
		'id' => 'number',
		'text' => 'Number',
		'type' => 'number',
		'min' => 0,
		'max' => 100,
		'step' => 10,
		'span' => 1/2,
	),
	array(
		'id' => 'week',
		'text' => 'Week',
		'type' => 'time',
		'purpose' => 'week',
		'span' => 1/4,
	),
	array(
		'id' => 'month',
		'text' => 'Month',
		'type' => 'time',
		'purpose' => 'month',
		'span' => 1/4,
	),
	array(
		'id' => 'date',
		'text' => 'Date',
		'type' => 'time',
		'purpose' => 'date',
		'span' => 1/4,
	),
	array(
		'id' => 'time',
		'text' => 'Time',
		'type' => 'time',
		//'purpose' => 'time',
		'span' => 1/4,
	),
	array(
		'id' => 'select',
		'text' => 'Select',
		'type' => 'select',
		'options' => array(
			'option1' => 'Option 1',
			'option2' => 'Option 2',
		),
		'span' => 1/3,
	),
	array(
		'id' => 'multi_select1',
		'text' => 'Multi Select 1',
		'type' => 'multi_select',
		'purpose' => 'taxonomy',
		'options' => 'category',
		'span' => 1/3,
	),
	array(
		'id' => 'multi_select2',
		'text' => 'Multi Select 2',
		'type' => 'multi_select',
		'purpose' => 'post',
		'options' => 'post',
		'span' => 1/3,
	),
	array(
		'id' => 'imageX',
		'text' => 'Image',
		'type' => 'image',
		'value' => 'View',
		'description' => 'This is an image.',
		'span' => 1,
	),
);

$custom_box=array(
	'id' => 'example',
	'title' => 'Example',
	'type' => 'post',
	'fields'=> $fields,
	'context' => 'normal',
	'priority' => 'default',
);
tf_add_custom_box($custom_box);

$profile_box=array(
	'id' => 'another_example',
	'title' => 'Another Example',
	'type' => 'read',
	'fields' => $fields,
);
tf_add_profile_box($profile_box);

$options_screen=array(
	'id' => 'last_example',
	'title' => 'Last Example',
	'type' => 'read',
	'menu' => 'options-general.php',
	'sections' => array(
		array(
			'id' => 'default',
			'options' => array(
				array(
					'id' => 'textX',
					'text' => 'Text X',
					'type' => 'text',
				),
			),
		),
		array(
			'id' => 'section1',
			'title' => 'Section 1',
			'options' => array(
				array(
					'id' => 'numberX',
					'text' => 'Number X',
					'type' => 'number',
				),
			),
		),
		array(
			'id' => 'section2',
			'title' => 'Section 2',
			'options' => $fields,
		),
	),
);
tf_add_options_screen($options_screen); 

$fields[]=array('type' => 'submit');

$custom_form=array(
	'id' => 'form_id',
	'title' => 'Form Title',
	'fields' => $fields,
	'purpose' => '<page url>',
	'type' => 'post',
);
//tf_show_custom_form($custom_form);

?>