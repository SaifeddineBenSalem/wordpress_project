<?php
require_once('wp-load.php');
global $wpdb;

$pages = $wpdb->get_results("SELECT ID, post_title, post_status FROM {$wpdb->posts} WHERE post_type = 'page' AND post_title LIKE '%Home%'");

foreach ($pages as $page) {
    echo "Page ID: {$page->ID}\n";
    echo "Title: {$page->post_title}\n";
    echo "Status: {$page->post_status}\n";
    $meta = get_post_meta($page->ID);
    foreach ($meta as $key => $values) {
        foreach ($values as $value) {
            echo "  Meta: $key => $value\n";
        }
    }
    echo "--------------------------\n";
} 