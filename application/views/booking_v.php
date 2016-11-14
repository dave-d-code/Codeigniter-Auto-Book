<div class="container">
<div class="row">
    <div class="col-md-6 text-center">
        <?php echo $cal_data; ?>

    </div>
    <div class="col-md-6 text-center">
        <?php echo $cal_data_next; ?><br>

    </div>


</div>
<div class="row">
    <div class="col-md-2 text-left">
    <?php echo $prev_y_link ? anchor($next_tabs . $prev_y_link . '/' . $prev_m_link, 'Previous 2 Months',
         array('class' => 'btn btn-success', 'role' => 'button')) :''; ?>   
    </div>
    <div class="col-md-8">
        <p>
            <span class="label label-lg label-info"><h4>Key:</h4></span>&nbsp;&nbsp;&nbsp;<img src="<?php echo base_url('assets/images/acme_colour_yellow.png') ?>" 
            alt="Yellow key for ACME Calendar">
            &nbsp;Booked - awaiting Confirmation
            <img src="<?php echo base_url('assets/images/acme_colour_red.png') ?>" alt="Red key for ACME Calendar">
            &nbsp;Confirmed Booking
        </p>
    </div>
    <div class="col-md-2  text-right">
        <?php echo anchor($next_tabs . $next_y_link . '/' . $next_m_link, 'Next 2 Months', 
        array('class' => 'btn btn-success', 'role' => 'button')); ?>  
    </div>
</div>
<div class="row" id="rowdown">
        <?php $this->load->view('forms/form1'); ?>
</div>

<?php if(validation_errors()): ?>
    <div class="row">
    <div class="col-md-5">
    <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
    </div></div>
<?php endif; ?>
<?php if($this->session->flashdata('msg')): ?>
    <div class="row">
    <div class="col-md-5">
    <div class="alert alert-info"><?php echo $this->session->flashdata('msg'); ?></div>
    </div></div>
<?php endif; ?>

</div>

