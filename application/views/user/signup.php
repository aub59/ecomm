<h2>Inscription</h2>

<hr>

<?php if($this->session->flashdata('success')):?>
  <div class="alert alert-success"><?php echo $this->session->flashdata('success');?></div>
  <?php endif;?>

<?php if(validation_errors()):?>
  <div class="alert alert-error"><?php echo validation_errors('<p>','</p>');?></div>
<?php endif;?>

<?php echo form_open('user/signup',array('class'=>'form-horizontal'));?>

  <div class="control-group">
    <label class="control-label">Email</label>
    <div class="controls">
        <input type="text" name="email" placeholder="Email" value="<?php echo set_value('email');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Mot de passe</label>
    <div class="controls">
        <input type="password" name="password" placeholder="Mot de passe" value="<?php echo set_value('password');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Nom</label>
    <div class="controls">
        <input type="text" name="lastname" placeholder="Nom" value="<?php echo set_value('lastname');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Prénom</label>
    <div class="controls">
        <input type="text" name="firstname" placeholder="Prénom" value="<?php echo set_value('firstname');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Adresse</label>
    <div class="controls">
        <input type="text" name="address" placeholder="Adresse" value="<?php echo set_value('address');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Code postal</label>
    <div class="controls">
        <input type="text" name="cp" placeholder="Code postal" value="<?php echo set_value('cp');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Ville</label>
    <div class="controls">
        <input type="text" name="city" placeholder="Ville" value="<?php echo set_value('city');?>">
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Pays</label>
    <div class="controls">
        <select name="country">
          <option value="0">Votre pays</option>
          <?php foreach($countries as $c):?>
            <option value="<?php echo $c->country_id;?>"<?php echo set_select('country',$c->country_id);?> > <?php echo $c->country_name;?></option>
         <?php endforeach;?>
        </select>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Téléphone</label>
    <div class="controls">
        <input type="text" name="phone" placeholder="Téléphone" value="<?php echo set_value('phone');?>">
    </div>
  </div>

  <button type="submit" class="btn">Inscription</button>

  <p><a href="<?php echo site_url('user/login');?>">J'ai déjà un compte</a></p>

<?php echo form_close();?>
