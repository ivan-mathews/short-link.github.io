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
	
	<input type="text" value="<?php print $f_name; ?>" placeholder="<?php print F_NAME; ?>" name="f_name"/>
	<input type="text" value="<?php print $l_name; ?>" placeholder="<?php print L_NAME; ?>" name="l_name"/>
		
	<input type="submit" value="<?php print REG; ?>"/>
	
</form>