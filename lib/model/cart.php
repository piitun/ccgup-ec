<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @param PDO $db
 * @param int $user_id
 * @param int $item_id
 * @return boolean
 */
function cart_is_exists_item($db, $user_id, $item_id) {
    $stmt = $db->prepare(
'SELECT item_id, amount FROM carts
 WHERE user_id = ? AND item_id = ?');

    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
    $stmt->bindValue(2,(int)$item_id,PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return array();
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        return null;
    }
    return $rows[0];

    $cart = $rows[0];
	return empty($cart) === false;
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return int | NULL
 */
function cart_total_price($db, $user_id) {

    $stmt = $db->prepare(
'SELECT sum(price * amount) as total_price
 FROM carts JOIN items
 ON carts.item_id = items.id
 WHERE items.status = 1 AND user_id = ?');

    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return array();
    }

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($row)) {
        return null;
    }

	return $row[0]['total_price'];
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return array
 */
function cart_list($db, $user_id) {

    $stmt = $db->prepare(
 'SELECT carts.id, user_id, item_id, name, price, img, stock, status, amount, (price * amount) as amount_price
 FROM carts JOIN items
 ON carts.item_id = items.id
 WHERE items.status = 1 AND user_id = ?');

    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return array();
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        return null;
    }
    return $rows;

}

/**
 * @param PDO $db
 * @param int $user_id
 * @param int $item_id
 * @return int
 */
function cart_regist($db, $user_id, $item_id) {
	$sql = '';

	if (cart_is_exists_item($db, $user_id, $item_id)) {
	    $stmt = $db->prepare(
'UPDATE carts
 SET amount = amount + 1 , update_date = NOW()
 WHERE user_id = ? AND item_id = ?');
	    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
	    $stmt->bindValue(2,(int)$item_id,PDO::PARAM_INT);

	    return $stmt->execute();
	    if ($stmt->rowCount() === 0) {
	        return false;
	    }

	} else {
	    $stmt = $db->prepare(
'INSERT INTO carts (user_id, item_id, amount, create_date, update_date)
VALUES (?,?, 1, NOW(), NOW())');
	    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);
	    $stmt->bindValue(2,(int)$item_id,PDO::PARAM_INT);

	    return $stmt->execute();
	    if ($stmt->rowCount() === 0) {
	        return false;
	    }

	}

}

/**
 * @param PDO $db
 * @param int $id
 * @param int $user_id
 * @param int $amount
 * @return int
 */
function cart_update($db, $id, $user_id, $amount) {
    $stmt = $db->prepare(
'UPDATE carts
 SET amount = ?, update_date = NOW()
 WHERE id = ? AND user_id = ?');
    $stmt->bindValue(1,(int)$amount,PDO::PARAM_INT);
    $stmt->bindValue(2,(int)$id,PDO::PARAM_INT);
    $stmt->bindValue(3,(int)$user_id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}

/**
 * @param PDO $db
 * @param int $id
 * @param int $user_id
 * @return int
 */
function cart_delete($db, $id, $user_id) {
    $stmt = $db->prepare(
'DELETE FROM carts
 WHERE id = ? AND user_id = ?');
    $stmt->bindValue(1,(int)$id,PDO::PARAM_INT);
    $stmt->bindValue(2,(int)$user_id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return int
 */
function cart_clear($db, $user_id) {
    $stmt = $db->prepare(
    'DELETE FROM carts WHERE user_id =?');
    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
	/*$sql = 'DELETE FROM carts WHERE user_id = ' . $user_id;
	return db_update($db, $sql);*/
}

function in_order_histories($db, $user_id){
    $stmt = $db->prepare(
    'INSERT INTO order_histories (user_id, bought_at)
    VALUES (?,NOW())');
    $stmt->bindValue(1,(int)$user_id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}

function in_log_items($db,$last_id,$name,$price,$amount){
    $stmt = $db->prepare(
    'INSERT INTO log_items (order_history_id, item_name, price, amount)
    VALUES (?,?,?,?)');
    $stmt->bindValue(1,(int)$last_id,PDO::PARAM_INT);
    $stmt->bindValue(2,$name,PDO::PARAM_STR);
    $stmt->bindValue(3,(int)$price,PDO::PARAM_INT);
    $stmt->bindValue(4,(int)$amount,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }

}
function last_insert_id($db){
    $sql = <<<EOD
    SELECT MAX(order_history_id)FROM order_histories
EOD;
    return db_select($db, $sql);
}

