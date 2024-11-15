@extends('layouts.master')

@section('content')

<style>
  .search-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 10px; 
  }

  #searchInput {
    width: 200px;
    height: 40px;
  }

  .text-count {
    color: #fff;
  }


  .stampCodeCheckbox {
    display: none; 
  }

  .stampCodeCheckbox + label {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    position: relative;
    padding-left: 22px;
    font-size: 14px;
  }

  /* สไตล์สำหรับกล่อง checkbox */
  .stampCodeCheckbox + label:before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    border: 2px solid #24caa1;
    background-color: #fff;
    border-radius: 4px;
    transition: background-color 0.3s, border-color 0.3s;
  }

  /* สไตล์เมื่อ checkbox ถูกเลือก */
  .stampCodeCheckbox:checked + label:before {
    background-color: #24caa1;
    border-color: #24caa1;
  }

  /* สไตล์เมื่อ hover */
  .stampCodeCheckbox + label:hover:before {
    border-color: #24caa1;
  }

  /* สไตล์สำหรับการตรวจสอบ checkbox */
  .stampCodeCheckbox:checked + label:after {
    content: '✔';
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 16px;
  }

  /* สไตล์สำหรับ label ที่เลือกทั้งหมด */
  .stampCodeCheckAll + label {
    cursor: pointer;
  }

  .stampCodeCheckAll {
    display: none;
  }

  .stampCodeCheckAll + label {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    position: relative;
    padding-left: 22px;
    font-size: 14px;
  }

.stampCodeCheckAll + label:before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  border: 2px solid #24caa1;
  background-color: #fff;
  border-radius: 4px;
  transition: background-color 0.3s, border-color 0.3s;
}

/* สไตล์เมื่อ checkbox ของปุ่ม "เลือกทั้งหมด" ถูกเลือก */
.stampCodeCheckAll:checked + label:before {
  background-color: #24caa1;
  border-color: #24caa1;
}

/* สไตล์เมื่อ hover */
.stampCodeCheckAll + label:hover:before {
  border-color: #24caa1;
}

/* สไตล์สำหรับการตรวจสอบ checkbox ของปุ่ม "เลือกทั้งหมด" */
.stampCodeCheckAll:checked + label:after {
  content: '✔';
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  color: #fff;
  font-size: 16px;
}

</style>

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
                      รายงานสรุปการใช้ตราประทับ รายเดือน
                    </h2>
                    <h2 class="data-count badge badge-primary"></h2>
                    <div class="selected-count text-count">
                      จำนวนที่เลือก: <span id="selectedCount">0</span>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="breadcomb-wp">
                  <div class="breadcomb-ctn">
                    <h4 style="color:#fff;">เลือกประเภทการส่งออก:</h4>
                    <select id="exportType">
                      <option value="pdf">PDF</option>
                      <option value="excel">Excel</option>
                    </select>
                    <h4 style="color:#fff; margin-top: 10px;">เลือกเดือนและปี:</h4>
                    <input type="month" id="selectedMonthYear">
                    <button id="exportButton" class="main-button">ดาวน์โหลดรายงาน</button>
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
          <div class="search-container">
            <input type="text" id="searchInput" placeholder="ค้นหา" autocomplete="off">
          </div>
          <table id="monthlyStampSumTable">
            @csrf
            <thead>
              <tr>
                <th>
                  <input type='checkbox' class='stampCodeCheckAll' id='checkAll'>
                  <label for='checkAll'></label> <span>ทั้งหมด</span>
                </th>
                <th>บุคคล/บริษัท</th>
                <th>รหัสตราประทับ</th>
                <th>เงื่อนไขตราประทับ</th>
                <th>ชั้น</th>
                <th>สถานที่</th>
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
  var selectedStampCodes = [];

  function updateTable(reports) {
    $('#monthlyStampSumTable tbody').empty();

    $.each(reports, function(index, report) {
      var isChecked = selectedStampCodes.includes(report.id);

      var row = "<tr>" +
        "<td>" +
          "<input type='checkbox' class='stampCodeCheckbox' id='checkbox_" + report.id + "' data-stampcode='" + report.id + "' " + (isChecked ? "checked" : "") + ">" +
          "<label for='checkbox_" + report.id + "'></label>" +
        "</td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.company_name + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.stamp_code + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.stamp_condition + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.floor_number + "</span></td>" +
        "<td data-label=''><span class='ellipsis-tb'>" + report.place_name + "</span></td>" +
        "</tr>";

      $('#monthlyStampSumTable tbody').append(row);
    });

    updateSelectedCount();
  }

  function fetchMonthlyStampSummary() {
    $.ajax({
      type: "GET",
      url: "{{ route('getMonthlyStampSummaryReport') }}",
      success: function(data) {
        updateTable(data.reports);
        $('.data-count').text(data.reports.length + " บริษัท");
      },
      error: function(error) {
        console.error('AJAX error:', error);
      }
    });
  }

  function setCurrentDate() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');

    $('#selectedMonthYear').val(currentYear + '-' + currentMonth);
  }

  function exportData(stampCodes, exportType, selectedMonthYear) {
    $('#exportButton').prop('disabled', true);
    $('#exportButton').html('<i class="fa fa-spinner fa-spin"></i> กำลังดาวน์โหลด...');

    // console.log(exportType);

    if(exportType == 'pdf') {
      $.ajax({
        type: "GET",
        url: "{{ route('exportMonthlyStampSummaryPDF') }}",
        data: {
          exportType: exportType,
          selectedMonthYear: selectedMonthYear,
          stampCodes: stampCodes,
        },
        success: function(resp) {
          if (resp && resp.status && resp.message) {
            var status = resp.status;
            var message = resp.message;

            if (status === "success") {
              console.log(resp.pdf_url);
              console.log(resp.pdf_filename);
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
            console.log("Invalid response format");
          }
          $('#exportButton').prop('disabled', false);
          $('#exportButton').html('ดาวน์โหลดรายงาน');
        },
        error: function(xhr, status, error) {
          $('#exportButton').prop('disabled', false);
          $('#exportButton').html('ดาวน์โหลดรายงาน');

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
    else if(exportType == 'excel') {
      $.ajax({
        type: "POST",
        url: "{{ route('exportMonthlyStampSummaryExcel') }}",
        data: {
          _token: '{{ csrf_token() }}',
          exportType: exportType,
          selectedMonthYear: selectedMonthYear,
          stampCodes: stampCodes,
        },
        success: function(resp) {
          console.log(resp.excel_url);
          console.log(resp.excel_filename);
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

              if (resp.excel_url) {
                var link = document.createElement('a');
                link.href = resp.excel_url;
                link.download = resp.excel_filename; 
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
            console.log("Invalid response format");
          }
          $('#exportButton').prop('disabled', false);
          $('#exportButton').html('ดาวน์โหลดรายงาน');
        },
        error: function(xhr, status, error) {
          $('#exportButton').prop('disabled', false);
          $('#exportButton').html('ดาวน์โหลดรายงาน');

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

  }

  function updateSelectedCount() {
    $('#selectedCount').text(selectedStampCodes.length);
  }

  function fetchTableData(search = '') {
    var table = $('#monthlyStampSumTable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true, // destroy existing DataTable instance to reinitialize
      searching: false,
      lengthMenu: [[50, 100, 200, 500], [50, 100, 200, 500]], 
      pageLength: 100, 
      ajax: {
          url: "{{ route('monthlyStampSummaryList') }}",
          data: { search: search }
      },
      columns: [
          {
            data: 'id',
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data) {
              var isChecked = selectedStampCodes.includes(data);
              return "<input type='checkbox' class='stampCodeCheckbox' id='checkbox_" + data + "' data-stampcode='" + data + "' " + (isChecked ? "checked" : "") + "><label for='checkbox_" + data + "'></label>";
            }
          },
          { data: 'company_name', name: 'companies.company_name' },
          { data: 'stamp_code', name: 'stamps.stamp_code' },
          { data: 'stamp_condition', name: 'stamps.stamp_condition' },
          { data: 'floor_number', name: 'floors.floor_number' },
          { data: 'place_name', name: 'places.place_name' }
      ],
      language: {
          paginate: {
              first: 'หน้าแรก',
              last: 'หน้าสุดท้าย',
              next: 'ถัดไป',
              previous: 'ก่อนหน้า'
          },
          search: 'ค้นหา',
          lengthMenu: 'แสดง _MENU_ รายการ',
          info: 'แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ',
          zeroRecords: 'ไม่พบรายการที่ตรงกับการค้นหา',
          infoEmpty: 'แสดง 0 ถึง 0 จาก 0 รายการ',
          infoFiltered: '(กรองจากทั้งหมด _MAX_ รายการ)'
      },
      error: function(xhr, error, thrown) {
          console.error('DataTables Ajax error:', error);
      }
    });
  }

  $(document).ready(function() {
    $(document).on('change', '.stampCodeCheckbox', function() {
      var stampCode = $(this).data('stampcode');
      if ($(this).is(':checked')) {
        if (!selectedStampCodes.includes(stampCode)) {
          selectedStampCodes.push(stampCode);
        }
      } else {
        selectedStampCodes = selectedStampCodes.filter(function(code) {
          return code !== stampCode;
        });
      }
      updateSelectedCount();
    });

    $(document).on('click', '.stampCodeCheckAll', function() {
      var isChecked = $(this).prop('checked');
      $('.stampCodeCheckbox').prop('checked', isChecked).trigger('change');
    });

    $('#searchInput').on('keyup', function() {
      var search = $(this).val();
      fetchTableData(search);
    });

    $('#exportButton').on('click', function() {
      var exportType = $('#exportType').val();
      var selectedMonthYear = $('#selectedMonthYear').val();

      if (selectedStampCodes.length === 0 || !exportType || !selectedMonthYear) {
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

      exportData(selectedStampCodes, exportType, selectedMonthYear);
    });

    fetchTableData();
    fetchMonthlyStampSummary();
    setCurrentDate();
  });
</script>


@endsection