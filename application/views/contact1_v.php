<div class="container">
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h2 class="lead">
		Thank you for choosing your dates with us.
		Please complete the form below.</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<?php $this->load->view('forms/forms2'); ?>
		<p>
		<?php echo anchor('house/Rent/calendar', '<span class="glyphicon glyphicon-backward"></span> Change Dates',
		 'class="btn btn-danger btn-lg"'); ?>
		</p>
	</div>
	<div class="col-md-7">
		<?php echo form_open('house/Rent/confirm'); ?>
		<?php $this->load->view('forms/forms3'); ?>
	</div>
	<div class="col-md-3">
		<?php if (validation_errors()): ?>
		<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
		<?php endif; ?>
	</div>
</div>



</div>
