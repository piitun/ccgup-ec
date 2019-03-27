<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';

{
    session_start();

    $response = array();
    $result = array();
    $db = db_connect();

    check_logined($db);

    $response['history'] = history_item($db, $_SESSION['user']['id']);
    $result = array_keys(array_column($response['history'], 'order_history_id'),$_POST['id']);
    //$result =  $response['history'][$keyIndex];
    var_dump($result);

    if (empty($_POST['id'])) {
        $response['error_msg'] = 'リクエストが不適切です。';
        return;
    }

    require_once DIR_VIEW . 'receipt.php';
}

/**
 * @param PDO $db
 * @param array $response
 */
function history_item($db, $user_id){
    $stmt = $db->prepare(
        'SELECT
  order_histories.order_history_id,
  order_histories.user_id,
  order_histories.bought_at,
  log_items.item_name,
  log_items.price,
  log_items.amount,
  (price * amount) as amount_price
FROM
  order_histories
  INNER JOIN log_items
  ON order_histories.order_history_id = log_items.order_history_id
  WHERE  order_histories.user_id = ?');
    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return array();
    }

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($row)) {
        return null;
    }

    return $row;
}
