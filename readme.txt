=== More Taxonomy Content For Jigoshop ===

== Description ==
This plugin allows you to add additional HTML content to your Product Tag and Category pages (Wrote this for a client)

== Installation ==

= Install =

1. Unzip the zip file. 
1. Upload the the `Currency-Converter-Jigoshop` folder (not just the files in it!) to your `wp-contents/plugins` folder.

= Activate =

1. In your WordPress administration, go to the Plugins page
2. Activate the More Taxonomy Content For Jigoshop

== Documentation ==

-- Base Usage

Once installed you will see a Additional Taxonomy Content menu appear under the Products section of the WP admin navigation. The settings are split into two tabbed sections Categories and Tags.

Its a pretty simple plugin, just add the relevant HTML content to the category or tag your require and your done.

******* IMPORTANT **************
You will need to add a quick function call to your product-taxonomy.php template. So wherever you want your content to appear add this line of script.

<?php if(function_exists('jigoshop_additonal_taxonomy')) { jigoshop_additonal_taxonomy(); } ?>