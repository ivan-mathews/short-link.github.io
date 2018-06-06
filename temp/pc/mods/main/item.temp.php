<?php $item = 0; ?>

<table border="1">
   <tr>
		<th>№</th>
		<th><?php print GET_DATE; ?></th>
		<th><?php print LINK; ?></th>
		<th><?php print GET_URL; ?></th>
		<th><?php print NAME; ?></th>
		<th><?php print GET_VIEW; ?></th>
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
