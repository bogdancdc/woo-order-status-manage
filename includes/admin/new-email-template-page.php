<?php
function custom_wc_email_templates_tab_content() {
    // Получаем сохраненные настройки email-шаблона, если они есть
    $saved_settings = get_option('custom_email_template_settings', array());
    $order_statuses = wc_get_order_statuses();

    $slug_name = '';
    $template_name = '';
    $template_subject = '';
    $template_content = '';
    $template_multiple_content = '';

    if (isset($_GET['edit_template']) && $_GET['edit_template'] !== '') {
        $slug_name = $_GET['edit_template'];

        $template_name = $saved_settings[$slug_name]->name;
        $template_subject = $saved_settings[$slug_name]->subject;
        $template_content = $saved_settings[$slug_name]->content;
        $template_multiple_content = $saved_settings[$slug_name]->content_multiple;
    }

    ?>
    <div class="wrap">
        <!-- <h1><?php echo esc_html(get_admin_page_title()); ?></h1> -->
        
        <form method="post" action="">
            
            <?php
                echo '<select name="status_slug">';
                foreach ($order_statuses as $slug => $label) {
                    $selected = selected($slug, $slug_name, false);
                    echo '<option value="' . $slug . '" ' . $selected . '>' . esc_html($label) . '</option>';
                }
                echo '</select>';
            ?>

            <br><br>
            <label for="email_template_name">Email template name:</label><br>
            <input type="text" name="email_template_name" id="email_template_name" value="<?php echo esc_attr($template_name); ?>"><br><br>


            <!-- Вывод полей для настройки email-шаблона -->
            <label for="email_template_subject">Email Subject:</label><br>
            <input type="text" name="email_template_subject" id="email_template_subject" value="<?php echo esc_attr($template_subject); ?>"><br><br>

            <label for="email_template">Email Content:</label><br>
            <?php
            
            $settings = array(
                'textarea_name' => 'email_template_content',
                'textarea_rows' => 10,
                'wpautop' => false,
            );
            $settings_multiple = array(
                'textarea_name' => 'email_template_multiple_content',
                'textarea_rows' => 10,
                'wpautop' => false,
            );

            wp_editor($template_content, 'email_template_content', $settings);
            ?>

            <label for="email_template">Email Content:</label><br>
            
            <?php
            
            wp_editor($template_multiple_content, 'email_template_multiple_content', $settings_multiple);

            ?>
            <br><br>

            <!-- Здесь можно добавить другие поля настройки по вашему желанию -->

            <input type="submit" name="submit" value="Save Template" class="button button-primary">
        </form>
    </div>
    <?php
}
?>