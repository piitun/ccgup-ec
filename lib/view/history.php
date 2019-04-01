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
<title>注文履歴</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<?php require DIR_VIEW_ELEMENT . 'output_navber.php'; ?>

	<div class="container-fluid px-md-3">
		<div class="row">
			<div class="col-12">
				<h1>注文履歴</h1>
			</div>
		</div>

<?php require DIR_VIEW_ELEMENT . 'output_message.php'; ?>

<?php if ( !empty($response['history'])) { ?>
		<div class="col-xs-12 col-md-10 offset-md-1 cart-list">
			<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">注文履歴</th>
						</tr>
					</thead>
					<tbody>
<?php
$index = 0;
foreach ($response['history'] as $key ) {
$index ++;
?>
						<tr class="<?php echo (0 === ($index % 2)) ? 'stripe' : '' ; ?>">
								<td colspan="2">注文番号:<?php echo $key['order_history_id']?></td>
								<td colspan="2" align = right>
								<form action="./receipt.php" method="get">
									<button type="submit" class="btn btn-success btn-sm">領収書／購入明細書</button>
									<input type="hidden" name="id"
										value="<?php echo $key['order_history_id']; ?>">
										<input type="hidden" name="time"
										value="<?php echo $key['bought_at']; ?>">
								</form>
							</td>
						</tr>
						<tr class="<?php echo (0 === ($index % 2)) ? 'stripe' : '' ; ?>">
							<td colspan="2">注文日時:<?php echo $key['bought_at']?></td>
							<td colspan="2" align = right><?php echo number_format($key["total_price"])?>円</td>
						</tr>
<?php } ?>
					</tbody>

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
