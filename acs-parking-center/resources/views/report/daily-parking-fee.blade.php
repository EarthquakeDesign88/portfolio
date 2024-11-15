@extends('layouts.master')

@section('content')

<div class="header-advance-area">
  @include('partials.header-responsive')

  <div class="breadcome-area">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="breadcome-list">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="breadcomb-wp">
                  <div class="breadcomb-ctn">
                    <h2>
                      รายงานสรุปค่าที่จอดรถ รายวัน
                      <!-- <span class="data-count"></span> -->
                    </h2>
                    <h2 class="data-count badge badge-primary"></h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="breadcomb-wp">
                  <div class="breadcomb-ctn">
                    <h4 style="color:#fff;">เลือกประเภทการส่งออก:</h4>
                    <select id="exportType">
                      <option value="pdf">PDF</option>
                      <!-- <option value="excel">Excel</option> -->
                    </select>
                    <h4 style="color:#fff; margin-top: 10px;">เลือกวัน:</h4>
                    <input type="date" id="selectedDate">
                    <button id="exportButton" class="main-button">ดาวน์โหลดรายงาน</button>
                  </div>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="breadcomb-wp">
                  <div class="breadcomb-ctn">
                    <h4 style="color:#fff; display: inline-block; margin-right: 10px;">เลือกวิธีชำระเงิน:</h4>
                    <select id="selectedPaymentMethod" style="display: inline-block;">
                      <option value="0">ทั้งหมด</option>
                      <option value="1">เงินสด</option>
                      <option value="2">โอนเงิน</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="product-status mg-b-30">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="product-status-wrap">
          <table id="dailyParkingFee">
            <thead>
              <tr>
                <th>รหัสบัตรจอดรถ</th>
                <th>ทะเบียนรถ</th>
                <th>เวลาเข้า</th>
                <th>เวลาออก</th>
                <th>เวลาที่จอดรถ</th>
                <th>จำนวนตราประทับ</th>
                <th>วิธีการชำระเงิน</th>
                <th>ค่าที่จอดรถ</th>
              </tr>
            </thead>
            <tbody>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>




<script src="{{ asset('assets/lib/jquery/dist/jquery.min.js') }}"></script>




<script type="text/javascript">
  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(reports) {
    $('#dailyParkingFee tbody').empty();

    $.each(reports, function(index, report) {
      var row = "<tr>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.parking_pass_code + "</span></td>" +
        // "<td data-label=''><span class='ellipsis-tb'><img src='" + report.license_plate_path + "' alt='License Plate' style='width: 100px; height: auto;'></span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.license_plate + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.carin_time + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.carout_time + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.total_parking_time + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + (report.stamp_qty != null ? report.stamp_qty : '0') + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.payment_method + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.fee + "</span></td>" +
        "</tr>";

      $('#dailyParkingFee tbody').append(row);
    });

  }

  function fetchDailyParkingFee(selectedDate, selectedPaymentMethod) {
    var formattedDate = new Date(selectedDate).toISOString().slice(0, 10);
    // console.log(selectedPaymentMethod);
    $.ajax({
      type: "GET",
      url: "{{ route('getDailyParkingFeeReport') }}",
      data: {
        selectedDate: formattedDate,
        selectedPaymentMethod: selectedPaymentMethod
      },
      success: function(data) {
        updateTable(data.reports);
        updateCount(data.totalFee);
      },
      error: function(error) {
        console.error('AJAX error:', error);
      }
    });
  }


  function setCurrentDate() {
    var currentDate = new Date();
    var currentDay = currentDate.getDate().toString().padStart(2, '0');
    var currentYear = currentDate.getFullYear();
    var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');

    var formattedCurrentDate = currentYear + '-' + currentMonth + '-' + currentDay;
    $('#selectedDate').val(formattedCurrentDate);
    
    fetchDailyParkingFee(formattedCurrentDate);
  }

  function exportData(exportType, selectedDate, selectedPaymentMethod) {
    $('#exportButton').prop('disabled', true);
    $('#exportButton').html('<i class="fa fa-spinner fa-spin"></i> กำลังดาวน์โหลด...');

    $.ajax({
      type: "GET",
      url: "{{ route('exportDailyParkingFee') }}",
      data: {
        exportType: exportType,
        selectedDate: selectedDate,
        selectedPaymentMethod: selectedPaymentMethod
      },
      success: function(resp) {
        if (typeof console !== 'undefined' && console.log) {
          // console.log(resp);
        }

        if (resp && resp.status && resp.message) {
          var status = resp.status;
          var message = resp.message;

          if (status === "success") {
            toastr["success"](message, null, {
              "showMethod": "slideDown",
              "hideMethod": "slideUp",
              "timeOut": 3000,
              "extendedTimeOut": 1000,
              "positionClass": "toast-top-right",
              "progressBar": true,
              "toastClass": "custom-toast"
            });

            if (resp.pdf_url) {
              var link = document.createElement('a');
              link.href = resp.pdf_url;
              link.download = resp.pdf_filename;
              document.body.appendChild(link);

              link.click();

              document.body.removeChild(link);
            }
          } else {
            toastr["error"](message, null, {
              "showMethod": "slideDown",
              "hideMethod": "slideUp",
              "timeOut": 3000,
              "extendedTimeOut": 1000,
              "positionClass": "toast-top-right",
              "progressBar": true,
              "toastClass": "custom-toast"
            });
          }
        } else {
          // console.log("Invalid response format");
        }
        $('#exportButton').prop('disabled', false);
        $('#exportButton').html('ดาวน์โหลดรายงาน');
      },
      error: function(xhr, status, error) {
        $('#exportButton').prop('disabled', false);
        $('#exportButton').html('ดาวน์โหลดรายงาน');
        // Handle error if AJAX request fails
        if (typeof console !== 'undefined' && console.error) {
          toastr["error"](error, null, {
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "timeOut": 3000,
            "extendedTimeOut": 1000,
            "positionClass": "toast-top-right",
            "progressBar": true,
            "toastClass": "custom-toast"
          });
        }
      }
    });
  }

  $("document").ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    setCurrentDate();

    $('#selectedDate').change(function() {
      var selectedDate = $(this).val();
      var selectedPaymentMethod = $('#selectedPaymentMethod').val();
      fetchDailyParkingFee(selectedDate, selectedPaymentMethod);
    });

    $('#selectedPaymentMethod').change(function() {
      var selectedPaymentMethod = $(this).val();
      var selectedDate = $('#selectedDate').val();
      fetchDailyParkingFee(selectedDate, selectedPaymentMethod);
    });


    $(document).on('click', '#exportButton', function() {
      var exportType = $('#exportType').val();
      var selectedDate = $('#selectedDate').val();
      var selectedPaymentMethod = $('#selectedPaymentMethod').val();

      if (!exportType || !selectedDate) {
        Command: toastr["warning"]('โปรดเลือกข้อมูลให้ครบ', null, {
          "showMethod": "slideDown",
          "hideMethod": "slideUp",
          "timeOut": 3000,
          "extendedTimeOut": 1000,
          "positionClass": "toast-top-right",
          "progressBar": true,
          "toastClass": "custom-toast"
        });

        return;
      }

      exportData(exportType, selectedDate, selectedPaymentMethod);
    });



  });
</script>



@endsection