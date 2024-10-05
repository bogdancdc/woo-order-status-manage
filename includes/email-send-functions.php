<?php

function send_custom_email_on_order_status_change($order_id, $old_status, $new_status, $order) {
    // Get the slug of the new order status
    $new_status_slug = sanitize_title($new_status);
    $wc_slug = 'wc-' . sanitize_title($new_status);

    $order_products = $order->get_items();
    $downloadable_products_file_links = array();

    foreach ($order_products as $item) {
        $product = $item->get_product();

        if ($product && $product->is_downloadable()) {
            $product_id = $product->get_id();
            $downloadable_files = $product->get_downloads();
            $downloadable_products_file_links[$product_id]['title'] = $product->get_title();

            // Get the file links for each downloadable file in the product
            foreach ($downloadable_files as $file) {
                $file_url = $file['file'];
                $downloadable_products_file_links[$product_id]['links'][] = $file_url;
            }
        }
    }

    

    // Get settings from the 'custom_email_template_settings' option
    $settings = get_option('custom_email_template_settings', array());
    // Check if we have a template associated with the new order status
    if (isset($settings[$wc_slug])) {

        $set = $settings[$wc_slug];

        // Get the template path from the option
        $active_theme = wp_get_theme();
        $active_theme_path = $active_theme->get_stylesheet_directory();
        $template_path = $active_theme_path . '/woocommerce/emails/customer-' . $new_status_slug . '.php';
        $template_path_multiple = $active_theme_path . '/woocommerce/emails/customer-' . $new_status_slug . '-multiple.php';

        $email_subject = $set->subject; // Replace with the desired email subject
        $email_headers = array('Content-Type: text/html; charset=UTF-8');
        $email_content = "";
        $product_links = "";
        $customer_email = $order->get_billing_email();

        if (!empty($downloadable_products_file_links)) {

            if (count($downloadable_products_file_links) > 1) {
                if (file_exists($template_path_multiple)) {
                    // Load the content of the multiple template
                    $email_content = file_get_contents($template_path_multiple);

                    // Replace special placeholders with corresponding values
                    $email_content = str_replace('{{order_number}}', $order->get_order_number(), $email_content);

                    $new_string = "";

                    foreach ($downloadable_products_file_links as $item) {
                        $new_string .= '<h3>' . $item['title'] . '</h3>';

                        foreach ($item['links'] as $link) {
                            $new_string .= $link . '<br>';
                        }
                    }

                    // $settings_str = var_export($new_string, true);
                    // file_put_contents(plugin_dir_path(__FILE__) . '/log.txt', $settings_str . PHP_EOL . '+++++' . PHP_EOL, FILE_APPEND);
    
                    $email_content = str_replace('{{product_link}}', $new_string, $email_content);
                }
            } else {
                if (file_exists($template_path)) {
                    // Load the content of the single template
                    $email_content = file_get_contents($template_path);

                    // Replace special placeholders with corresponding values
                    $email_content = str_replace('{{order_number}}', $order->get_order_number(), $email_content);

                    if (!empty($downloadable_products_file_links)) {
                        foreach ($downloadable_products_file_links[$product_id]['links'] as $item) {
                            $product_links .= $item . ", ";
                        }
                        $email_content = str_replace('{{product_link}}', rtrim($product_links, ", "), $email_content);
                    }
                }
            }
        }

        // Send the email using the appropriate template
        if (!empty($email_content)) {
            // Get customer email from the order
            $customer_email = $order->get_billing_email();

            // Send the email
            $sent = wp_mail($customer_email, $email_subject, $email_content, $email_headers);

            // if ($sent) {
            //     // Email successfully sent
            //     // Add additional processing code here
            // } else {
            //     // Error occurred while sending the email
            //     // Add error handling code here
            // }
        }
    }
}


add_action('woocommerce_order_status_changed', 'send_custom_email_on_order_status_change', 20, 4);
?>