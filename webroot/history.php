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
	$rows = array();
	$db = db_connect();

	check_logined($db);

	$response['history'] = array_reverse(history_list($db, $_SESSION['user']['id']));
	$rows = history_item($db, $_SESSION['user']['id']);

	$histories =[];

	foreach($rows as $row){
	    $order_history_id = $row['order_history_id'];
	    if(!array_key_exists($order_history_id, $histories)){
	        $histories[$order_history_id] = [
	            'order_history_id' => $row['order_history_id'],
	            'bought_at' => $row['bought_at'],
	            'total_price' => $row['price'],
	        ];
	    }else{
	        $histories[$order_history_id]['total_price'] += $row['price'];
	    }
	}
	$responce['history'] = $histories;


	if (empty($response['history'])) {
		$response['error_msg'] = '注文履歴はありません';
	}

	require_once DIR_VIEW . 'history.php';
}


function pre($rows){
    print('<pre>');
    print_r($rows);
    print('</pre>');
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