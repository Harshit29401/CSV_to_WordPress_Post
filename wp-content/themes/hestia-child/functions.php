<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('chld_thm_cfg_parent_css')):
    function chld_thm_cfg_parent_css()
    {
        wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array('bootstrap', 'hestia-font-sizes'));
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);

// END ENQUEUE PARENT ACTION

// Custom CSS
function custom_theme_styles() {
    wp_enqueue_style('custom-theme-styles', get_stylesheet_uri() . '/custom-css/custom-style.css');
}
add_action('wp_enqueue_scripts', 'custom_theme_styles');


// Now register the custom post type and taxonomy separately
function custom_register_news_post_type()
{
    $labels = array(
        'name' => _x('News', 'post type general name', 'textdomain'),
        'singular_name' => _x('News', 'post type singular name', 'textdomain'),
        'menu_name' => _x('News', 'admin menu', 'textdomain'),
        'name_admin_bar' => _x('News', 'add new on admin bar', 'textdomain'),
        'add_new' => _x('Add New', 'news', 'textdomain'),
        'add_new_item' => __('Add New News', 'textdomain'),
        'new_item' => __('New News', 'textdomain'),
        'edit_item' => __('Edit News', 'textdomain'),
        'view_item' => __('View News', 'textdomain'),
        'all_items' => __('All News', 'textdomain'),
        'search_items' => __('Search News', 'textdomain'),
        'not_found' => __('No news found.', 'textdomain'),
        'not_found_in_trash' => __('No news found in Trash.', 'textdomain')
    );

    $args = array(
        'labels' => $labels,
        'description' => __('Description.', 'textdomain'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies' => array('newtype') // Adding default category, tag, and custom taxonomy
    );

    register_post_type('news', $args);
}
add_action('init', 'custom_register_news_post_type');

// Register the custom taxonomy
function custom_register_newtype_taxonomy()
{
    $labels = array(
        'name' => _x('Newtypes', 'taxonomy general name', 'textdomain'),
        'singular_name' => _x('Newtype', 'taxonomy singular name', 'textdomain'),
        'search_items' => __('Search Newtypes', 'textdomain'),
        'all_items' => __('All Newtypes', 'textdomain'),
        'parent_item' => __('Parent Newtype', 'textdomain'),
        'parent_item_colon' => __('Parent Newtype:', 'textdomain'),
        'edit_item' => __('Edit Newtype', 'textdomain'),
        'update_item' => __('Update Newtype', 'textdomain'),
        'add_new_item' => __('Add New Newtype', 'textdomain'),
        'new_item_name' => __('New Newtype Name', 'textdomain'),
        'menu_name' => __('Newtype', 'textdomain'),
    );

    $args = array(
        'hierarchical' => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'newtype'),
    );

    register_taxonomy('newtype', array('news'), $args);
}
add_action('init', 'custom_register_newtype_taxonomy');

// Getting Taxonomy of particular Post
add_action('hestia_after_single_post_article', 'custom_add_categories_after_single_post_content');

function custom_add_categories_after_single_post_content()
{
    // Get the categories for the current post
    $categories = get_the_terms(get_the_ID(), 'newtype');
    // Replace 'newtype' with the name of your custom taxonomy

    // Check if categories exist
    if ($categories && !is_wp_error($categories)) {
        echo '<div class="entry-categories">News Type: ';
        foreach ($categories as $category) {
            echo '<span class="label label-primary"><a href="' . esc_url(get_term_link($category)) . '" style="color:#fff">' . esc_html($category->name) . '</a></span>';
        }
        echo '</div>';
    }
}

// CSV to Post
// Include WordPress functions
require_once(ABSPATH . 'wp-load.php');

// Function to import data from CSV to custom post type
function import_csv_to_custom_post_type($csv_file_path)
{
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Array to store processed IDs
    $processed_ids = array();
    $processed_ids_file = "processed_ids.txt";

    if (file_exists($processed_ids_file)) {
        $processed_ids = file($processed_ids_file, FILE_IGNORE_NEW_LINES);
    }

    if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
        $is_first_row = true;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($is_first_row) {
                $is_first_row = false;
                continue;
            }

            $id = isset($data[0]) ? $data[0] : '';
            $title = isset($data[1]) ? $data[1] : '';
            $content = isset($data[2]) ? $data[2] : '';
            $categories = isset($data[3]) ? explode(",", $data[3]) : array();
            $image_urls = isset($data[4]) ? explode(",", $data[4]) : array();
            $name = isset($data[5]) ? $data[5] : ''; // Name ACF Field
            $email = isset($data[6]) ? $data[6] : ''; // Email ACF Field
            $number = isset($data[7]) ? $data[7] : ''; // Number ACF Field

            $existing_post_query = new WP_Query(
                array(
                    'post_type' => 'news',
                    'posts_per_page' => 1,
                    'title' => $title,
                    'content' => $content
                )
            );

            if ($existing_post_query->have_posts()) {
                $existing_post = $existing_post_query->posts[0];
                $post_id = $existing_post->ID;

                // Update post data
                $post_data = array(
                    'ID' => $post_id,
                    'post_title' => $title,
                    'post_content' => $content,
                );

                wp_update_post($post_data);

                // Set taxonomy terms for News Categories
                if (!empty($categories)) {
                    $term_ids = [];
                    foreach ($categories as $category) {
                        $term = term_exists($category, 'newtype');
                        if ($term !== 0 && $term !== null) {
                            $term_ids[] = $term['term_id'];
                        } else {
                            $term_id = wp_insert_term($category, 'newtype');
                            if (!is_wp_error($term_id)) {
                                $term_ids[] = $term_id['term_id'];
                            }
                        }
                    }
                    wp_set_post_terms($post_id, $term_ids, 'newtype');
                }

                // Process images
                if (!empty($image_urls)) {
                    foreach ($image_urls as $index => $image_url) {
                        $image_id = media_sideload_image($image_url, $post_id, null, 'id');
                        if ($index == 0 && !is_wp_error($image_id)) {
                            set_post_thumbnail($post_id, $image_id);
                        } elseif (!is_wp_error($image_id)) {
                            // Insert image into post content
                            $image_src = wp_get_attachment_image_src($image_id, 'full');
                            if ($image_src) {
                                $content .= '<p><img src="' . esc_url($image_src[0]) . '" alt="" /></p>';
                            }
                        }
                    }
                }

                // Debug: Check if ACF fields exist
                error_log('Updating ACF fields for existing post: ' . $post_id);

                // Set ACF fields
                update_field('news_name', $name, $post_id); // Name ACF Field
                update_field('news_email', $email, $post_id); // Email ACF Field
                update_field('news_number', $number, $post_id); // Number ACF Field

                // Update post content with images
                $post_data = array(
                    'ID' => $post_id,
                    'post_content' => $content,
                );

                wp_update_post($post_data);

                $processed_ids[] = $id;
            } else {
                $post_data = array(
                    'post_title' => $title,
                    'post_content' => '',
                    'post_type' => 'news',
                    'post_status' => 'publish'
                );

                $post_id = wp_insert_post($post_data);

                // Set taxonomy terms for News Categories
                if (!empty($categories)) {
                    $term_ids = [];
                    foreach ($categories as $category) {
                        $term = term_exists($category, 'newtype');
                        if ($term !== 0 && $term !== null) {
                            $term_ids[] = $term['term_id'];
                        } else {
                            $term_id = wp_insert_term($category, 'newtype');
                            if (!is_wp_error($term_id)) {
                                $term_ids[] = $term_id['term_id'];
                            }
                        }
                    }
                    wp_set_post_terms($post_id, $term_ids, 'newtype');
                }

                // Process images
                if (!empty($image_urls)) {
                    foreach ($image_urls as $index => $image_url) {
                        $image_id = media_sideload_image($image_url, $post_id, null, 'id');
                        if ($index == 0 && !is_wp_error($image_id)) {
                            set_post_thumbnail($post_id, $image_id);
                        } elseif (!is_wp_error($image_id)) {
                            // Insert image into post content
                            $image_src = wp_get_attachment_image_src($image_id, 'full');
                            if ($image_src) {
                                $content .= '<p><img src="' . esc_url($image_src[0]) . '" alt="" /></p>';
                            }
                        }
                    }
                }

                // Debug: Check if ACF fields exist
                error_log('Setting ACF fields for new post: ' . $post_id);

                // Set ACF fields
                update_field('news_name', $name, $post_id); // Name ACF Field
                update_field('news_email', $email, $post_id); // Email ACF Field
                update_field('news_number', $number, $post_id); // Number ACF Field

                // Update post content with images
                $post_data = array(
                    'ID' => $post_id,
                    'post_content' => $content,
                );

                wp_update_post($post_data);

                $processed_ids[] = $id;
            }
        }

        fclose($handle);
        file_put_contents($processed_ids_file, implode("\n", $processed_ids));
    }
}

// Shortcode function to call the CSV import function
function import_csv_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'file' => get_stylesheet_directory() . "/posts.csv"
        ), $atts, 'import_csv');

    ob_start();
    import_csv_to_custom_post_type($atts['file']);
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('import_csv', 'import_csv_shortcode');

// Function to append ACF fields to post content
function append_acf_fields_to_content($content) {
    if (is_singular('news')) {
        global $post;
        
        // Get ACF field values
        $name = get_field('news_name', $post->ID);
        $email = get_field('news_email', $post->ID);
        $number = get_field('news_number', $post->ID);

        // Append ACF fields to content
        if ($name || $email || $number) {
            $acf_content = '<div class="acf-fields">';
            if ($name) {
                $acf_content .= '<p><strong>Name:</strong> ' . esc_html($name) . '</p>';
            }
            if ($email) {
                $acf_content .= '<p><strong>Email:</strong> ' . esc_html($email) . '</p>';
            }
            if ($number) {
                $acf_content .= '<p><strong>Number:</strong> ' . esc_html($number) . '</p>';
            }
            $acf_content .= '</div>';

            $content .= $acf_content;
        }
    }

    return $content;
}

// Add filter to append ACF fields to post content
add_filter('the_content', 'append_acf_fields_to_content');

