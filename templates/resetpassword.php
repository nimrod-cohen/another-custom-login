<?php
	
	$loginUrl = wp_login_url();

	$doResetUrl = add_query_arg("action","do_resetpassword",$loginUrl);

	$minStrength = AnotherCustomLogin::getSetting("pass_strength");
?>
<div id="login">
	<p class="message reset-pass">Enter your new password below.</p>
	<form name="resetpassform" id="resetpassform" action="<?php echo $doResetUrl; ?>" method="post">
		<input id="user_login" name="user_login" value="<?php echo $_GET["login"]; ?>" type="hidden">
		<input id="key" name="key" value="<?php echo $_GET["key"]; ?>" type="hidden">
		<p>
			<label for="pass1">New password<br>
				<input name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" type="password"></label>
		</p>
		<p>
			<label for="pass2">Confirm new password<br>
				<input name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" type="password"></label>
		</p>
		
		<div id="pass-strength-result" class="hide-if-no-js" style="display: block;">Strength indicator</div>
		<p class="description indicator-hint">Hint: Strong passwords should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).</p>
		
		<br class="clear">
		<p class="submit"><input name="wp-submit" id="wp-submit" class="button-primary" value="Reset Password" tabindex="100" type="submit"></p>
	</form>
	
	<p id="nav">
		<a href="<?php echo $loginUrl; ?>">Log in</a>
	</p>
	<p id="backtoblog">
		<a href="<?php echo home_url(); ?>">← Back to <?php echo get_bloginfo("name");?></a>
	</p>
</div>
<script>
	var minStrength = <?php echo $minStrength; ?>;
</script>