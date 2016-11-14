<div class="container">
<h3 class="text-center"><u><?php echo $page_title ?></u></h3>
<div class="row">
	<div class="col-md-8">
	<article>
	<h4>First Name: <mark><?php echo $first_name; ?></mark></h4>
	<h4>Last Name: <mark><?php echo $last_name; ?></mark></h4>
	<h4>Email Name: <mark><?php echo $email; ?></mark></h4>
	</article>
	</div>
</div>
<div class="row">
	<div class="col-md-10">
	<h4>Comments</h4>
	<article>
	<p><mark><?php echo $comments; ?></mark></p>
	</article>
	</div>

</div>
<style>
	#decisions {
		padding-top: 50px;
	}
	</style>
<div class="row" id="decisions">
	<div class="col-md-6">
		<?php echo anchor('ad/admin/messages', '<span class="glyphicon glyphicon-backward"></span> Go back to the table',
		 'class="btn btn-lg btn-info"'); ?>
	</div>
	<div class="col-md-6">
		<?php echo anchor('ad/admin/actioned/' . $id, '<span class="glyphicon glyphicon-check"></span> I\'ve actioned this message',
		 'class="btn btn-lg btn-danger"'); ?>
	</div>
</div>
</div>
