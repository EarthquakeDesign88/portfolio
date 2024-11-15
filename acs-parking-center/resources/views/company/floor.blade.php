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
                        ชั้น
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
          <h4>ชั้น</h4>
            <div class="add-product">
                <a href="#" onclick="openModal('addFloorModal')">เพิ่มชั้น</a>
            </div>

          <table id="floorTable">
            <thead>
              <tr>
                <th width="50%">ชั้น</th>
                <th width="50%">จัดการ</th>
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


<!-- Add Floor Modal -->
<div id="addFloorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="addFloorModalLabel">เพิ่มชั้น</h4>
            <button type="button" class="close" onclick="clearFloorForm('addFloorModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="addFloorForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                    <label for="floorNumber" class="form-label">ชั้น</label>
                                    <input type="text" class="form-control" id="floorNumber" name="floor_number" autocomplete="off" value="{{ old('floor_number') }}">
                                    <div id="floorNumberError"></div>
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

<!-- Edit Floor Modal -->
<div id="editFloorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editFloorModalLabel">แก้ไขชั้น</h4>
            <button type="button" class="close" onclick="closeModal('editFloorModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editFloorForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                    <label for="floorNumber" class="form-label">ชั้น</label>
                                    <input type="text" class="form-control" id="floorNumber" name="floor_number" autocomplete="off" value="{{ old('floor_number') }}">
                                    <div id="floorNumberError2"></div>
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

  function clearFloorForm(modalId) {
      $('#' + modalId + ' form').trigger("reset");
      closeModal(modalId);
      clearErrors();
  }

  function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'floor_number') {
                $('#floorNumberError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
        });
    }


  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(floors) {
    $('#floorTable tbody').empty();

    $.each(floors, function(index, floor) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + floor.floor_number + "</span></td>" +
            "<td>" +
              "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + floor.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + floor.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
            "</td>" +
          "</tr>";
            
        $('#floorTable tbody').append(row);
    });

    updateCount(floors.length);
  }

  function fetchFloors() {
    $.ajax({
      type: "GET",
      url: "{{ route('getFloors') }}",
      success: function(data) {
        updateTable(data.floors);
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

    fetchFloors();

    var table = $('#floorTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('floors') }}",
      columns: [
        { data: 'floor_number', name: 'floor_number'},
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

    $('#addFloorForm').submit(function(e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertFloor') }}",
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
                
                fetchFloors();
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

              clearFloorForm('addFloorModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });


    $('#floorTable').on('click', '.editData', function () {
        var floorId = $(this).data('id');

        $.ajax({
            url: "{{ route('getFloorById', ['id' => ':id']) }}".replace(':id', floorId),
            type: "GET",
            success: function(data) {
              $('#editFloorModal #id').val(data.floor.id);
              $('#editFloorModal #floorNumber').val(data.floor.floor_number);
              openModal('editFloorModal');
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editFloorForm').submit(function (e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);
        var floorId = formData.get('id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateFloor', ['id' => ':id']) }}".replace(':id', floorId),
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

                  fetchFloors();
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

                closeModal('editFloorModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#floorTable').on('click', '.deleteData', function () {
          var floorId = $(this).data('id');
        
          toastr.warning("ต้องการลบชั้นนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteFloor', ['id' => ':id']) }}".replace(':id', floorId),
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

                            fetchFloors();
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