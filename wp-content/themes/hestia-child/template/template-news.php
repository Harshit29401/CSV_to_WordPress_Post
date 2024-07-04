<?php
/**
 * Template Name: News Template
 *
 * The template for displaying news posts.
 */

get_header();
?>


<article id="post-<?php the_ID(); ?>" <?php post_class('section section-text'); ?>>
    <div class="container">
        <div class="row">
            <?php
            $sidebar_layout = get_theme_mod('hestia_blog_sidebar_layout', 'sidebar-right');
            if ($sidebar_layout === 'sidebar-left') {
                do_action('hestia_page_sidebar');
            }
            ?>
            <div class="<?php echo $sidebar_layout === 'no-sidebar' ? 'col-md-12' : 'col-md-8'; ?>">
                <div class="news-container">
                    <div class="news-list">
                        <?php
                        // Query the posts
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $args = array(
                            'post_type' => 'news',
                            'posts_per_page' => 5, // Adjust the number of posts per page as needed
                            'paged' => $paged,
                        );

                        $news_query = new WP_Query($args);

                        if ($news_query->have_posts()) :
                            while ($news_query->have_posts()) :
                                $news_query->the_post();
                        ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class('news-item'); ?>>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="entry-thumbnail">
                                            <a href="<?php the_permalink(); ?>" aria-hidden="true">
                                                <?php the_post_thumbnail('medium', array('class' => 'img-responsive')); ?>
                                            </a>
                                        </div><!-- .entry-thumbnail -->
                                    <?php endif; ?>
                                    <div class="entry-content">
                                        <header class="entry-header">
                                            <h2 class="entry-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h2>
                                        </header><!-- .entry-header -->
                                        <div class="entry-summary">
                                            <?php the_excerpt(); ?>
                                        </div><!-- .entry-summary -->
                                        <div class="entry-footer">
                                            <?php
                                            // Output taxonomy terms
                                            $taxonomy = 'newtype';
                                            $terms = get_the_terms(get_the_ID(), $taxonomy);
                                            if ($terms && !is_wp_error($terms)) {
                                                echo '<div class="taxonomy-terms">';
                                                $term_links = array();
                                                foreach ($terms as $term) {
                                                    $term_link = get_term_link($term);
                                                    if (!is_wp_error($term_link)) {
                                                        $term_links[] = '<a href="' . esc_url($term_link) . '">' . $term->name . '</a>';
                                                    } else {
                                                        $term_links[] = $term->name;
                                                    }
                                                }
                                                echo implode(', ', $term_links);
                                                echo '</div>';
                                            }
                                            ?>
                                        </div><!-- .entry-footer -->
                                    </div><!-- .entry-content -->
                                </article><!-- #post-<?php the_ID(); ?> -->
                        <?php
                            endwhile;
                        ?>
                            <div class="pagination">
                                <?php
                                echo paginate_links(array(
                                    'total' => $news_query->max_num_pages,
                                    'current' => $paged,
                                    'prev_text' => __('« Previous', 'hestia'),
                                    'next_text' => __('Next »', 'hestia'),
                                ));
                                ?>
                            </div>
                        <?php
                            // Restore original post data
                            wp_reset_postdata();
                        else :
                            // If no posts are found
                            echo '<p>No news items found.</p>';
                        endif;
                        ?>
                    </div><!-- .news-list -->
                </div><!-- .news-container -->

                <?php
                // Display standard page content
                while (have_posts()) : the_post();
                    the_content();
                endwhile;

                echo apply_filters('hestia_filter_blog_social_icons', '');

                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </div><!-- .col-md-8 -->

            <?php
            if ($sidebar_layout === 'sidebar-right') {
                do_action('hestia_page_sidebar');
            }
            ?>
        </div><!-- .row -->
    </div><!-- .container -->
</article>

<?php
get_footer();
?>
