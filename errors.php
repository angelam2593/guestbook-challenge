<?php if (count($errors) > 0): ?>
	<div class="alert alert-danger" role="alert"">
		<?php foreach ($errors as $error): ?>
			<p><?php echo e($error); ?></p>
		<?php endforeach ?>
	</div>
<?php endif ?>