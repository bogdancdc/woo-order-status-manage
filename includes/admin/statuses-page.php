<?php
// Функция для вывода списка статусов и селекта с кастомными емейл шаблонами
function custom_wc_statuses_tab_content() {
    ?>
    <div class="wrap">
        <h2><?php _e('Статусы заказов', 'wc-order-status-manage'); ?></h2>
        <div style="display: flex; gap: 24px; flex-direction: column; margin-top: 20px;">
            <?php
            // Получаем список всех статусов заказов
            $order_statuses = wc_get_order_statuses();

            foreach ($order_statuses as $slug => $label) {
                // Выводим статус заказа
                echo '<div style="display: flex; align-items: center; gap: 24px;">';
                // echo '<input type="checkbox" name="delete_status[]" value="' . $slug . '">';
                
                // Выводим инпут для редактирования названия статуса заказа
                echo '<input type="text" name="status_label[' . $slug . ']" value="' . $label . '">';
                // Выводим инпут для редактирования названия статуса заказа
                echo '<input type="text" name="status_slug[' . $slug . ']" value="' . $slug . '">';
                
                // Добавляем кнопку для удаления статуса заказа
                echo '<form method="POST">';
                wp_nonce_field('delete_custom_status', '_wpnonce');
                echo '<button type="submit" name="delete_custom_status_slug" value="' . $slug . '">Удалить</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
        <br>



        <h2><?php _e('Добавить новый статус заказа', 'wc-order-status-manage'); ?></h2>
        <form method="post" action="">
            <div style="display: flex; align-items: flex-end; gap: 24px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label for="new_custom_status_slug"><?php _e('Идентификатор (slug) нового статуса', 'wc-order-status-manage'); ?></label>
                    <input type="text" name="new_custom_status_slug" id="new_custom_status_slug" value="">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label for="new_custom_status_label"><?php _e('Название нового статуса', 'wc-order-status-manage'); ?></label>
                    <input type="text" name="new_custom_status_label" id="new_custom_status_label" value="">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php wp_nonce_field('add_custom_status'); ?>
                    <button class="button" type="submit"><?php _e('Сохранить', 'wc-order-status-manage'); ?></button>
                </div>
            </div>
        </form>
    </div>
    <?php
    
    function add_email_template_select($order_statuses) {
        // Получаем список email-шаблонов
        $email_templates = get_woocommerce_email_templates();
    
        // Вставляем селект с email-шаблонами для вашего статуса заказа
        echo '<tr valign="top">
            <th scope="row" class="titledesc">Custom Status Email Template</th>
            <td class="forminp">
                <select name="woocommerce_custom_status_email_template">';
        foreach ($email_templates as $template_id => $template_title) {
            echo '<option value="' . esc_attr($template_id) . '">' . esc_html($template_title) . '</option>';
        }
        echo '</select></td>
        </tr>';
    
        return $order_statuses;
    }
    add_filter('wc_order_statuses', 'add_email_template_select');
    
}

?>