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
                        สถานะบริษัท
                        <span><h2 class="data-count badge badge-primary"></h2></span>
                    </h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
          <h4>สถานะบริษัท</h4>
            <div class="add-product">
                <a href="#" onclick="openModal('addCompanyStatusModal')">เพิ่มสถานะบริษัท</a>
            </div>

          <table id="companyStatusTable">
            <thead>
              <tr>
                <th>สถานะบริษัท</th>
                <th>จัดการ</th>
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


<!-- Add Company Status Modal -->
<div id="addCompanyStatusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="addCompanyStatusModalLabel">เพิ่มสถานะบริษัท</h4>
            <button type="button" class="close" onclick="clearCompanyStatusForm('addCompanyStatusModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="addCompanyStatusForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                    <label for="companyStatus" class="form-label">สถานะบริษัท</label>
                                    <input type="text" class="form-control" id="companyStatus" name="status" autocomplete="off" value="{{ old('status') }}">
                                    <div id="companyStatusError"></div>
                                </div>

                                <button type="submit" class="submitButton">เพิ่ม</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Company Status Modal -->
<div id="editCompanyStatusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editCompanyStatusModalLabel">แก้ไขสถานะบริษัท</h4>
            <button type="button" class="close" onclick="closeModal('editCompanyStatusModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editCompanyStatusForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                  <label label for="companyStatus" class="form-label">สถานะบริษัท</label>
                                  <input type="text" class="form-control" id="companyStatus" name="status" autocomplete="off" value="{{ old('status') }}">
                                  <div id="companyStatusError2"></div>
                                </div>

                                <button type="submit" class="submitButton">อัพเดท</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="{{ asset('assets/lib/jquery/dist/jquery.min.js') }}"></script>



<script type="text/javascript"> 
  function openModal(modalId) {
    var customModal = document.getElementById(modalId);
    customModal.style.display = 'block';

    document.body.style.overflow = 'hidden';

    window.addEventListener('click', function (event) {
        if (event.target === customModal) {
          event.stopPropagation();
        }
    });
  }

  function closeModal(modalId) {
    var customModal = document.getElementById(modalId);
    customModal.style.display = 'none';

    document.body.style.overflow = '';
  }

  function clearErrors() {
      $('.text-error').html('');
  }

  function clearCompanyStatusForm(modalId) {
      $('#' + modalId + ' form').trigger("reset");
      closeModal(modalId);
      clearErrors();
  }

  function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'status') {
                $('#companyStatusError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
        });
    }


  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(companyStatus) {
    $('#companyStatusTable tbody').empty();

    $.each(companyStatus, function(index, st) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + st.status + "</span></td>" +
            "<td>" +
              "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + st.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + st.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
            "</td>" +
          "</tr>";
            
        $('#companyStatusTable tbody').append(row);
    });

    updateCount(companyStatus.length);
  }

  function fetchCompanyStatus() {
    $.ajax({
      type: "GET",
      url: "{{ route('getCompanyStatus') }}",
      success: function(data) {
        updateTable(data.companyStatus);
      },
      errror: function(error) {
        console.error('AJAX error:', error);
      }
    });
  }

  $("document").ready(function() {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    fetchCompanyStatus();

    var table = $('#companyStatusTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('companyStatus') }}",
      columns: [
        { data: 'status', name: 'status'},
        {
            data: 'id',
            name: 'action',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + data + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
                "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + data + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>";
            }
        }
      ],
      lengthMenu: [[50, 100, 200, 500], [50, 100, 200, 500]], 
      pageLength: 50, 
      language: {
        paginate: {
            first: 'หน้าแรก',
            last: 'หน้าสุดท้าย',
            next: 'ถัดไป',
            previous: 'ก่อนหน้า',
        },
        search: 'ค้นหา',
        lengthMenu: 'แสดง _MENU_ รายการ',
        info: 'แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ',
        zeroRecords: 'ไม่พบรายการที่ตรงกับการค้นหา',
        infoEmpty: 'แสดง 0 ถึง 0 จาก 0 รายการ',
        infoFiltered: '(กรองจากทั้งหมด _MAX_ รายการ)',
      },
      error: function(xhr, error, thrown) {
          console.error('DataTables Ajax error:', error);
      }
    });

    $('#addCompanyStatusForm').submit(function(e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertCompanyStatus') }}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(resp) {
              var status = resp.status;
              var message = resp.message;
              
              if (resp.status === "success") {
                Command: toastr["success"](message, null, {
                  "showMethod": "slideDown",
                  "hideMethod": "slideUp",
                  "timeOut": 3000,
                  "extendedTimeOut": 1000,
                  "positionClass": "toast-top-right",
                  "progressBar": true,
                  "toastClass": "custom-toast"
                });
                
                fetchCompanyStatus();
              } 
              else {
                Command: toastr["error"](message, null, {
                  "showMethod": "slideDown",
                  "hideMethod": "slideUp",
                  "timeOut": 3000,
                  "extendedTimeOut": 1000,
                  "positionClass": "toast-top-right",
                  "progressBar": true,
                  "toastClass": "custom-toast"
                });
              }

              clearCompanyStatusForm('addCompanyStatusModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });


    $('#companyStatusTable').on('click', '.editData', function () {
        var companyStatusId = $(this).data('id');

        $.ajax({
            url: "{{ route('getCompanyStatusById', ['id' => ':id']) }}".replace(':id', companyStatusId),
            type: "GET",
            success: function(data) {
              $('#editCompanyStatusModal #id').val(data.companyStatus.id);
              $('#editCompanyStatusModal #companyStatus').val(data.companyStatus.status);
              openModal('editCompanyStatusModal');
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editCompanyStatusForm').submit(function (e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);
        var companyStatusId = formData.get('id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateCompanyStatus', ['id' => ':id']) }}".replace(':id', companyStatusId),
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (resp) {
              var status = resp.status;
              var message = resp.message;

              if (resp.status == "success") {
                Command: toastr["success"](message, null, {
                  "showMethod": "slideDown",
                  "hideMethod": "slideUp",
                  "timeOut": 3000,
                  "extendedTimeOut": 1000,
                  "positionClass": "toast-top-right",
                  "progressBar": true,
                  "toastClass": "custom-toast"
                });

                fetchCompanyStatus();
              } 
              else {
                Command: toastr["error"](message, null, {
                  "showMethod": "slideDown",
                  "hideMethod": "slideUp",
                  "timeOut": 3000,
                  "extendedTimeOut": 1000,
                  "positionClass": "toast-top-right",
                  "progressBar": true,
                  "toastClass": "custom-toast"
                });
              }

              closeModal('editCompanyStatusModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#companyStatusTable').on('click', '.deleteData', function () {
          var companyStatusId = $(this).data('id');
        
          toastr.warning("ต้องการลบสถานะบริษัทนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteCompanyStatus', ['id' => ':id']) }}".replace(':id', companyStatusId),
                      type: "DELETE",
                      success: function (resp) {
                          var message = resp.message;

                          if (resp.status == 'success') {
                            Command: toastr["success"](message, null, {
                              "showMethod": "slideDown",
                              "hideMethod": "slideUp",
                              "timeOut": 3000,
                              "extendedTimeOut": 1000,
                              "positionClass": "toast-top-right",
                              "progressBar": true,
                              "toastClass": "custom-toast"
                            });

                            fetchCompanyStatus();
                          } 
                          else {
                            Command: toastr["error"](message, null, {
                              "showMethod": "slideDown",
                              "hideMethod": "slideUp",
                              "timeOut": 3000,
                              "extendedTimeOut": 1000,
                              "positionClass": "toast-top-right",
                              "progressBar": true,
                              "toastClass": "custom-toast"
                            });
                          }
                      },
                      error: function (error) {
                        Command: toastr["error"]("พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง", null, {
                          "showMethod": "slideDown",
                          "hideMethod": "slideUp",
                          "timeOut": 3000,
                          "extendedTimeOut": 1000,
                          "positionClass": "toast-top-right",
                          "progressBar": true,
                          "toastClass": "custom-toast"
                        });
                      }

                  });

              });
          },
          toastClass: 'custom-toast'
      });
    });

    
  });
</script>


@endsection