<?php if($cart):?>

<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Description</th>
			<th>Qty</th>
			<th>Price</th>
			<th>Total</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($cart as $cart):
		$article = $this->sitemodel->get_one($cart['id']); $show = site_url('article/show/'.$article->article_id);?>

			<tr>
				<td>
					<a href="<?php echo $show;?>">
						<img src="<?php echo $this->picture_path.$article->image_name;?>" width="100" height="100">
					</a>
				</td>
				<td><strong><?php echo $cart['name'];?></strong></td>
				<td>
					<span class="update_form">
						<?php echo form_open('article/update/'.$cart['rowid'], array('class'=>'form-inline'));?>
							<input type="hidden" name="article_id" value="<?php echo $article->article_id;?>">
							<input type="hidden" name="price" value="<?php echo $cart['price'];?>">
							<input type="text" name="qty" class="input-small" value="<?php echo $cart['qty'];?>">
							<button class="btn"><i class="icon-pencil"></i></button>
							<span class="delete">
								<a href="<?php echo site_url('article/delete/'.$cart['rowid']);?>" class="btn btn-inverse"><i class="icon-white icon-trash"></i></a>
							</span>
						<?php echo form_close();?>
					</span>
				</td>
				<td><?php echo number_format($cart['price'], 2, ',', ' ');?></td>
				<td><span class="total_for_item"><?php echo number_format($cart['price'] * $cart['qty'], 2, ',', ' ');?></span> €</td>
			</tr>

		<?php endforeach;?>

		<tr>
			<td colspan="6">&nbsp</td>
		</tr>
		<tr>
			<td colspan="4"><strong>Nombre d'articles</strong></td>
			<td><span class="nb_article"><?php echo $total_articles;?></span></td>
		</tr>

		<tr>
			<td colspan="4"><strong>Total</strong></td>
			<td><strong><span class="total"><?php echo number_format($total, 2, ',', ' ');?></span> € </strong></td>
		</tr>
	</tbody>
</table>
<span><a class="btn btn-success" href="<?php echo site_url('user/');?>">Payer ma commande</a>

<?php else:?>
<h2>Aucun article dans le panier</h2>
<?php endif;?>
