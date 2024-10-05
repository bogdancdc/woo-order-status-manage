<?php

// Функция для регистрации нового статуса заказа
function custom_register_order_status() {
    if (isset($_POST['new_custom_status_slug']) && !empty($_POST['new_custom_status_slug']) &&
        isset($_POST['new_custom_status_label']) && !empty($_POST['new_custom_status_label']) &&
        isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'add_custom_status')) {

        $new_status_slug = sanitize_text_field($_POST['new_custom_status_slug']);
        $new_status_label = sanitize_text_field($_POST['new_custom_status_label']);

        // Сохраняем статус заказа в базе данных
        $all_custom_statuses = get_option('custom_wc_order_statuses', array());
        $all_custom_statuses["wc-{$new_status_slug}"] = $new_status_label;
        update_option('custom_wc_order_statuses', $all_custom_statuses);
    }

    $all_custom_statuses = get_option('custom_wc_order_statuses', array());
    foreach ($all_custom_statuses as $key => $value) {
        register_post_status(
            $key,
            array(
                'label'                     => _x( $value, 'Order status', 'woocommerce' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'   => _n_noop($value . ' (%s)', $value . ' (%s)')
            )
        );
    }
}

// Добавляем пользовательский статус заказа в массив статусов заказа WooCommerce
function custom_add_order_statuses($order_statuses) {
    $all_custom_statuses = get_option('custom_wc_order_statuses', array());

    foreach ($all_custom_statuses as $key => $value) {
        $order_statuses[$key] = _x( $value, 'Order status', 'woocommerce' );
    }

    return $order_statuses;
}

function custom_edit_order_status() {
    if (isset($_POST['edit_custom_status_slug']) && !empty($_POST['edit_custom_status_slug']) &&
        isset($_POST['edit_custom_status_label']) && !empty($_POST['edit_custom_status_label']) &&
        isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'edit_custom_status')) {

        $edit_status_slug = "wc-" . sanitize_text_field($_POST['edit_custom_status_slug']);
        $edit_status_label = sanitize_text_field($_POST['edit_custom_status_label']);

        $all_custom_statuses = get_option('custom_wc_order_statuses', array());

        if (array_key_exists($edit_status_slug, $all_custom_statuses)) {
            $all_custom_statuses[$edit_status_slug] = $edit_status_label;
            update_option('custom_wc_order_statuses', $all_custom_statuses);
        }
    }
}

function custom_delete_order_status() {
    if (isset($_POST['delete_custom_status_slug']) && !empty($_POST['delete_custom_status_slug']) &&
        isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'delete_custom_status')) {

        $delete_status_slug = sanitize_text_field($_POST['delete_custom_status_slug']);

        $all_custom_statuses = get_option('custom_wc_order_statuses', array());

        if (array_key_exists($delete_status_slug, $all_custom_statuses)) {
            unset($all_custom_statuses[$delete_status_slug]);
            update_option('custom_wc_order_statuses', $all_custom_statuses);
        }
    }
}

add_filter('wc_order_statuses', 'custom_add_order_statuses');

add_action( 'init', 'custom_register_order_status' );
add_action( 'init', 'custom_edit_order_status' );
add_action( 'init', 'custom_delete_order_status' );

?>