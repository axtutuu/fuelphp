<?php if (isset($html_error)) :?>
<?php echo $html_error; ?>
<?php endif; ?>

<?php echo Form::open('form/confirm'); ?>
<p>
    <?php echo Form::label('name', 'name'); ?>(*):
    <?php echo Form::input('name', Input::post('name')); ?>
</p>
<p>
    <?php echo Form::label('email', 'email'); ?>(*):
    <?php echo Form::input('email', Input::post('email')); ?>
</p>
<p>
    <?php echo Form::label('comment', 'comment'); ?>(*):
    <?php echo Form::textarea('comment', Input::post('comment'),
    array('rows' => 6, 'cols' => 70)); ?>
</p>
<div class="actions">
    <?php echo Form::submit('submit', 'confirm'); ?>
</div>
    <?php echo Form::close(); ?>






