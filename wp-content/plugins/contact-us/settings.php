<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( $this->name ); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( $this->tag.'_options' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[name]"><?php _e( 'Name' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'name', $this->options ) ) { esc_html_e( $this->options['name'] ); } ?>" id="<?php echo $this->tag; ?>[name]" name="<?php echo $this->tag; ?>[name]"/>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[phone]"><?php _e( 'Phone' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'phone', $this->options ) ) { esc_html_e( $this->options['phone'] ); } ?>" id="<?php echo $this->tag; ?>[phone]" name="<?php echo $this->tag; ?>[phone]"/>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[fax]"><?php _e( 'Fax' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'fax', $this->options ) ) { esc_html_e( $this->options['fax'] ); } ?>" id="<?php echo $this->tag; ?>[fax]" name="<?php echo $this->tag; ?>[fax]"/>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[mobile]"><?php _e( 'Mobile' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'mobile', $this->options ) ) { esc_html_e( $this->options['mobile'] ); } ?>" id="<?php echo $this->tag; ?>[mobile]" name="<?php echo $this->tag; ?>[mobile]"/>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[hours]"><?php _e( 'Hours' ); ?></label>
				</th>
				<td>
					<textarea class="regular-text" cols="40" rows="5" id="<?php echo $this->tag; ?>[hours]" name="<?php echo $this->tag; ?>[hours]"><?php if ( array_key_exists( 'hours', $this->options ) ) { esc_html_e( $this->options['hours'] ); } ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[email]"><?php _e( 'Email' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'email', $this->options ) ) { esc_html_e( $this->options['email'] ); } ?>" id="<?php echo $this->tag; ?>[email]" name="<?php echo $this->tag; ?>[email]"/>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[location_address]"><?php _e( 'Location Address' ); ?></label>
				</th>
				<td>
					<textarea class="regular-text" cols="40" rows="5" id="<?php echo $this->tag; ?>[location_address]" name="<?php echo $this->tag; ?>[location_address]"><?php if ( array_key_exists( 'location_address', $this->options ) ) { esc_html_e( $this->options['location_address'] ); } ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[mailing_address]"><?php _e( 'Mailing Address' ); ?></label>
				</th>
				<td>
					<textarea class="regular-text" cols="40" rows="5" id="<?php echo $this->tag; ?>[mailing_address]" name="<?php echo $this->tag; ?>[mailing_address]"><?php if ( array_key_exists( 'mailing_address', $this->options ) ) { esc_html_e( $this->options['mailing_address'] ); } ?></textarea>
				</td>
			</tr>
		</table>
		
		<hr />
		
		<table class="form-table">
			<tr valign="top">
				<th>
					<?php _e( 'reCaptcha' ); ?>
					<p><a href="http://www.google.com/recaptcha/whyrecaptcha" target="_blank">Signup</a> for an account and keys.
				</th>
				<td>
				  <fieldset>
				    <legend class="screen-reader-text"><span><?php _e( 'reCaptcha' ); ?></span></legend> 
				    <label title='Enabled'><input type="radio" <?php if ( !array_key_exists( 'recaptcha_enable', $this->options ) || ( array_key_exists( 'recaptcha_enable', $this->options ) && $this->options['recaptcha_enable'] == 'false' ) ) { echo 'checked="true"'; } ?> id="" name="<?php echo $this->tag; ?>[recaptcha_enable]" value="false" /> <span><?php _e( 'Disabled' ); ?></span></label><br /> 
				    <label title='Enabled'><input type="radio" <?php if ( array_key_exists( 'recaptcha_enable', $this->options ) && $this->options['recaptcha_enable'] == 'true' ) { echo 'checked="true"'; } ?> id="" name="<?php echo $this->tag; ?>[recaptcha_enable]" value="true" /> <span><?php _e( 'Enabled' ); ?></span></label>
				  </fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[recaptcha_private_key]"><?php _e( 'Private Key' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'recaptcha_private_key', $this->options ) ) { esc_html_e( $this->options['recaptcha_private_key'] ); } ?>" id="<?php echo $this->tag; ?>[recaptcha_private_key]" name="<?php echo $this->tag; ?>[recaptcha_private_key]"/>
				</td>
			</tr>
			<tr valign="top">
				<th>
					<label for="<?php echo $this->tag; ?>[recaptcha_public_key]"><?php _e( 'Public Key' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" value="<?php if ( array_key_exists( 'recaptcha_public_key', $this->options ) ) { esc_html_e( $this->options['recaptcha_public_key'] ); } ?>" id="<?php echo $this->tag; ?>[recaptcha_public_key]" name="<?php echo $this->tag; ?>[recaptcha_public_key]"/>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
		</p>
	</form>
</div>