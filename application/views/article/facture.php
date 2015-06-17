<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>

  <div style="text-align:right">
   
    <p>Shop</p>
    <p>2 rue des plantes</p>
    <p>75 000 Paris</p>
    <p>SIRET : 5225123985752</p>
    <p>Date : <?php echo date('d-m-Y',strtotime($order->order_date));?></p>
  </div>

  <p><?php echo $this->user->lastname.' '.$this->user->firstname;?></p>
  <p><?php echo $this->user->address;?></p>
  <p><?php echo $this->user->postal;?></p>
  <p><?php echo $this->user->city;?></p>

  <br>

  <table class="table table-bordered">
    <tr style="background:#f7f7f7f;">
      <td>Description</td>
      <td>Quantité</td>
      <td>Prix unitaire</td>
      <td>Total</td>
    </tr>

    <?php foreach($sales as $s):
    $article = $this->sitemodel->get_one($s->sale_article_id);?>

    <tr>
      <td><?php echo $article->title;?></td>
      <td><?php echo $s->sale_qty;?></td>
      <td><?php echo number_format($article->price_amount, 2, ',', ' ');?></td>
      <td><?php echo number_format($article->price_amount * $s->sale_qty, 2, ',' , ' ');?></td>
    </tr>

  <?php endforeach;?>
  </table>
  <p>Prix en euro</p>

  <div style="text-align:right">
   <h4> Payé : <?php echo number_format($order->order_amt, 2, ',', ' ');?> €</h4>
  </div>

</body>
</html>
