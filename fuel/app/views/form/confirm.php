<p>
	名前:
	<?php echo $input['name']; ?>
</p>
<p>
	メールアドレス:
	<?php echo $input['email']; ?>
</p>
<p>
	コメント:
	<?php echo n12br($input['comment']); ?>
</p>

<?php 
echo Form::open('form/');
echo Form::hidden('name',$input['name']);
echo Form::hidden('email',$input['email']);
echo Form::hidden('comment',$input['comment']);
?>
<div class="actions">
	<?php echo Form::submit('submit1','修正'); ?>
</div>
<?php echo Form::close(); ?>

