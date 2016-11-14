<div class="row" id="decisions">
	<div class="col-md-6">
		<?php echo anchor('ad/admin/live', '<span class="glyphicon glyphicon-backward"></span> Go back to the table',
		 'class="btn btn-lg btn-info"'); ?>
	</div>
	<div class="col-md-6">
		<?php echo anchor('ad/admin/cancel_booking/' . $my_id, '<span class="glyphicon glyphicon-remove"></span> Cancel this booking',
		 array('class' =>'btn btn-lg btn-danger', 'onclick'=>"return confirm('Are you sure you want to cancel this confirmed booking?');")); ?>
	</div>
</div>