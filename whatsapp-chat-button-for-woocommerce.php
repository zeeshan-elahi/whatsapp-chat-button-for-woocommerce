<?php

/**
 * Plugin Name: WhatsApp Chat Button for WooCommerce
 * Plugin URI: https://github.com/zeeshan-elahi/whatsapp-chat-button-for-woocommerce
 * Description: This plugin will add WhatsApp Chat button in WooCommerce Store Theme next to "Add to Cart" button in product detail or view.
 * Version: 1.0
 * Author: Zeeshan Elahi
 * Author URI: http://blog.zeeshanelahi.com
 */

// Exit if plugin file accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add WhatsApp Button if product in stock
 */
function action_woocommerce_after_add_to_cart_button() {

    global $product;

    $wcbs_whatsapp_number = get_option('wcbs_whatsapp_number');

    if ( !empty($wcbs_whatsapp_number) ) {

        $message = "Hello,\nI want to know more about\n" . $product->get_name() . "(" . $product->get_sku() . ") - " . get_permalink($product->get_id()) . ".\nThank you";

        $wcbs_message = get_option('wcbs_message');

        if ( !empty($wcbs_message) ) {
            $message = $wcbs_message;

            // Replace {product_name} token
            $message = str_replace('{product_name}', $product->get_name(), $message);

            // Replace {product_sku} token
            $message = str_replace('{product_sku}', ( !empty($product->get_sku()) )? $product->get_sku() : '', $message);

            // Replace {product_url} token
            $message = str_replace('{product_url}', get_permalink($product->get_id()), $message);
        }

        $chatUrl = 'https://wa.me/' . trim( preg_replace("/[^0-9]/", "", $wcbs_whatsapp_number) ) . '?text=' . urlencode($message);

        $wcbs_whatsapp_button_class = 'button btn' . (( !empty(get_option('wcbs_whatsapp_button_class')) )? ' ' . get_option('wcbs_whatsapp_button_class') : '');
        $wcbs_button_text = get_option('wcbs_button_text');
        ?>
        <a href="<?php echo $chatUrl; ?>" class="<?php echo $wcbs_whatsapp_button_class; ?>" target="_blank">
            <?php echo ( !empty($wcbs_button_text) )? $wcbs_button_text : 'Chat with us'; ?>
        </a>
        <?php
    }
};

// add the action
add_action( 'woocommerce_after_add_to_cart_button', 'action_woocommerce_after_add_to_cart_button', 10, 0 );

/**
 * Add Plugin Settings to WooCommerce - General Settings Page
 * @param $settings
 * @return array
 */
function add_whatsapp_chat_button_settings( $settings ) {

    $new_settings = array(
        array(
            'title'     => __( 'WhatsApp Chat Button', 'whatsapp-chat-button-settings' ),
            'type'      => 'title',
            'id'        => 'whatsapp_chat_button_settings',
        ),
        array(
            'title'    => __( 'WhatsApp Contact Number', 'whatsapp-chat-button-settings' ),
            'id'       => 'wcbs_whatsapp_number',
            'type'     => 'text',
            'default'  => '',
            'desc_tip' => __( 'Please enter WhatsApp Contact Number with which you want to start chat. E.g. 0011231231234', 'whatsapp-chat-button-settings' ),
            'desc'     => __( 'Please enter WhatsApp Contact Number with which you want to start chat. E.g. 0011231231234', 'whatsapp-chat-button-settings' ),
        ),
        array(
            'title'    => __( 'Button Text', 'whatsapp-chat-button-settings' ),
            'id'       => 'wcbs_button_text',
            'type'     => 'text',
            'default'  => 'Chat with us',
            'desc_tip' => __( 'Please enter WhatsApp Contact Number with which you want to start chat. E.g. 0011231231234', 'whatsapp-chat-button-settings' ),
            'desc'     => __( 'Please enter WhatsApp Contact Number with which you want to start chat. E.g. 0011231231234', 'whatsapp-chat-button-settings' ),
        ),
        array(
            'title'    => __( 'Message', 'whatsapp-chat-button-settings' ),
            'id'       => 'wcbs_message',
            'type'     => 'textarea',
            'default'  => __('Hello, I want to know more about {product_name}({product_sku}) - {product_url}. Thank you', 'whatsapp-chat-button-settings' ),
            'desc_tip' => __( 'Please enter message to start chat', 'whatsapp-chat-button-settings' ),
            'desc'     => __( 'Please enter message to start chat. Available tokens are {product_name}, {product_sku}, {product_url}', 'whatsapp-chat-button-settings' ),
        ),
        array(
            'title'    => __( 'CSS Class', 'whatsapp-chat-button-settings' ),
            'id'       => 'wcbs_whatsapp_button_class',
            'type'     => 'text',
            'default'  => '',
            'desc_tip' => __( 'CSS Class to use with button. Class should be available in current theme', 'whatsapp-chat-button-settings' ),
        ),
        array(
            'type'  => 'sectionend',
            'id'    => 'whatsapp_chat_button_settings',
        )
    );

    return array_merge($settings, $new_settings);
}
add_filter( 'woocommerce_get_settings_general', 'add_whatsapp_chat_button_settings', 10, 2 );

function whatsapp_chat_button_for_woocommerce_settings_link( $links ) {

    //if ( defined(WC) )

    $settings_link = array();

    $settings_link[] = '<a href="' .
        admin_url( 'admin.php?page=wc-settings&tab=general' ) .
        '">' . __('Configure') . '</a>';
    return array_merge($settings_link ,$links);
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'whatsapp_chat_button_for_woocommerce_settings_link');

?>
