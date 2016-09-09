<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <link rel="stylesheet" href="view/vendor/datatables/jquery.dataTables.css">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo " "; ?></h3>
      </div>
      <div class="panel-body">
        <table id="sales-tableajax" class="table table-hover"  width="100%">
          <thead>
            <tr>
              <th>Order#</th>
              <th>Status</th>
              <th>SKU</th>
              <th>Title</th>
              <th>Color</th>
              <th>Size</th>
              <th>Style</th>
              <th>Qty</th>
              <th>Priority</th>
              <th>Tracking#</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php if(isset($debug_me)) { ?>
  <pre>
  <?php if(isset($debug_me)) {var_dump($debug_me);} ?>
  </pre>
  <?php } ?>
  <script src="view/vendor/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    var tableajax = 0;
    
    
    //$.extend( true, $.fn.dataTable.defaults, {
    //});
    
    $(document).ready(function() {
      tableajax = $('#sales-tableajax').DataTable({
        "searching": false,
        "ordering": false,
        "pagingType":'simple',
        "processing": true,
        "serverSide": true,
        "ajax": {
          url: "<?php echo $datatableajax; ?>",
          type: "post",
          error: function(xhr, textStatus, thrownError){  // error handling
            alert('datatable ajax error');
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        },
        "columns": [
            { "data": "orderNumber" },
            { "data": "orderStatus" },
            { "data": "sku" },
            { "data": "title" },
            { "data": "color" },
            { "data": "size" },
            { "data": "style" },
            { "data": "quantity" },
            { "data": "priorityShipping" },
            { "data": "trackingNumber" }
        ],
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
          return ' ';
        }
      });
    });
  </script>
</div>
<?php echo $footer; ?>