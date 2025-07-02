<?php
/*
Plugin Name: Conditional Accessories Discount

*/

if (!defined('ABSPATH')) exit;

// Apply discount logic
add_action('woocommerce_cart_calculate_fees', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    $discount_label = 'Accessories Discount';
    $discount_percentage = 15;
    $min_total = 2000;
    $has_accessory = false;

    // Get the Accessories category
    $accessory_category = get_term_by('slug', 'accessories', 'product_cat');
    if (!$accessory_category) return;

    // Check if any product in cart is part of Accessories
    foreach ($cart->get_cart() as $item) {
        if (has_term($accessory_category->term_id, 'product_cat', $item['product_id'])) {
            $has_accessory = true;
            break;
        }
    }

    // Apply discount if both conditions are met
    if ($has_accessory && $cart->subtotal >= $min_total) {
        $discount = $cart->subtotal * ($discount_percentage / 100);
        $cart->add_fee($discount_label, -$discount);
        WC()->session->set('accessories_discount_applied', true);
    } else {
        WC()->session->__unset('accessories_discount_applied');
    }
}, 20);

// Display a message near totals when discount is applied
function accessories_discount_message_html() {
    if (!WC()->session || !WC()->session->get('accessories_discount_applied')) return;

    echo '<tr class="accessories-discount-msg">
            <td colspan="2">
                <div style="background: #e6f7e6; border: 1px solid #2d662d; color: #2d662d; padding: 10px; margin-top: 10px; border-radius: 5px;">
                     You’ve received a 15% Accessories Discount!
                </div>
            </td>
          </tr>';
}
add_action('woocommerce_cart_totals_after_order_total', 'accessories_discount_message_html');
add_action('woocommerce_review_order_after_order_total', 'accessories_discount_message_html');
