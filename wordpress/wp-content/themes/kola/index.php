<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>" charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
        <header> 
            <p class="toggle-menu" onclick="document.querySelector('body').classList.toggle('show-menu')"><?php _e( 'Menu', 'kola' ); ?></p>
            <?php if ( has_nav_menu( 'primary-menu' ) ) wp_nav_menu( array( 'theme_location' => 'primary-menu' ) ); ?>
            <h2><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a></h2>
            <?php if ( get_bloginfo( 'description' ) ) : ?>
                <p><?php bloginfo( 'description' ); ?></p>
            <?php endif; ?>
        </header><!-- header -->
		<div class="wrapper">
            <?php if ( have_posts() )  : 
                while ( have_posts() ) : the_post(); ?>
                    <div <?php post_class( 'post' ); ?>>
                        <?php if ( ! get_post_format() == 'aside' ) : ?>
                            <h1 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                        <?php endif;
                        if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="featured-image">
                                <?php the_post_thumbnail( 'post-image' ); ?>    
                            </a>
                        <?php endif; ?>
                        <div class="content">
                            <?php the_content(); ?>
                        </div><!-- .content -->
                        <?php 
                        if ( is_singular() ) wp_link_pages();
                        if ( get_post_type() == 'post' ) : ?>
                            <div class="meta">
                                <p>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
                                    <?php if ( comments_open() ) : ?>
                                        <span class="sep"></span><?php comments_popup_link( __( 'Add Comment', 'kola' ), __( '1 Comment', 'kola' ), '% ' . __( 'Comments', 'kola' ), '', __( 'Comments off', 'kola' ) ); ?>
                                    <?php endif; ?>
                                    <?php if ( is_sticky() ) : ?>
                                        <span class="sep"></span><?php _e( 'Sticky', 'kola' ); ?>
                                    <?php endif ?>
                                </p>
                                <?php if ( is_singular( 'post' ) ) : ?>
                                    <p><?php _e( 'In', 'kola' ); ?> <?php the_category( ', ' ); ?></p>
                                    <p><?php the_tags( ' #', ' #', ' ' ); ?></p>
                                <?php endif; ?>
                            </div><!-- .meta -->
                        <?php endif;
                        if ( is_singular() ) comments_template(); ?>
                    </div><!-- .post -->
                    <?php 
                endwhile;
            else : ?>
                <div class="post">
                    <p><?php _e( 'Sorry, the page you requested cannot be found.', 'kola' ); ?></p>
                </div><!-- .post -->
            <?php endif;
            if ( ( ! is_singular() ) && ( $wp_query->post_count >= get_option( 'posts_per_page' ) ) ) : ?>
		        <div class="pagination">
					<?php previous_posts_link( '&larr; ' . __( 'Newer posts', 'kola' ) ); ?>
					<?php next_posts_link( __( 'Older posts', 'kola') . ' &rarr;' ); ?>
		        </div><!-- .pagination -->
	        <?php endif; ?>
	        <footer>
		        <p>&copy; <?php echo date( 'Y' ); ?> <a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a></p>
		        <p><?php _e( 'Themed by', 'kola' ); ?> <a href="https://kola.design">Kola Design</a></p>
	        </footer><!-- footer -->
	    </div><!-- .wrapper -->
	    <?php wp_footer(); ?>
	</body>
</html>