<?php

function process_custom_email_template_form() {
    if (isset($_POST['submit']) && $_POST['submit'] === 'Save Template') {
        // Обработка данных при сохранении нового шаблона
        
        $allowed_tags = array(
            'p' => array(),
            'br' => array(),
            'a' => array(),
            'h1' => array(),
            'strong' => array(),
        );
        

        $template_name = isset($_POST['email_template_name']) ? sanitize_text_field($_POST['email_template_name']) : '';
        $template_subject = isset($_POST['email_template_subject']) ? sanitize_text_field($_POST['email_template_subject']) : '';
        $template_content = isset($_POST['email_template_content']) ? wp_kses_post($_POST['email_template_content'], $allowed_tags) : '';
        $template_content_multiple = isset($_POST['email_template_multiple_content']) ? wp_kses_post($_POST['email_template_multiple_content'], $allowed_tags) : '';
        $slug = isset($_POST['status_slug']) ? wp_kses_post($_POST['status_slug']) : ''; // Создаем уникальный слаг для нового шаблона

        if (!empty($template_name) && !empty($template_subject) && !empty($template_content) && !empty($template_content_multiple)) {
            $settings = get_option('custom_email_template_settings', array());

            // Создаем объект EmailTemplate и добавляем его в массив
            $template = new EmailTemplate($slug, $template_name, $template_subject, $template_content, $template_content_multiple);
            $settings[$slug] = $template;
            update_option('custom_email_template_settings', $settings);

            // Сохраняем шаблон в папку
            $new_template_name = str_replace('wc-', 'customer-', $slug) . '.php';
            $new_template_multiple_name = str_replace('wc-', 'customer-', $slug) . '-multiple.php';
            $active_theme = wp_get_theme();
            $active_theme_path = $active_theme->get_stylesheet_directory();
            $template_path = $active_theme_path . '/woocommerce/emails/' . $new_template_name;
            $template_multiple_path = $active_theme_path . '/woocommerce/emails/' . $new_template_multiple_name;

            file_put_contents($template_path, $template_content);
            file_put_contents($template_multiple_path, $template_content_multiple);
        }
    } elseif (isset($_GET['delete_template']) && isset($_GET['_wpnonce'])) {
        // Обработка данных при удалении шаблона
        $slug = sanitize_title($_GET['delete_template']);
        $nonce = sanitize_text_field($_GET['_wpnonce']);

        if (wp_verify_nonce($nonce, 'delete_template_' . $slug)) {
            $settings = get_option('custom_email_template_settings', array());

            if (isset($settings[$slug])) {
                unset($settings[$slug]);
                update_option('custom_email_template_settings', $settings);

                // Удаляем шаблон из папки

                $new_template_name = str_replace('wc-', 'customer-', $slug) . '.php';
                $new_template_multiple_name = str_replace('wc-', 'customer-', $slug) . '-multiple.php';
                $active_theme = wp_get_theme();
                $active_theme_path = $active_theme->get_stylesheet_directory();
                $template_path = $active_theme_path . '/woocommerce/emails/' . $new_template_name;
                $template_multiple_path = $active_theme_path . '/woocommerce/emails/' . $new_template_multiple_name;

                if (file_exists($template_path)) {
                    unlink($template_path);
                }
                if (file_exists($template_multiple_path)) {
                    unlink($template_multiple_path);
                }
            }
        }
    }
}
add_action('admin_init', 'process_custom_email_template_form');

?>