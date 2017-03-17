<?php

	$loginUrl = wp_login_url();

	$doResetUrl = add_query_arg("action","do_resetpassword",$loginUrl);

	$minStrength = AnotherCustomLogin::getSetting("pass_strength");
?>
<div id="login">
	<p class="message reset-pass"><?php _e("Enter your new password below","another-custom-login")?>.</p>
	<form name="resetpassform" id="resetpassform" action="<?php echo $doResetUrl; ?>" method="post">
		<input id="user_login" name="user_login" value="<?php echo $_GET["login"]; ?>" type="hidden">
		<input id="key" name="key" value="<?php echo $_GET["key"]; ?>" type="hidden">
		<p>
			<label for="pass1"><?php _e("New password","another-custom-login"); ?><br>
				<input name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" type="password"></label>
		</p>
		<p>
			<label for="pass2"><?php _e("Confirm new password","another-custom-login"); ?><br>
				<input name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" type="password"></label>
		</p>

		<div id="pass-strength-result" class="hide-if-no-js" style="display: block;"><?php _e("Strength indicator","another-custom-login"); ?></div>
		<p class="description indicator-hint"><?php _e("Hint: Strong passwords should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! \" ? $ % ^ &amp; )","AnotherCustomLogin"); ?></p>

		<br class="clear">
		<p class="submit"><input name="wp-submit" id="wp-submit" class="button-primary" value="<?php _e('Reset Password','another-custom-login');?>" tabindex="100" type="submit"></p>
	</form>

	<p id="nav">
		<a href="<?php echo $loginUrl; ?>"><?php _e("Log in","another-custom-login"); ?></a>
	</p>
	<p id="backtoblog">
		<a href="<?php echo home_url(); ?>"><?php _e("← Back to ",'another-custom-login'); ?> <?php echo get_bloginfo("name");?></a>
	</p>
</div>
<script>
	var minStrength = <?php echo $minStrength; ?>;
</script>