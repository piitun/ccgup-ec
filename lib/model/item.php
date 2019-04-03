<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @param PDO $db
 * @param string $name
 * @param string $img
 * @param int $price
 * @param int $stock
 * @param int $status
 * @return number
 */
function item_regist($db, $name, $img, $price, $stock, $status) {

    $stmt = $db->prepare(
'INSERT INTO items (name, img, price, stock, status, create_date, update_date)
 VALUES (?,?,?,?,?,NOW(), NOW())');

    $stmt->bindValue(1,$name,PDO::PARAM_STR);
    $stmt->bindValue(2,$img,PDO::PARAM_STR);
    $stmt->bindValue(3,(int)$price,PDO::PARAM_INT);
    $stmt->bindValue(4,(int)$stock,PDO::PARAM_INT);
    $stmt->bindValue(5,(int)$status,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}

/**
 * @param PDO $db
 * @param int $id
 * @return number
 */
function item_delete($db, $id) {
	$row = item_get($db, $id);

	if (!empty($row)) {
		@unlink(DIR_IMG_FULL . $row['img']);
	}
	$stmt = $db->prepare(
	'DELETE FROM items WHERE id = ?');
	$stmt->bindValue(1,(int)$id,PDO::PARAM_INT);

	return $stmt->execute();
	if ($stmt->rowCount() === 0) {
	    return false;
	}

}

/**
 * @param PDO $db
 * @return array
 */
function top_item_list($db,$page_id,$max){
    $stmt = $db->prepare(
 'SELECT id, name, price, img, stock, status, create_date, update_date
 FROM items WHERE status = 1 LIMIT ?,?');

    $stmt->bindValue(1,(int)$page_id, PDO::PARAM_INT);
	$stmt->bindValue(2,(int)$max, PDO::PARAM_INT);
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

function item_list($db, $is_active_only = true) {
    $sql = <<<EOD
 SELECT id, name, price, img, stock, status, create_date, update_date
 FROM items
EOD;
    if ($is_active_only) {
        $sql .= " WHERE status = 1";
    }
    return db_select($db, $sql);
}


/**
 * @param PDO $db
 * @param int $id
 * @return NULL|mixed
 */
function item_get($db, $id) {
    $stmt = $db->prepare('SELECT id, name, price, img, stock, status, create_date, update_date
 FROM items
 WHERE id = ?');
    $stmt->bindValue(1,(int)$id,PDO::PARAM_INT);
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
 *
 * @param PDO $db
 * @param array $cart_items
 * @return boolean
 */
function item_update_stock($db, $id, $stock) {
    $stmt = $db->prepare(
 'UPDATE items
 SET stock = ?, update_date = NOW()
 WHERE id = ?');
    $stmt->bindValue(1,(int)$stock,PDO::PARAM_INT);
    $stmt->bindValue(2,(int)$id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }

}

/**
 *
 * @param PDO $db
 * @param array $cart_items
 * @return boolean
 */
function item_update_saled($db, $id, $amount) {
    $stmt = $db->prepare(
 'UPDATE items
 SET stock = stock - ?, update_date = NOW()
 WHERE id = ?');
    $stmt->bindValue(1,(int)$amount,PDO::PARAM_INT);
    $stmt->bindValue(2,(int)$id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}


/**
 *
 * @param PDO $db
 * @param array $cart_items
 * @return boolean
 */
function item_update_status($db, $id, $status) {
    $stmt = $db->prepare(
'UPDATE items
 SET status = ?, update_date = NOW()
 WHERE id = ?');
    $stmt->bindValue(1,(int)$status,PDO::PARAM_INT);
    $stmt->bindValue(2,(int)$id,PDO::PARAM_INT);

    return $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}

/**
 * @param string $status
 * @return boolean
 */
function item_valid_status($status) {
	return "0" === (string)$status || "1" === (string)$status;
}
