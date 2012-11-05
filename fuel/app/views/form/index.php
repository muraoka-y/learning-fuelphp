
<?php if (isset($html_error))://エラーの表示 ?>
<?php echo $html_error; ?>
<?php endif; ?>


<?php echo Form::open('form/confirm');//formタグの生成 ?>
<p>

	<?php echo Form::label('名前','name');//ラベルタグの生成?>

	<?php echo Form::input('name',Input::post('name')); //input tag	?>
</p>
<p>
	<?php echo Form::label('メールアドレス','email');?>
	<?php echo Form::input('email',Input::post('email'));?>
</p>
<p>
	<?php echo Form::label('コメント','comment');?>

	<?php echo Form::textarea('comment',Input::post('comment'),
	array('rows' =>6, 'colos' => 70)); //textarea tag生成?>
	</p>
<div class="actions">

<?php echo Form::submit('submit','確認');//submit button 生成?>
</div>
<?php echo Form::close(); ?>