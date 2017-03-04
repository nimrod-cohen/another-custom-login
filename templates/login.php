<div id="login">
	<?php
		
	$loginUrl = wp_login_url();
	
	if( $this->loginError )
			echo "<p class='message'>".$this->loginError."</p>";
	
	?><form name="loginform" id="loginform" action="<?php echo $loginUrl; ?>" method="post">
		<input type="hidden" name="action" value="do_login">
		<p class="login-username">
			<label for="user_login">Username or Email</label>
			<input name="log" id="user_login" class="input" value="" size="20" type="text">
		</p>
		<p class="login-password">
			<label for="user_pass">Password</label>
			<input name="pwd" id="user_pass" class="input" value="" size="20" type="password">
		</p>
		
		<p class="login-remember"><label><input name="rememberme" id="rememberme" value="forever" type="checkbox"> Remember Me</label></p>
		<p class="login-submit">
			<input name="wp-submit" id="wp-submit" class="button-primary" value="Log In" type="submit">
		</p>
	</form>
	<p id="nav">
		<a href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
	</p>
	<p id="backtoblog">
		<a href="<?php echo home_url(); ?>">← Back to <?php echo get_bloginfo("name");?></a>
	</p>
</div>
