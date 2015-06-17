<h2>Connexion</h2>

<hr>

<?php if($this->session->flashdata('error')):?>
  <div class="alert alert-error"><?php echo $this->session->flashdata('error');?></div>
  <?php endif;?>


<?php echo form_open('user/login',array('class'=>'form-horizontal'));?>

  <div class="control-group">
    <label class="control-label">Email</label>
    <div class="controls">
        <input type="text" name="email" placeholder="Email" value="<?php echo set_value('email');?>">
        <?php echo form_error('email','<span class="label label-important">','</span>');?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Mot de passe</label>
    <div class="controls">
        <input type="password" name="password" placeholder="Mot de passe" value="<?php echo set_value('password');?>">
        <?php echo form_error('password','<span class="label label-important">','</span>');?>
    </div>
  </div>

  <button type="submit" class="btn">Connexion</button>

  <p><a href="<?php echo site_url('user/forget');?>">J'ai oublié mon mot de passe.</a></p>
  <p><a href="<?php echo site_url('user/signup');?>">Inscription.</a></p>

<?php echo form_close();?>
