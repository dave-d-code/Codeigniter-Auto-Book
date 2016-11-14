<div class="container">
<h3 class="text-center"><u><?php echo $page_title ?></u></h3>
<div class="row">
<div class="col-md-4">
<article>
<h4>Start Date: <mark><?php echo $start_date; ?></mark></h4>
<h4>End Date: <mark><?php echo $end_date; ?></mark></h4>
<h4>Time Requested: <mark><?php echo $time_booked; ?></mark></h4>
</article>
</div>
	<div class="col-md-8">
	<article>
	<h4>First Name: <mark><?php echo $first_name; ?></mark></h4>
	<h4>Last Name: <mark><?php echo $last_name; ?></mark></h4>
	<h4>Email Name: <mark><?php echo $email; ?></mark></h4>
	</article>
	</div>
</div>
<div class="row">
	<div class="col-md-10">
	<h4>Comments</h4>
	<article>
	<p><mark><?php echo $comments; ?></mark></p>
	</article>
	</div>

</div>
<style>
	#decisions {
		padding-top: 50px;
	}
	</style>
<?php $this->load->view($choices);?>
</div>
