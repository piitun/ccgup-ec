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
	$row = array();
	$db = db_connect();

	check_logined($db);

	$response['history'] = history_list($db, $_SESSION['user']['id']);
/*	$row = history_item($db, $_SESSION['user']['id']);
	foreach($response['history'] as $key){
	    foreach($row as $rows){
	        if($key['order_history_id'] === $rows['order_history_id']){

	        }
	    }
	}*/

	if (empty($response['history'])) {
		$response['error_msg'] = '注文履歴はありません';
	}

	require_once DIR_VIEW . 'history.php';
}

function history_list($db, $user_id){
    $stmt = $db->prepare(
 'SELECT
  order_history_id,
  user_id,
  bought_at
FROM
  order_histories
  WHERE user_id = ?');
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

/*function history_total_price($db, $user_id) {

    $stmt = $db->prepare(
 'SELECT
order_history_id
(price * amount) as total_price
FROM
  log_items
JOIN
  order_histories
 ON order_histories.order_history_id = log_items.order_history_id
 WHERE user_id = ?');

    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return array();
    }

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($row)) {
        return null;
    }

    return $row['total_price'];
}*/

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