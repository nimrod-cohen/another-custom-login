<div id="login">
	<?php

	$loginUrl = wp_login_url();

	$loginLabel = AnotherCustomLogin::getSetting("login_by");

	switch($loginLabel)
	{
		case "both":
			$loginLabel = __("Username or Email",'another-custom-login');
			break;
		case "email":
			$loginLabel = __("Email Address",'another-custom-login');
			break;
		case "username":
			$loginLabel = __("Username",'another-custom-login');
			break;
	}

	if( $this->loginError )
			echo "<p class='message'>".$this->loginError."</p>";

	?><form name="loginform" id="loginform" action="<?php echo $loginUrl; ?>" method="post">
		<input type="hidden" name="action" value="do_login">
		<p class="login-username">
			<label for="user_login"><?php echo $loginLabel; ?></label>
			<input name="log" id="user_login" class="input" value="" size="20" type="text">
		</p>
		<p class="login-password">
			<label for="user_pass"><?php _e("Password",'another-custom-login'); ?></label>
			<input name="pwd" id="user_pass" class="input" value="" size="20" type="password">
		</p>

		<p class="login-remember"><label><input name="rememberme" id="rememberme" value="forever" type="checkbox"> <?php _e("Remember Me",'another-custom-login'); ?></label></p>
		<p class="login-submit">
			<input name="wp-submit" id="wp-submit" class="button-primary" value="<?php _e('Log in','another-custom-login'); ?>" type="submit">
		</p>
	</form>
	<p id="nav">
		<a href="<?php echo wp_lostpassword_url(); ?>"><?php _e("Lost your password?",'another-custom-login'); ?></a>
	</p>
	<p id="backtoblog">
		<a href="<?php echo home_url(); ?>"><?php _e("← Back to ",'another-custom-login'); ?> <?php echo get_bloginfo("name");?></a>
	</p>
</div>
