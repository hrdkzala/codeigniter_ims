<script>
   $(document).ready(function() {
    $('#fileData').dataTable( {
       "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
       "aaSorting": [[ 0, "desc" ]],
       "iDisplayLength": <?= $Settings->rows_per_page; ?>,
       'bProcessing'    : true,
       "oTableTools": {
          "sSwfPath": "<?= $assets; ?>media/swf/copy_csv_xls_pdf.swf",
          "aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ]
      },
      "aoColumns": [ null, { "bSortable": false } ]

  }).columnFilter({ aoColumns: [
   { type: "text", bRegex:true },
   //{ type: "text", bRegex:true },
   //{ type: "text", bRegex:true },
   null
   ]});
}); 
</script>

<div class="page-head">
  <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("list_results"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
  <div class="container">

   <table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-hover table-striped">
      <thead>
        <tr>
         <th><?= lang("name"); ?></th>
         <th style="width:45px;"><?= lang("actions"); ?></th>
     </tr>
 </thead>
 <tbody>
     <?php
     foreach($categories as $id=>$cat)
     {
     	if($cat!="")
     	{
	     	?>
	     	<tr>
	     		<td><?= $cat?></td>
	     		<td>
	     				<div class='btn-group'>
	     					<a class="tip btn btn-primary btn-xs" title="<?= lang("edit_category"); ?>" href="<?= site_url('category/edit?id='.$id); ?>">
	     						<i class="fa fa-edit"></i>
	     					</a>
	     					<a class="tip btn btn-danger btn-xs" title="<?= lang("delete_category"); ?>" href="<?= site_url('category/delete?id='.$id); ?>" onClick="return confirm(<?= lang('alert_x_category'); ?>)">
	     						<i class="fa fa-trash-o"></i>
	     					</a>
	     				</div>
	     		</td>
	     	</tr>
	     	<?php 
     	}
     } 
     ?>
</tbody>
<tfoot>
    <tr>
     <th><?= lang("name"); ?></th>
     <th style="width:100px;"><?= lang("actions"); ?></th>
 </tr>
</tfoot>
</table>

<p><a href="<?= site_url('category/add');?>" class="btn btn-primary"><?= lang("add_category"); ?></a></p>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>

