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
<title>購入明細書</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<?php require DIR_VIEW_ELEMENT . 'output_navber.php'; ?>
	<div class="container-fluid px-md-3">
		<div class="row">
			<div class="col-12">
				<h1>購入明細書</h1>
			</div>
		</div>

<?php require DIR_VIEW_ELEMENT . 'output_message.php'; ?>

<?php if ( !empty($response['history'])) { ?>
		<div class="col-xs-12 col-md-10 offset-md-1 cart-list">
			<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th colspan="2">注文番号：<?php  echo $order_history_id ?></th>
							<th colspan="">注文日時：<?php  echo $bought_time ?></th>

						</tr>
					</thead>
					<tbody>
<?php foreach ( $response['history'] as $key => $value ) {?>
						<tr class="<?php echo (0 === ($key % 2)) ? 'stripe' : '' ; ?>">
								<td colspan="3"><?php echo (htmlspecialchars($value['item_name'], ENT_QUOTES, 'UTF-8'))?></td>
								<td><?php echo number_format($value['amount'])?>個</td>
								<td><?php echo number_format($value['price'])?>円</td>
						</tr>
						<tr class="<?php echo (0 === ($key % 2)) ? 'stripe' : '' ; ?>">

							<td>小計：<?php echo number_format($value['amount_price'])?>円</td>

						</tr>
<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td colspan="5">
								<div>
									<span>合計</span> <span><?php echo number_format($sum); ?>円</span>
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
<?php }?>
	</div>
	<!-- /.container -->
	<script src="./assets/js/jquery/1.12.4/jquery.min.js"></script>
	<script src="./assets/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function submit_change_amount(id) {
			document.getElementById('form_select_amount' + id).submit();
		}
	</script>

</body>
</html>
