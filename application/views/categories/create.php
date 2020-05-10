<!-- 
	References that helped in the development of this application:
		https://www.pushpendra.net/how-to-make-a-blog-in-codeigniter/
-->
<h2><?= $title ;?></h2>

<?php echo validation_errors(); ?>

<?php echo form_open_multipart('categories/create'); ?> 
	<div class ="form-group">
		<label>Name</label>
		<input type="text" class="form-control" name="name" placeholder="Enter Name">

	</div>
	<button type="submit" class="btn btn-primary">Submit</button>

</form>