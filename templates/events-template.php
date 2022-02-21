<?php get_header(); ?>
<div class="outside-post-wrapper">
	<div class="category-posts-container">
	<?php
	global $query_string;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' => 'event',
		'posts_per_page'=>2,
		'paged'=>$paged
	);
	query_posts($args);
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="category-post-item"><div class="category-single-post"><div class="cat-post-img-wrap">
		<?php $feature_img = get_the_post_thumbnail_url(get_the_ID(),'full');
		 if ( $feature_img ) { ?>
			<a href="<?php echo get_permalink(); ?>"><img src="<?php echo $feature_img; ?>" alt=""></a>
		<?php } else { ?>
			<a href="<?php echo get_permalink(); ?>"><img src="https://images.unsplash.com/photo-1638913662539-46e7ccd6d912?ixlib=rb-1.2.1&ixid=MnwxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt=""></a>
		<?php } ?>
		</div><h2 class="cat-post-title"><a href="#"><?php the_title(); ?></a></h2></div></div>
	<?php endwhile; endif; ?>
   <?php wpbeginner_numeric_posts_nav(); ?>
   <?php wp_reset_query(); ?>
</div>
</div>
<?php get_footer(); ?>