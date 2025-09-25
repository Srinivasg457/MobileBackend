<footer class="main-footer">
  <div class="pull-right d-none d-sm-inline-block">

    <!-- <?php if ((!is_admin() && !is_employee())): ?>
      <?php if (check_payment_status() == TRUE || settings()->enable_paypal == 0 || user()->user_type == 'trial'): ?>
        <div id="floating-container">
          <div class="circle1 circle-blue1"></div>
          <div class="floating-menus" style="display:none;">
            <?php if (check_permissions(auth('role'), 'invoices') == TRUE): ?>
              <div>
                <a href="<?php echo base_url('admin/invoice/create') ?>"> <?php echo trans('create-new-invoice') ?>
                  <i class="fa fa-file-text-o"></i></a>
              </div>
            <?php endif ?>

            <?php if (check_permissions(auth('role'), 'estimates') == TRUE): ?>
              <div>
                <a href="<?php echo base_url('admin/estimate/create') ?>"> <?php echo trans('create-new-estimate') ?>
                  <i class="fa fa-file-text"></i></a>
              </div>
            <?php endif ?>

            <?php if (check_permissions(auth('role'), 'bills') == TRUE): ?>
              <div>
                <a href="<?php echo base_url('admin/bills/create') ?>"><?php echo trans('create-new-bill') ?>
                  <i class="fa fa-file-text-o"></i></a>
              </div>
            <?php endif ?>

            <?php if (check_permissions(auth('role'), 'customers') == TRUE): ?>
              <div>
                <a href="<?php echo base_url('admin/customer') ?>"><?php echo trans('add-customer') ?>
                  <i class="fa fa-user-o"></i></a>
              </div>
            <?php endif ?>

            <div>
              <a href="<?php echo base_url('admin/vendor') ?>"><?php echo trans('add-vendor') ?>
                <i class="fa ti-user"></i></a>
            </div>
          </div>
          <div class="fab-button">
            <i class="ti-plus" aria-hidden="true"></i>
          </div>
        </div>
      <?php endif ?>
    <?php endif ?> -->

    <!-- Chatbot -->
    <?php if ((!is_admin() && !is_employee())): ?>
      <div class="chat-bot">
        <div id="chat-icon">💬</div>

        <div id="chat-window">
          <div class="header">
            <button id="new-chat">
              <img width="30px" src="	http://work-room.local/uploads/thumbnail/3_medium-400x400_thumb-100x100.png" alt="">
            </button>
            Chatbot
            <span id="close-chat" style="position:absolute; right:10px; top:10px; cursor:pointer;">✖</span>
          </div>

          <div id="chat-content"></div>

          <!-- Quick Reply Buttons -->
          <div id="quick-replies">
            <button class="quick-btn">Hello</button>
            <button class="quick-btn">Can you help me?</button>
            <button class="quick-btn">Main Menu</button>
          </div>

          <div id="chat-input">
            <input type="text" id="chat-message" placeholder="Type a message...">
            <!-- Emoji Icon -->
            <!-- <div id="emoji-container">
            <span id="emoji-btn">😊</span>
            <div id="emoji-picker">
              😀 😁 😂 🤔 😎 😍 😡 🙏 👍 👎 🎉 🚀
            </div>
          </div> -->
            <button id="send-message">
              <i class="fa fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
    <?php endif ?>

    <!-- chatbot script -->
    <script type="text/javascript">
      $(document).ready(function() {

        // Helper: Start New Chat
        function startNewChat() {
          $('#chat-content').html('');
          $('#chat-content').append('<div class="message bot-message">Hello! How can I help you today?</div>');
          $('#chat-message').val('');
          $('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
        }


        // Show/Hide Chat Window

        $('#chat-icon').click(function(e) {
          e.stopPropagation();
          $('#chat-window').fadeToggle(200);
          startNewChat();
        });

        $('#close-chat').click(function() {
          $('#chat-window').fadeOut(200);
        });

        $('#new-chat').click(function() {
          startNewChat();
        });

        // Send Message Function
        function sendMessage(message = null) {
          let userMessage = message || $('#chat-message').val();
          if (userMessage.trim() == '') return;

          // Show user message
          $('#chat-content').append('<div class="message user-message">' + userMessage + '</div>');

          // AJAX request to chatbot
          $.ajax({
            url: "<?= base_url('chatbot/get_response') ?>",
            method: "POST",
            data: {
              message: userMessage
            },
            dataType: "json",
            success: function(res) {
              // Show bot reply
              res.reply == null ?
                "" : $('#chat-content').append('<div class="message bot-message">' + res.reply + '</div>');

              // Remove old menu and append new menu
              $('#chat-content .bot-menu').remove();
              $('#chat-content').append('<div class="message bot-menu">' + res.menu + '</div>');

              // Scroll to bottom
              $('#chat-content').scrollTop($('#chat-content')[0].scrollHeight);
            }
          });

          $('#chat-message').val('');
        }

        // Menu Click (using data-key)
        $(document).on('click', '#chat-menu td', function() {
          let selectedOption = $(this).data('key');
          sendMessage(selectedOption);
        });

        // Generic Table Cell Click
        // $(document).on('click', 'table tr td', function() {
        //     let selectedOption = $(this).data('key') || $(this).text().trim();
        //     sendMessage(selectedOption);
        // });

        // Send on Button Click
        $('#send-message').click(function() {
          sendMessage();
        });

        // Send on Enter Key

        $('#chat-message').keypress(function(e) {
          if (e.which == 13) sendMessage();
        });

        // Quick Reply Buttons

        $(document).on('click', '.quick-btn', function() {
          let message = $(this).text();
          // if (message.toLowerCase() === 'main menu') {
          //     message = 'main menu';
          // }
          sendMessage(message);
        });

        // Close Chat Clicking Outside
        $(document).click(function(e) {
          if (!$(e.target).closest('#chat-window, #chat-icon').length) {
            $('#chat-window').fadeOut(200);
          }
        });


        // Optional: Emoji Picker
        /*
        $('#emoji-btn').click(function(e) {
            e.stopPropagation();
            $('#emoji-picker').fadeToggle(150);
        });

        $('#emoji-picker span').click(function() {
            let emoji = $(this).text();
            let input = $('#chat-message');
            input.val(input.val() + emoji);
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('#emoji-container').length) {
                $('#emoji-picker').hide();
            }
        });
        */

      });
    </script>


  </div>

</footer>

<?php include 'js_msg_list.php'; ?>

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<?php $success = $this->session->flashdata('msg'); ?>
<?php $error = $this->session->flashdata('error'); ?>
<input type="hidden" id="success" value="<?php if (!empty($success)) {
                                            echo html_escape($success);
                                          } ?>">
<input type="hidden" id="error" value="<?php if (!empty($error)) {
                                          echo html_escape($error);
                                        } ?>">
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<input type="hidden" id="browser" value="<?php echo $this->agent->browser(); ?>">

<?php echo $this->session->unset_userdata('msg');
$this->session->unset_userdata('error'); ?>


<input type="hidden" id="unit_status" value="<?php if (!empty($this->business)) {
                                                echo $this->business->enable_unit;
                                              } else {
                                                echo "0";
                                              }; ?>">
<?php $u = 1;
foreach (get_units() as $unit): ?>
  <input type="hidden" id="unit_<?= $u ?>" name="unit_<?= $u ?>" value='<?php echo $unit ?>'>
<?php $u++;
endforeach ?>

<input type="hidden" id="units_count" name="unit_count" value='<?php echo count(get_units()) ?>'>

<!-- jQuery 3 -->
<?php if (strlen(settings()->ind_code) == 40): ?>
  <script src="<?php echo base_url() ?>assets/admin/js/jquery3.min.js"></script>
<?php endif ?>
<!-- popper -->
<script src="<?php echo base_url() ?>assets/admin/js/popper.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap.min.js"></script>
<!-- Custom js -->
<script src="<?php echo base_url() ?>assets/admin/js/admin.js?var=<?php echo settings()->version ?>&time=<?= time(); ?>"></script>

<script src="<?php echo base_url() ?>assets/admin/js/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/html2canvas.min.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/toast.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/sweet-alert.min.js"></script>
<!-- Datatables-->
<script src="<?php echo base_url() ?>assets/admin/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/validation.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/ckeditor/ckeditor.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/fastclick.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/template.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/demo.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/select2.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/jquery.invoice.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/wow.min.js"></script>

<?php if (isset($main_page) && $main_page == 'Report'): ?>
  <!-- datatable export buttons -->
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/buttons.min.js"> </script>
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/buttons.flash.min.js"> </script>
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/jszip.min.js"> </script>
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/pdfmake.min.js"> </script>
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/vfs_fonts.js"> </script>
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/buttons.html5.min.js"> </script>
  <script src="<?php echo base_url() ?>assets/admin/js/export_buttons/buttons.print.min.js"> </script>
<?php endif ?>

<script src="<?php echo base_url() ?>assets/admin/js/bootstrap4-toggle.min.js"> </script>
<script src="<?php echo base_url() ?>assets/admin/js/summernote.js"> </script>

<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-colorpicker.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
  $('.timepicker').timepicker({
    timeFormat: 'h:mm p',
    interval: 60,
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });

  $('.timepicker2').timepicker({
    timeFormat: 'h:mm p',
    interval: 10,
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });

  //Colorpicker
  $('.colorpicker').colorpicker();
</script>


<?php $this->load->view('include/stripe-js'); ?>


<!-- datatable export buttons -->
<script type="text/javascript">
  $(document).ready(function() {

    $(function() {
      $(".ac-textarea").on("keyup input", function() {
        $(this).css('height', 'auto').css('height', this.scrollHeight +
          (this.offsetHeight - this.clientHeight));
      });
    });

    $("#summernote").summernote({
      height: 100,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['insert', ['link']]
      ]
    });

    $(".summernote").summernote({
      height: 100,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['insert', ['link']]
      ]
    });

    $('.dt_btn').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });
</script>
<script type="text/javascript">
  $(".enable_input").on('change', function() {
    if ($(this).is(":checked")) {
      $('.unit_area').slideDown();
    } else {
      $('.unit_area').slideUp();
    }
    return false;
  });
</script>


<!-- high charts js-->
<?php if (isset($page_title) && $page_title == 'User Dashboard' || $page_title == 'Dashboard'): ?>
  <script src="https://code.highcharts.com/highcharts.js"></script>
<?php endif ?>


<script>
  <?php if (isset($page_title) && $page_title == 'User Dashboard'): ?>


    var incomeData = <?php echo $income_data; ?>;
    var expenseData = <?php echo $expense_data; ?>;
    var incomeAxis = <?php echo $income_axis; ?>;

    Highcharts.chart('incomeChart', {
      chart: {
        type: 'column'
      },
      credits: {
        enabled: false
      },
      title: {
        text: ''
      },
      xAxis: {
        reversed: true,
        categories: incomeAxis
      },
      yAxis: {
        title: {
          text: ''
        }

      },
      legend: {
        enabled: true
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: '<?php if ($this->business->symbol_direction == 'left') {
                        echo html_escape($currency);
                      } ?> {point.y:.2f} <?php if ($this->business->symbol_direction == 'right') {
                                            echo html_escape($currency);
                                          } ?>'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span> <b><?php if ($this->business->symbol_direction == 'left') {
                                                                                  echo html_escape($currency);
                                                                                } ?> {point.y:.2f} <?php if ($this->business->symbol_direction == 'right') {
                                                                                                      echo html_escape($currency);
                                                                                                    } ?></b><br/>'
      },

      series: [{
          name: "<?php echo trans('income') ?>",
          data: incomeData,
          color: '#2568ef'
        },
        {
          name: "<?php echo trans('expense') ?>",
          data: expenseData,
          color: '#67757c'
        }
      ]
    });

  <?php endif ?>

  <?php if (isset($page_title) && $page_title == 'Dashboard'): ?>

    var incomeData = <?php echo $income_data; ?>;
    var incomeAxis = <?php echo $income_axis; ?>;

    Highcharts.chart('adminIncomeChart', {
      chart: {
        type: 'column'
      },
      credits: {
        enabled: false
      },
      title: {
        text: ''
      },
      xAxis: {
        reversed: true,
        categories: incomeAxis
      },
      yAxis: {
        title: {
          text: ''
        }

      },
      legend: {
        enabled: true
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: '<?php if (settings()->symbol_direction == 'left') {
                        echo html_escape($currency);
                      } ?>{point.y:.2f}<?php if (settings()->symbol_direction == 'right') {
                                          echo html_escape($currency);
                                        } ?>'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span> <b><?php if (settings()->symbol_direction == 'left') {
                                                                                  echo html_escape($currency);
                                                                                } ?> {point.y:.2f} <?php if (settings()->symbol_direction == 'right') {
                                                                                                      echo html_escape($currency);
                                                                                                    } ?></b><br/>'
      },

      series: [{
        name: "<?php echo trans('income') ?>",
        data: incomeData,
        color: '#2568ef'
      }]
    });


    //users packages share pie chart

    Highcharts.chart('packagePie', {
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: {
        text: ''
      },
      credits: {
        enabled: false
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
          }
        }
      },
      series: [{
        name: 'Users',
        colorByPoint: true,

        data: [
          <?php
          foreach ($upackages as $upackage) {
            echo '{
                  name: "' . $upackage->name . '",
                  y: ' . $upackage->total . '
                },';
          }
          ?>
        ]
      }]
    });

  <?php endif ?>
</script>
<!-- high charts js end-->

<script src="<?php echo base_url() ?>assets/admin/js/printThis.js"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="<?php echo base_url() ?>assets/admin/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>


<!-- bt-switch -->
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-switch.min.js"></script>
<script type="text/javascript">
  $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
  var radioswitch = function() {
    var bt = function() {
      $(".radio-switch").on("switch-change", function() {
        $(".radio-switch").bootstrapSwitch("toggleRadioState")
      }), $(".radio-switch").on("switch-change", function() {
        $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
      }), $(".radio-switch").on("switch-change", function() {
        $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
      })
    };
    return {
      init: function() {
        bt()
      }
    }
  }();
  $(document).ready(function() {
    radioswitch.init()
  });
</script>


<!-- Style switcher -->
<!-- <script src="<?php echo base_url() ?>assets/admin/js/jQuery.style.switcher.js"></script> -->

<script type="text/javascript">
  <?php if (isset($success)): ?>
    $(document).ready(function() {
      var msg = $('#success').val();
      var msg_success = $('.msg_success').val();

      $.toast({
        heading: msg_success,
        text: msg,
        position: 'top-right',
        loaderBg: '#fff',
        icon: 'success',
        hideAfter: 8000
      });

    });
  <?php endif; ?>


  <?php if (isset($error)): ?>
    $(document).ready(function() {
      var msg = $('#error').val();
      var msg_error = $('.msg_error').val();

      $.toast({
        heading: msg_error,
        text: msg,
        position: 'top-right',
        loaderBg: '#fff',
        icon: 'error',
        hideAfter: 8000
      });

    });
  <?php endif; ?>
</script>

<script>
  ! function(window, document, $) {
    "use strict";
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
  }(window, document, jQuery);

  $(document).ready(function() {
    $('.datatable').dataTable();
    $('.multiple_select').select2();
    $('.single_select').select2();
  });
</script>

<script type="text/javascript">
  jQuery('.datepicker').datepicker({
    format: 'yyyy-mm-dd'
  });

  //colorpicker start
  $('.colorpicker-default').colorpicker({
    format: 'hex'
  });
  $('.colorpicker-rgba').colorpicker();
</script>

<!-- <script>
        CKEDITOR.replace('ckEditor', {
            language: 'en',
            filebrowserImageUploadUrl: "<?php //echo base_url(); 
                                        ?>admin/post/upload_ckimage_post?key=kgh764hdj990sghsg46r"
        });
    </script> -->

<?php if (isset($page_sub) && $page_sub == 'Edit'): ?>
  <script type="text/javascript">
    $(document).ready(function() {
      var Id = $('#customer').val();
      var base_url = $('#base_url').val();
      if (Id != '') {
        var url = base_url + 'admin/customer/load_customer_info/' + Id;
        $.post(url, {
          data: 'value',
          'csrf_test_name': csrf_token
        }, function(json) {
          if (json.st == 1) {
            $('#load_info').html(json.value);
            $('.currency_wrapper').html(json.currency);
            $('.currency_name').html(json.currency_name);
            $('.currency_code').val(json.code);
          }
        }, 'json');
      } else {
        $('.currency_wrapper').html('');
        $('#load_info').html('Select a customer');
      }
    });
  </script>
<?php endif ?>


<?php if (isset($page_sub) && $page_sub == 'Edit Bill'): ?>
  <script type="text/javascript">
    $(document).ready(function() {
      var Id = $('#vendors').val();
      var base_url = $('#base_url').val();
      if (Id != '') {
        var url = base_url + 'admin/vendor/load_customer_info/' + Id;
        $.post(url, {
          data: 'value',
          'csrf_test_name': csrf_token
        }, function(json) {
          if (json.st == 1) {
            $('#load_info').html(json.value);
            $('.currency_wrapper').html(json.currency);
            $('.currency_name').html(json.currency_name);
            $('.currency_code').val(json.code);
          }
        }, 'json');
      } else {
        $('.currency_wrapper').html('');
        $('#load_info').html('Select a vendor');
      }
    });
  </script>
<?php endif ?>


<?php if (isset($page) && $page == 'Invoice' || isset($page) && $page == 'Create' || isset($page) && $page == 'Bill'): ?>
  <script type="text/javascript">
    $(document).on("click", function() {
      var base_url = $('#base_url').val();
      var total = $('.grandtotal').val();
      var code = $('.currency_code').val();

      var url = base_url + 'admin/invoice/convert_currency/' + total + '/' + code;
      $.post(url, {
        data: 'value',
        'csrf_test_name': csrf_token
      }, function(json) {
        if (json.st == 1) {
          $('.conversion_currency').html(json.result);
          $('.convert_total').val(json.convert_total);
        }
      }, 'json');
    });
  </script>
<?php endif ?>

<script>
  // Initialize the clipboard instance
  var clipboard = new ClipboardJS('.copy_button', {
    target: function() {
      return document.querySelector('.copy_url');
    }
  });

  // Add event listener to the copy button
  clipboard.on('success', function(e) {
    $("#successMsg").html('Copied text to clipboard!').delay(3000).slideUp('slow');

    e.clearSelection();
  });
</script>


<script>
  $(document).on('change', ".show_method", function() {
    //
    $('.method_details').slideDown();
  });



  $('#country').on('change', function() {
    const country_id = $(this).val();
    console.log(country_id);

    // show a loading placeholder
    $('#timezone_select')
      .html('<option value="">Loading…</option>')
      .prop('disabled', true);

    if (country_id) {
      $.ajax({
        url: '<?= base_url('admin/organization_settings/get_timezones_by_country_id') ?>',
        type: 'GET',
        data: {
          country_id
        },
        dataType: 'json',
        success(res) {
          if (res.status && Array.isArray(res.data)) {
            let opts = '<option value="">Select</option>';
            res.data.forEach(tz => {
              opts += `<option value="${tz}">${tz}</option>`;
            });
            $('#timezone_select').html(opts);
          } else {
            $('#timezone_select').html('<option value="">No timezones found</option>');
          }
          $('#timezone_select').prop('disabled', false);
        },
        error() {
          $('#timezone_select')
            .html('<option value="">Error fetching timezones</option>')
            .prop('disabled', false);
        }
      });
    } else {
      $('#timezone_select')
        .html('<option value="">Select</option>')
        .prop('disabled', true);
    }
  });
</script>
<script>
  $(document).ready(function() {
    function toggleFieldsBasedOnPlanName() {
      var selectedPlanText = $('select[name="package"] option:selected').text().trim();
      if (selectedPlanText === 'SelectAll Packages') {
        return;
      }

      console.log(selectedPlanText);
      if (selectedPlanText === 'TrialAll Packages' || selectedPlanText === 'Trial') {
        // Show and select 'week' in billing type
        $('select[name="billing_type"] option[value="week"]').show();
        $('select[name="billing_type"] option[value="monthly"]').hide();
        $('select[name="billing_type"] option[value="yearly"]').hide();

        $('select[name="billing_type"]').val('week');

        // Show only 'verified' in payment status
        $('select[name="payment_status"] option[value="pending"]').hide();
        $('select[name="payment_status"]').val('verified');

      } else {
        $('select[name="billing_type"] option[value="week"]').hide();
        $('select[name="billing_type"] option[value="monthly"]').show();
        $('select[name="billing_type"] option[value="yearly"]').show();
        $('select[name="payment_status"] option[value="pending"]').show();
        $('select[name="billing_type"]').val('');

      }
    }
    <?php if (isset($page_title) && $page_title == "Edit") { ?>
      var selectedPlanText = $('select[name="package"] option:selected').text().trim();
      if (!selectedPlanText === 'TrialAll Packages' || !selectedPlanText === 'Trial') {
        toggleFieldsBasedOnPlanName();
      }
    <?php } ?>
    // Change event
    $('select[name="package"]').on('change', function() {
      toggleFieldsBasedOnPlanName();
    });
  });
</script>
<!-- 1.  include the shared WS client (root‑relative path) -->
<script src="<?= base_url('ws-client.js'); ?>"></script>

<!-- <?php if ($payload = $this->session->flashdata('org_settings_payload')): ?> -->
<!-- 2.  trigger the WebSocket packet exactly once after redirect
  <script>
    (function() {
      const data = <?= json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP); ?>;
      if (typeof changeOrganizationSetting === 'function') {
        changeOrganizationSetting(data.employeeId, data.userId, data.settings);
        console.log('[WS] org settings sent');
      }
    })();
  </script> -->
<!-- <?php endif; ?> -->

</body>

</html>