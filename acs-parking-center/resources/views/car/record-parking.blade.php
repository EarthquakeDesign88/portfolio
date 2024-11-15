@extends('layouts.master')

@section('content')

<style>
    .modal-content {
        max-width: 400px;
        margin: auto;
    }

    .badge-danger {
        background-color: #f30000 !important;
    }

    .pd-setting {
        background-color: #00af84 !important;
    }

    .button-container {
        text-align: right;
        margin-bottom: 20px;
    }

    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: #152036;
        opacity: 1;
    }

    #parkingPassCode2 {
        background-color: #337ab7 !important;
    }

    .margin-top-wrapper {
        margin-top: 20px;
    }

    .search-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    #searchInput {
        width: 200px;
        height: 40px;
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
                                            บัตรจอดรถ
                                            <span>
                                                <h2 class="data-count badge badge-primary"></h2>
                                            </span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <h4 style="color:#fff; margin-top: 10px;">เลือกวันที่เข้าจอด:</h4>
                                <input type="date" id="selectedDate">
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
                    <h4>
                        บัตรจอดรถ
                    </h4>
                    <div class="add-product">
                        <a href="#" onclick="openModal('addParkingRecordModal')">เพิ่มบัตรจอดรถ</a>
                    </div>
                    <div class="button-container">
                        <a href="{{ route('historyRecordManual') }}" class="pd-setting">ดูประวัติบัตรจอดรถ Manual</a>
                    </div>

                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="ค้นหา" autocomplete="off">
                    </div>

                    <table id="parkingRecordTable">
                        <thead>
                            <tr>
                                <th>รหัสบัตรจอดรถ</th>
                                <th>รหัสตราประทับ</th>
                                <th>จำนวนตราประทับ</th>
                                <th>เวลาเข้า</th>
                                <th>เวลาออก</th>
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


<div id="addParkingRecordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="companyModalLabel">เพิ่มบัตรจอดรถ</h4>
        </div>
        <div class="modal-body">
            <button type="button" class="close" onclick="clearRecordParkingForm('addParkingRecordModal')">&times;</button>
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{ asset('assets/img/parking/elephant_tower_new.jpg') }}" height="100" width="200">
                            <form id="addParkingRecordForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                    <label for="parkingPaSsCode" class="form-label">รหัสบัตรจอดรถ</label>
                                    <input type="text" class="form-control" id="parkingPassCode" name="parking_pass_code" autocomplete="off" maxlength="13">
                                    <div id="parkingPassCodeError"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="licensePlate" class="form-label">ป้ายทะเบียนรถ</label>
                                    <input type="text" class="form-control" id="licensePlate" name="license_plate" autocomplete="off" maxlength="10">
                                    <div id="licensePlateError"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="stampCode" class="form-label">รหัสตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCode" name="stamp_id" autocomplete="off">
                                    <div id="stampCodeError"></div>
                                </div>
                                <ul id="stamps"></ul>

                                <div class="mb-3">
                                    <label for="stampQty" class="form-label">จำนวนตราประทับ</label>
                                    <input type="number" class="form-control" id="stampQty" name="stamp_qty" autocomplete="off" min="0" max="6" maxlength="1">
                                    <div id="stampQtyError"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="carinDatetime">เวลาเข้า</label>
                                    <input type="datetime-local" class="form-control" id="carinDatetime" name="carin_datetime" step="1">
                                    <div id="carinDatetimeError"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="caroutDatetime">เวลาออก:</label>
                                    <input type="datetime-local" class="form-control" id="caroutDatetime" name="carout_datetime" step="1">
                                    <div id="caroutDatetimeError"></div>
                                </div>

                                <div class="mb-3">
                                    <label>วิธีการชำระเงิน:</label><br>
                                    <div class="row">
                                        @foreach($paymentMethods as $paymentMethod)
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <input type="radio" id="paymentMethod{{ $paymentMethod->id }}" name="payment_method_id" value="{{ $paymentMethod->id }}">
                                            <label for="paymentMethod{{ $paymentMethod->id }}">{{ $paymentMethod->payment_method }}</label>
                                        </div>
                                        @endforeach
                                        <div id="paymentMethodError"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="parkingFee">ยอดชำระทั้งสิ้น:</label>
                                    <input type="text" class="form-control" id="parkingFee" name="fee" autocomplete="off">
                                    <div id="parkingFeeError"></div>
                                </div>

                                <button type="submit" class="submitButton">ยืนยัน</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="editParkingRecordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editParkingRecordModalLabel">แก้ไขบัตรจอดรถ</h4>
        </div>
        <div class="modal-body">
            <button type="button" class="close" onclick="clearRecordParkingForm('editParkingRecordModal')">&times;</button>
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{ asset('assets/img/parking/elephant_tower_new.jpg') }}" height="100" width="200">
                            <form id="editParkingRecordForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                    <label for="parkingPaSsCode" class="form-label">รหัสบัตรจอดรถ</label>
                                    <input type="text" class="form-control" id="parkingPassCode2" name="parking_pass_code" autocomplete="off" maxlength="13" readonly>
                                    <div id="parkingPassCodeError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="licensePlate" class="form-label">ป้ายทะเบียนรถ</label>
                                    <input type="text" class="form-control" id="licensePlate2" name="license_plate" autocomplete="off" maxlength="10">
                                    <div id="licensePlateError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="stampCode" class="form-label">รหัสตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCode2" name="stamp_id" autocomplete="off">
                                    <div id="stampCodeError2"></div>
                                </div>
                                <ul id="stampsEdit"></ul>

                                <div class="mb-3">
                                    <label for="stampQty" class="form-label">จำนวนตราประทับ</label>
                                    <input type="number" class="form-control" id="stampQty2" name="stamp_qty" autocomplete="off" min="0" max="6" maxlength="1">
                                    <div id="stampQtyError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="carinDatetime">เวลาเข้า</label>
                                    <input type="datetime-local" class="form-control" id="carinDatetime2" name="carin_datetime" step="1" readonly>
                                    <div id="carinDatetimeError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="caroutDatetime">เวลาออก:</label>
                                    <input type="datetime-local" class="form-control" id="caroutDatetime2" name="carout_datetime" step="1">
                                    <div id="caroutDatetimeError2"></div>
                                </div>

                                <div class="mb-3">
                                    <label>วิธีการชำระเงิน:</label><br>
                                    <div class="row">
                                        @foreach($paymentMethods as $paymentMethod)
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <input type="radio" id="paymentMethod{{ $paymentMethod->id }}" name="payment_method_id" value="{{ $paymentMethod->id }}">
                                            <label for="paymentMethod{{ $paymentMethod->id }}">{{ $paymentMethod->payment_method }}</label>
                                        </div>
                                        @endforeach
                                        <div id="paymentMethodError2"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="parkingFee">ยอดชำระทั้งสิ้น:</label>
                                    <input type="text" class="form-control" id="parkingFee2" name="fee" autocomplete="off">
                                    <div id="parkingFeeError2"></div>
                                </div>

                                <button type="submit" class="submitButton">ยืนยัน</button>
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

        window.addEventListener('click', function(event) {
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

    function clearRecordParkingForm(modalId) {
        $('#' + modalId + ' form').trigger("reset");
        closeModal(modalId);
        clearErrors();
    }

    function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'parking_pass_code') {
                $('#parkingPassCodeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'license_plate') {
                $('#licensePlateError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'stamp_id') {
                $('#stampCodeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'stamp_qty') {
                $('#stampQtyError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'carin_datetime') {
                $('#carinDatetimeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'carout_datetime') {
                $('#caroutDatetimeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'payment_method_id') {
                $('#paymentMethodError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'fee') {
                $('#parkingFeeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            }
        });
    }


    function updateCount(count) {
        console.log("Updating count:", count);
        $('.data-count').text(count);
    }

    function updateTable(recordParkings) {
        $('#parkingRecordTable tbody').empty();

        $.each(recordParkings, function(index, recordParking) {
            var row = "<tr>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.parking_pass_code ? recordParking.parking_pass_code : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.stamp_code ? recordParking.stamp_code : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.stamp_qty ? recordParking.stamp_qty : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.carin_datetime ? recordParking.carin_datetime : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.carout_datetime ? recordParking.carout_datetime : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td>" +
                "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + recordParking.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
                "</td>" +
                "</tr>";

            $('#parkingRecordTable tbody').append(row);
        });

        updateCount(recordParkings.length);
    }

    function fetchRecordParking() {
        $.ajax({
            type: "GET",
            url: "{{ route('getRecordParkings') }}",
            success: function(data) {
                updateTable(data.recordParkings);
            },
            errror: function(error) {
                console.error('AJAX error:', error);
            }
        });
    }

    function setDefaultDate() {
        var today = new Date().toISOString().split('T')[0];
        $('#selectedDate').val(today);
    }


    function setCurrentDate() {
        var currentDate = new Date();
        var currentDay = currentDate.getDate().toString().padStart(2, '0');
        var currentYear = currentDate.getFullYear();
        var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');

        var formattedCurrentDate = currentYear + '-' + currentMonth + '-' + currentDay;
        $('#selectedDate').val(formattedCurrentDate);
    }



    function fetchTableData(search = '', selectedDate = '') {
        var table = $('#parkingRecordTable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            searching: false,
            lengthMenu: [
                [50, 100, 200, 500],
                [50, 100, 200, 500]
            ],
            pageLength: 100,
            ajax: {
                url: "{{ route('recordParking') }}",
                data: {
                    search: search,
                    selectedDate: selectedDate,
                }
            },
            columns: [{
                    data: 'parking_pass_code',
                    name: 'parking_pass_code',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่พบข้อมูล';
                    }
                },
                {
                    data: 'stamp_code',
                    name: 'stamp_code',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่พบข้อมูล';
                    }
                },
                {
                    data: 'stamp_qty',
                    name: 'stamp_qty',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่พบข้อมูล';
                    }
                },
                {
                    data: 'carin_datetime',
                    name: 'carin_datetime',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่พบข้อมูล';
                    }
                },
                {
                    data: 'carout_datetime',
                    name: 'carout_datetime',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่พบข้อมูล';
                    }
                },
                {
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + data + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>";
                    }
                }
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
            drawCallback: function(settings) {
                updateCount(settings.json.recordsTotal);
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables Ajax error:', error);
            }
        });
    }


    $("document").ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        setDefaultDate();
        fetchTableData('', $('#selectedDate').val());

        // Event listener for date change
        $('#selectedDate').change(function() {
            fetchTableData($('#searchInput').val(), $(this).val());
        });

        // Event listener for search input
        $('#searchInput').keyup(function() {
            fetchTableData($(this).val(), $('#selectedDate').val());
        });


        var selectedStamp;
        var selectedStampEdit;

        $('#stampCode').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('searchStamps') }}",
                    method: "GET",
                    data: {
                        stamp_code: query
                    },
                    success: function(data) {
                        displaySearchData(data, 'stamps');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('stamps');
            }
        });

        $('#stampCode2').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('searchStamps') }}",
                    method: "GET",
                    data: {
                        stamp_code: query
                    },
                    success: function(data) {
                        displaySearchData(data, 'stampsEdit');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('stampsEdit');
            }
        });


        function displaySearchData(keyword, type) {
            var searchResults = $('#' + type);
            searchResults.empty();


            if (keyword.length === 0) {
                var noResultsMessage = $('<li>').addClass('custom-list-item text-error').text('ไม่พบข้อมูลนี้ในระบบ');
                searchResults.append(noResultsMessage);
            } else {
                keyword.forEach(function(key) {
                    var listItem;

                    if (type === 'stamps') {
                        var listItem = $('<li>').addClass('custom-list-item').text(key.stamp_code);
                        listItem.click(function() {
                            $('#stampCode').val(key.stamp_code);
                            selectedStamp = key.id;

                            hideSearchData('stamps');
                        });
                    } else if (type === 'stampsEdit') {
                        var listItem = $('<li>').addClass('custom-list-item').text(key.stamp_code);
                        listItem.click(function() {
                            $('#stampCode2').val(key.stamp_code);
                            selectedStampEdit = key.id;

                            hideSearchData('stampsEdit');
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

        $('#addParkingRecordForm').submit(function(e) {
            clearErrors();
            e.preventDefault();

            var formData = new FormData(this);

            if (selectedStamp !== undefined && selectedStamp !== '') {
                formData.append('stamp_id', selectedStamp);
            }

            $.ajax({
                type: "POST",
                url: "{{ route('insertRecordParking') }}",
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

                        fetchRecordParking();
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

                    clearRecordParkingForm('addParkingRecordModal');
                },
                error: function(resp) {
                    if (resp.status === 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'add');
                    }
                },
                complete: function() {
                    hideSearchData('stamps');
                }
            })
        });


        $('#parkingRecordTable').on('click', '.editData', function() {
            var parkingRecordId = $(this).data('id');


            $.ajax({
                url: "{{ route('getParkingRecordById', ['id' => ':id']) }}".replace(':id', parkingRecordId),
                type: "GET",
                success: function(data) {
                    $('#editParkingRecordModal #id').val(data.parkingRecord.id);
                    $('#editParkingRecordModal #parkingPassCode2').val(data.parkingRecord.parking_pass_code);
                    $('#editParkingRecordModal #licensePlate2').val(data.parkingRecord.license_plate);
                    $('#editParkingRecordModal #stampCode2').val(data.parkingRecord.stamp_code);
                    $('#editParkingRecordModal #stampQty2').val(data.parkingRecord.stamp_qty);
                    $('#editParkingRecordModal #carinDatetime2').val(data.parkingRecord.carin_datetime);
                    $('#editParkingRecordModal #caroutDatetime2').val(data.parkingRecord.carout_datetime);
                    $('#editParkingRecordModal #parkingFee2').val(data.parkingRecord.fee);

                    var selectedPaymentMethodId = data.parkingRecord.payment_method_id;
                    if (selectedPaymentMethodId) {
                        $('input[name="payment_method_id"][value="' + selectedPaymentMethodId + '"]').prop('checked', true);
                    }

                    selectedStampEdit = data.parkingRecord.stamp_id;

                    openModal('editParkingRecordModal');
                },
                error: function(error) {
                    console.error('AJAX error:', error);
                }
            });
        });


        $('#editParkingRecordForm').submit(function(e) {
            clearErrors();
            e.preventDefault();
            var formData = new FormData(this);
            var parkingRecordId = formData.get('id');

            if (selectedStampEdit !== undefined && selectedStampEdit !== '') {
                formData.append('stamp_id', selectedStampEdit);
            }

            $.ajax({
                type: "POST",
                url: "{{ route('updateRecordParking', ['id' => ':id']) }}".replace(':id', parkingRecordId),
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(resp) {
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

                        fetchRecordParking();
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

                    closeModal('editParkingRecordModal');
                },
                error: function(resp) {
                    if (resp.status == 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'edit');
                    }
                }
            });
        });

    });
</script>


@endsection