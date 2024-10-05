<?php

function custom_wc_email_existing_templates() {

    $settings = get_option('custom_email_template_settings', array());
    $order_statuses = wc_get_order_statuses();
    ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php echo esc_html__('Статус заказа', 'wc-order-status-manage'); ?></th>
                <th><?php echo esc_html__('Название шаблона', 'wc-order-status-manage'); ?></th>
                <th><?php echo esc_html__('Действия', 'wc-order-status-manage'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($settings as $slug => $template) : ?>
                <tr>
                    <td><?php echo esc_html($order_statuses[$slug]); ?></td>
                    <td><?php echo esc_html($template->name); ?></td>
                    <td>
                        <a href="<?php echo esc_url(add_query_arg('edit_template', $slug, admin_url('admin.php?page=custom-order-status&tab=add_new_template'))); ?>"><?php echo esc_html__('Редактировать', 'wc-order-status-manage'); ?></a>
                        <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('delete_template', $slug, admin_url('admin.php?page=custom-order-status&tab=existing_templates')), 'delete_template_' . $slug)); ?>"><?php echo esc_html__('Удалить', 'wc-order-status-manage'); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

?>