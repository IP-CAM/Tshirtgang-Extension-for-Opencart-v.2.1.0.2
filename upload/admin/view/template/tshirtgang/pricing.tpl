<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <p>Below are your t-shirt and shipping price management tables. We've set default prices that work for many Tshirtgang sellers, however you can make adjustments according to your own selling style. The Base Shirt Price is the base price your customer will see for an XS Youth through XL Standard style t-shirt. The Price Adjustments table allows you to increase the price for Ladies and Men's Fitted t-shirts to compensate for your $1 additional cost on these shirts. You can also adjust the price on XL and larger shirts to compensate for their additional fulfillment/shipping costs. </p>
        <p>Note: Than you can also make adjustments to the shipping costs for XL and larger shirts. These options allow you to balance the shirt and shipping costs between your shirt prices and shipping prices in a way that you feel is best for your shoppers.</p>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-auspost" class="form-horizontal">
          <br/><br/><br/>
          <h3>Base Shirt Price</h3>
          <table class="table table-bordered table-hover">
            <tr>
              <td>White Shirt Base Price <span class="pull-right">$ <input type="number" step="0.01" min="0" name="base_white" value="<?php echo $values['WhiteShirt'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Color Shirt Base Price <span class="pull-right">$ <input type="number" step="0.01" min="0" name="base_color" value="<?php echo $values['ColorShirt'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Ringer Shirt Base Price <span class="pull-right">$ <input type="number" step="0.01" min="0" name="base_ringer" value="<?php echo $values['RingerShirt'];?>" style="text-align:center;"></span></td>
            </tr>
          </table>
          <br/><br/><br/>
          <h3>Price Adjustments</h3>
          <table class="table table-bordered table-hover">
            <tr>
              <td>Baby One-Piece Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="baby_one_piece_incr" value="<?php echo $values['BabyOnePieceIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Ladies Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="ladies_incr" value="<?php echo $values['LadiesIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Men's Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="mens_incr" value="<?php echo $values['MensFittedIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Hooded Pullover Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="hooded_pullover_incr" value="<?php echo $values['HoodieIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Apron Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="apron_incr" value="<?php echo $values['ApronIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>V-Neck Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="vneck_incr" value="<?php echo $values['VneckIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>Tanktop Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="tanktop_incr" value="<?php echo $values['TanktopIncremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>2XL Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="2xl_incr" value="<?php echo $values['Shirt_2XL_Incremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>3XL-6XL Increment Cost <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="3xl_6xl_incr" value="<?php echo $values['Shirt_3XL6XL_Incremental'];?>" style="text-align:center;"></span></td>
            </tr>
          </table>
          <br/><br/><br/>
          <h3>Shipping: United States & Canada</h3>
          <ul class="nav nav-tabs" id="classicflatratetab">
            <li><a href="#tab-classic" data-toggle="tab"><input type="checkbox" style="display: inline-block;" name="classiccheckbox" id="classiccheckbox" value="classiccheckbox" /><div style="display: inline-block;"> Classic Shipping</div></a></li>
            <li class="active"><a href="#tab-flatrate" data-toggle="tab"><input type="checkbox" style="display: inline-block;" name="flatratecheckbox" id="flatratecheckbox" value="flatratecheckbox" checked/><div style="display: inline-block;"> Flat Rate Shipping [NEW]</div></a></li>
          </ul>
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="tab-classic">
              <table class="table table-bordered table-hover">
                <tr>
                  <td>Standard Shipping <span class="pull-right">$ <input type="number" step="0.01" min="0" name="classic_standard_shipping" value="<?php echo $values['StandardShipping'];?>" style="text-align:center;"></span></td>
                </tr>
                <tr>
                  <td>Rush Domestic Shipping <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="classic_rush_domestic_shipping" value="<?php echo $values['RushDomesticShipping'];?>" style="text-align:center;"></span></td>
                </tr>
                <tr>
                  <td>XS Youth - Large Incremental <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="classic_xs_youth_large_incr_shipping" value="<?php echo $values['US_CAD_YTHLG_Incremental'];?>" style="text-align:center;"></span></td>
                </tr>
                <tr>
                  <td>3XL-6XL Incremental <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="classic_3xl_6xl_incr_shipping" value="<?php echo $values['US_CAD_3XL6XL_Incremental'];?>" style="text-align:center;"></span></td>
                </tr>
              </table>
            </div>
            <div role="tabpanel" class="tab-pane active" id="tab-flatrate">
              <p><input type="checkbox" name="exclude_hpatv" id="exclude_hpatv" <?php if($values['ExcludeStyles']=='1.00') { ?>checked<?php } ?> <strong>Exclude Hooded Pullover, Apron, Tanktop and Vnecks from Flat Rate Shipping</strong></p>
              <table class="table table-bordered table-hover">
                <tr>
                  <td>Flat Rate Tshirt Shipping <span class="pull-right">$ <input type="number" step="0.01" min="0" name="flatrate_tshirt_shipping" value="<?php echo $values['FlatRateDomestic'];?>" style="text-align:center;"></span></td>
                </tr>
                <tr>
                  <td>Hoodie Flat Rate Incremental <span class="pull-right">$ <input type="number" step="0.01" min="0" name="flatrate_hoodie_shipping" value="<?php echo $values['HoodieFlatRateIncremental'];?>" style="text-align:center;"></span></td>
                </tr>
                <tr>
                  <td>Rush Domestic Shipping <span class="pull-right">$ <input type="number" step="0.01" min="0" name="flatrate_rush_domestic_shipping" value="<?php echo $values['RushDomesticShipping'];?>" style="text-align:center;"></span></td>
                </tr>
              </table>
            </div>
          </div>
          <table class="table table-bordered table-hover">
          </table>
          <br/><br/><br/>
          <h3>Shipping: International</h3>
          <table class="table table-bordered table-hover">
            <tr>
              <td>International Shipping <span class="pull-right">$ <input type="number" step="0.01" min="0" name="intl_shipping" value="<?php echo $values['InternationalShipping'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>XS Youth - Large Incremental <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="intl_xs_youth_large_shipping" value="<?php echo $values['International_YTHLG_Incremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>XL-2XL Incremental <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="intl_xl_2xl_incr_shipping" value="<?php echo $values['International_XL2XL_Incremental'];?>" style="text-align:center;"></span></td>
            </tr>
            <tr>
              <td>3XL-6XL Incremental <span class="pull-right">+$ <input type="number" step="0.01" min="0" name="intl_3xl_6xl_incr_shipping" value="<?php echo $values['International_3XL6XL_Incremental'];?>" style="text-align:center;"></span></td>
            </tr>
          </table>
          <br/><br/><br/>
          <h3>Shipping: Hoodies</h3>
          <table class="table table-bordered table-hover">
            <tr>
              <td><div role="tabpanel" class="tab-pane" id="tab-classic">Domestic <span class="pull-right">$ <input type="number" step="0.01" min="0" name="hoodie_domestic_shipping" value="<?php echo $values['US_Hoodie_Price'];?>" style="text-align:center;"></span></div></td>
            </tr>
            <tr>
              <td>International <span class="pull-right">$ <input type="number" step="0.01" min="0" name="hoodie_intl_shipping" value="<?php echo $values['International_Hoodie_Price'];?>" style="text-align:center;"></span></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
  <?php if(isset($debug_me)) { ?>
  <pre>
  <?php if(isset($debug_me)) {var_dump($debug_me);} ?>
  </pre>
  <?php } ?>
  <script type="text/javascript">
  $('#classicflatratetab li').click(function (e) {
    $(this).find('a').tab('show');
    $(this).closest('ul').find('input[type="checkbox"]').prop('checked','');
    $(this).closest('li').find('input[type="checkbox"]').prop('checked','checked');
  });
  </script>
</div>
<?php echo $footer; ?>