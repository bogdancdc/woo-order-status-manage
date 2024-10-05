<?php
/**
 * Базовый шаблон для email-уведомлений WooCommerce.
 *
 * Внутри этого шаблона вы можете использовать специальные метки, которые будут заменяться реальными значениями
 * при отправке письма.
 *
 * Список доступных специальных меток:
 * {{order_date}} - дата создания заказа
 * {{order_number}} - номер заказа
 * {{order_status}} - статус заказа
 * {{order_billing_name}} - имя клиента (для оплаты)
 * {{order_billing_email}} - email клиента (для оплаты)
 * {{order_billing_phone}} - телефон клиента (для оплаты)
 * {{order_billing_address}} - адрес клиента (для оплаты)
 * {{order_shipping_address}} - адрес доставки клиента (если применимо)
 * {{order_payment_method}} - способ оплаты
 * {{order_shipping_method}} - способ доставки (если применимо)
 * {{order_note}} - дополнительные заметки к заказу
 * {{order_items}} - список товаров в заказе (включая названия, цены, количество и т. д.)
 * {{site_title}} - название вашего сайта
 * {{site_url}} - URL вашего сайта
 *
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_email_header', $email_heading, $email);
?>
{{content}}
<?php
do_action('woocommerce_email_footer', $email);
