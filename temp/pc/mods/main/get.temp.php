
<?php $core = Core::I(); ?>

<input type="hidden" value="<?php print $sid; ?>" link="/<?php print $data['uri']; ?>" name="<?php print $hash; ?>"/>

<?php 
	/*
		<div class="big_link_block">
			<div class="big_link">
				<a href="/<?php print $data['uri']; ?>"><?php print $data['name'] ? $data['name'] : $data['uri']; ?></a>
			</div>
		</div>
	*/ 
?>

<div class="link_block">
	<span class="other"><?php print LINK ; ?> <?php print GENERATED ; ?></span>
	<span class="time"><?php print $core->conf->TIME_OUT; ?></span> <span class="seconds"><?php print SECOND ?></span>
</div>