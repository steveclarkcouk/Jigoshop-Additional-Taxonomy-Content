<?php
/*
   Plugin Name: Jigoshop - Additonal Taxonomy Content
   Plugin URI: 
   Description: Add Further descriptive content to your Jigoshop Category / Tags Pages
   Version: 1.0
   Author: Steve Clark
   Author URI: www.the-escape.co.uk
 */

include 'classes/jigoshop-taxonmy-meta.class.php';


// --- Admin Builder
$jct_admin = new Jigoshop_Additonal_Taxonomy_Content_Admin();
$jct_admin->setData('prefix', 'jigo_tax_');

function jigoshop_additonal_taxonomy()
{
	global $term, $jct_admin;
	$term_additonal_content = get_option($jct_admin->data['prefix'] . 'tax_' . $term);
	if($term_additonal_content) echo '<div class="more-tax-desc">' . html_entity_decode(wp_richedit_pre( stripslashes ($term_additonal_content) )) . '</div>';
}

?>