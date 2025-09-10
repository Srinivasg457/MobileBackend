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
          <?php if (!$is_employee_admin): ?>
            <li class="<?php if (isset($page_title) && $page_title == "Employee Dashboard") {
                          echo "active";
                        } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
              <a href="<?php echo base_url('employee/dashboard') ?>">
                <i class="bi bi-house-door mr-5"></i> <span><?php echo trans('dashboard') ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Screenshots") {
                          echo "active";
                        } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
              <a href="<?php echo base_url('employee/view_screenshots') ?>">
                <i class="bi bi-camera mr-5"></i> <span><?php echo "View Screenshots" ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Employee Webcam screenshots") {
                          echo "active";
                        } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
              <a href="<?php echo base_url('employee/webcam_screenshots') ?>">
                <i class="bi bi-webcam mr-5"></i> <span><?php echo "Webcam screenshots" ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Activity Log") {
                          echo "active";
                        } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
              <a href="<?php echo base_url('employee/activity_log') ?>">
                <i class="bi bi-clock-history mr-5"></i> <span><?php echo "Activity Log" ?></span>
              </a>
            </li>
            <li class="<?php if (isset($page_title) && $page_title == "Report") {
                          echo "active";
                        } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
              <a href="<?php echo base_url('employee/report') ?>">
                <i class="bi bi-file-bar-graph mr-5"></i> <span><?php echo "Report" ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Change Password") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('change_password') ?>">
                <i class="bi bi-lock mr-5"></i> <span><?php echo trans('change-password') ?></span>
              </a>
            </li>
          <?php else :  ?>
            <!-- // rest of the employess -->
              <?php $allowed = get_allowed_feature_ids();
              $can = function ($fid) use ($allowed) {
                return in_array($fid, $allowed, true);
              };
              ?>
              <!-- Analytics tree, shown only if any child feature is allowed -->
              <?php if ($can(6) || $can(7) || $can(1) || $can(2)): ?>
                <li class="treeview <?= (isset($main_page) && $main_page === 'Analytics') ? 'active' : '' ?>">
                  <a href="#">
                    <i class="bi bi-graph-up-arrow mr-5"></i><span>Analytics</span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
                  </a>

                  <ul class="treeview-menu">
                    <?php if ($can(6)): ?>
                      <li class="<?= (isset($page_title) && $page_title === 'User Screenshots') ? 'active' : '' ?>"
                        <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                        <a href="<?= base_url('admin/view_screenshots') ?>">
                          <i class="bi bi-camera mr-5"></i> <span>View Screenshots</span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php if ($can(7)): ?>
                      <li class="<?= (isset($page_title) && $page_title === 'Webcam screenshots') ? 'active' : '' ?>"
                        <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                        <a href="<?= base_url('admin/webcam_screenshots') ?>">
                          <i class="bi bi-webcam mr-5"></i> <span>Webcam screenshots</span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <!-- Feature ID 1: Activity Log -->
                    <?php if ($can(1)): ?>
                      <li class="<?= (isset($page_title) && $page_title === 'Activity Log Admin') ? 'active' : '' ?>"
                        <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                        <a href="<?= base_url('admin/activity_logs') ?>">
                          <i class="bi bi-file-bar-graph mr-5"></i> <span>Activity Log</span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <!-- Feature ID 2: Time Cards -->
                    <?php if ($can(2)): ?>
                      <li class="<?= (isset($page_title) && $page_title === 'employee_activity') ? 'active' : '' ?>"
                        <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                        <a href="<?= base_url('admin/time_cards') ?>">
                          <i class="bi bi-clock mr-5"></i> <span>Time Cards</span>
                        </a>
                      </li>
                    <?php endif; ?>
                  </ul>
                </li>
              <?php endif; ?>
            <?php if ($can(13)): ?>
              <li class="<?php if (isset($page_title) && $page_title == "application_tracker") {
                            echo "active";
                          } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                <a href="<?php echo base_url('admin/application_tracker') ?>">
                  <i class="bi bi-window-stack mr-5"></i> <span><?php echo "Application Tracker" ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($can(8)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Live Monitoring") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/live_monitoring') ?>">
                    <i class="bi bi-eye mr-5"></i> <span><?php echo "Live Monitoring" ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(3)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Notification") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/notification/webcam') ?>">
                    <i class="bi bi-chat-left-dots mr-5"></i> <span><?php echo "Notification" ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(9)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Time_Approval") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('employee/Timecards_manual/Time_Approval') ?>">
                    <i class="bi-clipboard-check mr-5"></i> <span><?php echo "Time Approval" ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(4)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Edit") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/organization_settings') ?>">
                    <i class="bi bi-toggle-on mr-5"></i> <span><?php echo "Organization settings" ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(5)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Ex Organization settings") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/employee_settings') ?>">
                    <i class="bi bi-tools mr-5"></i> <span><?php echo "Employee settings" ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(12)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Department") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/hrm/departments') ?>">
                    <i class="bi bi-list-check mr-5"></i> <span><?php echo trans('departments') ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(10)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Employee") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/hrm/employees') ?>">
                    <i class="bi bi-people mr-5"></i> <span><?php echo trans('employees') ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($can(11)): ?>
                <li class="<?php if (isset($page_title) && $page_title == "Create Roles & Permission") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/roles_permissions') ?>">
                    <i class="bi bi-shield-lock mr-5"></i> <span><?php echo "Roles & Permissions" ?></span>
                  </a>
                </li>
            <?php endif; ?>
          <?php endif; ?>

        <?php else :  ?>
          <?php if (is_admin()): ?>
            <li class="<?php if (isset($page_title) && $page_title == "Dashboard") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/dashboard') ?>">
                <i class="bi bi-speedometer mr-5"></i> <span><?php echo trans('dashboard') ?></span>
              </a>
            </li>

            <li class="treeview <?php if (isset($main_page) && $main_page == "Settings") {
                                  echo "active";
                                } ?>">
              <a href="#"><i class="bi bi-gear-fill mr-5"></i>
                <span><?php echo trans('settings') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
              </a>

              <ul class="treeview-menu">
                <li class="<?php if (isset($page_title) && $page_title == "Settings") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/settings') ?>">
                    <i class="bi bi-layout-text-window-reverse mr-5"></i> <span><?php echo trans('website-settings') ?></span>
                  </a>
                </li>

                <li class="<?php if (isset($page_title) && $page_title == "Appearance") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/settings/appearance') ?>">
                    <i class="bi bi-brightness-high mr-5"></i> <span><?php echo trans('appearance') ?></span>
                  </a>
                </li>

                <li class="<?php if (isset($page_title) && $page_title == "Preferences") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/settings/preferences') ?>">
                    <i class="bi bi-lightbulb mr-5"></i> <span><?php echo trans('preferences') ?></span>
                  </a>
                </li>

                <li class="<?php if (isset($page_title) && $page_title == "Payment Settings") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/payment') ?>">
                    <i class="bi bi-credit-card mr-5"></i> <span><?php echo trans('payment-settings') ?></span>
                  </a>
                </li>

                <li class="<?php if (isset($page_title) && $page_title == "License") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/settings/license') ?>">
                    <i class="bi bi-key mr-5"></i> <span><?php echo trans('license') ?></span>
                  </a>
                </li>

                <li class="<?php if (isset($page_title) && $page_title == "Discounts") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/discount') ?>">
                    <i class="bi bi-percent mr-5"></i> <span><?php echo trans('discount') ?></span>
                  </a>
                </li>

                <li class="<?php if (isset($page_title) && $page_title == "Categories") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/business/categories') ?>">
                    <i class="bi bi-menu-app mr-5"></i> <span><span><?php echo trans('business') . ' ' . trans('categories') ?></span>
                  </a>
                </li>
              </ul>
            </li>


            <li class="treeview <?php if (isset($page_title) && $page_title == "Affiliate " || isset($page) && $page == "Affiliate") {
                                  echo "active";
                                } ?> <?= $uval; ?>">
              <a href="#"><i class="bi bi-link-45deg mr-5"></i>
                <span>Affiliate</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-right pull-right mr-5"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if (isset($page_title) && $page_title == "Referral_Settings") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/referral/settings') ?>">
                    <i class="bi bi-gear mr-5"></i> <?php echo trans('affiliate') ?> <?php echo trans('settings') ?>
                  </a>
                </li>
                <?php if (affiliate_settings()->is_enable == 1): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Payout Request") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/referral/payout_request') ?>">
                      <i class="bi bi-credit-card mr-5"></i> <?php echo trans('payout-request') ?>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "Completed Payout") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/referral/completed_payout') ?>">
                      <i class="bi bi-check-circle mr-5"></i> <?php echo trans('completed') ?>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </li>


            <li class="<?php if (isset($page_title) && $page_title == "Language") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/language') ?>" class="waves-effect"><i class="bi bi-globe mr-5"></i> <span><?php echo trans('testimonials') ?> <?php echo trans('language') ?> </span> </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Users") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/users') ?>">
                <i class="bi bi-people mr-5"></i> <span><span><?php echo trans('users') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Package") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/package') ?>">
                <i class="bi bi-box mr-5"></i> <span><?php echo trans('pricing-package') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Feature") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/feature') ?>">
                <i class="bi bi-card-list mr-5"></i> <span><?php echo trans('features') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Pages") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/pages') ?>">
                <i class="bi bi-file-earmark-text mr-5"></i> <span><?php echo trans('pages') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Faqs") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/faq') ?>">
                <i class="bi bi-info-circle mr-5"></i> <span><?php echo trans('faqs') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Testimonial") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/testimonial') ?>">
                <i class="bi bi-chat-left-quote mr-5"></i> <span><?php echo trans('testimonial') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Contact") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/contact') ?>">
                <i class="bi bi-person-lines-fill mr-5"></i> <span><?php echo trans('contact') ?></span>
              </a>
            </li>


            <li class="treeview <?php if (isset($page) && $page == "Blog") {
                                  echo "active";
                                } ?>">
              <a href="#"><i class="bi bi-file-image mr-5"></i>
                <span><?php echo trans('blog') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if (isset($page_title) && $page_title == "Blog Category") {
                              echo "active";
                            } ?>"><a href="<?php echo base_url('admin/blog_category') ?>"><i class="bi bi-arrow-right mr-5"></i><?php echo trans('add-category') ?> </a></li>
                <li class="<?php if (isset($page_title) && $page_title == "Blog Posts") {
                              echo "active";
                            } ?>"><a href="<?php echo base_url('admin/blog') ?>"><i class="bi bi-arrow-right mr-5"></i><?php echo trans('blog-posts') ?></a></li>
              </ul>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Workflow") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('admin/workflow') ?>">
                <i class="bi bi-1-circle mr-5"></i> <span><?php echo trans('workflow') ?></span>
              </a>
            </li>

            <li class="<?php if (isset($page_title) && $page_title == "Change Password") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('change_password') ?>">
                <i class="bi bi-lock mr-5"></i> <span><?php echo trans('change-password') ?></span>
              </a>
            </li>
          <?php else: ?>
            <li class="<?php if (isset($page_title) && $page_title == "User Dashboard") {
                          echo "active";
                        } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
              <a href="<?php echo base_url('admin/dashboard/business') ?>">
                <i class="bi bi-house-door mr-5"></i> <span><?php echo trans('dashboard') ?></span>
              </a>
            </li>

              <!-- <li class="treeview <?php if (isset($main_page) && $main_page == "Sales") {
                                          echo "active";
                                        } ?>">

              <a href="#"><i class="bi bi-credit-card mr-5"></i>
                <span><?php echo trans('sales') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
              </a>

              <ul class="treeview-menu">

                <?php if (check_permissions(auth('role'), 'customers') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Customers") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/customer') ?>">
                      <i class="bi bi-people mr-5"></i> <span><?php echo trans('customers') ?></span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (check_permissions(auth('role'), 'products') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Products") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/product/all/sell') ?>">
                      <i class="bi bi-box mr-5"></i> <span><?php echo trans('products-services') ?></span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (check_permissions(auth('role'), 'estimates') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Estimate") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/estimate') ?>">
                      <i class="bi bi-file-text mr-5"></i> <span><?php echo trans('estimates') ?></span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (check_permissions(auth('role'), 'invoices') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Invoices") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/invoice/type/1') ?>">
                      <i class="bi bi-receipt mr-5"></i> <span><?php echo trans('invoices') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Create Recurring Invoice") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/invoice/create/1') ?>">
                      <i class="flaticon-iterative mr-5"></i> <span><?php echo trans('recurring-invoice') ?> </span>
                    </a>
                  </li>
                <?php endif; ?>

              </ul>
            </li> -->

              <!-- <li class="treeview <?php if (isset($main_page) && $main_page == "Purchases") {
                                          echo "active";
                                        } ?>">

              <a href="#"><i class="bi bi-cart mr-5"></i>
                <span><?php echo trans('purchases') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
              </a>
              <ul class="treeview-menu">

                <?php if (check_permissions(auth('role'), 'bills') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Bills") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/bills') ?>">
                      <i class="bi bi-credit-card-2-back mr-5"></i> <span><?php echo trans('bills') ?></span>
                    </a>
                  </li>
                <?php endif; ?>

                <li class="<?php if (isset($page_title) && $page_title == "Vendor") {
                              echo "active";
                            } ?>">
                  <a href="<?php echo base_url('admin/vendor') ?>">
                    <i class="bi bi-people mr-5"></i> <span><?php echo trans('vendors') ?></span>
                  </a>
                </li>

                <?php if (check_permissions(auth('role'), 'expenses') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Expense") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/expense') ?>">
                      <i class="bi bi-journal-check mr-5"></i> <span><?php echo trans('expense') ?></span>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (check_permissions(auth('role'), 'products') == TRUE): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Products") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/product/all/buy') ?>">
                      <i class="bi bi-box mr-5"></i> <span><?php echo trans('products-services') ?></span>
                    </a>
                  </li>
                <?php endif; ?>

              </ul>
            </li> -->

              <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
                <!-- <li class="<?php if (isset($page_title) && $page_title == "Category") {
                                  echo "active";
                                } ?>">
                <a href="<?php echo base_url('admin/category') ?>">
                  <i class="bi bi-folder2-open mr-5"></i> <span><?php echo trans('categories') ?></span>
                </a>
              </li> -->

                <!-- <li class="<?php if (isset($page_title) && $page_title == "Tax") {
                                  echo "active";
                                } ?>">
                <a href="<?php echo base_url('admin/tax') ?>">
                  <i class="bi bi-receipt mr-5"></i> <span><?php echo trans('tax') ?></span>
                </a>
              </li> -->
              <li class="treeview <?php if (isset($main_page) && $main_page == "Analytics") {
                                      echo "active";
                                    } ?>">

                  <a href="#"><i class="bi bi-graph-up-arrow mr-5"></i>
                    <span><?php echo "Employee Metrics" ?></span>
                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
                  </a>
                  <ul class="treeview-menu">
                    <li class="<?php if (isset($page_title) && $page_title == "User Screenshots") {
                                  echo "active";
                                } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                      <a href="<?php echo base_url('admin/view_screenshots') ?>">
                        <i class="bi bi-camera mr-5"></i> <span><?php echo "View Screenshots" ?></span>
                      </a>
                    </li>
                    <li class="<?php if (isset($page_title) && $page_title == "Webcam screenshots") {
                                  echo "active";
                                } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                      <a href="<?php echo base_url('admin/webcam_screenshots') ?>">
                        <i class="bi bi-webcam mr-5"></i> <span><?php echo "Webcam screenshots" ?></span>
                      </a>
                    </li>
                    <li class="<?php if (isset($page_title) && $page_title == "Activity Log Admin") {
                                  echo "active";
                                } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                      <a href="<?php echo base_url('admin/activity_logs') ?>">
                        <i class="bi bi-file-bar-graph mr-5"></i> <span><?php echo "Activity Logs" ?></span>
                      </a>
                    </li>
                    <li class="<?php if (isset($page_title) && $page_title == "employee_activity") {
                                  echo "active";
                                } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                      <a href="<?php echo base_url('admin/time_cards') ?>">
                        <i class="bi bi-clock mr-5"></i> <span><?php echo "Time Cards" ?></span>
                      </a>
                    </li>

                  </ul>
                </li>
                <!-- Live Monitoring -->
                <li class="<?php if (isset($page_title) && $page_title == "application_tracker") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/application_tracker') ?>">
                    <i class="bi bi-window-stack mr-5"></i> <span><?php echo "Application Tracker" ?></span>
                  </a>
                </li>
                <!-- Application Tracker -->
                <li class="<?php if (isset($page_title) && $page_title == "Live Monitoring") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/live_monitoring') ?>">
                    <i class="bi bi-eye mr-5"></i> <span><?php echo "Live Monitoring" ?></span>
                  </a>
                </li>
                <!-- Time Approval -->
                <li class="<?php if (isset($page_title) && $page_title == "Time_Approval") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('employee/Timecards_manual/Time_Approval') ?>">
                    <i class="bi-clipboard-check mr-5"></i> <span><?php echo "Time Approval" ?></span>
                  </a>
                </li>
                <li class="<?php if (isset($page_title) && $page_title == "Notification") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/notification/webcam') ?>">
                    <i class="bi bi-chat-left-dots mr-5"></i> <span><?php echo "Notification" ?></span>
                  </a>
                </li>

                <!-- Employees -->
                <li class="<?php if (isset($page_title) && $page_title == "Employee") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/hrm/employees') ?>">
                    <i class="bi bi-people mr-5"></i> <span><?php echo ('Employees') ?></span>
                  </a>
                </li>
                <!-- Departments -->
                <li class="<?php if (isset($page_title) && $page_title == "Department") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/hrm/departments') ?>">
                    <i class="bi bi-list-check mr-5"></i> <span><?php echo trans('departments') ?></span>
                  </a>
                </li>
                <!-- Roles and permission -->
                <li class="<?php if (isset($page_title) && $page_title == "Create Roles & Permission") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/roles_permissions') ?>">
                    <i class="bi bi-shield-lock mr-5"></i> <span><?php echo "Roles & Permissions" ?></span>
                  </a>
                </li>
                <!-- Organization Settings -->
                <li class="<?php if (isset($page_title) && $page_title == "Edit") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/organization_settings') ?>">
                    <i class="bi bi-toggle-on mr-5"></i> <span><?php echo "Organization settings" ?></span>
                  </a>
                </li>
                <!-- Employee Settings -->
                <li class="<?php if (isset($page_title) && $page_title == "Ex Organization settings") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/employee_settings') ?>">
                    <i class="bi bi-tools mr-5"></i> <span><?php echo "Employee settings" ?></span>
                  </a>
                </li>


              <?php endif; ?>

              <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
                <li class="<?php if (isset($page) && $page == "Settings") {
                              echo "active";
                            } ?>" <?= !is_subscribed() ? 'data-toggle="tooltip" data-placement="right" title="Please subscribe to access this feature"' : '' ?>>
                  <a href="<?php echo base_url('admin/profile') ?>">
                    <i class="bi bi-gear mr-5"></i> <span><?php echo trans('settings') ?></span>
                  </a>
                </li>


                <?php if (check_package_limit('invoice-payments') == -1): ?>
                  <li class="<?php if (isset($page_title) && $page_title == "Payment Settings") {
                                echo "active";
                              } ?> <?= $uval; ?>">
                    <a href="<?php echo base_url('admin/payment/user') ?>">
                      <i class="bi bi-coin mr-5"></i> <span><?php echo trans('payment-settings') ?></span>
                    </a>
                  </li>
                <?php endif; ?>
              <?php endif; ?>

              <!-- <?php if (check_permissions(auth('role'), 'reports') == TRUE): ?>
              <li class="treeview <?php if (isset($main_page) && $main_page == "Report") {
                                    echo "active";
                                  } ?>">

                <a href="#"><i class="bi bi-pie-chart mr-5"></i>
                  <span><?php echo trans('reports') ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
                </a>
                <ul class="treeview-menu">

                  <li class="<?php if (isset($page_title) && $page_title == "Profit & Loss") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/reports/profit_loss?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right mr-5"></i>
                      <span><?php echo trans('profit-loss') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Sales Tax") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/reports/sales_tax?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right mr-5"></i>
                      <span><?php echo trans('sales-tax-report') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Customers") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/reports/customers?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right mr-5"></i>
                      <span><?php echo trans('income-by-customer'); ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Vendors") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/reports/vendors?end=' . date('Y-m-d') . '&start=' . date('Y') . '-01-01&report_type=1') ?>"><i class="bi bi-arrow-right mr-5"></i>
                      <span><?php echo trans('purchases-by-Vendor'); ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Reports") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/reports') ?>"><i class="bi bi-arrow-right mr-5"></i>
                      <span> <?php echo trans('general') . ' ' . trans('reports') ?></span>
                    </a>
                  </li>

                </ul>
              </li>
            <?php endif ?> -->


              <!-- <?php if (check_package_limit('hrm') == -1): ?>
              <li class="treeview <?php if (isset($main_page) && $main_page == "Hrm") {
                                    echo "active";
                                  } ?>">

                <a href="#"><i class="bi bi-person mr-5"></i>
                  <span><?php echo trans('hrm') ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
                </a>
                <ul class="treeview-menu">

                  <li class="<?php if (isset($page_title) && $page_title == "Hrm settings hour") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/hrm/hrm_settings') ?>">
                      <i class="bi bi-gear-fill mr-5"></i> <span><?php echo trans('settings') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Department") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/hrm/departments') ?>">
                      <i class="bi bi-list-check mr-5"></i> <span><?php echo trans('departments') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Employee") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/hrm/employees') ?>">
                      <i class="bi bi-people mr-5"></i> <span><?php echo trans('employees') ?></span>
                    </a>
                  </li>


                  <li class="<?php if (isset($page_title) && $page_title == "Attendance") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/hrm/attendance') ?>">
                      <i class="bi bi-calendar-check mr-5"></i> <span><?php echo trans('attendence') ?></span>
                    </a>
                  </li>


                  <li class="<?php if (isset($page_title) && $page_title == "Salary") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/hrm/salary') ?>">
                      <i class="bi bi-currency-exchange mr-5"></i> <span><?php echo trans('salary') ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Create Roles & Permission") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/roles_permissions') ?>">
                      <i class="bi bi-shield-lock mr-5"></i> <span><?php echo "Roles & Permissions" ?></span>
                    </a>
                  </li>

                </ul>
              </li>
            <?php endif; ?> -->


              <!-- <?php if (affiliate_settings()->is_enable == 1): ?>
              <li class="treeview <?php if (isset($page_title) && $page_title == "Affiliate " || isset($page) && $page == "Affiliate") {
                                    echo "active";
                                  } ?> <?= $uval; ?>">
                <a href="#"><i class="bi bi-link-45deg mr-5"></i>
                  <span class="pl-0"><?php echo trans('affiliate') ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
                </a>
                <ul class="treeview-menu">
                  <li class="<?php if (isset($page_title) && $page_title == "Home") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/referral/user') ?>">
                      <span><i class="bi bi-house mr-5"></i><?php echo trans('home') ?></span>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "Referral") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/referral/my_referrals') ?>">
                      <span><i class="bi bi-share mr-5"></i><?php echo trans('referrals') ?></span>
                    </a>
                  </li>
                  <li class="<?php if (isset($page_title) && $page_title == "Payouts") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/referral/payouts ') ?>">
                      <span><i class="bi bi-credit-card mr-5"></i><?php echo trans('payouts') ?></span>
                    </a>
                  </li>
                </ul>
              </li>
            <?php endif ?> -->

  <!-- code for hiding the navbar whenthe payment is pending -->
            <?php if (check_payment_status() == TRUE || settings()->enable_paypal == 0 || user()->user_type == 'trial'): ?>
              <?php endif ?>

            <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
              <li class="treeview <?php if (isset($main_page) && $main_page == "plan&pack") {
                                    echo "active";
                                  } ?>">

                <a href="#">
                  <i class="bi bi-bar-chart-fill mr-5"></i>
                  <span><?php echo "Subscription" ?></span>
                  <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
                </a>

                <ul class="treeview-menu">
                  <li class="<?php if (isset($page_title) && $page_title == "CurrentPlan") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/subscription/current_plan') ?>">
                      <i class="bi bi-clock-history mr-5"></i> <span><?php echo "Current plan" ?></span>
                    </a>
                  </li>

                  <li class="<?php if (isset($page_title) && $page_title == "Subscription") {
                                echo "active";
                              } ?>">
                    <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>">
                      <i class="bi bi-gem mr-5"></i> <span><?php echo trans('upgrade-plan') ?></span>
                    </a>
                  </li>
                </ul>

              </li>


            <?php endif; ?>

            <li class="<?php if (isset($page_title) && $page_title == "Change Password") {
                          echo "active";
                        } ?>">
              <a href="<?php echo base_url('change_password') ?>">
                <i class="bi bi-lock mr-5"></i> <span><?php echo trans('change-password') ?></span>
              </a>
            </li>
          <?php endif; ?>
        <?php endif ?>


        <!-- <li class="<?php if (isset($page_title) && $page_title == "Country") {
                          echo "active";
                        } ?>">
        <a href="<?php echo base_url('admin/country') ?>">
          <i class="bi bi-flag mr-5"></i> <span><?php echo trans('country') ?></span>
        </a>
      </li> -->



        <li class="">
          <a href="<?php echo base_url('auth/logout') ?>">
            <i class="bi bi-box-arrow-right mr-5"></i> <span><?php echo trans('logout') ?></span>
          </a>
        </li>

        <?php if (is_admin()): ?>
          <?php if (file_exists(APPPATH . 'controllers/addons/Razorpay.php')): ?>
            <li class="treeview <?php if (isset($main_page) && $main_page == "Addons") {
                                  echo "active";
                                } ?> d-none">
              <a href="#" class=""><i class="flaticon-favorites mr-5"></i>
                <span><?php echo trans('addons') ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-right pull-right mr-5"></i></span>
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
      <?php if (is_employee()): ?>
        <div class="d-flex justify-content-start mt-20">
          <?php if (check_department()): ?>
            <div>
              <?php if (!$is_employee_admin): ?>
                <a href="<?php echo base_url('admin/Navbar_Redirection') ?>" class="btn btn-secondary bg-dark upgrade_btn">
                  <i class="bi bi-arrow-right-square mr-5"></i> <span><?php echo "nav to admin" ?></span>
                </a>
              <?php else: ?>
                <a href="<?php echo base_url('admin/Navbar_Redirection/employee_nav') ?>" class="btn btn-secondary bg-dark upgrade_btn">
                  <i class="bi bi-arrow-left-square mr-5"></i> <span><?php echo "nav to your account" ?></span>
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <?php if (is_admin()): ?>
            <a href="#" class="btn btn-secondary upgrade_btn mt-20">
              <i class="bi bi-info-circle-fill mr-5"></i> <span><?php echo trans('version') ?> <?php echo html_escape(settings()->version) ?></span>
            </a>
          <?php else: ?>
            <div class="d-flex justify-content-start mt-20">
              <div>
                <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>" class="btn btn-secondary bg-dark upgrade_btn">
                  <i class="bi bi-rocket-takeoff mr-5"></i> <span><?php echo trans('upgrade') ?></span>
                </a>
              </div>

              <?php if (settings()->enable_multilingual == 1): ?>
                <div class="dropdown show">
                  <a class="btn btn-secondary upgrade_btn bg-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-translate mr-5"></i> <i class="bi bi-chevron-down mr-5"></i>
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
      <?php endif; ?>

    </section>
  </aside>

<?php endif ?>