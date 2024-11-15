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
                        ตราประทับ
                        <span><h2 class="data-count badge badge-primary"></h2></span>
                    </h2>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <!-- <div class="breadcomb-report">
                  <button data-toggle="tooltip" data-placement="left" title="Download Report" class="btn"><i class="icon nalika-download"></i></button>
                </div> -->
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
          <h4>ตราประทับ</h4>
            <div class="add-product">
                <a href="#" onclick="openModal('addStampModal')">เพิ่มตราประทับ</a>
            </div>

          <table id="stampTable">
            <thead>
              <tr>
                <th>รหัสตราประทับ</th>
                <th>เงื่อนไขตราประทับ</th>
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


<!-- Add Stamp Modal -->
<div id="addStampModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="addStampModalLabel">เพิ่มตราประทับ</h4>
            <button type="button" class="close" onclick="clearStampForm('addStampModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="addStampForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                    <label for="stampCode" class="form-label">รหัสตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCode" name="stamp_code" autocomplete="off" value="{{ old('stamp_code') }}">
                                    <div id="stampCodeError"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="stampCondtion" class="form-label">เงื่อนไขตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCondition" name="stamp_condition" autocomplete="off" value="{{ old('stamp_condition') }}">
                                    <div id="stampConditionError"></div>
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

<!-- Edit Stamp Modal -->
<div id="editStampModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editStampModalLabel">แก้ไขตราประทับ</h4>
            <button type="button" class="close" onclick="closeModal('editStampModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editStampForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                    <label for="stampCode" class="form-label">รหัสตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCode" name="stamp_code" autocomplete="off" value="{{ old('stamp_code') }}">
                                    <div id="stampCodeError2"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="stampCondtion" class="form-label">เงื่อนไขตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCondition" name="stamp_condition" autocomplete="off" value="{{ old('stamp_condition') }}">
                                    <div id="stampConditionError2"></div>
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

  function clearStampForm(modalId) {
      $('#' + modalId + ' form').trigger("reset");
      closeModal(modalId);
      clearErrors();
  }

  function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'stamp_code') {
                $('#stampCodeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'stamp_condition') {
                $('#stampConditionError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
        });
    }


  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(stamps) {
    $('#stampTable tbody').empty();

    $.each(stamps, function(index, stamp) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + stamp.stamp_code + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + stamp.stamp_condition + "</span></td>" +
            "<td>" +
              "<button data-toggle='tooltip' title='เพิ่ม' class='pd-setting-ed editData' data-id='" + stamp.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + stamp.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
            "</td>" +
          "</tr>";
            
        $('#stampTable tbody').append(row);
    });

    updateCount(stamps.length);
  }

  function fetchStamps() {
    $.ajax({
      type: "GET",
      url: "{{ route('getStamps') }}",
      success: function(data) {
        updateTable(data.stamps);
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

    fetchStamps();

    var table = $('#stampTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('stamps') }}",
      columns: [
        { data: 'stamp_code', name: 'stamp_code'},
        { data: 'stamp_condition', name: 'stamp_condition'},
        {
            data: 'id',
            name: 'action',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return "<button data-toggle='tooltip' title='เพิ่ม' class='pd-setting-ed editData' data-id='" + data + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
                "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + data + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>";
            }
        }
      ],
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
      lengthMenu: [[50, 100, 200, 500], [50, 100, 200, 500]], 
      pageLength: 50, 
      error: function(xhr, error, thrown) {
          console.error('DataTables Ajax error:', error);
      }
    });

    $('#addStampForm').submit(function(e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertStamp') }}",
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
                
                fetchStamps();
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

              clearStampForm('addStampModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });


    $('#stampTable').on('click', '.editData', function () {
        var stampId = $(this).data('id');

        $.ajax({
            url: "{{ route('getStampById', ['id' => ':id']) }}".replace(':id', stampId),
            type: "GET",
            success: function(data) {
              $('#editStampModal #id').val(data.stamp.id);
              $('#editStampModal #stampCode').val(data.stamp.stamp_code);
              $('#editStampModal #stampCondition').val(data.stamp.stamp_condition);
              openModal('editStampModal');
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editStampForm').submit(function (e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);
        var stampId = formData.get('id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateStamp', ['id' => ':id']) }}".replace(':id', stampId),
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

                  fetchStamps();
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

                closeModal('editStampModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#stampTable').on('click', '.deleteData', function () {
          var stampId = $(this).data('id');
        
          toastr.warning("ต้องการลบตราประทับนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteStamp', ['id' => ':id']) }}".replace(':id', stampId),
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

                            fetchStamps();
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