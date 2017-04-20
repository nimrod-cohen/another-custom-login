<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 11/10/16
 * Time: 22:21
 */

	$showSaved = false;

	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login_page"]))
	{
		//collect all redirects after login
		$ralins = array();
		foreach($_POST as $key => $val)
		{
			if(strstr($key,"ral_"))
				$ralins[substr($key,4)] = $val;
		}

		AnotherCustomLogin::setSettings(array(
			"login_page" => $_POST["login_page"],
			"login_by" => $_POST["login_by"],
			"ralins" => $ralins,
			"pass_strength" => $_POST["pass_strength"]
		));
		$showSaved = true;
	}

	$pages = get_pages();

	$settings = AnotherCustomLogin::getSettings();
?>
<style>
	table.roles-table td { padding:0 10px; }
	table.roles-table th { padding:0 10px 0 0; vertical-align:middle; }
</style>
<div class="wrap">
	<h1>Another Custom Login - Settings</h1>
	<?php if ($showSaved) { ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
			<p><strong>Settings saved.</strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>
	<?php } ?>
	<p>Set your preferences, then click save.</p>
	<form name="form1" method="post" action="">
		<p>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="login_page">Login page</label></th>
						<td>
							<select id="login_page" name="login_page" style="width:250px;">
								<option value="" <?php echo $settings["login_page"] === false ? "selected" : "";?>>Not selected</option>
								<?php foreach($pages as $page) { ?>
									<option value="<?php echo $page->ID; ?>" <?php echo $settings["login_page"] == $page->ID ? "selected" : "";?>><?php echo $page->post_title; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="login_by">Login by</label></th>
						<td>
							<select id="login_by" name="login_by" style="width:250px;">
								<option value="both" <?php echo $settings["login_by"] === false ? "selected" : "";?>>Both</option>
								<option value="email" <?php echo $settings["login_by"] == "email" ? "selected" : "";?>>Email</option>
								<option value="username" <?php echo $settings["login_by"] == "username" ? "selected" : "";?>>User</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="pass_strength">Minimal Password Strength</label></th>
						<td>
							<select id="pass_strength" name="pass_strength" style="width:250px;">
								<option value="0" <?php echo $settings["pass_strength"] == 0 ? "selected" : "";?>>Very weak</option>
								<option value="2" <?php echo $settings["pass_strength"] == 2 ? "selected" : "";?>>Weak</option>
								<option value="3" <?php echo $settings["pass_strength"] == 3 ? "selected" : "";?>>Medium</option>
								<option value="4" <?php echo $settings["pass_strength"] == 4 ? "selected" : "";?>>Strong</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<h3>Redirect After Login</h3>
			<table class="form-table roles-table">
				<tbody>
					<?php
					$roles = get_editable_roles();
					$ralins = $settings["ralins"];
					foreach ( $roles as $role => $details ) { ?>
						<tr>
							<th scope="row"><label for="ral_<?php echo $role; ?>"><?php echo $details["name"]; ?></label></th>
							<td>
								<select style="width:250px;" id="ral_<?php echo $role; ?>" name="ral_<?php echo $role; ?>">
									<option value="" <?php echo (!isset($ralins[$role]) || $ralins[$role] == "") ? "selected" : "";?>>Not selected</option>
									<option value="-1" <?php echo (isset($ralins[$role]) && $ralins[$role]) == "-1" ? "selected" : "";?>>Dashboard</option>
								<?php foreach($pages as $page) { ?>
									<option value="<?php echo $page->ID; ?>" <?php echo isset($ralins[$role]) && $ralins[$role] == $page->ID ? "selected" : "";?>><?php echo $page->post_title; ?></option>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</p>
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		</p>
	</form>
</div>
