<div class="container">
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h2 class="lead">Please complete the form below with any questions or comments.</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-8 col-md-offset-1">
		<?php echo form_open('house/Rent/ask_me'); ?>
		<?php $this->load->view('forms/forms3'); ?>
	</div>
	<div class="col-md-3">
		<?php if (validation_errors()): ?>
		<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
		<?php endif; ?>
	</div>
</div>



</div>