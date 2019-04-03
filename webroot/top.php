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

{
	session_start();
	make_token();

	$db = db_connect();
	$response = array();
	$max = 9;

	__regist($db, $response);

	if(empty($_GET['page'])){
	    $page = 1;
	}else{
	    $page = $_GET['page'];
	}

	$page_id = ($page-1)*$max;

	if($page_id < 0){
	    $page_id = 0;
	}

	$response['items'] = top_item_list($db, $page_id,$max);
	$item_count = count(item_list($db));
	$max_page = ceil($item_count/ $max);

	require_once DIR_VIEW  . 'top.php';
}

/**
 * getアクセス時のみtokenを発行してsessionに保存
 */
function make_token() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        return;
    }

    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

/**
 * @param PDO $db
 * @param array $response
 */
function __regist($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	check_logined($db);

	if (empty($_POST['id']) === TRUE) {
		$response['error_msg'] = '商品の指定が不適切です。';
		return;
	}

	if (isset($_POST['token'])&& $_POST["token"] === $_SESSION['token']) {
	    if (cart_regist($db, $_SESSION['user']['id'], $_POST['id'])) {
	        $response['result_msg'] = 'カートに登録しました。';
	        return;
	    }
	}else{
	    $response['error_msg'] = '不正なアクセスです。';
	}

	$response['error_msg'] = 'カート登録に失敗しました。';
	return;
}
