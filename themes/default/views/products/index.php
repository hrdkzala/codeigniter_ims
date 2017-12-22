<script>
   $(document).ready(function() {
    $('#fileData').dataTable( {
       "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
       "aaSorting": [[ 0, "desc" ]],
       "iDisplayLength": <?= $Settings->rows_per_page; ?>,
       'bProcessing'    : true, 'bServerSide'    : true,
       'sAjaxSource'    : '<?= site_url('products/getdatatableajax'); ?>',
       'fnServerData': function(sSource, aoData, fnCallback) {
          aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
          $.ajax ({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
      },	
      "oTableTools": {
          "sSwfPath": "<?= $assets; ?>media/swf/copy_csv_xls_pdf.swf",
          "aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ]
      },
      "aoColumns": [ null, null, null, null, null, null, { "bSortable": false } ]

  }).columnFilter({ aoColumns: [
	{ type: "integer", bRegex:true },
   { type: "text", bRegex:true },
   { type: "text", bRegex:true },
   { type: "text", bRegex:true },
   { type: "text", bRegex:true },
   { type: "text", bRegex:true },
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
         <th><?= lang("id"); ?></th>
         <th><?= lang("name"); ?></th>
         <th><?= lang("category"); ?></th>
         <th><?= lang("price"); ?></th>
         <th><?= lang("quantity"); ?></th>
         <th><?= lang("description"); ?></th>
         <th style="width:45px;"><?= lang("actions"); ?></th>
     </tr>
 </thead>
 <tbody>
     <tr>
       <td colspan="4" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
   </tr>
</tbody>
<tfoot>
    <tr>
     <th><?= lang("id"); ?></th>
     <th><?= lang("name"); ?></th>
     <th><?= lang("category"); ?></th>
     <th><?= lang("price"); ?></th>
     <th><?= lang("quantity"); ?></th>
     <th><?= lang("description"); ?></th>
     <th style="width:100px;"><?= lang("actions"); ?></th>
 </tr>
</tfoot>
</table>

<p><a href="<?= site_url('products/add');?>" class="btn btn-primary"><?= lang("add_product"); ?></a></p>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>

