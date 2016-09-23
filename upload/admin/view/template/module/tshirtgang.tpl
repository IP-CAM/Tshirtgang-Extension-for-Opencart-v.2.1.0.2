<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <link href="view/stylesheet/jquery.steps.css" type="text/css" rel="stylesheet">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tshirtgang" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-api_key"><span data-toggle="tooltip" title="<?php echo $help_api_key; ?>"><?php echo $entry_api_key; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="tshirtgang_api_key" value="<?php echo $tshirtgang_api_key; ?>" placeholder="<?php echo $entry_api_key; ?>" id="entry-api_key" class="form-control"/>
              <?php if ($error_api_key) { ?>
              <div class="text-danger"><?php echo $error_api_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="tshirtgang_status" id="input-status" class="form-control">
                <?php if ($tshirtgang_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Version</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" value="<?php echo $entry_tsg_version; ?>" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">ImageMagick</label>
            <label class="col-sm-10 text-center"><?php if(extension_loaded('imagick')) { ?><i class="fa fa-circle text-success"></i> Installed<?php } else { ?><i class="fa fa-exclamation-circle text-danger"> Not Installed</i><?php }?></label>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-on_uninstall">On Uninstall</label>
            <div class="col-sm-10">
              <select name="tshirtgang_delete_on_uninstall" id="input-delete_on_uninstall" class="form-control">
                <?php if ($tshirtgang_delete_on_uninstall) { ?>
                <option value="1" selected="selected"><?php echo $text_delete_on_uninstall; ?></option>
                <option value="0"><?php echo $text_dont_delete_on_uninstall; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_delete_on_uninstall; ?></option>
                <option value="0" selected="selected"><?php echo $text_dont_delete_on_uninstall; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div id="tshirtgang-installdocs" >
      <h3>Register to Tshirtgang</h3>
      <section>
        <p>First thing you need to do after installing this plugin is to register an account with www.Tshirtgang.com</p>
        <p>By registering an account with Tshirtgang you will be given a webstore of your own. With this module you are also given an option to use Opencart as your store frontend.</p>
        <p>Follow the instruction on the Tshirtgang website. Make sure you have the following:</p>
        <ul>
          <li><strong>Paypal Account:</strong> so that you can start receiving payments from customer.</li>
          <li><strong>Domain #1:</strong> to have a web address for your default Tshirtgang store.</li>
          <li><strong>Domain #2:</strong> to have a web address for your opencart store.</li>
        </ul>
      </section>
      <h3>API key</h3>
      <section>
        <p>Login to your Tshirtgang account and obtain an API key These information will be used by opencart so it can communicate with the main tshirtgang server to access your products, sales and other information.</p>
      	<p>See https://www.tshirtgang.com/cp/account#authkey to obtain an API key.</p>
	  </section>
      <h3>Add Products</h3>
      <section>
        <p>Start designing tshirt and uploading your design to tshirtgang. These design will be stored in the Tshirtgang server.</p>
        <p>Make sure you price your products accordingly.</p>
      </section>
      <h3>Setup Pricing</h3>
      <section>
        <p>Copy the same pricing you have on your tshirtgang store to this opencart module .</p>
      </section>
      <h3>Retrieve your Products</h3>
      <section>
        <p>Start retrieving designs you've made in your tshirtgang store so you can also sell them here in your opencart store. Make sure you setup pricing in this module first so that the correct price will be applied to your products as they are retrieved.</p>
      </section>
    </div>
  </div>
</div>
<script src="view/javascript/jquery-steps/jquery.steps.js"></script>
<script type="text/javascript">
$("#tshirtgang-installdocs").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    autoFocus: true
});
</script>
<?php echo $footer; ?>
