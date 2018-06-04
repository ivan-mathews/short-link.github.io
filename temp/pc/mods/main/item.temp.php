<?php $item = 0; ?>

<table border="1">
   <tr>
		<th>№</th>
		<th>Дата</th>
		<th>Ссылка</th>
		<th>URL-ссылки</th>
		<th>Имя ссылки</th>
		<th>Просмотры</th>
   </tr>
	   
	<?php foreach($data as $key=>$value){ ?>

		<?php $item++ ?>
		
	   <tr>
		   <td><?php print $item; ?></td>
		   <td><?php print date('d m Y', $value['date']); ?></td>
		   <td><?php print $value['link_uri']; ?></td>
		   <td><?php print $value['link_link']; ?></td>
		   <td><?php print $value['link_name']; ?></td>
		   <td><?php print $value['count']; ?></td>
	   </tr>
	   
<?php } ?>

</table>

<?php if(empty($data)){ ?>
	<div class="no_such">
		<?php print NO_SUCH; ?>
	</div>
<?php } ?>
