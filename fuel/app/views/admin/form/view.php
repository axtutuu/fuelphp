<h2>表示 #<?php echo $form->id; ?></h2>

<p>
	<strong>Date:</strong>
	<?php echo Date::forge($form->created_at)->format('mysql'); ?></p>
<p>
	<strong>Name:</strong>
	<?php echo $form->name; ?></p>
<p>
	<strong>Email:</strong>
	<?php echo $form->email; ?></p>
<p>
	<strong>Comment:</strong>
	<?php echo $form->comment; ?></p>
<p>
	<strong>Ip address:</strong>
	<?php echo $form->ip_address; ?></p>
<p>
	<strong>user_agent:</strong>
	<?php echo $form->user_agent; ?></p>

<?php echo Html::anchor('admin/form/edit/'.$form->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/form', 'Back'); ?>
