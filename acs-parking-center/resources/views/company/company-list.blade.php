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
                        บริษัท
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
          <h4>บริษัท</h4>
            <div class="add-product">
                <a href="#" onclick="openModal('addCompanyModal')">เพิ่มบริษัท</a>
            </div>

          <table id="companyTable">
            <thead>
              <tr>
                <th>บริษัท</th>
                <th>เบอร์โทรศัพท์</th>
                <th>ที่อยู่</th>
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


<!-- Add Company Modal -->
<div id="addCompanyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="addCompanyModalLabel">เพิ่มบริษัท</h4>
            <button type="button" class="close" onclick="clearCompanyForm('addCompanyModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="addCompanyForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                    <label for="companyName" class="form-label">บริษัท</label>
                                    <input type="text" class="form-control" id="companyName" name="company_name" autocomplete="off" value="{{ old('company_name') }}">
                                    <div id="companyNameError"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="companyPhone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control" id="companyPhone" name="company_phone" autocomplete="off" value="{{ old('company_phone') }}">
                                    <div id="companyPhoneError"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="companyAddress" class="form-label">ที่อยู่</label>
                                    <textarea class="form-control" id="companyAddress" name="company_address" autocomplete="off">{{ old('company_address') }}</textarea>
                                    <div id="companyAddressError"></div>
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

<!-- Edit Company Modal -->
<div id="editCompanyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editCompanyModalLabel">แก้ไขบริษัท</h4>
            <button type="button" class="close" onclick="closeModal('editCompanyModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editCompanyForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                    <label for="companyName" class="form-label">บริษัท</label>
                                    <input type="text" class="form-control" id="companyName" name="company_name" autocomplete="off" value="{{ old('company_name') }}">
                                    <div id="companyNameError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="companyPhone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control" id="companyPhone" name="company_phone" autocomplete="off" value="{{ old('company_phone') }}">
                                    <div id="companyPhoneError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="companyAddress" class="form-label">ที่อยู่</label>
                                    <textarea class="form-control" id="companyAddress" name="company_address" autocomplete="off">{{ old('company_address') }}</textarea>
                                    <div id="companyAddressError2"></div>
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

  function clearCompanyForm(modalId) {
      $('#' + modalId + ' form').trigger("reset");
      closeModal(modalId);
      clearErrors();
  }

  function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'company_name') {
                $('#companyNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'company_phone') {
                $('#companyPhoneError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'company_address') {
                $('#companyAddressError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
        });
    }


  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(companies) {
    $('#companyTable tbody').empty();

    $.each(companies, function(index, company) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + company.company_name + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + company.company_phone + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + company.company_address + "</span></td>" +
            "<td>" +
              "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + company.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + company.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
            "</td>" +
          "</tr>";
            
        $('#companyTable tbody').append(row);
    });

    updateCount(companies.length);
  }

  function fetchCompanies() {
    $.ajax({
      type: "GET",
      url: "{{ route('getCompanies') }}",
      success: function(data) {
        updateTable(data.companies);
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

    fetchCompanies();

    var table = $('#companyTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('companyList') }}",
      columns: [
        { data: 'company_name', name: 'company_name'},
        { data: 'company_phone', name: 'company_phone'},
        { data: 'company_address', name: 'company_address'},
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

    $('#addCompanyForm').submit(function(e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertCompany') }}",
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
                
                fetchCompanies();
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

              clearCompanyForm('addCompanyModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });


    $('#companyTable').on('click', '.editData', function () {
        var companyId = $(this).data('id');

        $.ajax({
            url: "{{ route('getCompanyById', ['id' => ':id']) }}".replace(':id', companyId),
            type: "GET",
            success: function(data) {
              $('#editCompanyModal #id').val(data.company.id);
              $('#editCompanyModal #companyName').val(data.company.company_name);
              $('#editCompanyModal #companyPhone').val(data.company.company_phone);
              $('#editCompanyModal #companyAddress').val(data.company.company_address);
              openModal('editCompanyModal');
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editCompanyForm').submit(function (e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);
        var companyId = formData.get('id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateCompany', ['id' => ':id']) }}".replace(':id', companyId),
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

                fetchCompanies();
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

              closeModal('editCompanyModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#companyTable').on('click', '.deleteData', function () {
          var companyId = $(this).data('id');
        
          toastr.warning("ต้องการลบบริษัทนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteCompany', ['id' => ':id']) }}".replace(':id', companyId),
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

                            fetchCompanies();
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