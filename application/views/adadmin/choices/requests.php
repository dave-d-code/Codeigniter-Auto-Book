<div class="row" id="decisions">
	<div class="col-md-4">
		<?php echo anchor('ad/admin/requests', '<span class="glyphicon glyphicon-backward"></span> Go back to the table',
		 'class="btn btn-lg btn-info"'); ?>
	</div>
	<div class="col-md-4">
		<?php echo anchor('ad/admin/requests_decision/' . $my_id . '/reject', '<span class="glyphicon glyphicon-remove"></span> Reject this booking',
		 'class="btn btn-lg btn-danger"'); ?>
	</div>
	<div class="col-md-4">
		<?php echo anchor('ad/admin/requests_decision/' . $my_id . '/accept', '<span class="glyphicon glyphicon-ok"></span> Confirm this booking',
		 'class="btn btn-lg btn-success"'); ?>
	</div>
</div>