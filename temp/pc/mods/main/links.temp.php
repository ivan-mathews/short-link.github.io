<?php $item = 0; ?>

<style>
	input[type="text"] {
		width: 97%;
		padding-bottom: 5px;
		padding-top: 4px;
		text-align: center;
		border-radius: 4px;
		margin-left: 1px;
		font-size: 17px;
		margin-right: 36px;
	}
</style>

<table border="1">
	<tr>
		<th>№</th>
		<th><?php print GET_DATE; ?></th>
		<th><?php print LINK; ?></th>
		<th><?php print GET_URL; ?></th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	<form>
		<?php foreach($data as $key=>$value){ ?>
			<?php $item++ ?>
			<tr>
				<td><?php print $item; ?></td>
				<td><?php print date('d m Y, H:i:s',$value['date_create']); ?></td>
				<td><?php print $value['link']; ?></td>
				<td><input readonly onclick="Sel(<?php print $item-1; ?>)" type="text" value="<?php print $_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['HTTP_HOST'].'/'.$value['uri']; ?>"/></td>
				<td><a class="one_link" href="/main/<?php print $value['id']; ?>"><?php print GET_STAT; ?></a></td>
				<td><a class="one_link" href="/main/edit/<?php print $value['id']; ?>"><?php print EDIT ?></a></td>
				<td><a class="one_link" href="/main/del/<?php print $value['id']; ?>"><?php print DEL ?></a></td>
			</tr>
			
		<?php } ?>
	</form>
</table>

<?php if(empty($data)){ ?>
	<div class="no_such">
		<?php print NO_SUCH; ?>
	</div>
<?php } ?>
