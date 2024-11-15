@extends('partials.master')

@section('content')

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <a href="#" onclick="openModal('addUserModal')" class="btn mb-1 btn-info fs-20">เพิ่มบัญชี</a>
        </ol>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><h3>ข้อมูลบัญชี <span class="label label-info data-count"></span></h3> </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id ="userTable">
                                <thead>
                                    <tr>
                                        <th>บัญชีผู้ใช้</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>อีเมล</th>
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



<!-- Add User Modal -->
<div id="addUserModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เพิ่มบัญชี</h3>
                <button type="button" class="close" onclick="clearForm('addUserModal')"><span>&times;</span>
                </button>
            </div>
            <form id="addUserForm" method="POST" action="javascript:void(0)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="username" class="form-label">ชื่อบัญชีผู้ใช้ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="userName" name="user_name" autocomplete="off" value="{{ old('user_name') }}">
                            <div id="userNameError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="password" class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="password" name="password" autocomplete="off" value="{{ old('password') }}">
                            <div id="passwordError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="firstName" class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name" autocomplete="off" value="{{ old('first_name') }}">
                            <div id="firstNameError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="lastName" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name" autocomplete="off" value="{{ old('last_name') }}">
                            <div id="lastNameError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" autocomplete="off" value="{{ old('email') }}">
                            <div id="emailError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('addUserModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">แก้ไขบัญชีผู้ใช้</h3>
                <button type="button" class="close" onclick="clearForm('editUserModal')"><span>&times;</span>
                </button>
            </div>
            <form id="editUserForm" method="POST" action="javascript:void(0)">
                @csrf
                <input type="hidden" id="userId" name="user_id">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="username" class="form-label">ชื่อบัญชีผู้ใช้ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="userName" name="user_name" autocomplete="off" value="{{ old('user_name') }}">
                            <div id="userNameError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="firstName" class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name" autocomplete="off" value="{{ old('first_name') }}">
                            <div id="firstNameError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="lastName" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name" autocomplete="off" value="{{ old('last_name') }}">
                            <div id="lastNameError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" autocomplete="off" value="{{ old('email') }}">
                            <div id="emailError2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('editUserModal')">ปิด</button>
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
        if (key === 'user_name') {
          $('#userNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'password') {
          $('#passwordError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'first_name') {
          $('#firstNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'last_name') {
          $('#lastNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'email') {
          $('#emailError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
    });
  }

  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(users) {
    $('#userTable tbody').empty();

    $.each(users, function(index, user) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + user.user_name + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + user.first_name + " " + user.last_name + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + user.email  + "</span></td>" +
            "<td>" +
                "<button class='btn mb-1 btn-warning editData mr-2' data-id='" + user.user_id + "'><i class='fa fa-pencil-square-o'></i></button>" +
                "<button class='btn mb-1 btn-danger deleteData' data-id='" + user.user_id + "'><i class='fa fa-trash-o'></i></button>"
              "</td>" +
          "</tr>";
            
        $('#userTable tbody').append(row);
    });

    updateCount(users.length);
  }

  function fetchUsers() {
    $.ajax({
      type: "GET",
      url: "{{ route('getUsers') }}",
      success: function(data) {
        updateTable(data.users);
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

    fetchUsers();

    $('#addUserForm').submit(function(e) {
        e.preventDefault();
        clearErrors();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('insertUser') }}",
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
                
                fetchUsers();
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

              clearForm('addUserModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            }
        })
    });

    $('#userTable').on('click', '.editData', function () {
        var userId = $(this).data('id');

        $.ajax({
            url: "{{ route('getUserById', ['id' => ':id']) }}".replace(':id', userId),
            type: "GET",
            success: function(data) {
              console.log(data);
                $('#editUserModal #userId').val(data.user.user_id);
                $('#editUserModal #userName').val(data.user.user_name);
                $('#editUserModal #firstName').val(data.user.first_name);
                $('#editUserModal #lastName').val(data.user.last_name);
                $('#editUserModal #email').val(data.user.email);
                openModal('editUserModal'); 
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    });

    $('#editUserForm').submit(function (e) {
        e.preventDefault();
        clearErrors();
        var formData = new FormData(this);
        var userId = formData.get('user_id');

        $.ajax({
            type: "POST",
            url: "{{ route('updateUser', ['id' => ':id']) }}".replace(':id', userId),
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

                  fetchUsers();
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

                closeModal('editUserModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#userTable').on('click', '.deleteData', function () {
          var userId = $(this).data('id');
        
          toastr.warning("ต้องการลบบัญชีผู้ใช้นี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteUser', ['id' => ':id']) }}".replace(':id', userId),
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

                            fetchUsers();
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