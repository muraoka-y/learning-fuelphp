
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
//buttonに隠しフィールドでデータをしこみ、次のページにデータを渡す
echo Form::hidden('name',$input['name']);
echo Form::hidden('email',$input['email']);
echo Form::hidden('comment',$input['comment']);
?>
<div class="actions">
<?php echo Form::submit('submit1','修正'); ?>
</div>
<?php 
echo Form::open('form/send'); 

//CSRF対策
echo Form::hidden(Config::get('security.csrf_token'));

echo Form::hidden('comment',$input['comment']);
echo Form::hidden('comment',$input['comment']);
echo Form::hidden('comment',$input['comment']);
?>