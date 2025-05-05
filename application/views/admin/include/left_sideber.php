  <?php if (isset($page_title) && $page_title != 'Online Payment'): ?>

    <aside class="main-sidebar">

      <section class="sidebar mt-10">

        <ul class="sidebar-menu" data-widget="tree">
          <?php if (get_user_info() == TRUE) {
            $uval = 'd-block';
          } else {
            $uval = 'd-none';
          } ?>

          <?php if (is_employee()): ?>
            <li class="<?php if (isset($page_title) && $page_title == "Employee Dashboard") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('employeedashboard') ?>">
                <i class="bi bi-house-door"></i> <span><?php echo trans('dashboard') ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Screenshots") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('employeedashboard/screenshot') ?>">
                <i class="bi bi-camera"></i> <span><?php echo "View Screenshots" ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Activity Log") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('employee/Timecards_manual') ?>">
                <i class="bi bi-clock-history"></i> <span><?php echo "Activity Log" ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Report") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('employee/report') ?>">
                <i class="bi bi-file-bar-graph"></i> <span><?php echo "Report" ?></span>
              </a>
            </li>

          <?php else :  ?>
            <?php if (is_admin()): ?>
              <li class="<?php if (isset($page_title) && $page_title == "Dashboard") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/dashboard') ?>">
                  <i class="bi bi-speedometer"></i> <span><?php echo trans('dashboard') ?></span>
                </a>
              </li>

              <li class="treeview <?php if (isset($main_page) && $main_page == "Settings") {
                                    echo "active";
                                  } ?>">
                <a href="#"><i class="bi bi-gear-fill"></i>
                  <span><?php echo trans('settings') ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>

                <ul class="treeview-menu">
                  <li class="<?php if (isset($page_title) && $page_title == "Settings") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/settings') ?>">
                      <i class="bi bi-layout-text-window-reverse"></i> <span><?php echo trans('website-settings') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Appearance") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/settings/appearance') ?>">
                      <i class="bi bi-brightness-high"></i> <span><?php echo trans('appearance') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Preferences") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/settings/preferences') ?>">
                      <i class="bi bi-lightbulb"></i> <span><?php echo trans('preferences') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Payment Settings") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/payment') ?>">
                      <i class="bi bi-credit-card"></i> <span><?php echo trans('payment-settings') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "License") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/settings/license') ?>">
                      <i class="bi bi-key"></i> <span><?php echo trans('license') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Discounts") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/discount') ?>">
                      <i class="bi bi-percent"></i> <span><?php echo trans('discount') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Categories") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/business/categories') ?>">
                      <i class="bi bi-menu-app"></i> <span><span><?php echo trans('business') . ' ' . trans('categories') ?></span>
                    </a>
                  </li>
                </ul>
              </li>


              <li class="treeview <?php if (isset($page_title) && $page_title == "Affiliate " || isset($page) && $page == "Affiliate") {
                                    echo "active";
                                  } ?> <?= $uval; ?>">
                <a href="#"><i class="bi bi-link-45deg"></i>
                  <span>Affiliate</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-right pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li class="<?php if (isset($page_title) && $page_title == "Referral_Settings") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/referral/settings') ?>">
                      <i class="bi bi-gear"></i> <?php echo trans('affiliate') ?> <?php echo trans('settings') ?>
                    </a>
                  </li>
                  <?php if (affiliate_settings()->is_enable == 1): ?>
                    <li class="<?php if (isset($page_title) && $page_title == "Payout Request") {
                                  echo "active";
                                } ?>">
                      <a href="<?php echo base_url('admin/referral/payout_request') ?>">
                        <i class="bi bi-credit-card"></i> <?php echo trans('payout-request') ?>
                      </a>
                    </li>
                    <li class="<?php if (isset($page_title) && $page_title == "Completed Payout") {
                                  echo "active";
                                } ?>">
                      <a href="<?php echo base_url('admin/referral/completed_payout') ?>">
                        <i class="bi bi-check-circle"></i> <?php echo trans('completed') ?>
                      </a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>


              <li class="<?php if (isset($page_title) && $page_title == "Language") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/language') ?>" class="waves-effect"><i class="bi bi-globe"></i> <span><?php echo trans('testimonials') ?> <?php echo trans('language') ?> </span> </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Users") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/users') ?>">
                  <i class="bi bi-people"></i> <span><span><?php echo trans('users') ?></span>
                </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Package") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/package') ?>">
                  <i class="bi bi-box"></i> <span><?php echo trans('pricing-package') ?></span>
                </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Feature") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/feature') ?>">
                  <i class="bi bi-card-list"></i> <span><?php echo trans('features') ?></span>
                </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Pages") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/pages') ?>">
                  <i class="bi bi-file-earmark-text"></i> <span><?php echo trans('pages') ?></span>
                </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Faqs") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/faq') ?>">
                  <i class="bi bi-info-circle"></i> <span><?php echo trans('faqs') ?></span>
                </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Testimonial") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/testimonial') ?>">
                  <i class="bi bi-chat-left-quote"></i> <span><?php echo trans('testimonial') ?></span>
                </a>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Contact") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/contact') ?>">
                  <i class="bi bi-person-lines-fill"></i> <span><?php echo trans('contact') ?></span>
                </a>
              </li>


              <li class="treeview <?php if (isset($page) && $page == "Blog") {
                                    echo "active";
                                  } ?>">
                <a href="#"><i class="bi bi-file-image"></i>
                  <span><?php echo trans('blog') ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                  <li class="<?php if (isset($page_title) && $page_title == "Blog Category") {
                                echo "active";
                              } ?>"><a href="<?php echo base_url('admin/blog_category') ?>"><i class="bi bi-arrow-right"></i><?php echo trans('add-category') ?> </a></li>
                  <li class="<?php if (isset($page_title) && $page_title == "Blog Posts") {
                                echo "active";
                              } ?>"><a href="<?php echo base_url('admin/blog') ?>"><i class="bi bi-arrow-right"></i><?php echo trans('blog-posts') ?></a></li>
                </ul>
              </li>

              <li class="<?php if (isset($page_title) && $page_title == "Workflow") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/workflow') ?>">
                  <i class="bi bi-1-circle"></i> <span><?php echo trans('workflow') ?></span>
                </a>
              </li>

            <?php else: ?>
              <li class="<?php if (isset($page_title) && $page_title == "User Dashboard") {
                            echo "active";
                          } ?>">
                <a href="<?php echo base_url('admin/dashboard/business') ?>">
                  <i class="bi bi-house-door"></i> <span><?php echo trans('dashboard') ?></span>
                </a>
              </li>

              <?php if (check_payment_status() == TRUE || settings()->enable_paypal == 0 || user()->user_type == 'trial'): ?>

                <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
                  <li class="<?php if (isset($page) && $page == "Settings") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/profile') ?>">
                      <i class="bi bi-gear"></i> <span><?php echo trans('settings') ?></span>
                    </a>
                  </li>


                  <?php if (check_package_limit('invoice-payments') == -1): ?>
                    <li class="<?php if (isset($page_title) && $page_title == "Payment Settings") {
                                  echo "active";
                                } ?> <?= $uval; ?>">
                      <a href="<?php echo base_url('admin/payment/user') ?>">
                        <i class="bi bi-coin"></i> <span><?php echo trans('payment-settings') ?></span>
                      </a>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>


                <li class="treeview <?php if (isset($main_page) && $main_page == "Sales") {
                                      echo "active";
                                    } ?>">

                  <a href="#"><i class="bi bi-credit-card"></i>
                    <span><?php echo trans('sales') ?></span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>

                  <ul class="treeview-menu">

                    <?php if (check_permissions(auth('role'), 'customers') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Customers") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/customer') ?>">
                          <i class="bi bi-people"></i> <span><?php echo trans('customers') ?></span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php if (check_permissions(auth('role'), 'products') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Products") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/product/all/sell') ?>">
                          <i class="bi bi-box"></i> <span><?php echo trans('products-services') ?></span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php if (check_permissions(auth('role'), 'estimates') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Estimate") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/estimate') ?>">
                          <i class="bi bi-file-text"></i> <span><?php echo trans('estimates') ?></span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php if (check_permissions(auth('role'), 'invoices') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Invoices") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/invoice/type/1') ?>">
                          <i class="bi bi-receipt"></i> <span><?php echo trans('invoices') ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Create Recurring Invoice") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/invoice/create/1') ?>">
                          <i class="flaticon-iterative"></i> <span><?php echo trans('recurring-invoice') ?> </span>
                        </a>
                      </li>
                    <?php endif; ?>

                  </ul>
                </li>

                <li class="treeview <?php if (isset($main_page) && $main_page == "Purchases") {
                                      echo "active";
                                    } ?>">

                  <a href="#"><i class="bi bi-cart"></i>
                    <span><?php echo trans('purchases') ?></span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                  </a>
                  <ul class="treeview-menu">

                    <?php if (check_permissions(auth('role'), 'bills') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Bills") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/bills') ?>">
                          <i class="bi bi-credit-card-2-back"></i> <span><?php echo trans('bills') ?></span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <li class="<?php if (isset($page_title) && $page_title == "Vendor") {
                                  echo "active";
                                } ?>">
                      <a href="<?php echo base_url('admin/vendor') ?>">
                        <i class="bi bi-people"></i> <span><?php echo trans('vendors') ?></span>
                      </a>
                    </li>

                    <?php if (check_permissions(auth('role'), 'expenses') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Expense") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/expense') ?>">
                          <i class="bi bi-journal-check"></i> <span><?php echo trans('expense') ?></span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php if (check_permissions(auth('role'), 'products') == TRUE): ?>
                      <li class="<?php if (isset($page_title) && $page_title == "Products") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/product/all/buy') ?>">
                          <i class="bi bi-box"></i> <span><?php echo trans('products-services') ?></span>
                        </a>
                      </li>
                    <?php endif; ?>

                  </ul>
                </li>

                <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Category") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/category') ?>">
                      <i class="bi bi-folder2-open"></i> <span><?php echo trans('categories') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Tax") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/tax') ?>">
                      <i class="bi bi-receipt"></i> <span><?php echo trans('tax') ?></span>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "User Screenshots") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/ScreenshotController') ?>">
                      <i class="bi bi-camera"></i> <span><?php echo "View Screenshots" ?></span>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "Activity Log Admin") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/Activity_logs') ?>">
                      <i class="bi bi-file-bar-graph"></i> <span><?php echo "Activity Log" ?></span>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "Time_Approval") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('employee/Timecards_manual/approve') ?>">
                      <i class="bi bi-clock"></i> <span><?php echo "Time Approval" ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Organization settings") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('organization') ?>">
                      <i class="bi bi-toggle-on"></i> <span><?php echo "Organization settings" ?></span>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "Ex Organization settings") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('organization/org_exception') ?>">
                      <i class="bi bi-tools"></i> <span><?php echo "Employee settings" ?></span>
                    </a>
                  </li>

                <?php endif; ?>

                <?php if (check_permissions(auth('role'), 'reports') == TRUE): ?>
                  <li class="treeview <?php if (isset($main_page) && $main_page == "Report") {
                                        echo "active";
                                      } ?>">

                    <a href="#"><i class="bi bi-pie-chart"></i>
                      <span><?php echo trans('reports') ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">

                      <li class="<?php if (isset($page_title) && $page_title == "Profit & Loss") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/reports/profit_loss?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right"></i>
                          <span><?php echo trans('profit-loss') ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Sales Tax") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/reports/sales_tax?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right"></i>
                          <span><?php echo trans('sales-tax-report') ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Customers") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/reports/customers?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right"></i>
                          <span><?php echo trans('income-by-customer'); ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Vendors") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/reports/vendors?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right"></i>
                          <span><?php echo trans('purchases-by-Vendor'); ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Reports") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/reports') ?>"><i class="bi bi-arrow-right"></i>
                          <span> <?php echo trans('general') . ' ' . trans('reports') ?></span>
                        </a>
                      </li>

                    </ul>
                  </li>
                <?php endif ?>


                <?php if (check_package_limit('hrm') == -1): ?>
                  <li class="treeview <?php if (isset($main_page) && $main_page == "Hrm") {
                                        echo "active";
                                      } ?>">

                    <a href="#"><i class="bi bi-person"></i>
                      <span><?php echo trans('hrm') ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">

                      <li class="<?php if (isset($page_title) && $page_title == "Hrm settings hour") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/hrm/hrm_settings') ?>">
                          <i class="bi bi-gear-fill"></i> <span><?php echo trans('settings') ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Department") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/hrm/department') ?>">
                          <i class="bi bi-list-check"></i> <span><?php echo trans('department') ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Employee") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/hrm/employee') ?>">
                          <i class="bi bi-people"></i> <span><?php echo trans('employees') ?></span>
                        </a>
                      </li>


                      <li class="<?php if (isset($page_title) && $page_title == "Attendance") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/hrm/attendance') ?>">
                          <i class="bi bi-calendar-check"></i> <span><?php echo trans('attendence') ?></span>
                        </a>
                      </li>


                      <li class="<?php if (isset($page_title) && $page_title == "Salary") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/hrm/salary') ?>">
                          <i class="bi bi-currency-exchange"></i> <span><?php echo trans('salary') ?></span>
                        </a>
                      </li>

                      <li class="<?php if (isset($page_title) && $page_title == "Create Roles & Permission") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('employee/EmployeeRoles/role_permission') ?>">
                          <i class="bi bi-person-plus"></i> <span><?php echo "Roles & Permissions" ?></span>
                        </a>
                      </li>

                    </ul>
                  </li>
                <?php endif; ?>


                <?php if (affiliate_settings()->is_enable == 1): ?>
                  <li class="treeview <?php if (isset($page_title) && $page_title == "Affiliate " || isset($page) && $page == "Affiliate") {
                                        echo "active";
                                      } ?> <?= $uval; ?>">
                    <a href="#"><i class="bi bi-link-45deg"></i>
                      <span class="pl-0"><?php echo trans('affiliate') ?></span>
                      <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                      <li class="<?php if (isset($page_title) && $page_title == "Home") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/referral/user') ?>">
                          <span><i class="bi bi-house"></i><?php echo trans('home') ?></span>
                        </a>
                      </li>
                      <li class="<?php if (isset($page_title) && $page_title == "Referral") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/referral/my_referrals') ?>">
                          <span><i class="bi bi-share"></i><?php echo trans('referrals') ?></span>
                        </a>
                      </li>
                      <li class="<?php if (isset($page_title) && $page_title == "Payouts") {
                                    echo "active";
                                  } ?>">
                        <a href="<?php echo base_url('admin/referral/payouts ') ?>">
                          <span><i class="bi bi-credit-card"></i><?php echo trans('payouts') ?></span>
                        </a>
                      </li>
                    </ul>
                  </li>
                <?php endif ?>



              <?php endif ?>

              <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Subscription") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/subscription') ?>">
                    <i class="bi bi-clock"></i> <span><?php echo trans('subscription') ?></span>
                  </a>
                </li>
              <?php endif; ?>

            <?php endif; ?>
          <?php endif ?>


          <li class="<?php if (isset($page_title) && $page_title == "Country") {
                        echo "active";
                      } ?>">
            <a href="<?php echo base_url('admin/country') ?>">
              <i class="bi bi-flag"></i> <span><?php echo trans('country') ?></span>
            </a>
          </li>


          <li class="<?php if (isset($page_title) && $page_title == "Change Password") {
                        echo "active";
                      } ?>">
            <a href="<?php echo base_url('change_password') ?>">
              <i class="bi bi-lock"></i> <span><?php echo trans('change-password') ?></span>
            </a>
          </li>

          <li class="">
            <a href="<?php echo base_url('auth/logout') ?>">
              <i class="bi bi-box-arrow-right"></i> <span><?php echo trans('logout') ?></span>
            </a>
          </li>

          <?php if (is_admin()): ?>
            <?php if (file_exists(APPPATH . 'controllers/addons/Razorpay.php')): ?>
              <li class="treeview <?php if (isset($main_page) && $main_page == "Addons") {
                                    echo "active";
                                  } ?> d-none">
                <a href="#" class=""><i class="flaticon-favorites"></i>
                  <span><?php echo trans('addons') ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                  <li class="<?php if (isset($page_title) && $page_title == "Razorpay") {
                                echo "active";
                              } ?>"><a href="<?php echo base_url('addons/razorpay') ?>">Razorpay </a></li>
                </ul>
              </li>
            <?php endif ?>
          <?php endif; ?>

        </ul>

        <?php if (is_admin()): ?>
          <a href="#" class="btn btn-secondary upgrade_btn mt-20">
            <i class="bi bi-info-circle-fill"></i> <span><?php echo trans('version') ?> <?php echo html_escape(settings()->version) ?></span>
          </a>
        <?php else: ?>
          <div class="d-flex justify-content-start mt-20">
            <div>
              <a href="<?php echo base_url('admin/subscription') ?>" class="btn btn-secondary bg-dark upgrade_btn">
                <i class="bi bi-rocket-takeoff"></i> <span><?php echo trans('upgrade') ?></span>
              </a>
            </div>

            <?php if (settings()->enable_multilingual == 1): ?>
              <div class="dropdown show">
                <a class="btn btn-secondary upgrade_btn bg-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bi bi-translate"></i> <i class="bi bi-chevron-down"></i>
                </a>

                <div class="dropdown-menu lang" aria-labelledby="dropdownMenuLink">
                  <?php foreach (get_language() as $lang): ?>
                    <a class="dropdown-item mhover" href="<?php echo base_url('home/switch_lang/' . $lang->slug) ?>"><?php echo html_escape($lang->name) ?></a>
                  <?php endforeach; ?>
                </div>
              </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      </section>
    </aside>

  <?php endif ?>