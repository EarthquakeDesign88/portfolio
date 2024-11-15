@extends('partials.master')

@section('content')

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <a href="#" onclick="openModal('addWorkModeModal')" class="btn mb-1 btn-info fs-20">เพิ่มรูปแบบงาน</a>
        </ol>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><h3>รูปแบบงาน <span class="label label-info data-count"></span></h3> </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id ="workModeTable">
                                <thead>
                                    <tr>
                                        <th>รูปแบบงาน (TH)</th>
                                        <th>รูปแบบงาน (EN)</th>
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



<!-- Add Work mode Modal -->
<div id="addWorkModeModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เพิ่มรูปแบบงาน</h3>
                <button type="button" class="close" onclick="clearForm('addWorkModeModal')"><span>&times;</span>
                </button>
            </div>
            <form id="addWorkModeForm" method="POST" action="javascript:void(0)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="modeDescTH" class="form-label">รูปแบบงานภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modeDescTH" name="mode_desc_th" autocomplete="off" value="{{ old('mode_desc_th') }}">
                            <div id="modeDescTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="modeDescEN" class="form-label">รูปแบบงานภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modeDescEN" name="mode_desc_en" autocomplete="off" value="{{ old('mode_desc_en') }}">
                            <div id="modeDescENError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('addWorkModeModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Edit Work mode Modal -->
<div id="editWorkModeModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">แก้ไขรูปแบบงาน</h3>
                <button type="button" class="close" onclick="clearForm('editWorkModeModal')"><span>&times;</span>
                </button>
            </div>
            <form id="editWorkModeForm" method="POST" action="javascript:void(0)">
                @csrf
                <input type="hidden" id="modeId" name="mode_id">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="modeDescTH" class="form-label">ชื่อรูปแบบงานภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modeDescTH" name="mode_desc_th" autocomplete="off" value="{{ old('mode_desc_th') }}">
                            <div id="modeDescTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="lastName" class="form-label">ชื่อรูปแบบงานภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modeDescEN" name="mode_desc_en" autocomplete="off" value="{{ old('mode_desc_en') }}">
                            <div id="modeDescENError2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('editWorkModeModal')">ปิด</button>
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
        if (key === 'mode_desc_th') {
          $('#modeDescTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'mode_desc_en') {
          $('#modeDescENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
    });
  }

  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(workModes) {
    $('#workModeTable tbody').empty();

    $.each(workModes, function(index, workMode) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + workMode.mode_desc_th + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + workMode.mode_desc_en + "</span></td>" +
            "<td>" +
                "<button class='btn mb-1 btn-warning editData mr-2' data-id='" + workMode.mode_id + "'><i class='fa fa-pencil-square-o'></i></button>" +
                "<button class='btn mb-1 btn-danger deleteData' data-id='" + workMode.mode_id + "'><i class='fa fa-trash-o'></i></button>"
              "</td>" +
          "</tr>";
            
        $('#workModeTable tbody').append(row);
    });

    updateCount(workModes.length);
  }

  function fetchWorkModes() {
    $.ajax({
      type: "GET",
      url: "{{ route('getWorkModes') }}",
      success: function(data) {
        updateTable(data.workModes);
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

    fetchWorkModes();

    $('#addWorkModeForm').submit(function(e) {
        e.preventDefault();
        clearErrors();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertWorkMode') }}",
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
                
                fetchWorkModes();
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

              clearForm('addWorkModeModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });

    $('#workModeTable').on('click', '.editData', function () {
        var modeId = $(this).data('id');

        $.ajax({
            url: "{{ route('getWorkModeById', ['id' => ':id']) }}".replace(':id', modeId),
            type: "GET",
            success: function(data) {
                console.log(data); 
                $('#editWorkModeModal #modeId').val(data.workMode.mode_id);
                $('#editWorkModeModal #modeDescTH').val(data.workMode.mode_desc_th);
                $('#editWorkModeModal #modeDescEN').val(data.workMode.mode_desc_en);
                openModal('editWorkModeModal'); 
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editWorkModeForm').submit(function (e) {
        e.preventDefault();
        clearErrors();
        var formData = new FormData(this);
        var modeId = formData.get('mode_id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateWorkMode', ['id' => ':id']) }}".replace(':id', modeId),
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

                  fetchWorkModes();
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

                closeModal('editWorkModeModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#workModeTable').on('click', '.deleteData', function () {
          var modeId = $(this).data('id');
        
          toastr.warning("ต้องการลบรูปแบบงานนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteWorkMode', ['id' => ':id']) }}".replace(':id', modeId),
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

                            fetchWorkModes();
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