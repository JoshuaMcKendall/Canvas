<?php
/*
Template Name: Contact Page
*/

get_header();
?>
<div id="page" class="clearfix">

	<div id="contact">
		<form id="contactform" action="<?php the_permalink(); ?>" method="post" name="contact-form">
                                    <div class="field">
                                    <input type="text" name="message-name" id="name" class="input" placeholder="Name"> </div>
                                    <br />
                                    <div class="field">
                                	<input type="text" name="message-email" class="input" id="email" placeholder="Email"></div>
                                    <input type="text" id="contact-title" name="title" class="conceal" />
                             		<br />
                                    <div class="txt">
                                  <textarea name="message-text" id="message" class="textarea"  placeholder="Message"></textarea></div>
                                <div class="sub">
                                <input type="submit" name="button" value="Send" id="button" class="btn">
                                </div>
						</form>
	</div><!--contact-->
	<div id="content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
			<?php endwhile; // end of the loop. ?>
	</div><!--content-->
</div><!--page-->
<?php get_footer(); ?>
