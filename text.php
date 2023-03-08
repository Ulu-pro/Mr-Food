<?php /** @noinspection SpellCheckingInspection */
class Text {
  const STATUS = [
      1 => "💳 Оплачено",
      2 => "✅ Доставлено"
  ];

  const ORDER_DATA = "
Сумма: %s
Имя: `%s`
Тел: +%s
Адрес: `%s`
";

  const PAID_ORDER = "
📌 *Заказ #%s*\n
Статус: `". self::STATUS[1] ."`". self::ORDER_DATA;

  const DELIVERED_ORDER = "
✨ *Заказ #%s*\n
Статус: `". self::STATUS[2] ."`". self::ORDER_DATA ."
Приятного аппетита!
";

  const START = "
➕ *Новый заказ*\n
Закажите идеальный обед!
";

  const START_BUTTON = "Заказать";
  const BUTTON_TOTAL_ORDER = "ИТОГО ЗАКАЗ";
  const BUTTON_PAY_ORDER = "%s сум";
  const ORDER_HEADER = "ЗАКАЗ";
  const ORDER_ID_HEADER = "ЗАКАЗ #%s";
  const ORDER_EDIT = "Изменить";
  const ORDER_COMMENT_PLACEHOLDER = "Оставить комментарий...";
  const ORDER_COMMENT_DESCRIPTION =
      "Для особых запросов по заказу";
  const INVOICE_TITLE = "Заказ #%s";
  const INVOICE_DESCRIPTION =
      "Нажмите на кнопку, чтобы оплатить заказ";
  const ORDER_ITEM_FORMAT = "%s %s x%s";
  const MORE_DETAILS = "Подробнее";
}