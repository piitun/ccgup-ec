<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @param PDO $db
 * @param int $login_id
 * @param string $password
 * @return NULL|array
 */
function user_get_login($db, $login_id, $password) {
	$stmt = $db->prepare('SELECT id, login_id, password, is_admin, create_date, update_date
                        FROM users
                        WHERE login_id = ? AND password = ?');
	$stmt->bindValue(1,(int)$login_id,PDO::PARAM_INT);
	$stmt->bindValue(2,sha1($password),PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() === 0) {
	    return array();
	}
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (empty($rows)) {
	    return null;
	}
	return $rows[0];
	//return $rows;
}

/**
 * @param PDO $db
 * @param int $id
 * @return NULL|array
 */
function user_get($db, $id) {
    $stmt = $db->prepare('SELECT id, login_id, password, is_admin, create_date, update_date
                            FROM users
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
    return $rows[0];

}
