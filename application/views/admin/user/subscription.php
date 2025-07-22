<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- <div class="col-6">
        <div class="box add_area">
          <div class="box-header flex-between">
            <div>
              <h3 class="box-title"><?php echo trans('subscription') ?></h3>
            </div>

            <div>
              <a target="_blank" href="<?php echo base_url('admin/payment/lists') ?>" class="pull-right btn btn-default btn-xs mt-1 brd-30"><i class="fa fa-file-text-o"></i> <?php echo trans('view-invoice') ?></a>
            </div>

          </div>



          <?php if ($user->package_name != 'Trial'): ?>
            <div class="box-body">
              <p><?php echo trans('your-subscription') ?>: <strong><?php echo html_escape($user->package_name) ?> <?php echo trans('plan') ?></strong></p>
              <p><?php echo trans('price') ?>: <strong><?php echo price_formatted($user->amount, 'site') ?> </strong></p>
              <p><?php echo trans('billing-frequency') ?> : <strong><?php echo ucfirst(html_escape($user->billing_type)) ?></strong> </p>
              <p><?php echo trans('last-billing') ?> : <strong><?php echo my_date_show($user->created_at) ?></strong> </p>
              <p><?php echo trans('next-billing') ?> : <strong><?php echo my_date_show($user->expire_on); ?></strong>
                <strong class="text-danger">(<?php echo date_dif(date('Y-m-d'), $user->expire_on) ?> <?php echo trans('days-left') ?>)</strong>
              </p>
            </div>

            <div class="box-footer text-center soft-<?php if ($user->status == 'verified') {
                                                      echo "success";
                                                    } else {
                                                      echo "danger";
                                                    } ?>">
              <?php echo trans('payment-status') ?>: &emsp; <i class="fa fa-<?php if ($user->status == 'verified') {
                                                                              echo "check";
                                                                            } else {
                                                                              echo "times";
                                                                            } ?>"></i> <?php echo ucfirst(html_escape($user->status)) ?>
            </div>
          <?php else: ?>
            <div class="box-body">
              <p><?php echo trans('your-subscription') ?>: <strong><?php echo trans('free-trial-of') ?> <?php echo settings()->trial_days . ' ' . trans('days') ?></strong></p>
              <p><?php echo trans('billing-frequency') ?> : <strong><?php echo settings()->trial_days . ' ' . trans('days') ?></strong> </p>
              <p><?php echo trans('created') ?> : <strong><?php echo my_date_show(user()->created_at) ?></strong>
                <strong class="text-danger">(<?php echo date_dif(date('Y-m-d'), user()->trial_expire) ?> <?php echo trans('days-left') ?>)</strong>
              </p>
            </div>
          <?php endif; ?>
        </div>

      </div> -->

      <div class="col-12">
        <div class="box add_area">
          <div class="box-header">
            <h3 class="box-title"><?php echo trans('upgrade-plan') ?> </h3>
          </div>

          <div class="box-body p-0 ">

            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
              <div class="col-md-12 col-sm-12 col-xs-12 scroll p-0">

                <div class="pricing-switcher mb-5 text-center">
                  <p class="fieldset">
                    <input type="radio" name="billing_type" value="monthly" class="switch_price" id="monthly-1" <?php if ($user->billing_type == 'monthly') {
                                                                                                                  echo "checked";
                                                                                                                } ?>>
                    <label for="monthly-1"><?php echo trans('monthly') ?></label> &emsp;&emsp;
                    <input type="radio" name="billing_type" value="yearly" class="switch_price" id="yearly-1" <?php if ($user->billing_type == 'yearly') {
                                                                                                                echo "checked";
                                                                                                              } ?>>
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
                      <?php $i = 1;
                      foreach ($packages as $package):
                        if ($package->name == "Trial" ) {
                          continue;
                        }  ?>

                        <td class="text-center">
                          <h2 class="mt-10"><?php if ($user->package == $package->id) {
                                              echo '<i class="fa fa-check-circle text-success"></i>';
                                            } ?> <?php echo html_escape($package->name); ?></h2>

                          <?php if (settings()->enable_discount == 1): ?>
                            <h4>
                              <?php if ($package->dis_month != 0 && $package->monthly_price != 0): ?>
                                <span class="monthly_show soft-blue price_month" style="display: <?php if ($user->billing_type == 'monthly') {
                                                                                                    echo "inline-block";
                                                                                                  } else {
                                                                                                    echo "none";
                                                                                                  } ?>;">
                                  <?php echo html_escape($package->dis_month); ?>% <?php echo trans('off') ?>
                                </span>
                              <?php endif ?>

                              <?php if ($package->dis_year != 0 && $package->yearly_price != 0): ?>
                                <span class="yearly_show soft-blue price_year" style="display: <?php if ($user->billing_type == 'yearly') {
                                                                                                  echo "inline-block";
                                                                                                } else {
                                                                                                  echo "none";
                                                                                                } ?>;">
                                  <?php echo html_escape($package->dis_year); ?>% <?php echo trans('off') ?>
                                </span>
                              <?php endif ?>
                            </h4>
                          <?php endif ?>

                          <h4 class="mb-15">
                            <span class="price_year <?php if (settings()->enable_discount == 1 && $package->dis_year != 0 && $package->yearly_price != 0) {
                                                      echo "price-off";
                                                    } ?>" style="display: <?php if ($user->billing_type == 'yearly') {
                                                                            echo "inline-block";
                                                                          } else {
                                                                            echo "none";
                                                                          } ?>">
                              <?php echo price_formatted($package->yearly_price, 'site'); ?>
                            </span>

                            <?php if (settings()->enable_discount == 1 && $package->dis_year != 0 && $package->yearly_price != 0): ?>
                              <span class="price_year" style="display: <?php if ($user->billing_type == 'yearly') {
                                                                          echo "inline-block";
                                                                        } else {
                                                                          echo "none";
                                                                        } ?>">
                                <?php $discount_price =  get_discount($package->yearly_price, $package->dis_year) ?>
                                <?php echo price_formatted($discount_price, 'site'); ?>
                              </span>
                            <?php endif ?>

                            <span class="price_month <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->yearly_price != 0) {
                                                        echo "price-off";
                                                      } ?>" style="display: <?php if ($user->billing_type == 'monthly') {
                                                                              echo "inline-block";
                                                                            } else {
                                                                              echo "none";
                                                                            } ?>;">
                              <?php echo price_formatted($package->monthly_price, 'site'); ?>
                            </span>

                            <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->yearly_price != 0): ?>
                              <span class="price_month" style="display: <?php if ($user->billing_type == 'monthly') {
                                                                          echo "inline-block";
                                                                        } else {
                                                                          echo "none";
                                                                        } ?>">

                                <?php $discount_monthly_price = get_discount($package->monthly_price, $package->dis_month) ?>
                                <?php echo price_formatted($discount_monthly_price, 'site'); ?>
                              </span>
                            <?php endif ?>
                          </h4>

                          <p class="mt-0 bill_type">
                            <?php if ($user->billing_type == 'monthly'): ?>
                              <?php echo trans('per-month') ?>
                            <?php elseif ($user->billing_type == 'yearly'): ?>
                              <?php echo trans('per-year') ?>
                            <?php else: ?>
                              <?php echo trans('per-year') ?>
                            <?php endif ?>
                          </p>

                        </td>
                      <?php $i++;
                      endforeach; ?>
                    </tr>


                    <?php if (get_user_info() == FALSE) {
                      $uval = 'd-none';
                    } ?>

                    <?php
                    foreach ($features as $feature): ?>

                      <?php if (get_user_info() == FALSE) {
                        $uval = 'd-none';
                      } ?>

                      <tr class="<?php if ($feature->id == 6) {
                                    echo $uval;
                                  } ?>">
                        <td width="20%"><?php echo html_escape($feature->name); ?> <br>
                          <span class="text-danger"><?php if (!empty($feature->text)) {
                                                      echo html_escape('(' . $feature->text . ')');
                                                    } ?></span>
                        </td>
                        <!-- <td class="text-center">
                          <?php if ($feature->free == "none"): ?>
                            <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                          <?php else: ?>
                            <?php echo trans('monthly') ?> 
                            <strong><?php echo html_escape($feature->free); ?></strong> <span class="vr"></span>
                           <?php echo trans('yearly') ?><strong><?php echo html_escape($feature->year_basic); ?></strong> 
                          <?php endif ?>
                        </td> -->
                        <?php if ($feature->basic): ?>
                        <td class="text-center">
                          <?php if ($feature->basic == "none"): ?>
                            <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                          <?php else: ?>
                            <!-- <?php echo trans('monthly') ?> -->
                            <strong><?php echo html_escape($feature->basic); ?></strong> <span class="vr"></span>
                            <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_basic); ?></strong> -->
                          <?php endif ?>
                        </td>
                      <?php endif ?>
                      <?php if ($feature->standard): ?>
                        <td class="text-center">
                          <?php if ($feature->standard == "none"): ?>
                            <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                          <?php else: ?>
                            <!-- <?php echo trans('monthly') ?>  -->
                            <strong><?php echo html_escape($feature->standard); ?></strong><span class="vr"></span>
                            <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_standared); ?></strong> -->
                          <?php endif ?>
                        </td>
                      <?php endif ?>
                      <?php if ($feature->premium): ?>
                        <td class="text-center">
                          <?php if ($feature->premium == "none"): ?>
                            <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                          <?php else: ?>
                            <!-- <?php echo trans('monthly') ?>  -->
                            <strong><?php echo html_escape($feature->premium); ?></strong><span class="vr"></span>
                            <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_premium); ?></strong> -->
                          <?php endif ?>
                        </td>
                      <?php endif ?>
                      <?php if ($feature->customization): ?>
                        <td class="text-center">
                          <?php if ($feature->customization == "none"): ?>
                            <p class="mb-0 feature-item"><i class="fa fa-times text-danger"></i></p>
                          <?php else: ?>
                            <!-- <?php echo trans('monthly') ?>  -->
                            <strong><?php echo html_escape($feature->customization); ?></strong> <span class="vr"></span>
                            <!-- <?php echo trans('yearly') ?> <strong><?php echo html_escape($feature->year_basic); ?></strong> -->
                          <?php endif ?>
                        </td>
                        <?php endif ?>
                        <!-- <td width="5%"><a href="#featureModal_<?php echo html_escape($feature->id); ?>" data-toggle="modal" class="btn btn-default" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> <?php echo trans('edit-features') ?></a></td> -->
                      </tr>
                    <?php endforeach ?>

                    <tr>
                      <td></td>
                      <?php $b = 1;
                      foreach ($packages as $package):
                        if ($package->name == "Trial") {
                          continue;
                        } ?>

                        <td class="<?php if ($b == 2) {
                                      echo "active";
                                    } ?> text-center">
                          <?php if ($package->slug != 'trial'): ?>
                            <a href="<?php echo base_url('admin/subscription/upgrade/' . $package->slug) ?>"
                              class="btn btn-<?php if ($b == 2) {
                                                echo "default";
                                              } else {
                                                echo "default";
                                              } ?> package_btn">
                              <?php if ($b == 1) {
                                echo trans('upgrade');
                              } else {
                                echo trans('upgrade');
                              } ?>
                            </a>
                          <?php else: ?>
                            <!-- Optional: You can show something else for Trial package or leave empty -->
                          <?php endif; ?>
                        </td>
                      <?php $b++;
                      endforeach; ?>
                      <input type="hidden" name="billing_type" class="billing_type" value="<?php if ($user->billing_type == 'monthly') {
                                                                                              echo "monthly";
                                                                                            } else {
                                                                                              echo "yearly";
                                                                                            } ?>">
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

</div>