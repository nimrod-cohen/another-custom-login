<div id="login">
	<?php
	global $anculo_error;

	$loginUrl = wp_login_url();

	if( $anculo_error )
		echo "<p class='message'>".$anculo_error."</p>";
	else
		echo "<p class='message'>".__("Please enter your email address. You will receieve a link to create a new password via email",'another-custom-login')."</p>";
	?><form name="loginform" id="loginform" action="<?php echo $loginUrl; ?>" method="post">
		<input type="hidden" name="action" value="do_lostpassword">
		<p class="login-username">
			<label for="user_login"><?php _e("Email Address",'another-custom-login'); ?></label>
			<input name="user_login" id="user_login" class="input" value="" size="20" type="text">
		</p>

		<p class="login-submit">
			<input name="wp-submit" id="wp-submit" class="button-primary" value="<?php _e('Get New Password','another-custom-login'); ?>" type="submit">
		</p>
	</form>
	<p id="nav">
		<a href="<?php echo $loginUrl; ?>"><?php _e("Log in",'another-custom-login'); ?></a>
	</p>
	<p id="backtoblog">
		<a href="<?php echo home_url(); ?>"><?php _e("← Back to ",'another-custom-login'); ?> <?php echo get_bloginfo("name");?></a>
	</p>
</div>
