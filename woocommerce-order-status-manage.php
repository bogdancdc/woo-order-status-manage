<?php
/*
Plugin Name: Управление статусами заказов Woocommerce
Description: Добавляет возможность настраивать статусы заказов и email шаблоны для Woocommerce
Version: 1.0
Author: Cdc
License: GPL2
*/

if (!defined('WPINC')) {
    die;
}

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/email-send-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-template-class.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/settings-page.php';

require_once plugin_dir_path(__FILE__) . 'includes/admin/controllers/email-template.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/controllers/wc-status.php';

require_once plugin_dir_path(__FILE__) . 'includes/admin/statuses-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/email-templates-list.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/new-email-template-page.php';

function register_email_template_setting() {
    register_setting('add_new_template', 'email_template');
}
add_action('admin_init', 'register_email_template_setting');


function register_custom_email_template_settings() {
    add_option('custom_email_template_settings', array());
    register_setting('custom_email_templates', 'custom_email_template_settings');
}
add_action('admin_init', 'register_custom_email_template_settings');


// Обработка успешной онлайн-оплаты и привязка виртуальных товаров к заказу
add_action('woocommerce_payment_complete', 'process_downloadable_products_on_payment_complete');

function process_downloadable_products_on_payment_complete($order_id) {
    // Получаем список виртуальных товаров из метаданных статуса заказа
    $order = wc_get_order($order_id);
    $downloadable_products = array();

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if ($product && $product->is_downloadable()) {
            $downloadable_products[] = $product;
        }
    }

    // Проверяем, что список виртуальных товаров не пустой
    if (!empty($downloadable_products)) {
        $status_to_set = 'downloadable-paid'; // Замените на нужный вам статус
        wc_update_order_status($order_id, $status_to_set);
    }
}