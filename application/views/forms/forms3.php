<div class="form-group">
<?php echo form_label('First name', 'first_name'); ?>
<?php echo form_input('first_name', set_value('first_name'), array('class'=>'form-control', 'id'=>'first_name')); ?>
</div>
<div class="form-group">
<?php echo form_label('Last Name', 'last_name'); ?>
<?php echo form_input('last_name',set_value('last_name'), array('class'=>'form-control', 'id'=>'last_name')); ?>
</div>
<div class="form-group">
<?php echo form_label('Your Email', 'email'); ?>
<?php echo form_input('email',set_value('email'), array('class'=>'form-control', 'id'=>'email')); ?>
</div>
<div class="form-group">
<?php echo form_label('Any comments', 'comments'); ?>
<?php echo form_textarea('comments',set_value('comments'), array('class'=>'form-control', 'id'=>'comments', 'placeholder'=>'Place your comments here')); ?>
</div>
<?php echo form_hidden('start_date', $start_date); ?>
<?php echo form_hidden('end_date', $end_date); ?>
<button type="submit" class="btn btn-success btn-lg pull-right">
  <span class="glyphicon glyphicon-ok"></span> Submit This!!
</button>
<?php echo form_close(); ?>
