<?php if($articles):?>

  <ul class="thumbnails">
    <?php foreach($articles as $a):
    $show = site_url('article/show/'.$a->article_id);?>
    <li class="span4">
        <div class="thumbnail">
            <a href="<?php echo $show;?>">
              <img src="<?php echo $this->picture_path.$a->image_name;?>" alt="<?php echo $a->title;?>" width="256" height="256">
            </a>
            <div class="caption">
              <h3><?php echo $a->title;?></h3>
              <p><?php echo character_limiter($a->description, 200);?></p>
              <p>
                <a href="<?php echo $show;?>" class="btn btn-primary">Voir</a>
				
                <a href="" class="btn btn-warning"><?php echo number_format($a->price_amount,2, ',', ' ');?> €</a>
              </p>
            </div>
        </div>
    </li>
    <?php endforeach;?>
  </ul>

<?php endif;?>
