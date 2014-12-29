<h2>Listing Forms</h2>
<br>
<?php if ($forms): ?>
<table class="table table-striped">
  <thead>
    <tr>
      <th>日時</th>
      <th>名前</th>
      <th>メールアドレス</th>
      <th>コメント</th>
      <th>Ip</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($forms as $item): ?>    <tr>

      <td><?php echo Date::forge($item->created_at)->format('mysql'); ?></td>
      <td><?php echo $item->name; ?></td>
      <td><?php echo $item->email; ?></td>
      <td><?php echo Str::truncate($item->comment, 5, '...', true); ?></td>
      <td><?php echo $item->ip_address; ?></td>
      <td>
        <?php echo Html::anchor('admin/form/view/'.$item->id, '詳細'); ?> |
        <?php echo Html::anchor('admin/form/edit/'.$item->id, 'Edit'); ?> |
        <?php echo Html::anchor('admin/form/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

      </td>
    </tr>
<?php endforeach; ?>  </tbody>
</table>

<?php else: ?>
<p>お問い合わせはありません。</p>

<?php endif; ?><p>
  <?php echo Html::anchor('admin/form/create', 'Add new Form', array('class' => 'btn btn-success')); ?>

</p>
