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
                        ประเภทสมาชิก
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
          <h4>ประเภทสมาชิก</h4>
            <div class="add-product">
                <a href="#" onclick="openModal('addMemberTypeModal')">เพิ่มประเภทสมาชิก</a>
            </div>

          <table id="memberTypeTable">
            <thead>
              <tr>
                <th>ประเภทสมาชิก</th>
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


<!-- Add Member Type Modal -->
<div id="addMemberTypeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="addMemberTypeModalLabel">เพิ่มประเภทสมาชิก</h4>
            <button type="button" class="close" onclick="clearMemberTypeForm('addMemberTypeModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="addMemberTypeForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                  <label for="MemberType" class="form-label">ประเภทสมาชิก</label>
                                  <input type="text" class="form-control" id="memberType" name="member_type" autocomplete="off" value="{{ old('member_type') }}">
                                  <div id="memberTypeError"></div>
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

<!-- Edit Member Type Modal -->
<div id="editMemberTypeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editMemberTypeModalLabel">แก้ไขประเภทสมาชิก</h4>
            <button type="button" class="close" onclick="closeModal('editMemberTypeModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editMemberTypeForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                    <label for="MemberType" class="form-label">ประเภทสมาชิก</label>
                                    <input type="text" class="form-control" id="memberType" name="member_type" autocomplete="off" value="{{ old('member_type') }}">
                                    <div id="memberTypeError2"></div>
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

  function clearMemberTypeForm(modalId) {
    $('#' + modalId + ' form').trigger("reset");
    closeModal(modalId);
    clearErrors();
  }

  function handleValidationErrors(errors, action) {
    $.each(errors, function(key, value) {
        if (key === 'member_type') {
            $('#memberTypeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
    });
  }

  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(memberTypes) {
    $('#memberTypeTable tbody').empty();

    $.each(memberTypes, function(index, memberType) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + memberType.member_type + "</span></td>" +
            "<td>" +
              "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + memberType.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + memberType.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
            "</td>" +
          "</tr>";
            
        $('#memberTypeTable tbody').append(row);
    });

    updateCount(memberTypes.length);
  }

  function fetchMemberTypes() {
    $.ajax({
      type: "GET",
      url: "{{ route('getMemberTypes') }}",
      success: function(data) {
        updateTable(data.memberTypes);
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

    fetchMemberTypes();

    var table = $('#memberTypeTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('memberTypes') }}",
      columns: [
        { data: 'member_type', name: 'member_type'},
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

    $('#addMemberTypeForm').submit(function(e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertMemberType') }}",
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
                
                fetchMemberTypes();
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

              clearMemberTypeForm('addMemberTypeModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });


    $('#memberTypeTable').on('click', '.editData', function () {
        var memberTypeId = $(this).data('id');

        $.ajax({
            url: "{{ route('getMemberTypeById', ['id' => ':id']) }}".replace(':id', memberTypeId),
            type: "GET",
            success: function(data) {
              $('#editMemberTypeModal #id').val(data.memberType.id);
              $('#editMemberTypeModal #memberType').val(data.memberType.member_type);
              openModal('editMemberTypeModal');
            },
            error: function(error) {
              console.error('AJAX error:', error);
            }
        });
    });

    $('#editMemberTypeForm').submit(function (e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);
        var MemberTypeId = formData.get('id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateMemberType', ['id' => ':id']) }}".replace(':id', MemberTypeId),
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

                  fetchMemberTypes();
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

                closeModal('editMemberTypeModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#memberTypeTable').on('click', '.deleteData', function () {
          var memberTypeId = $(this).data('id');
        
          toastr.warning("ต้องการลบประเภทสมาชิกนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteMemberType', ['id' => ':id']) }}".replace(':id', memberTypeId),
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

                            fetchMemberTypes();
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