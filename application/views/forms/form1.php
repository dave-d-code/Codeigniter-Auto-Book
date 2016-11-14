<?php echo form_open($form_address,'class="form-inline"'); ?>
<div class="form-group">
<?php echo form_label('Start Date', 'start_date'); ?>
<?php echo form_input('start_date', '', array('class'=> 'datepicker form-control', 'id'=>'start_date')); ?>
</div>
<div class="form-group">
<?php echo form_label('End Date', 'end_date'); ?>
<?php echo form_input('end_date', '', array('class'=> 'datepicker form-control', 'id'=>'end_date')); ?>
</div>
<?php echo form_submit('submit', $form_action, 'class="btn btn-lg btn-info"'); ?>
<?php echo form_close(); ?>
