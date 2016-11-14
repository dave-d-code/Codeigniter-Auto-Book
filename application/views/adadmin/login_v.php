<style>
#row-down {
	padding-top: 20px;
}
</style>


<div class="container">
<div class="row">
<div class="col-md-4 col-md-offset-4">
<h3 class="text-center">Please Log In</h3>
<?php echo form_open('ad/admin/authenticate'); ?>
<div class="form-group">
<?php echo form_label('Username', 'username'); ?>
<?php echo form_input('username', set_value('username'), array('class' => 'form-control', 'id'=> 'username')); ?>
</div>
<div class="form-group">
<?php echo form_label('Password', 'password'); ?>
<?php echo form_password('password', '', array('class' => 'form-control', 'id'=> 'password')); ?>
</div>
<button type="submit" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-thumbs-up"></span>  
	Submit</button>

</div>
</div>
<div class="row" id="row-down">
<div class="col-md-4 col-md-offset-4">
<?php echo form_close(); ?>
<?php if(validation_errors()): ?>
<div class="alert alert-danger">
<?php echo validation_errors(); ?>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('msg')): ?>
<div class="alert alert-danger">
<?php echo $this->session->flashdata('msg'); ?>
</div>
<?php endif; ?>
</div>
</div>
</div>
