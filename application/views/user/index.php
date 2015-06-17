<h2>Mes achats</h2>

<table class="table table-bordered table-striped">
  <thead>
    <th>N° de commande</th>
    <th>Nombre d'articles</th>
    <th>Pric TTC</th>
    <th>Status</th>
    <th>Date commande</th>
    <th>Facture</th>
  </thead>

  <tbody>
    <?php if($orders):
    foreach($orders as $o):?>
    <tr>
      <td><a href="<?php echo site_url('user/commande/'.$o->order_token);?>"><?php echo $o->order_token;?></a></td>
      <td><?php echo $o->order_total_items;?></td>
      <td><?php echo number_format($o->order_amt, 2, ',' , ' ');?></td>
      <td><?php echo ($o->order_valid) ? 'Payée' : 'Attente de paiement';?></td>
      <td><?php echo date('d-m-Y',strtotime($o->order_date));?></td>
      <td><a class="btn" href="<?php echo site_url('user/facture/'.$o->order_token);?>"><i class="icon-print"></i></a></td>
    </tr>
  <?php endforeach; endif;?>
</tbody>

</table>
