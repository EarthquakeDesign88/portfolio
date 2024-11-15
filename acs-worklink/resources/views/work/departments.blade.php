@extends('partials.master')

@section('content')

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <a href="#" onclick="openModal('addDepartmentModal')" class="btn mb-1 btn-info fs-20">เพิ่มแผนก</a>
        </ol>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><h3>แผนก <span class="label label-info data-count"></span></h3> </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id ="departmentTable">
                                <thead>
                                    <tr>
                                        <th>แผนก (TH)</th>
                                        <th>แผนก (EN)</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Department Modal -->
<div id="addDepartmentModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เพิ่มแผนก</h3>
                <button type="button" class="close" onclick="clearForm('addDepartmentModal')"><span>&times;</span>
                </button>
            </div>
            <form id="addDepartmentForm" method="POST" action="javascript:void(0)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="departmentDescTH" class="form-label">ชื่อแผนกภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="departmentDescTH" name="department_desc_th" autocomplete="off" value="{{ old('department_desc_th') }}">
                            <div id="departmentDescTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="departmentDescEN" class="form-label">ชื่อแผนกภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="departmentDescEN" name="department_desc_en" autocomplete="off" value="{{ old('department_desc_en') }}">
                            <div id="departmentDescENError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('addDepartmentModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Edit Department Modal -->
<div id="editDepartmentModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">แก้ไขแผนก</h3>
                <button type="button" class="close" onclick="clearForm('editDepartmentModal')"><span>&times;</span>
                </button>
            </div>
            <form id="editDepartmentForm" method="POST" action="javascript:void(0)">
                @csrf
                <input type="hidden" id="departmentId" name="department_id">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="departmentDescTH" class="form-label">ชื่อแผนกภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="departmentDescTH" name="department_desc_th" autocomplete="off" value="{{ old('department_desc_th') }}">
                            <div id="departmentDescTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="departmentDescEN" class="form-label">ชื่อแผนกภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="departmentDescEN" name="department_desc_en" autocomplete="off" value="{{ old('department_desc_en') }}">
                            <div id="departmentDescENError2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('editDepartmentModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
  function openModal(modalId) {
    $('#' + modalId).modal('show');
  }

  function closeModal(modalId) {
    $('#' + modalId).modal('hide');
  }

  function clearErrors() {
    $('.text-error').html('');
  }

  function clearForm(modalId) {
    $('#' + modalId + ' form').trigger("reset");
    clearErrors();
    closeModal(modalId)
  }

  function handleValidationErrors(errors, action) {
    $.each(errors, function(key, value) {
        if (key === 'department_desc_th') {
          $('#departmentDescTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'department_desc_en') {
          $('#departmentDescENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
    });
  }

  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(departments) {
    $('#departmentTable tbody').empty();

    $.each(departments, function(index, department) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + department.department_desc_th + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + department.department_desc_en + "</span></td>" +
            "<td>" +
                "<button class='btn mb-1 btn-warning editData mr-2' data-id='" + department.department_id + "'><i class='fa fa-pencil-square-o'></i></button>" +
                "<button class='btn mb-1 btn-danger deleteData' data-id='" + department.department_id + "'><i class='fa fa-trash-o'></i></button>"
              "</td>" +
          "</tr>";
            
        $('#departmentTable tbody').append(row);
    });

    updateCount(departments.length);
  }

  function fetchDepartments() {
    $.ajax({
      type: "GET",
      url: "{{ route('getDepartments') }}",
      success: function(data) {
        updateTable(data.departments);
      },
      error: function(error) {
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

    fetchDepartments();

    $('#addDepartmentForm').submit(function(e) {
        e.preventDefault();
        clearErrors();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertDepartment') }}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(resp) {
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
                
                fetchDepartments();
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

              clearForm('addDepartmentModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });

    $('#departmentTable').on('click', '.editData', function () {
        var departmentId = $(this).data('id');

        $.ajax({
            url: "{{ route('getDepartmentById', ['id' => ':id']) }}".replace(':id', departmentId),
            type: "GET",
            success: function(data) {
                console.log(data); 
                $('#editDepartmentModal #departmentId').val(data.department.department_id);
                $('#editDepartmentModal #departmentDescTH').val(data.department.department_desc_th);
                $('#editDepartmentModal #departmentDescEN').val(data.department.department_desc_en);
                openModal('editDepartmentModal'); 
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editDepartmentForm').submit(function (e) {
        e.preventDefault();
        clearErrors();
        var formData = new FormData(this);
        var departmentId = formData.get('department_id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateDepartment', ['id' => ':id']) }}".replace(':id', departmentId),
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (resp) {
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

                  fetchDepartments();
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

                closeModal('editDepartmentModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#departmentTable').on('click', '.deleteData', function () {
          var departmentId = $(this).data('id');
        
          toastr.warning("ต้องการลบแผนกนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteDepartment', ['id' => ':id']) }}".replace(':id', departmentId),
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

                            fetchDepartments();
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