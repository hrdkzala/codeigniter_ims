
<div class="page-head">
  <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("update_info"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
  <div class="container">
   	<?php $attrib = array('class' => 'form-horizontal'); echo form_open_multipart("category/edit?id=".$id);?><div class="form-group">  <label for="name"><?= lang("parent_id"); ?></label>  <div class="controls">   <?php   if($category->parent_id > 0)  {  	echo form_dropdown('parent_id',$parent_category,$category->parent_id,'class="form-control"');  }  else  {  	echo form_dropdown('parent_id',$parent_category,'','class="form-control"');  }  ?>  </div></div>    	   	
<div class="form-group">
  <label for="name"><?= lang("name"); ?></label>
  <div class="controls"> <?= form_input('name', $category->name, 'class="form-control" id="name"');?>
  </div>
</div> 
<div class="form-group">  <label for="price"><?= lang("description"); ?></label>  <div class="controls">   	<?php   	//echo form_input('price', '', 'class="form-control" id="price"');  	echo form_textarea('description',$category->description, 'class="form-control" id="description"');  	?>  </div></div> <div class="form-group">  <label for="price"><?= lang("image"); ?></label>  <div class="controls">   	<input type="file" name="flCategory" id="flCategory" value="" />  	<?php  	if($category->image)  	{  		echo '<img src="'.base_url().'uploads/category/'.$category->image.'" width="100">';  	}   	?>  </div></div>
<div class="form-group">
  <div class="controls"> <?= form_submit('submit', lang("update_category"), 'class="btn btn-primary"');?> </div>
</div>
<?= form_close();?> 
   
   <div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>
