
            <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
              <!-- <li class="<?php if(isset($page_title) && $page_title == "Profile"){echo "active";} ?>">
                <a href="<?php echo base_url('admin/profile') ?>">
                  <i class="flaticon-settings-1"></i> <span><?php echo trans('settings') ?></span>
                </a>
              </li> -->
            
            <?php endif; ?>



            <?php if (check_permissions(auth('role'), 'customers') == TRUE): ?>
                <li class="<?php if(isset($page_title) && $page_title == "Customers"){echo "active";} ?>">
                  <a href="<?php echo base_url('admin/customer') ?>">
                    <i class="flaticon-network"></i> <span><?php echo trans('customers') ?></span>
                  </a>
                </li>
            <?php endif; ?>


            <?php if (check_permissions(auth('role'), 'estimates') == TRUE): ?>
              <li class="<?php if(isset($page_title) && $page_title == "Estimate"){echo "active";} ?>">
                <a href="<?php echo base_url('admin/estimate') ?>">
                  <i class="flaticon-contract"></i> <span><?php echo trans('estimates') ?></span>
                </a>
              </li>
            <?php endif; ?>

            <li class="<?php if(isset($page_title) && $page_title == "Payments"){echo "active";} ?>">
              <a href="<?php echo base_url('admin/payment/payment_record') ?>">
                <i class="flaticon-time-is-money"></i> <span>Payments</span>
              </a>
            </li>

            <li class="treeview <?php if(isset($main_page) && $main_page == "Sales"){echo "active";} ?>">
              
              <a href="#"><i class="flaticon-business-cards"></i>
                <span><?php echo trans('sales') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
              </a>

              <ul class="treeview-menu">
          
                  

                  <?php if (check_permissions(auth('role'), 'products') == TRUE): ?>
                  <li class="<?php if(isset($page_title) && $page_title == "Products"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/product/all/sell') ?>">
                      <i class="flaticon-box-1"></i> <span><?php echo trans('products-services') ?></span>
                    </a>
                  </li>
                  <?php endif; ?>

                  

                  <?php if (check_permissions(auth('role'), 'invoices') == TRUE): ?>
                  <li class="<?php if(isset($page_title) && $page_title == "Invoices"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/invoice/type/3') ?>">
                      <i class="flaticon-approve-invoice"></i> <span><?php echo trans('invoices') ?></span>
                    </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Create Recurring Invoice"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/invoice/create/1') ?>">
                      <i class="flaticon-iterative"></i> <span><?php echo trans('recurring-invoice') ?> </span>
                    </a>
                  </li>
                  <?php endif; ?>

              </ul>
            </li> 

            <li class="treeview <?php if(isset($main_page) && $main_page == "Purchases"){echo "active";} ?>">
              
              <a href="#"><i class="icon-basket"></i>
                <span><?php echo trans('purchases') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                  
                  <?php if (check_permissions(auth('role'), 'bills') == TRUE): ?>
                  <li class="<?php if(isset($page_title) && $page_title == "Bills"){echo "active";} ?> d-none">
                    <a href="<?php echo base_url('admin/bills') ?>">
                      <i class="flaticon-credit-card"></i> <span><?php echo trans('bills') ?></span>
                    </a>
                  </li>
                  <?php endif; ?>

                  <li class="<?php if(isset($page_title) && $page_title == "Vendor"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/vendor') ?>">
                      <i class="flaticon-group"></i> <span><?php echo trans('vendors') ?></span>
                    </a>
                  </li>

                  <?php if (check_permissions(auth('role'), 'expenses') == TRUE): ?>
                  <li class="<?php if(isset($page_title) && $page_title == "Expense"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/expense') ?>">
                      <i class="flaticon-bill"></i> <span><?php echo trans('expenses') ?></span>
                    </a>
                  </li>
                  <?php endif; ?>

                  <?php if (check_permissions(auth('role'), 'products') == TRUE): ?>
                  <li class="<?php if(isset($page_title) && $page_title == "Products"){echo "active";} ?> d-none">
                    <a href="<?php echo base_url('admin/product/all/buy') ?>">
                      <i class="flaticon-box-1"></i> <span><?php echo trans('products-services') ?></span>
                    </a>
                  </li>
                  <?php endif; ?>

              </ul>
            </li> 

            <li class="treeview <?php if(isset($main_page) && $main_page == "Hrm"){echo "active";} ?> d-none">
              
              <a href="#"><i class="icon-user"></i>
                <span>Team Management</span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                  
                  <li class="<?php if(isset($page_title) && $page_title == "Team Management Settings"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/hrm/hrm_settings') ?>">
                      <i class="flaticon-settings-1"></i> <span><?php echo trans('settings') ?></span>
                    </a>
                  </li>
                  
                  <li class="<?php if(isset($page_title) && $page_title == "Department"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/hrm/department') ?>">
                      <i class="flaticon-menu-3"></i> <span><?php echo trans('department') ?></span>
                    </a>
                  </li>
                  
                  <li class="<?php if(isset($page_title) && $page_title == "Employee"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/hrm/employee') ?>">
                      <i class="flaticon-group"></i> <span><?php echo trans('employees') ?></span>
                    </a>
                  </li>

                  
                  <li class="<?php if(isset($page_title) && $page_title == "Attendance"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/hrm/attendance') ?>">
                      <i class="flaticon-bill"></i> <span><?php echo trans('attendence') ?></span>
                    </a>
                  </li>

                  
                  <li class="<?php if(isset($page_title) && $page_title == "Salary"){echo "active";} ?>">
                    <a href="<?php echo base_url('admin/hrm/salary') ?>">
                      <i class="flaticon-save-money"></i> <span><?php echo trans('salary') ?></span>
                    </a>
                  </li>

              </ul>
            </li>

            <li><hr style="border-top: 1px solid #ddd; margin-top: 40px; margin-bottom:40px"></li>
           
            <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
              <li class="<?php if(isset($page_title) && $page_title == "Categories"){echo "active";} ?>">
                <a href="<?php echo base_url('admin/category') ?>">
                  <i class="flaticon-folder-1"></i> <span><?php echo trans('categories') ?></span>
                </a>
              </li>

              <li class="<?php if(isset($page_title) && $page_title == "Taxes"){echo "active";} ?>">
                <a href="<?php echo base_url('admin/tax') ?>">
                  <i class="flaticon-tax"></i> <span>Taxes</span>
                </a>
              </li>
            <?php endif; ?>


            
            
            <?php if (check_permissions(auth('role'), 'reports') == TRUE): ?>
            <li class="treeview <?php if(isset($main_page) && $main_page == "Report"){echo "active";} ?>">
              
              <a href="#"><i class="icon-pie-chart"></i>
                <span><?php echo trans('reports') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                  
                  <li class="<?php if(isset($page_title) && $page_title == "Profit & Loss"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/profit_loss?end='.date('Y-m-d').'&start='.date('Y').'-01-01&report_type=1') ?>">
                        <span><?php echo trans('profit-loss') ?></span>
                      </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Sales Tax"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/sales_tax?end='.date('Y-m-d').'&start='.date('Y').'-01-01&report_type=1') ?>">
                        <span><?php echo trans('sales-tax-report') ?></span>
                      </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Customers"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/customers?end='.date('Y-m-d').'&start='.date('Y').'-01-01&report_type=1') ?>">
                        <span><?php echo trans('income-by-customer'); ?></span>
                      </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Vendors"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/vendors?end='.date('Y-m-d').'&start='.date('Y').'-01-01&report_type=1') ?>">
                        <span><?php echo trans('purchases-by-Vendor'); ?></span>
                      </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Reports"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports') ?>">
                        <span> <?php echo trans('general').' '.trans('reports') ?></span>
                      </a>
                  </li>



                  <li class="<?php if(isset($page_title) && $page_title == "Invoice Details"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/invoice_details') ?>">
                        <span>Invoice Details</span>
                      </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Account Report"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/account_report_invoice') ?>">
                        <span>Account Ageing</span>
                      </a>
                  </li>

                  <li class="<?php if(isset($page_title) && $page_title == "Expenses"){echo "active";} ?>">
                      <a href="<?php echo base_url('admin/reports/expenses') ?>">
                        <span>Expenses</span>
                      </a>
                  </li>

              </ul>
            </li> 
            <?php endif ?>



            <?php if (affiliate_settings()->is_enable == 1): ?>
            <li class="treeview <?php if(isset($page_title) && $page_title == "Affiliate " || isset($page) && $page == "Affiliate"){echo "active";} ?>">
              <a href="#"><i class="icon-link"></i>
                <span class="pl-0"> Affiliate</span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if(isset($page_title) && $page_title == "Home"){echo "active";} ?>">
                  <a href="<?php echo base_url('admin/referral/user') ?>">
                    <span><i class="icon-home"></i><?php echo trans('home') ?></span>
                  </a>
                </li>
                <li class="<?php if(isset($page_title) && $page_title == "Referral"){echo "active";} ?>">
                  <a href="<?php echo base_url('admin/referral/my_referrals') ?>">
                    <span><i class="fa fa-share-alt" aria-hidden="true"></i><?php echo trans('referrals') ?></span>
                  </a>
                </li>
                <li class="<?php if(isset($page_title) && $page_title == "Payouts"){echo "active";} ?>">
                  <a href="<?php echo base_url('admin/referral/payouts ') ?>">
                    <span><i class="icon-credit-card" aria-hidden="true"></i><?php echo trans('payouts') ?></span>
                  </a>
                </li>
              </ul>
            </li>
            <?php endif ?>
