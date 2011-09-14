=== Limit Post Add-On ===
Contributors: Doc4
Donate link: http://www.doc4design.com/donate
Tags: limit post, limit text, limit copy, copy, post
Requires at least: 1.5
Tested up to: 3.1
Stable tag: 1.0


== Description ==

= Plugin URL =
http://www.doc4design.com/plugins/limit-post/

"Limit-Post" is one of the better WordPress post content limiters we have come across both in terms of usability and size. Developed by labitacora.net "Limit-Post" provides excellent control over the post character-length and even adds the ability to create "read more ..." link with a single line of code.

With the "Limit Post Add-On" we have expanded the original plugin to include WordPress' get_the_content tag in order to limit post copy with stripped html tags.


== Screenshots ==

View Screenshots:
http://www.doc4design.com/plugins/limit-post


== Installation ==

To install the plugin just follow these simple steps:

1. Download the plugin and expand it.
2. Copy the limitpost-addon folder into your plugins folder ( wp-content/plugins ).
3. Log-in to the WordPress administration panel and visit the Plugins page.
4. Locate the Limit Post plugin and click on the activate link.
5. Replace the_content(); with the_content_limit(200, "continue..."); or
6. Replace the_content(); with get_the_content(200, "continue...");