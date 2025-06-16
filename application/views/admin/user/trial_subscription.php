<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- <div class="col-md-4">
        <div class="box add_area">
          <div class="box-header">
            <h3 class="box-title"><?php echo trans('subscription') ?> </h3>
          </div>

          <div class="box-body">
            <p><?php echo trans('your-subscription') ?>: <strong><?php echo trans('free-trial-of') ?> <?php echo settings()->trial_days . ' ' . trans('days') ?></strong></p>
            <p><?php echo trans('billing-frequency') ?> : <strong><?php echo settings()->trial_days . ' ' . trans('days') ?></strong> </p>
            <p><?php echo trans('created') ?> : <strong><?php echo my_date_show(user()->created_at) ?></strong>
              <strong class="text-danger">(<?php echo date_dif(date('Y-m-d'), user()->trial_expire) ?> <?php echo trans('days-left') ?>)</strong></strong>
            </p>
          </div>

        </div>
      </div> -->
      <div class="col-12">
        <div class="row">
          <div class="col-4">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title"><?php echo trans('subscription') ?> </h3>
              </div>

              <div class="box-body p-0">
                <div style="padding: 10px;">
                  <p><?php echo trans('your-subscription') ?>: <strong><?php echo trans('free-trial-of') ?> <?php echo settings()->trial_days . ' ' . trans('days') ?></strong></p>
                  <p><?php echo trans('billing-frequency') ?> : <strong><?php echo settings()->trial_days . ' ' . trans('days') ?></strong> </p>
                  <p><?php echo trans('created') ?> : <strong><?php echo my_date_show(user()->created_at) ?></strong>
                    <strong class="text-danger">(<?php echo date_dif(date('Y-m-d'), user()->trial_expire) ?> <?php echo trans('days-left') ?>)</strong></strong>
                  </p>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <div class="pricing-switcher mb-5 mt-4 text-center">

                    </div>
                    <tbody>
                      <tr>
                        <td class="" style="width:100%">
                          <h2 class="mt-10">Trial Features
                          </h2>
                        </td>
                      </tr>
                      <?php foreach ($features as $feature): ?>
                        <tr>
                          <td>
                            <?php echo html_escape($feature->name); ?>
                            <?php if (!empty($feature->text)): ?>
                              <br><span class="text-danger">(<?php echo html_escape($feature->text); ?>)</span>
                            <?php endif ?>
                          </td>
                          <td>
                            <?php if ($feature->free == "none"): ?>
                              <i class="fa fa-times text-danger"></i>
                            <?php else: ?>
                              <strong><?php echo html_escape($feature->free); ?></strong>
                            <?php endif ?>
                          </td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          <div class="col-8">
            <div class="box add_area">
              <div class="box-header">
                <h3 class="box-title"><?php echo trans('upgrade-plan') ?> </h3>
              </div>

              <div class="box-body p-0">
                <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
                  <div class="col-md-12 col-sm-12 col-xs-12 scroll p-0">

                    <!-- Billing Switcher -->
                    <div class="pricing-switcher mb-5 text-center">
                      <p class="fieldset">
                        <input type="radio" name="billing_type" value="monthly" class="switch_price" id="monthly-1" checked>
                        <label for="monthly-1"><?php echo trans('monthly') ?></label> &emsp;&emsp;
                        <input type="radio" name="billing_type" value="yearly" class="switch_price" id="yearly-1">
                        <label for="yearly-1"><?php echo trans('yearly') ?></label>
                        <span class="switch"></span>
                      </p>
                    </div>

                    <table class="table table-hover mb-0">
                      <tbody>
                        <tr>
                          <td width="30%">
                            <h2></h2>
                          </td>
                          <?php foreach ($packages as $package): if ($package->name == "Trial") continue; ?>
                            <td class="text-center">
                              <h2 class="mt-10"><?php echo html_escape($package->name); ?></h2>

                              <?php if (settings()->enable_discount == 1): ?>
                                <h4>
                                  <?php if ($package->dis_month != 0 && $package->monthly_price != 0): ?>
                                    <span class="monthly_show soft-blue price_month" style="display:inline-block;">
                                      <?php echo html_escape($package->dis_month); ?>% <?php echo trans('off') ?>
                                    </span>
                                  <?php endif ?>
                                  <?php if ($package->dis_year != 0 && $package->yearly_price != 0): ?>
                                    <span class="yearly_show soft-blue price_year" style="display:none;">
                                      <?php echo html_escape($package->dis_year); ?>% <?php echo trans('off') ?>
                                    </span>
                                  <?php endif ?>
                                </h4>
                              <?php endif ?>

                              <h4 class="mb-15">
                                <span class="price_year <?php if (settings()->enable_discount == 1 && $package->dis_year != 0) echo "price-off"; ?>" style="display:none;">
                                  <?php echo price_formatted($package->yearly_price, 'site'); ?>
                                </span>

                                <?php if (settings()->enable_discount == 1 && $package->dis_year != 0): ?>
                                  <span class="price_year" style="display:none;">
                                    <?php echo price_formatted(get_discount($package->yearly_price, $package->dis_year), 'site'); ?>
                                  </span>
                                <?php endif ?>

                                <span class="price_month <?php if (settings()->enable_discount == 1 && $package->dis_month != 0) echo "price-off"; ?>" style="display:inline-block;">
                                  <?php echo price_formatted($package->monthly_price, 'site'); ?>
                                </span>

                                <?php if (settings()->enable_discount == 1 && $package->dis_month != 0): ?>
                                  <span class="price_month" style="display:inline-block;">
                                    <?php echo price_formatted(get_discount($package->monthly_price, $package->dis_month), 'site'); ?>
                                  </span>
                                <?php endif ?>
                              </h4>

                              <p class="mt-0 bill_type"><?php echo trans('per-month') ?></p>
                            </td>
                          <?php endforeach; ?>
                        </tr>

                        <?php
                        foreach ($features as $feature):
                          $uval = (get_user_info() == FALSE && $feature->id == 6) ? 'd-none' : '';

                        ?>
                          <tr class="<?php echo $uval; ?>">
                            <td width="20%"><?php echo html_escape($feature->name); ?><br>
                              <span class="text-danger"><?php echo !empty($feature->text) ? '(' . html_escape($feature->text) . ')' : ''; ?></span>
                            </td>
                            <?php foreach (['basic', 'standard', 'premium', 'customization'] as $level): ?>
                              <td class="text-center">
                                <?php if ($feature->$level == "none"): ?>
                                  <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                                <?php else: ?>
                                  <strong><?php echo html_escape($feature->$level); ?></strong>
                                <?php endif ?>
                              </td>
                            <?php endforeach; ?>
                          </tr>
                        <?php endforeach ?>

                        <tr>
                          <td></td>
                          <?php $b = 1;
                          foreach ($packages as $package): if ($package->name == "Trial") continue; ?>
                            <td class="text-center">
                              <a href="<?php echo base_url('admin/subscription/upgrade/' . $package->slug) ?>" class="btn btn-default package_btn">
                                <?php echo trans('upgrade'); ?>
                              </a>
                            </td>
                          <?php $b++;
                          endforeach; ?>
                          <input type="hidden" name="billing_type" class="billing_type" value="monthly">
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

</div>