<div class="content-wrapper">

 <section class="content">

  <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/extra_settings/update') ?>" role="form" class="form-horizontal">

    <div class="nav-tabs-custom">

      <div class="row mt-20">
        <div class="col-md-8">


          <div class="tab-content box">

            <div class="box-header">
              <h3 class="box-title">Under Hero Section <i class="bi bi-people"></i></h3>
            </div>
            <div class="active tab-pane">
              <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-12 control-label" for="example-input-normal">Title</label>
                    <div class="col-sm-12">
                        <input type="text" name="title1" class="form-control" value="<?php echo html_escape($extra_settings->title1); ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-12 control-label" for="example-input-normal">Details</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" name="details1" rows="8"><?php echo $extra_settings->details1 ?></textarea>
                    </div>
                  </div>

              </div>
            </div>
          </div>



          <div class="tab-content box">

            <div class="box-header">
              <h3 class="box-title">Image Banner & Details <i class="bi bi-people"></i></h3>
            </div>
            <div class="active tab-pane">
              <div class="box-body">
                <div class="row">


                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <img width="200px" src="<?php echo base_url($extra_settings->invoicetopia_image); ?>">
                        <div class="input-group mt-2">
                          <span class="input-group-btn">
                            <span class="btn btn-info btn-file">
                              <i class="fa fa-cloud-upload"></i>Invoicetopia Image <input type="file" id="imgInp" name="photo2">
                            </span>
                          </span>
                        </div><br>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group m-t-20">
                  <label class="col-sm-4 control-label" for="example-input-normal">Ivoicetopia Title</label>
                  <div class="col-sm-12">
                    <input type="text" name="invoicetopia_title" value="<?php echo html_escape($extra_settings->invoicetopia_title); ?>" class="form-control" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="example-input-normal">Ivoicetopia Short Details</label>
                  <div class="col-sm-12">
                    <textarea class="form-control" name="invoicetopiua_short_details" rows="4"><?php echo html_escape($extra_settings->invoicetopiua_short_details); ?></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="example-input-normal1">Ivoicetopia  Details</label>
                  <div class="col-sm-12">
                    <textarea class="form-control" name="invoicetopia_details" rows="4"><?php echo html_escape($extra_settings->invoicetopia_details); ?></textarea>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="tab-content box ">

            <div class="box-header">
              <h3 class="box-title">Featuer Section 1 <i class="bi bi-people"></i></h3>
            </div>
            <div class="active tab-pane">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <img width="100px" src="<?php echo base_url($extra_settings->invoicing_thumb); ?>">
                        <div class="input-group mt-2">
                          <span class="input-group-btn">
                            <span class="btn btn-info btn-file">
                              <i class="fa fa-cloud-upload"></i>Invoice Image <input type="file" id="imgInp" name="photo1">
                            </span>
                          </span>
                        </div><br>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group m-t-20">
                  <label class="col-sm-4 control-label" for="example-input-normal">Invoicing Header</label>
                  <div class="col-sm-12">
                    <input type="text" name="invoicing_header" value="<?php echo html_escape($extra_settings->invoicing_header); ?>" class="form-control" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="example-input-normal">Ivoicing Short Details</label>
                  <div class="col-sm-12">
                    <textarea class="form-control" name="invoicing_short_details" rows="4"><?php echo html_escape($extra_settings->invoicing_short_details); ?></textarea>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 m-b-5">
                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Invoice Efficiency</label>
                      <div class="col-sm-12">
                        <input type="text" name="invoice_efficiency" value="<?php echo html_escape($extra_settings->invoice_efficiency); ?>" class="form-control" >
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label" for="example-input-normal">Invoice Efficiency Details</label>
                      <div class="col-sm-12">
                        <textarea class="form-control" name="invoice_efficiency_details" rows="4"><?php echo html_escape($extra_settings->invoice_efficiency_details); ?></textarea>
                      </div>
                    </div>

                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Invoicing Efficiency Icon </label>
                      <div class="col-sm-12">
                        <input type="text" name="invoicing_icon1" value="<?php echo html_escape($extra_settings->invoicing_icon1); ?>" class="form-control iconpickers" >
                      </div>
                    </div><br><hr><br>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Improve Cash Flow</label>
                      <div class="col-sm-12">
                        <input type="text" name="improved_cash_flow" value="<?php echo html_escape($extra_settings->improved_cash_flow); ?>" class="form-control" >
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label" for="example-input-normal">Improve Cash Flow Details</label>
                      <div class="col-sm-12">
                        <textarea class="form-control" name="improved_cash_flow_details" rows="4"><?php echo html_escape($extra_settings->improved_cash_flow_details); ?></textarea>
                      </div>
                    </div>

                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Improve Cash Flow Icon</label>
                      <div class="col-sm-12">
                        <input type="text" name="invoicing_icon2" value="<?php echo html_escape($extra_settings->invoicing_icon2); ?>" class="form-control iconpickers" >
                      </div>
                    </div><br><hr><br>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Streamlined Accounting</label>
                      <div class="col-sm-12">
                        <input type="text" name="streamlined_accounting" value="<?php echo html_escape($extra_settings->streamlined_accounting); ?>" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label" for="example-input-normal">Streamlined Accounting Details</label>
                      <div class="col-sm-12">
                        <textarea class="form-control" name="streamlined_accounting_details" rows="4"><?php echo html_escape($extra_settings->streamlined_accounting_details); ?></textarea>
                      </div>
                    </div>

                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Streamlined Accounting Icon</label>
                      <div class="col-sm-12">
                        <input type="text" name="invoicing_icon3" value="<?php echo html_escape($extra_settings->invoicing_icon3); ?>" class="form-control iconpickers" >
                      </div>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Business Growth</label>
                      <div class="col-sm-12">
                        <input type="text" name="business_growth" value="<?php echo html_escape($extra_settings->business_growth); ?>" class="form-control" >
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-12 control-label" for="example-input-normal">Business Growth Details</label>
                      <div class="col-sm-12">
                        <textarea class="form-control" name="business_growth_details" rows="4"><?php echo html_escape($extra_settings->business_growth_details); ?></textarea>
                      </div>
                    </div>

                    <div class="form-group m-t-20">
                      <label class="col-sm-12 control-label" for="example-input-normal">Business Growth Icon</label>
                      <div class="col-sm-12">
                        <input type="text" name="invoicing_icon4" value="<?php echo html_escape($extra_settings->invoicing_icon4); ?>" class="form-control iconpickers" >
                      </div>
                    </div>
                  </div>

                </div>

              </div>

            </div>
          </div>

          <div class="tab-content box">

            <div class="box-header">
              <h3 class="box-title">Featuer Section 2 <i class="bi bi-people"></i></h3>
            </div>
            <div class="active tab-pane">
              <div class="box-body">
                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <img width="200px" src="<?php echo base_url($extra_settings->business_dashboard_image); ?>">
                        <div class="input-group mt-2">
                          <span class="input-group-btn">
                            <span class="btn btn-info btn-file">
                              <i class="fa fa-cloud-upload"></i> Business Dashboard Image <input type="file" id="imgInp" name="photo3">
                            </span>
                          </span>
                        </div><br>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="form-group m-t-20">
                  <label class="col-sm-4 control-label" for="example-input-normal">Business Dashboard</label>
                  <div class="col-sm-12">
                    <input type="text" name="business_dashboard" value="<?php echo html_escape($extra_settings->business_dashboard); ?>" class="form-control" >
                  </div>
                </div>


                <div class="form-group m-t-20">
                  <label class="col-sm-4 control-label" for="example-input-normal">Business Dashboard Title</label>
                  <div class="col-sm-12">
                    <input type="text" name="business_dashboard_title" value="<?php echo html_escape($extra_settings->business_dashboard_title); ?>" class="form-control" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label" for="example-input-normal">Business Dashboard Details</label>
                  <div class="col-sm-12">
                    <textarea class="form-control" name="business_dashboard_details" rows="4"><?php echo html_escape($extra_settings->business_dashboard_details); ?></textarea>
                  </div>
                </div>


                </div>

            </div>
          </div>



          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

          <button type="submit" class="btn btn-info btn-block btn-md waves-effect w-md waves-light m-b-5"><i class="fa fa-check"></i> <?php echo trans('save-changes') ?></button>



        </div>
      </div>
    </div>
  </form>
</section>
</div>



