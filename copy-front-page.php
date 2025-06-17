<?php
// Load WordPress
require_once('wp-load.php');

date_default_timezone_set('UTC');

// Function to copy front page content
function copy_front_page_content() {
    $german_page_id = 186; // ID of the German front page
    $german_page = get_post($german_page_id);
    if (!$german_page) {
        echo "German front page not found\n";
        return;
    }

    // Check if an English version already exists (by Polylang or by title)
    $existing_en = get_posts([
        'post_type' => 'page',
        'post_status' => 'any',
        'meta_query' => [
            [
                'key' => 'pll_lang',
                'value' => 'en',
            ]
        ],
        'posts_per_page' => 1
    ]);
    if ($existing_en) {
        echo "English front page already exists (ID: {$existing_en[0]->ID})\n";
        return;
    }

    // Create English page
    $en_page_id = wp_insert_post([
        'post_title' => 'Home',
        'post_content' => $german_page->post_content,
        'post_status' => 'publish',
        'post_type' => 'page',
    ]);
    if (!$en_page_id) {
        echo "Failed to create English page\n";
        return;
    }
    update_post_meta($en_page_id, 'pll_lang', 'en');

    // Copy all meta except language
    $meta = get_post_meta($german_page_id);
    foreach ($meta as $key => $values) {
        if ($key === 'pll_lang') continue;
        foreach ($values as $value) {
            update_post_meta($en_page_id, $key, maybe_unserialize($value));
        }
    }

    echo "English front page created with ID: $en_page_id\n";
}

// Run the function
copy_front_page_content(); 