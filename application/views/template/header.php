
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php  echo (!empty($title)) ? $title : false;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo site_url();?>">Shop</a>
		  <a class="brand" href="<?php echo site_url('article/prix');?>">Prix</a>
		  <a class="brand" href="<?php echo site_url('article/panier');?>">Dispo</a>
          <div class="nav-collapse collapse">

            <ul class="nav">
              <?php if(!$this->sitemodel->is_logged()):?>
                <li><a href="<?php echo site_url('user/signup');?>">Inscription</a></li>
             <?php else:?>
                  <li><a href="<?php echo site_url('user');?>">Mes achats</a></li>
                  <li><a href="<?php echo site_url('user/logout');?>">Logout</a></li>
            <?php endif;?>
              <?php if($this->cart->contents()):?>
                <li>
                  <a href="<?php echo site_url('article/panier');?>">
                   Mon panier(<span class="nb_article"><?php echo $this->cart->total_items();?></span>)
                  </a>
                </li>
              <?php endif;?>
            </ul>

            <?php if(!$this->user):?>
            <?php echo form_open('user/login',array('class'=>'navbar-form pull-right'));?>
              <input name="email" class="span2" type="text" placeholder="Email">
              <input name="password" class="span2" type="password" placeholder="Mot de passe">
              <button type="submit" class="btn">Login</button>
            <?php echo form_close();?>
          <?php endif;?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
