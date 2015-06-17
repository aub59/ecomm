
  <h2>Commande n° <?php echo $order->order_token;?></h2>

  <table class="table table-bordered table-striped">

    <thead>
      <th>Image</th>
      <th>Description</th>
      <th>Prix</th>
      <th>Quantité</th>
      <th>Total</th>
    </thead>

    <tbody>
      <?php foreach($sales as $s):
      $article = $this->sitemodel->get_one($s->sale_article_id);?>
      <tr>
        <td>
          <a href="<?php echo site_url('article/show/'.$article->article_id);?>">
            <img src="<?php echo $this->picture_path.$article->image_name;?>" width="100" height="100">
          </a>
        </td>

        <td><?php echo $article->title;?></td>
        <td><?php echo number_format($article->price_amount, 2, ',', ' ');?> €</td>
        <td><?php echo $s->sale_qty;?></td>
        <td><?php echo number_format($article->price_amount * $s->sale_qty, 2, ',', ' ');?> €</td>
      </tr>
    <?php endforeach;?>
    </tbody>

  </table>

  <h4>Total de la commande : <?php echo number_format($order->order_amt,2,',',' ');?></h4>

<?php endif;?>
