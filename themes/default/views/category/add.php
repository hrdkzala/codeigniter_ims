<div class="page-head">
  <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("enter_info"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
  <div class="container">

   	<?php $attrib = array('class' => 'form-horizontal'); echo form_open_multipart("category/add");?>

<div class="form-group">
  <label for="name"><?= lang("name"); ?></label>
  <div class="controls"> <?= form_input('name', '', 'class="form-control" id="name"');?>
  </div>
</div> 
<div class="form-group">
  <label for="price"><?= lang("description"); ?></label>
  <div class="controls"> 
  </div>
</div> 
<div class="form-group">
  <div class="controls"> <?= form_submit('submit', lang("add_category"), 'class="btn btn-primary"');?> </div>
</div>
<?= form_close();?> 
   
   <div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>