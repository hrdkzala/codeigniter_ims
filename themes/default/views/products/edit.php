
<div class="page-head">
  <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("update_info"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
  <div class="container">
   	<?php $attrib = array('class' => 'form-horizontal'); echo form_open_multipart("products/edit?id=".$id);?>
<div class="row">
<div class="form-group">
  <div class="controls"> <?= form_submit('submit', lang("update_product"), 'class="btn btn-primary"');?> </div>
</div>
<?= form_close();?> 
   
   <div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>