<form>
<fieldset disabled>
<div class="form-group">
<?php echo form_label('Start Date', 'temp_start_date'); ?>
<?php echo form_input('temp_start_date', '', array('placeholder'=> $start_date, 'class'=>'form-control'));?>
</div>
<div class="form-group">
<?php echo form_label('End Date', 'temp_end_date'); ?>
<?php echo form_input('temp_end_date', '', array('placeholder'=> $end_date, 'class'=>'form-control'));?>
</div>
</fieldset>
</form>

