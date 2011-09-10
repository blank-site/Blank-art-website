=== Contact Us ===
Contributors: EONMediaGroup
Donate link: http://wordpress.org/extend/plugins/contact/
Tags: contact, contact us, business, global, details, options, info, phone, fax, mobile, email, address, form, captcha, recaptcha
Requires at least: 2.8.2
Tested up to: 3.2.1
Stable tag: 1.6

Adds the ability to easily enter and display contact information.

== Description ==

*Contact Us* adds the ability to enter business contact information, business hours, business location, etc and output the details in your posts, pages or templates.

Use the shortcode `[contact_us type="phone" heading="Corporate Phone Number"]` to display any of the contact information, or use the function call `<?php if (function_exists('contact_us')) { contact_us('phone'); } ?>`.

**Shortcode Options:**

`
* type => false
* heading => ''
* heading_open_tag => '<h4>'
* heading_close_tag => '</h4>'
* nl2br => false
* before => ''
* after => ''
`

Once you have defined a contact email address, use the shortcode `[contact_us type="form"]` to output the contact form.

Thanks to http://wordpress.org/extend/plugins/profile/36flavours for "Contact Details" plugin

== Installation ==

Here we go:

1. Upload the `contact-extended` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Enter you contact information on the options page `Settings > Contact Us`.
4. Display the information using either the shortcodes or function calls.

== Frequently Asked Questions ==

= How do I edit my contact information? =

Navigate to the settings page by clicking on `Settings` on the left hand menu, and then the `Contact Us` option.

= How do I include the information in my template? =

You can use the following function call to output information in your templates:

`<?php if (function_exists('contact_us')) { contact_us('fax'); } ?>`

= What contact information can I store? =

Current available contact fields are: `name`, `phone`, `fax`, `mobile`, `email`, `hours`, `location_address` and `mailing_address`.

= How do you fetch contact information without outputting the value? =

The last parameter passed to `contact_us()` determines whether the value is returned, by setting the value to false.

`<?php $phone = contact_us('phone', '', '', '', true, '<b>', '</b>', false); ?>`

The above code will fetch the phone number stored and wrap the response in bold tags.

= How can I customize the contact form? =

If you require more customization that cannot be achieved using CSS, you can define your own template file.

To do this add the the attribute `include` to the shortcode tag, e.g. `[contact type="form" include="myfile.php"]`.

This file should be placed within your theme directory and should include the processing and output of errors.

We suggest you use the `contact.php` file used by the plugin as a starting point / template.

= How do I enable reCaptcha? =

Navigate to the settings page: `Settings > Contact Us` and select to enable the reCaptcha and fill out the appropriate keys.

== Screenshots ==

1. The contact us settings page.
2. The reCaptcha options.

== Changelog ==

= 1.6 =
* Fixed form to validate against W3C
= 1.5 =
* Fixed readme to reflect rendering
= 1.4 =
* Fixed bugs in the shortcode execution
= 1.3 =
* Changed to new version number
= 1.2 =
* Added reCaptcha support
= 1.1 =
* Added extra shortcode options
= 1.0 =
* Added new fields, and converted into a separate plugin
= 0.7.1 =
* Started new plugin from contact