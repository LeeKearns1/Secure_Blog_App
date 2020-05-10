<!-- 
	References that helped in the development of this application:
		https://www.pushpendra.net/how-to-make-a-blog-in-codeigniter/
-->
<h2><?= $title ?></h2>

<?php foreach($posts as $post) : ?>

		<h3><?php echo $post['title']; ?> </h3>
		<div class="row">
		<div class="cold-md-9">
			<small class="post-date">Posted On: <?php echo $post['created_at']; ?> in <strong><?php echo $post['name']; ?></strong></small><br>

			<?php echo word_limiter($post['body'], 60); ?>
			<br><br>
			<p><a class="btn btn-info" href="<?php echo site_url('/posts/'.$post['slug']); ?>">Read More</a></p>
		</div>
		</div>

<?php endforeach; ?>