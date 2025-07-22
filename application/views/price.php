<header class="page header text-contrast bg-light" style="">
  <div class="container text-center">
    <h1 class="bold fs-heading display-md-4 text-dark mb-2"><?php echo trans('price-title') ?></h1>
    <p class="lead text-dark"><?php echo trans('price-desc') ?></p>
  </div>
</header>


<section class="section">

  <div class="container-fluidh bring-to-front pt-2">
    <section class="block bg-contrast card shadow-lgh border-0">
      <div class="container py-4">
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="col-md-4 text-center m-auto mb-20 price-swicher">

              <?php if (settings()->enable_discount == 1 && !empty($max_discount)): ?>
                <figure class="discount-badge d-none">
                  <svg class="d-block mt-n2 ml-n4" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 99.3 57" width="48" transform="scale(-1,1)">
                    <path fill="none" stroke="#bdc5d1" stroke-width="4" stroke-linecap="round" stroke-miterlimit="10" d="M2,39.5l7.7,14.8c0.4,0.7,1.3,0.9,2,0.4L27.9,42"></path>
                    <path fill="none" stroke="#bdc5d1" stroke-width="4" stroke-linecap="round" stroke-miterlimit="10" d="M11,54.3c0,0,10.3-65.2,86.3-50"></path>
                  </svg>
                  <span class="badge badge-default soft-blue badge-pill py-sm-2 px-sm-3"><?php echo trans('save-upto') ?> <?php if (!empty($max_discount)) {
                                                                                                                            echo $max_discount;
                                                                                                                          } ?>%</span>
                </figure>
              <?php endif ?>

              <div class="btn-group btn-group-toggle mt-3 pricing-table-basis" data-toggle="buttons">
                <input type="radio" class="btn-check switch_price" name="payment_type" id="trail-1" value="trail" autocomplete="off">
                <label class="btn btn-outline-primary custom-btngp" for="trail-1"><?php echo "Trial" ?></label>

                <input type="radio" class="btn-check switch_price" name="payment_type" id="monthly-1" value="monthly" autocomplete="off" checked>
                <label class="btn btn-outline-primary custom-btngp" for="monthly-1"><?php echo trans('monthly') ?></label>

                <input type="radio" class="btn-check switch_price" name="payment_type" id="yearly-1" value="yearly" autocomplete="off">
                <label class="btn btn-outline-primary custom-btngp" for="yearly-1"><?php echo trans('yearly') ?></label>
              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-center">
          <?php $i = 1;
          foreach ($packages as $package): ?>

            <div class="col-md-4 mt-5 <?= ($package->name == "Trial") ? "trial-plan" : "paid-plan"; ?>" <?= ($package->name == "Trial") ? 'style="display: none;"' : ""; ?>>
              <div class=" card pricing-card text-center bg-<?php if ($i % 2 == 0) {
                                                              echo "primary";
                                                            } ?>">
                <div class="pricing card-headerh p-5  d-flex align-items-center flex-column">
                  <p class="lead <?php if ($i % 2 == 0) {
                                    echo "text-light";
                                  } ?>"><?php echo html_escape($package->name); ?></p>
                  <div class="pricing-values">
                    <span class="price text-dark">
                      <span class="price_year bold fs-40 <?php if ($i % 2 == 0) {
                                                            echo "text-light";
                                                          } ?> <?php if (settings()->enable_discount == 1 && $package->dis_year != 0 && $package->price != 0) {
                                                                  echo "price-off";
                                                                } ?>" style="display:none">
                        <?php echo price_formatted($package->price, 'site'); ?> <br>
                      </span>

                      <?php if (settings()->enable_discount == 1 && $package->dis_year != 0 && $package->price != 0): ?>
                        <span class="h2 <?php if ($i % 2 == 0) {
                                          echo "text-light";
                                        } ?> semi-bold price_year fs-40 bold" style="display:none">
                          <?php
                          $discount_price = get_discount($package->price, $package->dis_year);
                          echo price_formatted($discount_price, 'site');
                          ?>
                        </span>
                      <?php endif ?>

                      <span class="price_month fs-40 bold <?php if ($i % 2 == 0) {
                                                            echo "text-light";
                                                          } ?> <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->price != 0) {
                                                                  echo "price-off";
                                                                } ?>">
                        <?php
                        echo price_formatted($package->monthly_price, 'site');
                        ?> <br>
                      </span>

                      <?php if (settings()->enable_discount == 1 && $package->dis_month != 0 && $package->price != 0): ?>
                        <span class="h2 <?php if ($i % 2 == 0) {
                                          echo "text-light";
                                        } ?> semi-bold price_month fs-40 bold">
                          <?php
                          $discount_monthly_price = get_discount($package->monthly_price, $package->dis_month);
                          echo price_formatted($discount_monthly_price, 'site');
                          ?>
                        </span>
                      <?php endif ?>
                    </span>

                    <p class="mt-0 bill_type small <?php if ($i % 2 == 0) {
                                                      echo "text-light";
                                                    } ?>"><?php echo trans('per-month') ?></p>
                  </div>

                  <p class="m-0">
                    <?php if (settings()->enable_discount == 1): ?>

                      <?php if ($package->dis_month != 0 && $package->price != 0): ?>
                        <span class="monthly_show price-dis <?php if ($i % 2 == 0) {
                                                              echo "text-light";
                                                            } else {
                                                              echo "text-muted";
                                                            } ?> mt-2">
                          <?php echo html_escape($package->dis_month); ?>% <?php echo trans('off') ?>
                        </span>
                      <?php endif ?>

                      <?php if ($package->dis_year != 0 && $package->price != 0): ?>
                        <span class="yearly_show price-dis <?php if ($i % 2 == 0) {
                                                              echo "text-light";
                                                            } else {
                                                              echo "text-muted";
                                                            } ?> mt-2" style="display:none">
                          <?php echo html_escape($package->dis_year); ?>% <?php echo trans('off') ?>
                        </span>
                      <?php endif ?>

                    <?php endif ?>
                  </p>
                </div>

                <?php $m = 1;
                foreach ($features as $feature): ?>
                  <ul class="list-group list-group-flush monthly_row border-0 px-3">
                    <li class="list-group-item d-flex justify-content-between bg-<?php if ($i % 2 == 0) {
                                                                                    echo "primary text-light";
                                                                                  } ?> ">
                      <span><?php echo $feature->name; ?></span>
                      <span class="bold">
                        <?php if ($i == 1): ?>
                          <?php if (check_package_status('trial') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->free); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>
                        <?php if ($i == 2): ?>
                          <?php if (check_package_status('basic') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->basic); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>

                        <?php if ($i == 3): ?>
                          <?php if (check_package_status('standard') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->standard); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>

                        <?php if ($i == 4): ?>
                          <?php if (check_package_status('premium') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->premium); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>

                      </span>
                    </li>
                  </ul>
                <?php $m++;
                endforeach; ?>

                <?php $y = 1;
                foreach ($features as $feature): ?>
                  <ul class="list-group list-group-flush yearly_row border-0 px-3" style="display:none">
                    <li class="list-group-item d-flex justify-content-between bg-<?php if ($i % 2 == 0) {
                                                                                    echo "primary text-light";
                                                                                  } ?>">
                      <span><?php echo $feature->name; ?></span>
                      <span class="bold">
                        <?php if ($i == 2): ?>
                          <?php if (check_package_status('basic') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->basic); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>

                        <?php if ($i == 3): ?>
                          <?php if (check_package_status('standard') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->standard); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>

                        <?php if ($i == 4): ?>
                          <?php if (check_package_status('premium') == true): ?>
                            <td class="text-center">
                              <?php echo html_escape($feature->premium); ?>
                            </td>
                          <?php endif ?>
                        <?php endif ?>

                      </span>
                    </li>
                  </ul>
                <?php $y++;
                endforeach; ?>


                <input type="hidden" name="billing_type" value="yearly" class="billing_type">

                <div class="card-body pb-5">
                  <a href="<?php echo base_url('register?plan=' . $package->slug) ?><?php if (settings()->trial_days != 0) {
                                                                                      echo '&trial=start';
                                                                                    } ?>" class="btn btn-block  <?php if ($i % 2 == 1) {
                                                                                                                  echo "btn-outline-primary";
                                                                                                                } else {
                                                                                                                  echo "btn-outline-light";
                                                                                                                } ?>"><?php echo trans('get-started') ?> </a>
                </div>

              </div>
            </div>
          <?php $i++;
          endforeach; ?>
        </div>

      </div>
    </section>


  </div>

</section><!-- ./Plans features -->