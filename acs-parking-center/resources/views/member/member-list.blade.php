@extends('layouts.master')

@section('content')
<style>
  #memberTable th:nth-child(3), #memberTable td:nth-child(3), {
    width: 150px; /* Adjust the width as needed */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#memberTable .toggle-status {
    display: inline-block;
    width: 100%;
    box-sizing: border-box;
    text-align: center;
    padding: 5px 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Background color on hover */
.toggle-status:hover {
    background-color: #02F0B5; 
}

.ds-setting:hover {
    background-color: #e12503; 
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
                    <h2>สมาชิก
                      <span><h2 class="data-count badge badge-primary"></h2></span>
                    </h2>
                    <button id="exportButton" class="main-button">ดาวน์โหลดข้อมูลสมาชิก</button>
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
          <h4>สมาชิก</h4>
          <div class="add-product">
                <a href="#" onclick="openModal('addMemberModal')">เพิ่มสมาชิก</a>
            </div>

          <table id="memberTable">
            <thead>
              <tr>
                <th>รหัสสมาชิก</th>
                <th>ชื่อสมาชิก</th>
                <th>ป้ายทะเบียน</th>
                <th>บริษัท</th>
                <th>สถานที่</th>
                <th>สถานะ</th>
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


<!-- Add Member Modal -->
<div id="addMemberModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="addMemberModalLabel">เพิ่มสมาชิก</h4>
            <button type="button" class="close" onclick="clearMemberForm('addMemberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="addMemberForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstName" class="form-label">ชื่อจริง</label>
                                        <input type="text" class="form-control" id="firstName" name="first_name" autocomplete="off" value="{{ old('first_name') }}">
                                        <div id="firstNameError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="lastName" name="last_name" autocomplete="off" value="{{ old('last_name') }}">
                                        <div id="lastNameError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="memberCode" class="form-label">รหัสสมาชิก</label>
                                        <input type="text" class="form-control" id="memberCode" name="member_code" autocomplete="off" value="{{ old('member_code') }}" maxlength="10">
                                        <div id="memberCodeError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                        <input type="text" class="form-control" id="phone" name="phone" autocomplete="off" value="{{ old('phone') }}" maxlength="10">
                                        <div id="phoneError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                      <label for="companyName" class="form-label">บริษัท</label>
                                      <input type="text" class="form-control" id="companyName" name="company_id" autocomplete="off" value="{{ old('company_id') }}">

                                      <ul id="companies"></ul>
                                      <div id="companyNameError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                      <label for="idCard" class="form-label">เลขบัตรประชาชน</label>
                                      <input type="text" class="form-control" id="idCard" name="id_card" autocomplete="off" value="{{ old('id_card') }}" maxlength="13">

                                      <div id="idCardError"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="placeName" class="form-label">สถานที่</label>
                                        <select class="form-control" id="placeName" name="place_id">
                                            <option value="" selected disabled>กรุณาเลือกสถานที่</option>
                                            @foreach($places as $place)
                                                <option value="{{ $place->id }}">{{ $place->place_name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="placeNameError"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="memberType" class="form-label">ประเภทสมาชิก</label>
                                        <select class="form-control" id="memberType" name="member_type_id">
                                            <option value="" selected disabled>กรุณาเลือกประเภทสมาชิก</option>
                                            @foreach($memberTypes as $memberType)
                                                <option value="{{ $memberType->id }}">{{ $memberType->member_type }}</option>
                                            @endforeach
                                        </select>
                                        <div id="memberTypeError"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="licenseDriver" class="form-label">เลขใบขับขี่รถยนต์</label>
                                        <input type="text" class="form-control" id="licenseDriver" name="license_driver" autocomplete="off" value="{{ old('license_driver') }}">
                                        <div id="licenseDriverError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="LicensePlate" class="form-label">ทะเบียนรถยนต์</label>
                                        <input type="text" class="form-control" id="LicensePlate" name="license_plate" autocomplete="off" value="{{ old('license_plate') }}">
                                        <div id="licensePlateError"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="issueDate" class="form-label">วันที่ออกบัตรสมาชิก</label>
                                        <input type="date" class="form-control" id="issueDate" name="issue_date" value="{{ old('issue_date') }}">
                                        <div id="issueDateError"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expiryDate" class="form-label">วันที่หมดอายุสมาชิก</label>
                                        <input type="date" class="form-control" id="expiryDate" name="expiry_date" value="{{ old('expiry_date') }}">
                                        <div id="expiryDateError"></div>
                                    </div>
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

<!-- Edit Member Modal -->
<div id="editMemberModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editMemberModalLabel">แก้ไขสมาชิก</h4>
            <button type="button" class="close" onclick="closeModal('editMemberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editMemberForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">
                                <div class="row">
                                <div class="col-md-6 mb-3">
                                        <label for="firstName" class="form-label">ชื่อจริง</label>
                                        <input type="text" class="form-control" id="firstName" name="first_name" autocomplete="off" value="{{ old('first_name') }}">
                                        <div id="firstNameError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="lastName" name="last_name" autocomplete="off" value="{{ old('last_name') }}">
                                        <div id="lastNameError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="memberCode" class="form-label">รหัสสมาชิก</label>
                                        <input type="text" class="form-control" id="memberCode" name="member_code" autocomplete="off" value="{{ old('member_code') }}">
                                        <div id="memberCodeError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                        <input type="text" class="form-control" id="phone" name="phone" autocomplete="off" value="{{ old('phone') }}" maxlength="10">
                                        <div id="phoneError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                      <label for="companyName" class="form-label">บริษัท</label>
                                      <input type="text" class="form-control" id="companyName2" name="company_id" autocomplete="off" value="{{ old('company_id') }}">

                                      <ul id="companiesEdit"></ul>
                                      <div id="companyNameError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                      <label for="idCard" class="form-label">เลขบัตรประชาชน</label>
                                      <input type="text" class="form-control" id="idCard" name="id_card" autocomplete="off" value="{{ old('id_card') }}" maxlength="13">

                                      <div id="idCardError2"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="placeName" class="form-label">สถานที่</label>
                                        <select class="form-control" id="placeName" name="place_id">
                                            <option value="" selected disabled>กรุณาเลือกสถานที่</option>
                                            @foreach($places as $place)
                                                <option value="{{ $place->id }}">{{ $place->place_name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="placeNameError2"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="memberType" class="form-label">ประเภทสมาชิก</label>
                                        <select class="form-control" id="memberType" name="member_type_id">
                                            <option value="" selected disabled>กรุณาเลือกประเภทสมาชิก</option>
                                            @foreach($memberTypes as $memberType)
                                                <option value="{{ $memberType->id }}">{{ $memberType->member_type }}</option>
                                            @endforeach
                                        </select>
                                        <div id="memberTypeError2"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="licenseDriver" class="form-label">เลขใบขับขี่รถยนต์</label>
                                        <input type="text" class="form-control" id="licenseDriver" name="license_driver" autocomplete="off" value="{{ old('license_driver') }}" maxlength="13">
                                        <div id="licenseDriverError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="LicensePlate" class="form-label">ทะเบียนรถยนต์</label>
                                        <input type="text" class="form-control" id="licensePlate" name="license_plate" autocomplete="off" value="{{ old('license_plate') }}">
                                        <div id="licensePlateError2"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="issueDate" class="form-label">วันที่ออกบัตรสมาชิก</label>
                                        <input type="date" class="form-control" id="issueDate" name="issue_date" value="{{ old('issue_date') }}">
                                        <div id="issueDateError2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expiryDate" class="form-label">วันที่หมดอายุสมาชิก</label>
                                        <input type="date" class="form-control" id="expiryDate" name="expiry_date" value="{{ old('expiry_date') }}">
                                        <div id="expiryDateError2"></div>
                                    </div>
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

<!-- View Member Modal -->
<div id="viewMemberModal" class="modal">
  <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="viewMemberModalLabel">รายละเอียดสมาชิก</h4>
        <button type="button" class="close" onclick="closeModal('viewMemberModal')">&times;</button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>ชื่อ-นามสกุล:</strong> <span id="firstName"></span> <span id="lastName"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>รหัสสมาชิก:</strong> <span id="memberCode"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>เบอร์โทรศีพท์:</strong> <span id="phone"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>สถานะสมาชิก:</strong> <span id="memberStatus"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>เลขบัตรประชาชน:</strong> <span id="idCard"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>ประเภทสมาชิก:</strong> <span id="memberType"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>บริษัท:</strong> <span id="companyName"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>สถานที่:</strong> <span id="placeName"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>ทะเบียนรถยนต์:</strong> <span id="licensePlate"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>เลขใบขับขี่รถยนต์:</strong> <span id="licenseDriver"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>วันที่ออกบัตรสมาชิก:</strong> <span id="issueDate"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>วันที่หมดอายุสมาชิก:</strong> <span id="expiryDate"></span></p>
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

  function clearMemberForm(modalId) {
    $('#' + modalId + ' form').trigger("reset");
    closeModal(modalId);
    clearErrors();
  }

  function handleValidationErrors(errors, action) {
    $.each(errors, function(key, value) {
        if (key === 'first_name') {
          $('#firstNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'last_name') {
          $('#lastNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'member_code') {
          $('#memberCodeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'phone') {
          $('#phoneError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'company_id') {
          $('#companyNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'id_card') {
          $('#idCardError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'place_id') {
          $('#placeNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'member_type_id') {
          $('#memberTypeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'license_driver') {
          $('#licenseDriverError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'license_plate') {
          $('#licensePlateError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'issue_date') {
          $('#issueDateError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
        else if (key === 'expiry_date') {
          $('#expiryDateError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
        } 
    });
  }

  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(members) {
    $('#memberTable tbody').empty();

    $.each(members, function(index, member) {
      var memberStatusClass = member.member_status === 'active' ? 'pd-setting' : 'ds-setting';
      var memberStatusLabel = member.member_status === 'active' ? 'ใช้งานอยู่' : 'ไม่ได้ใช้งาน';
      var memberFullName = member.first_name + " " + member.last_name;

        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + member.member_code + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + memberFullName + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + member.license_plate + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + member.company_name + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + member.place_name + "</span></td>" +
            "<td data-label=''><button class='" + memberStatusClass + "'>" + memberStatusLabel + "</button></td>" +
            "<td>" +
              "<button data-toggle='tooltip' title='ดู' class='pd-setting-ed viewData' data-id='" + member.id + "'><i class='fa fa fa-eye' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + member.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
              "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + member.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
            "</td>" +
          "</tr>";
            
        $('#memberTable tbody').append(row);
    });

    updateCount(members.length);
  }

  function fetchMembers() {
    $.ajax({
      type: "GET",
      url: "{{ route('getMembers') }}",
      success: function(data) {
        updateTable(data.members);
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

    fetchMembers();

    var table = $('#memberTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('memberList') }}",
      columns: [
        { data: 'member_code', name: 'member_code' },
        { data: 'member_name', name: 'member_name'},
        { data: 'license_plate', name: 'license_plate' },
        { data: 'company_name', name: 'company_name' },
        { data: 'place_name', name: 'place_name' },
        {
            data: 'member_status',
            name: 'member_status',
            render: function (data, type, row) {
                var statusClass = (data === 'active') ? 'pd-setting' : 'ds-setting';
                var statusLabel = (data === 'active') ? 'ใช้งานอยู่' : 'ไม่ได้ใช้งาน';
                return "<button class='toggle-status " + statusClass + "' data-id='" + row.id + "'>" + statusLabel + "</button>";
            }
        },
        {
            data: 'id',
            name: 'action',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return "<button data-toggle='tooltip' title='ดู' class='pd-setting-ed viewData' data-id='" + data + "'><i class='fa fa-eye' aria-hidden='true' ></i></button>" +
                "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + data + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
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

    var selectedCompany;

    $('#companyName').keyup(function () {
        var query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: "{{ route('searchCompanies') }}",
                method: "GET",
                data: { company_name: query },
                success: function (data) {
                    displaySearchData(data, 'companies');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            hideSearchData('companies');
        }
      });

      $('#companyName2').keyup(function () {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: "{{ route('searchCompanies') }}",
                    method: "GET",
                    data: { company_name: query },
                    success: function (data) {
                        displaySearchData(data, 'companiesEdit');
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('companiesEdit');
            }
        });


      function displaySearchData(keyword, type) {
          var searchResults = $('#' + type);
          searchResults.empty();


          if(keyword.length === 0) {
              var noResultsMessage = $('<li>').addClass('custom-list-item text-error').text('ไม่พบข้อมูลนี้ในระบบ');
              searchResults.append(noResultsMessage);
          }
          else {
              keyword.forEach(function(key) {
              var listItem;
              
              if (type === 'companies') {
                  listItem = $('<li>').addClass('custom-list-item').text(key.company_name);
                  listItem.click(function () {
                      $('#companyName').val(key.company_name);
                      selectedCompany = key.id;
                      
                      hideSearchData('companies');
                  });
              }
              else if (type === 'companiesEdit') {
                  listItem = $('<li>').addClass('custom-list-item').text(key.company_name);
                  listItem.click(function () {
                      $('#companyName2').val(key.company_name);
                      selectedCompanyEdit = key.id;

                      hideSearchData('companiesEdit');
                  });
              }

                  searchResults.append(listItem);
              });
          }

          searchResults.fadeIn();
      }

      function hideSearchData(type) {
          $('#' + type).fadeOut();
      }



    $('#addMemberForm').submit(function(e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);

        if (selectedCompany !== undefined && selectedCompany !== '') {
          formData.append('company_id', selectedCompany);
        }

        $.ajax({
            type: "POST",
            url: "{{ route('insertMember') }}",
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
                
                fetchMembers();
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

              clearMemberForm('addMemberModal'); 
            },
            error: function(resp) {
              if (resp.status === 422) {
                  var errors = resp.responseJSON.errors;
                  handleValidationErrors(errors , 'add');
              }
            },
            complete: function () {
              hideSearchData('companies');
            }
        })
    });

    $('#memberTable').on('click', '.viewData', function () {
        var memberId = $(this).data('id');

        $.ajax({
            url: "{{ route('getMemberById', ['id' => ':id']) }}".replace(':id', memberId),
            type: "GET",
            success: function(data) {
              $('#viewMemberModal #id').text(data.member.id);
              $('#viewMemberModal #firstName').text(data.member.first_name);
              $('#viewMemberModal #lastName').text(data.member.last_name);
              $('#viewMemberModal #memberCode').text(data.member.member_code);
              $('#viewMemberModal #phone').text(data.member.phone);
              var memberStatusLabel = data.member.member_status === 'active' ? 'ใช้งานอยู่' : 'ไม่ได้ใช้งาน';
              $('#viewMemberModal #memberStatus').text(memberStatusLabel);
              $('#viewMemberModal #companyName').text(data.member.company_name);
              $('#viewMemberModal #idCard').text(data.member.id_card);
              $('#viewMemberModal #placeName').text(data.member.place_name);
              $('#viewMemberModal #memberType').text(data.member.member_type);
              $('#viewMemberModal #licenseDriver').text(data.member.license_driver);
              $('#viewMemberModal #licensePlate').text(data.member.license_plate);
              $('#viewMemberModal #issueDate').text(data.member.issue_date);
              $('#viewMemberModal #expiryDate').text(data.member.expiry_date);


              openModal('viewMemberModal');
            },
            error: function(error) {
              console.error('AJAX error:', error);
            }
        });
    });


    $('#memberTable').on('click', '.toggle-status', function() {
      var memberId = $(this).data('id');
      var $button = $(this);

      
      toastr.warning("ต้องการเปลี่ยนสถานะสมาชิกนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmChange'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmChange').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                    type: 'POST',
                    url: "{{ route('toggleMemberStatus') }}",
                    data: { id: memberId },
                    success: function(resp) {
                      var message = resp.message;

                      if (resp.status == "success") {
                        var newStatus = resp.new_status;
                        var statusClass = (newStatus === 'active') ? 'pd-setting' : 'ds-setting';
                        var statusLabel = (newStatus === 'active') ? 'ใช้งานอยู่' : 'ไม่ได้ใช้งาน';
                        
                        $button.removeClass('pd-setting ds-setting').addClass(statusClass).text(statusLabel);

                        Command: toastr["success"](message, null, {
                          "showMethod": "slideDown",
                          "hideMethod": "slideUp",
                          "timeOut": 3000,
                          "extendedTimeOut": 1000,
                          "positionClass": "toast-top-right",
                          "progressBar": true,
                          "toastClass": "custom-toast"
                        });
                      }
                      else {
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
                    },
                    error: function(xhr, status, error) {
                      console.error('AJAX error:', status, error);
                    }
                  });

              });
          },
          toastClass: 'custom-toast'
        });

    });


    $('#memberTable').on('click', '.editData', function () {
        var memberId = $(this).data('id');

        $.ajax({
            url: "{{ route('getMemberById', ['id' => ':id']) }}".replace(':id', memberId),
            type: "GET",
            success: function(data) {
              $('#editMemberModal #id').val(data.member.id);
              $('#editMemberModal #firstName').val(data.member.first_name);
              $('#editMemberModal #lastName').val(data.member.last_name);
              $('#editMemberModal #memberCode').val(data.member.member_code);
              $('#editMemberModal #phone').val(data.member.phone);
              $('#editMemberModal #companyName2').val(data.member.company_name);
              $('#editMemberModal #idCard').val(data.member.id_card);
              $('#editMemberModal #placeName').val(data.member.place_id);
              $('#editMemberModal #memberType').val(data.member.member_type_id);
              $('#editMemberModal #licenseDriver').val(data.member.license_driver);
              $('#editMemberModal #licensePlate').val(data.member.license_plate);
              $('#editMemberModal #issueDate').val(data.member.issue_date);
              $('#editMemberModal #expiryDate').val(data.member.expiry_date);

              selectedCompanyEdit = data.member.company_id;

              openModal('editMemberModal');
            },
            error: function(error) {
              console.error('AJAX error:', error);
            }
        });
    });

    $('#editMemberForm').submit(function (e) {
        clearErrors();
        e.preventDefault();
        var formData = new FormData(this);
        var MemberId = formData.get('id');

        if (selectedCompanyEdit !== undefined && selectedCompanyEdit !== '') {
          formData.append('company_id', selectedCompanyEdit);
        }


        $.ajax({
            type: "POST",
            url: "{{ route('updateMember', ['id' => ':id']) }}".replace(':id', MemberId),
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

                  fetchMembers();
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

                closeModal('editMemberModal');
            },
            error: function(resp) {
                if (resp.status == 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors, 'edit');
                }
            }
        });
    });

    $('#memberTable').on('click', '.deleteData', function () {
          var memberId = $(this).data('id');
        
          toastr.warning("ต้องการลบสมาชิกนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
          closeButton: true,
          positionClass: 'toast-top-right',
          timeOut: 0,
          onShown: function (toast) {
              $('#confirmDelete').click(function () {
                  toastr.clear(toast);

                  $.ajax({
                      url: "{{ route('deleteMember', ['id' => ':id']) }}".replace(':id', memberId),
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

                            fetchMembers();
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

    $(document).on('click', '#exportButton', function() {
      $('#exportButton').prop('disabled', true);
      $('#exportButton').html('<i class="fa fa-spinner fa-spin"></i> กำลังดาวน์โหลด...');


      window.location.href = "{{ route('exportMemberList') }}";

      setTimeout(function() {
          $('#exportButton').prop('disabled', false);
          $('#exportButton').html('ดาวน์โหลดรายงาน');
          
          toastr["success"]("ดาวน์โหลดเสร็จสิ้น", null, {
              "showMethod": "slideDown",
              "hideMethod": "slideUp",
              "timeOut": 3000,
              "extendedTimeOut": 1000,
              "positionClass": "toast-top-right",
              "progressBar": true,
              "toastClass": "custom-toast"
          });
      }, 1000); 
  });



  });
</script>

@endsection