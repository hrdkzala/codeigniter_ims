<div class="page-head">

  <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("enter_info"); ?></span> </h2>

</div>

<div class="clearfix"></div>

<div class="matter">

  <div class="container">



   	<?php $attrib = array('class' => 'form-horizontal'); echo form_open_multipart("products/add");?>


<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		  <label for="name"><?= lang("name"); ?></label>
		
		  <div class="controls"> <?= form_input('name', '', 'class="form-control" id="name"');?>
		
		  </div>
		
		</div> 
	</div>
	<div class="col-md-6">
		<div class="form-group">
		  <label for="name"><?= lang("category"); ?></label>
		  
		  <div class="controls"> 
		  <?= 
		  form_dropdown('category_id',$categories,'','class="form-control"');
		  ?>
		  </div>
		</div> 
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		  <label for="price"><?= lang("price"); ?></label>
		
		  <div class="controls"> <?= form_input('price', '', 'class="form-control" id="price"');?>
		
		  </div>
		
		</div> 
	</div>
	<div class="col-md-6">
		<div class="form-group">
		  <label for="price"><?= lang("quantity"); ?></label>
		
		  <div class="controls"> <?= form_input('qty', '', 'class="form-control" id="qty"');?>
		
		  </div>
		
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<?php if($Settings->default_tax_rate) { ?>
		
			<div class="form-group">
		
		      <label for="tax_rate"><?= lang("tax_rate"); ?></label>
		
		      <div class="controls">
		
		        <?php 
		
			 	foreach($tax_rates as $rate){
		
		    		$tr[$rate->id] = $rate->name;
		
				}
				echo form_dropdown('tax_rate', $tr, $Settings->default_tax_rate, 'class="form-control" id"tax_rate"'); ?>
		
		      </div>
		
		    </div>
		
		    <?php } ?>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group">
		  <label for="price"><?= lang("Image"); ?></label>
		
		  <div class="controls"> 
		  	<table border="0" width="100%">
		  		<tbody id="firstRow">
		  			<input type="hidden" name="txtTotalRow" id="txtTotalRow" value="1" />
			  		<tr id="row1">
			  			<td><input type="file" name="productImage[]" value="" /></td>
			  			<td align="center"><img src="<?php echo base_url()?>themes/default/assets/img/plus.png" onclick="addRow()" /></td>
			  		</tr>
		  		</tbody>
		  	</table>
		  	
		  </div>
		
		</div>
	</div>
	<div class="col-md-6">
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-group">
		  <label for="price"><?= lang("description"); ?></label>
		
		  <div class="controls"> 
		  	<?php
		  	echo form_textarea('description','','class="summernote" rows="10"');
		  	?>
		
		  </div>
		
		</div>
	</div>
</div>


<div class="form-group">

  <div class="controls"> <?= form_submit('submit', lang("add_product"), 'class="btn btn-primary"');?> </div>

</div>

<?= form_close();?> 

   

   <div class="clearfix"></div>

  </div>

  <div class="clearfix"></div>

</div>

<link href="<?php echo base_url()?>themes/default/assets/summernote/summernote.css" rel="stylesheet">
<script src="<?php echo base_url()?>themes/default/assets/summernote/summernote.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('.summernote').summernote({
		  height: 200                 // set editor height
		  //minHeight: null,             // set minimum height of editor
		  //maxHeight: null,             // set maximum height of editor
		  //focus: true                  // set focus to editable area after initializing summernote
		});
});

function addRow()
{
	var getTotalRows = $("#txtTotalRow").val();
	var calRow = parseInt(getTotalRows) + 1;
	$("#txtTotalRow").val(calRow);
	var html = '';
	html += '<tr id="row'+calRow+'">';
	html += '<td><input type="file" name="productImage[]" value="" /></td>';
	html += '<td align="center"><img src="<?php echo base_url()?>themes/default/assets/img/minus.png" onclick="minus('+calRow+')" /></td>';
	html += '</tr>';

	$("#firstRow").last().append(html);
}

function minus(rowNo)
{
	$("#row"+rowNo).remove();
}
</script>