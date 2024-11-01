//Change field-image. Used in tf_show_image_field (custom-fields.php).
function tf_change_image_field(id) {
	var value=document.getElementById(id).value;
	document.getElementById("tf-image-"+id).src=value;
}