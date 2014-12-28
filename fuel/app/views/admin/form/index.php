<h2>Listing Forms</h2>
<br>
<?php if ($forms): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Comment</th>
			<th>Ip address</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($forms as $item): ?>		<tr>

			<td><?php echo $item->name; ?></td>
			<td><?php echo $item->email; ?></td>
			<td><?php echo $item->comment; ?></td>
			<td><?php echo $item->ip_address; ?></td>
			<td>
				<?php echo Html::anchor('admin/form/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/form/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/form/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Forms.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/form/create', 'Add new Form', array('class' => 'btn btn-success')); ?>

</p>
