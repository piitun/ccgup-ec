<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>商品一覧</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<?php require DIR_VIEW_ELEMENT . 'output_navber.php'; ?>
	<div class="container-fluid px-md-5">
		<div class="row">
			<div class="col-12">
				<h1>商品一覧</h1>
			</div>
		</div>
<?php require DIR_VIEW_ELEMENT . 'output_message.php'; ?>
		<div class="row">
<?php foreach ($response['items'] as $value)  { ?>
			<div class="card col-12 col-md-4 p-0 m-0 shadow-sm">
				<img class="item-img w-100 img-responsive"
					src="<?php echo DIR_IMG . $value['img']; ?>">
				<div class="card-body">
					<div class="row item-info">
						<div class="col-12 item-price"><?php echo (htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8')) ?>：<?php echo number_format($value['price']); ?>円</div>
						<div class="col-12 mt-1">
<?php if ($value['stock'] > 0) { ?>
							<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
								<input type="hidden" name="id"
									value="<?php echo $value['id']; ?>">
									<input type="hidden" name="token"
									value="<?php echo $_SESSION['token']; ?>">
								<button type="submit" class="btn btn-primary cart-btn">カートに入れる</button>
							</form>
<?php } else { ?>
							<p class="sold-out">売り切れ</p>
<?php } ?>
						</div>
					</div>
				</div>
			</div>
<?php } ?>

<div class="pagelink">
<?php echo '全'. $item_count. '件'. '　'; // 全データ数の表示です。

if($page > 1){ // リンクをつけるかの判定
    echo '<a href=\'/top.php?page='.($page -1).'\')>前へ</a>'. '　';
}

for($i = 1; $i <= $max_page; $i++){
    if ($i == $page) {
        echo $page. '　';
    } else {
        echo '<a href=\'/top.php?page='. $i. '\')>'. $i. '</a>'. '　';
    }
}

if($page < $max_page){ // リンクをつけるかの判定
    echo '<a href=\'/top.php?page='.($page + 1).'\')>次へ</a>'. '　';
}
?>
</div>
		</div>
	</div>
	<!-- /.container -->
	<script src="./assets/js/jquery/1.12.4/jquery.min.js"></script>
	<script src="./assets/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
