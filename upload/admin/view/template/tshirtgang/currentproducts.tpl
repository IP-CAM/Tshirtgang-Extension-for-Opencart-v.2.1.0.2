<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <link rel="stylesheet" href="view/vendor/datatables/jquery.dataTables.css">
  <style>
  .popover.right {
    max-width: 100%;
  }
  #page_input {
    width: 80px;
  }
  #ipp_input {
    width: 50px;
  }
  </style>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <input type='number' id="page_input" placeholder="Page" value="1" data-toggle="tooltip" title="Start Page"></input>
        <input type='number' id="ipp_input" placeholder="Item per Page" value="100" data-toggle="tooltip" title="Item per Page"></input>
        <input type='checkbox' id="continuous_input" checked data-toggle="tooltip" title="Continuous"></input>
        <input type='checkbox' id="update_table_input" checked data-toggle="tooltip" title="Update Table"></input>
        <button type="button" id="button-sync" data-toggle="tooltip" title="<?php echo $button_sync; ?>" class="btn btn-primary"><i class="fa fa-arrows-h"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div id="sync-alert" class="alert alert-warning fade in" role="alert">
      <span id="sync-spinner"><i class="fa fa-refresh fa-spin"></i> <strong>Syncing!</strong> Please wait while we retrieve your products.</span>
      <p>
        <strong>Products:  </strong><span id="products-count">0</span><br>
        <strong>Retrieved: </strong><span id="retrieved-count">0</span><br>
        <strong>Duplicate: </strong><span id="duplicate-count">0</span><br>
        <ul id="sync-messages">
        </ul>
      </p>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <table id="products-tableajax" class="table table-hover"  width="100%">
          <thead>
            <tr>
              <th>OC ID</th>
              <th>TSG ID</th>
              <th>Image</th>
              <th>Title</th>
              <th>Color</th>
              <th>Style</th>
              <th>Status</th>
              <th>Date Retrieved</th>
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
    var start_page = 1;
    var item_per_page = 4;
    var continuous = true;
    var update_table = true;
    var unloadFunc = function() {
      var Ans = confirm("Are you sure you want leave this page? Your connection to the Tshirtgang server will be lost.");
      if(Ans==true)
        return true;
      else
        return false;
    };
    $(document).ready(function() {
      tableajax = $('#products-tableajax').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[ 7, "desc" ]],
        "columnDefs": [
          {
            "render": function ( data, type, row ) {
              if(data === null){
                return '<i class="fa fa-exclamation-triangle text-danger"></i>';
              } else {
                return '<a href="<?php echo $edit_ocp_link ?>'+data+'" target="_blank">'+data+'</a>';
              }
            },
            "targets": 0
          },
          {
            "render": function ( data, type, row ) {
              return '<a href="http://www.tshirtgang.com/cp/myproducts/search/'+row[1]+'" target="_blank">'+data+'</a>';
            },
            "targets":1
          },
          {
            "render": function ( data, type, row ) {
              if(data == ''){
                return '<i class="fa fa-exclamation-triangle text-danger"></i>';
              } else {
                return '<img height="60" width="55" rel="popover" data-img="' + data +'" src="' + data +'" alt="hi" class="img-thumbnail" data-original-title title>';
              }
            },
            "targets": 2
          },
          {
            "render": function ( data, type, row ) {
              if(data === null){
                return '<i class="fa fa-exclamation-triangle text-danger"></i>';
              } else {
                if(data == 1){
                  return '<a href="<?php echo $edit_ocp_status_link; ?>'+row[0]+'&status=0" class="enabledisable_status"><i class="fa fa-check-square-o text-primary"></i></a>';
                } else {
                  return '<a href="<?php echo $edit_ocp_status_link; ?>'+row[0]+'&status=1" class="enabledisable_status"><i class="fa fa-square-o text-danger"></i></a>';
                }
              }
            },
            "targets": 6
          }
        ],
        "ajax": {
          url: "<?php echo $datatableajax; ?>",
          type: "post",
          error: function(){  // error handling
            alert('datatable ajax error');
          }
        },
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
          $('img[rel=popover]').popover({
            html: true,
            trigger: 'hover',
            placement: 'right',
            container: 'body',
            content: function(data){return '<img height="300" width="300" src="'+$(this).data('img') + '" />';}
          });
          $('.enabledisable_status').click(function(event){
            event.preventDefault();
            var old_url = $(this).attr('href');
            var url_and_param_array = old_url.split('?');
            var objp = JSON.parse('{"' + decodeURI(url_and_param_array[1]).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
            $.ajax({
              url: url_and_param_array[0]+'?route='+objp.route+'&token='+objp.token,
              type: 'POST',
              data: objp,
              success: function(response){
                tableajax.ajax.reload( null, false );
              }
            });
            return false;
          });
          if(iMax == iTotal){
            return iStart +" to "+ iEnd + " of "+iMax;
          } else {
            return iStart +" to "+ iEnd + " of "+iTotal+" (filitered from "+iMax+" total entries)";
          }
        }
      });
    });
    $( document ).ready(function() {
      $('#sync-alert').hide();
    });
    $('#button-sync').on('click', function() {
      $('#button-sync').prop('disabled', true);
      $('#products-count').text(0);
      $('#retrieved-count').text(0);
      $('#duplicate-count').text(0);
      $('#sync-alert').removeClass('alert-success');
      $('#sync-alert').addClass('alert-warning');
      $('#sync-spinner').show();
      $('#sync-messages').empty();
      document.getElementById('page_input').readOnly = true;
      document.getElementById('ipp_input').readOnly = true;
      start_page    = parseInt( document.getElementById('page_input').value);
      item_per_page = parseInt( document.getElementById('ipp_input' ).value);
      continuous   =  $('#continuous_input:checked').val();
      update_table =  $('#update_table_input:checked').val();
      var item = document.createElement('li');
      var ul = document.getElementById("sync-messages");
      var now = new Date();
      item.appendChild(document.createTextNode('start: '+now.toLocaleString()));
      ul.appendChild(item);
      $('#sync-alert').show();
      //$('#empty-row').remove();
      window.onbeforeunload = unloadFunc;
      sync(start_page);
    });
    function sync(startpage){
      $.ajax({
          url: '<?php echo $sync; ?>',
          type: 'POST',
          dataType: 'json',
          tryCount: 0,
          retryLimit: 9,
          cache: false,
          data: {
            page: startpage,
            ipp: item_per_page,
          },
          success: function(json){
            //console.log(json);
            pc = parseInt( $('#products-count').html() ,10) + json.count;
            rc = parseInt( $('#retrieved-count').html(),10) + json.retrieved;
            dc = parseInt( $('#duplicate-count').html(),10) + json.duplicate;
            $('#products-count').text(pc);
            $('#retrieved-count').text(rc);
            $('#duplicate-count').text(dc);
            document.getElementById('page_input').value = startpage+1;
            continuous   =  $('#continuous_input:checked').val();
            update_table =  $('#update_table_input:checked').val();
            for(var i = 0; i < json.messages.length; i++) {
              var item = document.createElement('li');
              var ul = document.getElementById("sync-messages");
              item.appendChild(document.createTextNode(json.messages[i]));
              ul.appendChild(item);
            }
            if(update_table){
              tableajax.draw();
            }
            $('.popover').remove();
            if(json.count != item_per_page){
              json.done=true;
            }
            if(!continuous){
              json.done=true;
            }
            if(json.done){
              $('#button-sync').prop('disabled', false);
              $('#sync-spinner').hide();
              $('#sync-alert').removeClass('alert-warning');
              $('#sync-alert').addClass('alert-success');
              document.getElementById('page_input').readOnly = false;
              document.getElementById('ipp_input').readOnly = false;
              var item = document.createElement('li');
              var ul = document.getElementById("sync-messages");
              var now = new Date();
              item.appendChild(document.createTextNode('end: '+now.toLocaleString()));
              ul.appendChild(item);
              window.onbeforeunload = null;
            } else {
              sync(startpage+1);
              debugger;
            }
          },
          error: function(xhr, textStatus, thrownError){
            if (textStatus == 'timeout') {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                //try again
                $.ajax(this);
                return;
              }            
              return;
            }
            if (xhr.status == 500) {
              //handle error
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                //try again
                $.ajax(this);
                return;
              }            
              return;
            } else {
              //handle error
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            //window.onbeforeunload = null;
          },
      });
    }
    $('img[rel=popover]').popover({
      html: true,
      trigger: 'hover',
      placement: 'right',
      container: 'body',
      content: function(data){return '<img height="300" width="300" src="'+$(this).data('img') + '" />';}
    });
  </script>
</div>

<?php echo $footer; ?>