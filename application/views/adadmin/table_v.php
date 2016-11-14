<style>
.owntable {
	background-color: #ADD8E6;
}
</style>


<div class="container">
<h2 class="text-center"><?php echo $page_title; ?></h2>
<?php if ($this->session->flashdata('msg')): ?>
	<div class="alert alert-info"><?php echo $this->session->flashdata('msg'); ?></div>
<?php endif; ?>

<div class="row">
	<div class="col-md-10">
		<?php echo $table; ?>
	</div>
</div>
</div>