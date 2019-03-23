<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'cart.php';
require_once DIR_MODEL . 'item.php';


	session_start();

	$response = array();
	$sum = 0;

try {
    $db = db_connect();
	check_logined($db);
	__finish($db, $response);
	$db->beginTransaction();

    //採番テーブルの番号取得
	$user_id = $response["cart_items"][0]['user_id'];
	in_order_histories($db, $user_id);
	$last_id = last_insert_id($db);
	$last_id = $last_id[0]["MAX(order_history_id)"];
	foreach($response["cart_items"] as $item){
	    //$db->beginTransaction();
	    try{
	        if($item['stock'] !== 0 && $item['status'] === 1 && $item['stock'] >= $item['amount']){
	            $name = $item['name'];
	            $price = $item['price'];
	            $amount = $item['amount'];

	        //在庫数を減らす
	        item_update_saled($db, $item['item_id'], $item['amount']);
	        //履歴テーブルに追加
	        in_log_items($db,$last_id,$name,$price,$amount);
	        //カート消去
	        cart_clear($db, $_SESSION['user']['id']);

	       $response['result_msg'] = 'ご購入、ありがとうございました。';
	    }else{
	        $response['error_msg'] = '購入できませんでした。';
	    }
	} catch (PDOException $e) {
	    // 例外をスロー
	    throw $e;
	}
	}
	$db->commit();
}catch (PDOException $e) {
    $response['error_msg'] = 'DBエラー：'.$e->getMessage();
    // ロールバック処理
    $db->rollback();
}
        //合計値を求める
        foreach($response["cart_items"] as $value){
            $sum = $sum + $value['amount_price'];
         }


	require_once DIR_VIEW . 'finish.php';


/**
 * @param PDO $db
 * @param array $response
 */
function __finish($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}
	$response["cart_items"] = cart_list($db, $_SESSION['user']['id']);
	if (empty($response)) {
		$response['error_msg'] = 'カートに商品がありません。';
		return;
	}
    }
