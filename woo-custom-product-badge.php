<?php
/*
Plugin Name: Woo Custom Product Badge
Description: Adds a custom badge option for WooCommerce products.
*/

if (!defined('ABSPATH')) {
    exit;
}

//  a meta box on the product edit screen
add_action('add_meta_boxes', function () {
    add_meta_box(
        'woo_custom_badge_box',
        'Product Badge',
        'woo_custom_badge_box_content',
        'product',
        'side'
    );
});

// Output HTML for the badge dropdown
function woo_custom_badge_box_content($post) {
    $selected_badge = get_post_meta($post->ID, '_woo_custom_badge', true);
    wp_nonce_field('woo_custom_badge_nonce_action', 'woo_custom_badge_nonce');

    $badges = [
        '' => 'None',
        'Best Seller' => 'Best Seller',
        'Hot Deal' => 'Hot Deal',
        'New Arrival' => 'New Arrival'
    ];

    echo '<label for="woo_custom_badge">Select a Badge:</label><br>';
    echo '<select name="woo_custom_badge" id="woo_custom_badge" style="width:100%;">';
    foreach ($badges as $value => $label) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($value),
            selected($selected_badge, $value, false),
            esc_html($label)
        );
    }
    echo '</select>';
}

// Save the selected badge when the product is saved
add_action('save_post', function ($post_id) {
    if (!isset($_POST['woo_custom_badge_nonce']) || !wp_verify_nonce($_POST['woo_custom_badge_nonce'], 'woo_custom_badge_nonce_action')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['woo_custom_badge'])) {
        update_post_meta($post_id, '_woo_custom_badge', sanitize_text_field($_POST['woo_custom_badge']));
    }
});

// Display the badge above the title on single product pages
add_action('woocommerce_single_product_summary', function () {
    global $post;
    $badge = get_post_meta($post->ID, '_woo_custom_badge', true);

    if (!empty($badge)) {
        echo '<div class="woo-product-badge" style="background:#e63946; color:#fff; padding:5px 12px; border-radius:5px; margin-bottom:10px; display:inline-block;">' . esc_html($badge) . '</div>';
    }
}, 4);

// Shortcode to show products with a specific badge
add_shortcode('custom_badge_products', function ($atts) {
    $atts = shortcode_atts([
        'badge' => ''
    ], $atts);

    if (empty($atts['badge'])) {
        return '';
    }

    $products = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_woo_custom_badge',
                'value' => sanitize_text_field($atts['badge']),
                'compare' => '='
            ]
        ]
    ]);

    if (!$products->have_posts()) {
        return '<p>No products found for this badge.</p>';
    }

    ob_start();
    echo '<ul class="custom-badge-product-list" style="list-style:none; padding:0;">';

    while ($products->have_posts()) {
        $products->the_post();
        global $product;

        echo '<li style="margin-bottom:20px; padding:10px; border:1px solid #ccc; border-radius:5px;">';
        echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</a><br>';
        echo '<a href="' . get_permalink() . '"><strong>' . get_the_title() . '</strong></a><br>';
        echo '<span>' . $product->get_price_html() . '</span>';
        echo '</li>';
    }

    echo '</ul>';
    wp_reset_postdata();
    return ob_get_clean();
});
