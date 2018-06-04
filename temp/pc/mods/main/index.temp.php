<?php if($errors){ ?>
	<div class="reg_error_array">
		<?php foreach($errors as $error){ ?>
			<div class="reg_error">
				<?php print $error; ?><br>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<form method="POST" action="<?php print $url; ?>">

	<input type="text" name="link" placeholder="<?php print ENTER_LINK; ?>" value="<?php print $link; ?>"/>
	<input type="text" name="name" placeholder="<?php print ENTER_NAME; ?>" value="<?php print $name; ?>"/>
	<input type="submit" value="<?php print GO; ?>"/>
	
</form>
