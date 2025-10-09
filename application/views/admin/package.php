<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">

    <?php if (isset($page_title) && $page_title != "Edit"): ?>
      <div class="list_area box container" style="display: <?php if (strlen(settings()->purchase_code) != 36) {
                                                              echo "none";
                                                            } ?>">

        <div class="box-header with-border">
          <h3 class="box-title"><?php echo trans('manage-packages') ?></h3>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
          <div class="col-md-12 col-sm-12 col-xs-12 scroll p-0">
            <table class="table table-hover table-stripe">
              <tbody>
                <thead class="thead-dark">
                  <tr>
                    <td width="30%">
                      <h3 class="font-weight-normal"></h3>
                    </td>
                    <?php $i = 1;
                    foreach ($packages as $package): ?>
                      <td class="text-center">
                        <?php if ($package->is_active == 1) {
                          $status = "0";
                        } else {
                          $status = "1";
                        }; ?>
                        <a href="<?php echo base_url('admin/package/update_status/' . $package->id . '/' . $status) ?>" class="package_status_btn btn btn-sm btn-toggle <?php if ($package->is_active == 1) {
                                                                                                                                                                          echo "active";
                                                                                                                                                                        } ?>" data-toggle="button" aria-pressed="false" autocomplete="off">
                          <div class="handle"></div>
                        </a>

                        <h2 class="mt-10"><span class="label label-primary"><?php echo html_escape($package->name); ?></span> </h2>

                        <?php if ($package->slug == "trial") { ?>
                          <p class="mb-15"><?php echo "USD 0.00" ?><span class="fs-14"><?php echo " week" ?></span> <br> <span class="fs-14"></p>
                          <a style="visibility: hidden;" href="#packageModal_<?php echo html_escape($package->id); ?>" data-toggle="modal" class="btn btn-default" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> <?php echo trans('edit-package') ?></a>
                        <?php } else { ?>
                          <p class="mb-15"><?php echo price_formatted($package->yearly_price, 'site'); ?> <span class="fs-14"><?php echo trans('per-year') ?></span> <br> <?php echo price_formatted($package->monthly_price, 'site'); ?> <span class="fs-14"> <?php echo trans('per-month') ?></p>
                          <a href="#packageModal_<?php echo html_escape($package->id); ?>" data-toggle="modal" class="btn btn-default" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> <?php echo trans('edit-package') ?></a>
                        <?php   }; ?>
                      </td>
                    <?php $i++;
                    endforeach; ?>
                    <td></td>
                  </tr>
                </thead>

                <?php foreach ($features as $feature): ?>

                  <?php if (get_user_info() == FALSE) {
                    $uval = 'd-none';
                  } ?>

                  <tr class="<?php if ($feature->id == 6) {
                                echo $uval;
                              } ?>">
                    <td width="30%"><?php echo html_escape($feature->name); ?> <br>
                      <span class="text-danger"><?php if (!empty($feature->text)) {
                                                  echo html_escape('(' . $feature->text . ')');
                                                } ?></span>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->free == "none"): ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php else: ?>
                        <!-- <?php echo trans('monthly') ?> -->
                        <strong><?php echo html_escape($feature->free); ?></strong> <span class=""></span>
                        <!-- <?php echo trans('yearly') ?><strong><?php echo html_escape($feature->year_basic); ?></strong> -->
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->basic == "none"): ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php else: ?>
                        <!-- <?php echo trans('monthly') ?> -->
                        <strong><?php echo html_escape($feature->basic); ?></strong> <span class=""></span>
                        <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_basic); ?></strong> -->
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->standard == "none"): ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php else: ?>
                        <!-- <?php echo trans('monthly') ?>  -->
                        <strong><?php echo html_escape($feature->standard); ?></strong><span class=""></span>
                        <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_standared); ?></strong> -->
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->premium == "none"): ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php else: ?>
                        <!-- <?php echo trans('monthly') ?>  -->
                        <strong><?php echo html_escape($feature->premium); ?></strong><span class=""></span>
                        <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_premium); ?></strong> -->
                      <?php endif ?>
                    </td>
                    <td class="text-center">
                      <?php if ($feature->customization == "none"): ?>
                        <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                      <?php else: ?>
                        <!-- <?php echo trans('monthly') ?>  -->
                        <strong><?php echo html_escape($feature->customization); ?></strong> <span class=""></span>
                        <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_basic); ?></strong> -->
                      <?php endif ?>
                    </td>
                    <td class="hide" width="5%"><a href="#featureModal_<?php echo html_escape($feature->id); ?>" data-toggle="modal" class="btn btn-default" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> <?php echo trans('edit-features') ?></a></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    <?php endif; ?>
  </section>
</div>



<?php foreach ($features as $feature): ?>
  <div id="featureModal_<?php echo html_escape($feature->id) ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
      <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/package/update_features/' . $feature->id) ?>" role="form" novalidate>
        <div class="modal-content modal-lg">
          <div class="modal-header">
            <h4 class="modal-title" id="vcenter"><?php echo trans('update') ?> - <?php echo html_escape($feature->name) ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">

            <div class="nav-tabs-customs">

              <div class="row">

                <div class="col-md-12 mb-20">
                  <div class="form-group row">
                    <label class="col-sm-12 text-left control-label col-form-label"><?php echo trans('name') ?></label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" name="name" value="<?php echo html_escape($feature->name) ?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <!-- mohthly -->
                  <div class="monthly_area">
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo trans('monthly') ?> <?php echo trans('basic-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="basic" value="<?php echo $feature->basic; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo trans('monthly') ?> <?php echo trans('standared-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="standared" value="<?php echo $feature->standared; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo trans('monthly') ?> <?php echo trans('premium-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="premium" value="<?php echo $feature->premium; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo trans('limit-suggestions'); ?></p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <!-- yearly -->
                  <div class="yearly_area">
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo trans('yearly') ?> <?php echo trans('basic-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="year_basic" value="<?php echo $feature->year_basic; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo trans('yearly') ?> <?php echo trans('standared-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="year_standared" value="<?php echo $feature->year_standared; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo trans('limit-suggestions'); ?></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"><?php echo trans('yearly') ?> <?php echo trans('premium-limit') ?></label>
                      <div class="col-sm-12">
                        <input type="number" class="form-control" name="year_premium" value="<?php echo $feature->year_premium; ?>">
                        <p class="small text-info"><i class="fa fa-info-circle"></i> <?php echo trans('limit-suggestions'); ?></p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div>

          <div class="modal-footer">
            <!-- csrf token -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" class="btn btn-info waves-effect pull-left"><i class="fa fa-check"></i> <?php echo trans('save-changes') ?></button>
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php endforeach; ?>


<?php foreach ($packages as $package): ?>
  <div id="packageModal_<?php echo html_escape($package->id) ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom modal-md">
      <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/package/update/' . $package->id) ?>" role="form" novalidate>
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title" id="vcenter"><?php echo trans('update-package') ?> - <?php echo html_escape($package->name) ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-12 text-left control-label col-form-label"><?php echo trans('name') ?></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="name" value="<?php echo html_escape($package->name) ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-12 text-left control-label col-form-label"><?php echo trans('monthly-price') ?></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="monthly_price" value="<?php echo html_escape($package->monthly_price) ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-12 text-left control-label col-form-label"><?php echo trans('yearly-price') ?></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="yearly_price" value="<?php echo html_escape($package->yearly_price) ?>">
              </div>
            </div>

            <div class="form-group row mt-20 hide">
              <label for="inputEmail3" class="col-sm-12 text-left control-label col-form-label"></label>
              <div class="col-sm-12">
                <input type="checkbox" name="is_special" value="1" id="md_checkbox_3" class="filled-in chk-col-blue" <?php if ($package->is_special == 1) {
                                                                                                                        echo "checked";
                                                                                                                      } ?> />
                <label for="md_checkbox_3"><?php echo trans('is-popular-packages') ?>?</label>
              </div>
            </div>


          </div>

          <div class="modal-footer">
            <!-- csrf token -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" class="btn btn-info waves-effect pull-right"><?php echo trans('save-changes') ?></button>
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php endforeach; ?>