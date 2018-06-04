<?php if($errors){ ?>
	<div class="reg_error_array">
		<?php foreach($errors as $error){ ?>
			<div class="reg_error">
				<?php print $error; ?><br>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<form action="<?php print $url; ?>" method="POST">

	<input type="text" value="<?php print $email; ?>" placeholder="<?php print EMAIL; ?>" name="email"/>
	
	<input type="password" value="<?php print $password; ?>" placeholder="<?php print PASSWORD; ?>" name="password"/>
	
	<label class="checkbox">
		<input  name="member" <?php print $member ? 'checked=""' : ''; ?> type="checkbox" />
		<span class="checkbox__text" title="<?php print MEMBER_ME ?>"></span>
	</label>

	<input type="submit" value="<?php print AUTH; ?>"/>
	
</form>