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
    $sum = 0;
    $order_history_id = $_GET['id'];
    $bought_time = $_GET['time'];


    check_logined($db);

    $response['history'] = history_item($db, $_GET['id']);



    if (empty($_GET['id'])) {
        $response['error_msg'] = 'リクエストが不適切です。';
        return;
    }

    if (empty($_GET['time'])) {
        $response['error_msg'] = 'リクエストが不適切です。';
        return;
    }

    //合計値を求める
    foreach($response["history"] as $value){
        $sum = $sum + $value['amount_price'];
    }

    require_once DIR_VIEW . 'receipt.php';
}

/**
 * @param PDO $db
 * @param array $response
 */
function history_item($db, $order_history_id){
    $stmt = $db->prepare(
        'SELECT
  log_items.item_name,
  log_items.price,
  log_items.amount,
  (price * amount) as amount_price
FROM
  log_items
  WHERE  order_history_id = ?');
    $stmt->bindValue(1,(int)$order_history_id,PDO::PARAM_INT);
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
