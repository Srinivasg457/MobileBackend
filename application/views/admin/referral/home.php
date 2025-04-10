<div class="content-wrapper">

  <!-- Main content -->
  <div class="content pt-4">
    <div class="container">
      <div class="row box-payout-areas">

        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border border-success">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-success"><?php echo count($referrals) ?></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize"> <?php echo trans('total-referrals') ?> </span>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border border-primary">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-primary"><?php echo settings()->currency_symbol ?>
                <?php if (empty($earns->commision_amount)) {echo price_formatted('0', 'site');}else{echo price_formatted($earns->commision_amount, 'site');}; ?>
              </span>
              <span class="info-box-texts text-dark fs-13 text-capitalize"><?php echo trans('total-earnings') ?></span>
              <span class="small mt-1"></span>
            </div>
          </div>

          <span class="small mt-1"><i class="fa fa-info-circle text-muted"></i> <?php echo trans('commision') ?> <?php echo '<b>'.$settings->commision_rate.'%</b>' ?></span>
        </div>

        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border border-danger">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-danger">
                <?php if (empty($withdraws->amount)) {echo price_formatted('0', 'site');}else{echo price_formatted($withdraws->amount, 'site');}; ?></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize"> <?php echo trans('total-withdraw') ?></span>
              <span class="small mt-1"></span>
            </div>
          </div>
          <span class="small mt-1"><i class="fa fa-info-circle text-muted"></i> <?php echo trans('minimum-payout-amounts') ?> <?php echo '<b>'.price_formatted($settings->minimum_payout,'site').'</b>' ?></span>
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-12 mb-1">
          <div class="info-box-pay border border-success">
            <div class="info-box-content-pay">
              <span class="info-box-number-pay text-success">
                <?php if (empty($earns->commision_amount)) {echo price_formatted('0', 'site');}else{echo price_formatted(user()->referral_earn, 'site');}; ?></span>
              <span class="info-box-texts text-dark fs-13 text-capitalize"> <?php echo trans('balance') ?> </span>
            </div>
            <!-- /.info-box-content-pay -->
          </div>
        </div>
      </div>
      <div class="card-body mt-5">
        <div class="">
          <h5 class="font-weight-bold"><?php echo trans('my-referral-url') ?>:</h5>
          <div class="input-group mb-3">
            <input type="text" class="form-control copy_url" placeholder="" name="url" value="<?php echo base_url() ?>?ref=<?php echo html_escape($user->referral_id) ?>" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <a href="#" class="btn btn-info copy_button" style="border-radius:0px;" type="button"><i class="fa fa-copy"></i></a>
            </div>
          </div>

          <p class="font-weight-bold text-success text-right" id="successMsg"></p>

        </div>

        <div class="mt-5 hide">
          <h5 class="font-weight-bold mb-2"><?php echo trans('referral-policy') ?></h5>
          <p class="badge badge-info">
            <?php if ($settings->referral_policy==1): ?>
              <?php echo trans('first-successful-payment-by-referred-person') ?>
            <?php else: ?>
              <?php echo trans('every-successful-payment-by-referred-person') ?>
            <?php endif ?>
          </p>
        </div>
        
        <?php if (!empty($settings->referral_guideline)): ?>
          <div class="mt-5">
            <h5 class="font-weight-bold text-dark mb-2"><?php echo trans('referral-guidelines') ?></h5>
            <p><?php echo $settings->referral_guideline ?></p> <br>
          </div>
        <?php endif ?>
      </div>

      <div class="card-body mt-5">
        <div class="mt-5">
          <h3 class="font-weight-bold mb-1 text-center"><?php echo trans('how-it-works') ?></h3>
          <div class="row mt-5">
            <div class="col-4 text-center">
              <div>
                <i class="fa fa-paper-plane ref-icon"></i>
              </div>
              <h5 class="font-weight-bold mt-4"><?php echo trans('send-invitation') ?></h5>
              <p class=" mt-1 pl-0 text-muted"><?php echo trans('send-your-referral-link-to-your-friends-and-tell-them-how-cool-is-this') ?>!</p>
            </div>
            <div class="col-4 text-center">
              <div>
                <i class="fa fa-user-plus ref-icon"></i>
              </div>
              <h5 class="font-weight-bold mt-4"><?php echo trans('registration') ?></h5>
              <p class=" mt-1 pl-0 text-muted"><?php echo trans('let-them-register-using-your-referral-link') ?>.</p>
            </div>
            <div class="col-4 text-center">
              <div>
                <i class="fa fa-percent ref-icon"></i>
              </div>
              <h5 class="font-weight-bold mt-4">
                <?php echo trans('get-commissions') ?>
              </h5>
              <p class="mt-1 pl-0 text-muted"><?php echo trans('earn-commission-for-their-first-subscription-plan-payments') ?></p>
            </div>
          </div>
        </div>


      </div>

    </div>
  </div>
</div>
