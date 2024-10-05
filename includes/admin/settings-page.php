<?php
function custom_wc_settings_page() {
    add_submenu_page(
        'woocommerce',
        // __('Статусы заказов', 'wc-order-status-manage'),
        __('Статусы заказов', 'wc-order-status-manage'),
        'manage_options',
        'custom-order-status',
        'custom_wc_settings_page_content'
    );
}
add_action('admin_menu', 'custom_wc_settings_page');

function custom_wc_settings_tabs() {
    $tabs = array(
        'statuses' => __('Статусы заказов', 'wc-order-status-manage'),
        'existing_templates' => __('Шаблоны писем', 'wc-order-status-manage'),
        'add_new_template' => __('Добавить шаблон', 'wc-order-status-manage'),
    );
    return $tabs;
}

function custom_wc_settings_tab_content($tab) {
    switch ($tab) {
        case 'statuses':
            // Управление статусами заказов
            custom_wc_statuses_tab_content();
            break;
        case 'add_new_template':
            // Добавление Email шаблона
            custom_wc_email_templates_tab_content();
            break;
        case 'existing_templates':
            // Список Email шаблонов
            custom_wc_email_existing_templates();
            break;
        default:
            // Таба по умолчанию Управление статусами заказов
            custom_wc_statuses_tab_content();
            break;
    }
}

// Содержимое страницы настроек с формой для создания нового статуса
function custom_wc_settings_page_content() {
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'create';
    ?>
    <div class="wrap">
        <!-- Output navigation tabs -->
        <h2 class="nav-tab-wrapper">
            <?php
            $tabs = custom_wc_settings_tabs();
            foreach ($tabs as $tab_slug => $tab_title) {
                $tab_url = add_query_arg(['tab' => $tab_slug], menu_page_url('custom-order-status', false));
                printf('<a href="%s" class="nav-tab %s">%s</a>', esc_url($tab_url), $active_tab == $tab_slug ? 'nav-tab-active' : '', esc_html($tab_title));
            }
            ?>
        </h2>

        <!-- Output tab content -->
        <?php custom_wc_settings_tab_content($active_tab); ?>
    </div>
    <?php
}
?>