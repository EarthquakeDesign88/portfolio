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
                                            ตั้งค่าบริษัท
                                            <span>
                                                <h2 class="data-count badge badge-primary"></h2>
                                            </span>
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
                    <h4> ตั้งค่าบริษัท</h4>
                    <div class="add-product">
                        <a href="#" onclick="openModal('companySetupModal')">ตั้งค่าบริษัท</a>
                    </div>

                    <table id="companySetupTable">
                        <thead>
                            <tr>
                                <th>บริษัท</th>
                                <th>ชั้น</th>
                                <th>สถานที่</th>
                                <th>รหัสตราประทับ</th>
                                <th>เงื่อนไข</th>
                                <th>จำนวนโควต้า</th>
                                <th>โควต้าคงเหลือ</th>
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


<!-- Company Setup Modal -->
<div id="companySetupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="companyModalLabel">ตั้งค่าบริษัท</h4>
            <button type="button" class="close" onclick="clearCompanySetupForm('companySetupModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="companySetupForm" method="post" action="javascript:void(0)">
                                @csrf
                                <div class="mb-3">
                                    <label for="companyName" class="form-label">บริษัท</label>
                                    <input type="text" class="form-control" id="companyName" name="company_id" autocomplete="off">
                                    <div id="companyNameError"></div>
                                </div>
                                <ul id="companies"></ul>

                                <div class="mb-3">
                                    <label for="stampCode" class="form-label">รหัสตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCode" name="stamp_id" autocomplete="off">
                                    <div id="stampCodeError"></div>
                                </div>
                                <ul id="stamps"></ul>

                                <div class="mb-3">
                                    <label for="placeName" class="form-label">สถานที่</label>
                                    <input type="text" class="form-control" id="placeName" name="place_id" autocomplete="off">
                                    <div id="placeNameError"></div>
                                </div>
                                <ul id="places"></ul>

                                <div class="mb-3">
                                    <label for="floorNumber" class="form-label">ชั้น</label>
                                    <input type="text" class="form-control" id="floorNumber" name="floor_id" autocomplete="off">
                                    <div id="floorNumberError"></div>
                                </div>
                                <ul id="floors"></ul>

                                <div class="mb-3">
                                    <label for="quota" class="form-label">จำนวนโควต้า</label>
                                    <input type="text" class="form-control" id="quota" name="total_quota" autocomplete="off">
                                    <div id="quotaError"></div>
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

<!-- Edit Company Setup Modal -->
<div id="editCompanySetupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="editCompanySetupModalLabel">ตั้งค่าบริษัท</h4>
            <button type="button" class="close" onclick="closeModal('editCompanySetupModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <form id="editCompanySetupForm" method="post" action="javascript:void(0)">
                                @csrf
                                <input type="hidden" id="id" name="id">

                                <div class="mb-3">
                                    <label for="companyName" class="form-label">บริษัท</label>
                                    <input type="text" class="form-control" id="companyName2" name="company_id" autocomplete="off">
                                    <div id="companyNameError2"></div>
                                </div>
                                <ul id="companiesEdit"></ul>

                                <div class="mb-3">
                                    <label for="stampCode" class="form-label">รหัสตราประทับ</label>
                                    <input type="text" class="form-control" id="stampCode2" name="stamp_id" autocomplete="off">
                                    <div id="stampCodeError2"></div>
                                </div>
                                <ul id="stampsEdit"></ul>

                                <div class="mb-3">
                                    <label for="placeName" class="form-label">สถานที่</label>
                                    <input type="text" class="form-control" id="placeName2" name="place_id" autocomplete="off">
                                    <div id="placeNameError2"></div>
                                </div>
                                <ul id="placesEdit"></ul>

                                <div class="mb-3">
                                    <label for="floorNumber" class="form-label">ชั้น</label>
                                    <input type="text" class="form-control" id="floorNumber2" name="floor_id" autocomplete="off">
                                    <div id="floorNumberError2"></div>
                                </div>
                                <ul id="floorsEdit"></ul>

                                <div class="mb-3">
                                    <label for="quota" class="form-label">จำนวนโควต้า</label>
                                    <input type="text" class="form-control" id="quota2" name="total_quota" autocomplete="off">
                                    <div id="quotaError2"></div>
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

    function clearCompanySetupForm(modalId) {
        $('#' + modalId + ' form').trigger("reset");
        closeModal(modalId);
        clearErrors();
    }

    function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'company_id') {
                $('#companyNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'stamp_id') {
                $('#stampCodeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'place_id') {
                $('#placeNameError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'floor_id') {
                $('#floorNumberError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } else if (key === 'total_quota') {
                $('#quotaError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            }
        });
    }


    function updateCount(count) {
        $('.data-count').text(count);
    }

    function updateTable(companySetups) {
        $('#companySetupTable tbody').empty();

        $.each(companySetups, function(index, companySetup) {
            var row = "<tr>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.company_name + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.floor_number + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.place_name + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.stamp_code + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.stamp_condition + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.total_quota + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + companySetup.remaining_quota + "</span></td>" +
                "<td>" +
                "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + companySetup.id + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
                "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + companySetup.id + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
                "<button data-toggle='tooltip' title='รีโควต้า' class='pd-setting-ed reQuota' data-id='" + companySetup.id + "'><i class='fa fa-refresh' aria-hidden='true' ></i></button>" +
                "</td>" +
                "</tr>";

            $('#companySetupTable tbody').append(row);
        });

        updateCount(companySetups.length);
    }

    function fetchCompanySetup() {
        $.ajax({
            type: "GET",
            url: "{{ route('getCompanySetup') }}",
            success: function(data) {
                updateTable(data.companySetups);
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

        fetchCompanySetup();

        var table = $('#companySetupTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('companySetup') }}",
            columns: [{
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'floor_number',
                    name: 'floor_number'
                },
                {
                    data: 'place_name',
                    name: 'place_name'
                },
                {
                    data: 'stamp_code',
                    name: 'stamp_code'
                },
                {
                    data: 'stamp_condition',
                    name: 'stamp_condition'
                },
                {
                    data: 'total_quota',
                    name: 'total_quota'
                },
                {
                    data: 'remaining_quota',
                    name: 'remaining_quota'
                },
                {
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return "<button data-toggle='tooltip' title='แก้ไข' class='pd-setting-ed editData' data-id='" + data + "'><i class='fa fa-pencil-square-o' aria-hidden='true' ></i></button>" +
                            "<button data-toggle='tooltip' title='ลบ' class='pd-setting-ed deleteData' data-id='" + data + "'><i class='fa fa-trash-o' aria-hidden='true' ></i></button>" +
                            "<button data-toggle='tooltip' title='รีโควต้า' class='pd-setting-ed reQuota' data-id='" + data + "'><i class='fa fa-refresh' aria-hidden='true' ></i></button>";
                    }
                }
            ],
            lengthMenu: [
                [50, 100, 200, 500],
                [50, 100, 200, 500]
            ],
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
        var selectedStamp;
        var selectedFloor;
        var selectedPlace;

        var selectedCompanyEdit;
        var selectedStampEdit;
        var selectedFloorEdit;
        var selectedPlaceEdit;

        $('#companyName').keyup(function() {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: "{{ route('searchCompanies') }}",
                    method: "GET",
                    data: {
                        company_name: query
                    },
                    success: function(data) {
                        displaySearchData(data, 'companies');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('companies');
            }
        });

        $('#companyName2').keyup(function() {
            var query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: "{{ route('searchCompanies') }}",
                    method: "GET",
                    data: {
                        company_name: query
                    },
                    success: function(data) {
                        displaySearchData(data, 'companiesEdit');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('companiesEdit');
            }
        });

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

        $('#floorNumber').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('searchFloors') }}",
                    method: "GET",
                    data: {
                        floor_number: query
                    },
                    success: function(data) {
                        displaySearchData(data, 'floors');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('floors');
            }
        });

        $('#floorNumber2').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('searchFloors') }}",
                    method: "GET",
                    data: {
                        floor_number: query
                    },
                    success: function(data) {
                        displaySearchData(data, 'floorsEdit');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('floorsEdit');
            }
        });

        $('#placeName').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('searchPlaces') }}",
                    method: "GET",
                    data: {
                        place_name: query
                    },
                    success: function(data) {
                        console.log('data');
                        displaySearchData(data, 'places');
                    },
                    error: function(xhr, status, error) {
                        console.log('No results found.');
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData();
            }
        });

        $('#placeName2').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "{{ route('searchPlaces') }}",
                    method: "GET",
                    data: {
                        place_name: query
                    },
                    success: function(data) {
                        console.log('data');
                        displaySearchData(data, 'placesEdit');
                    },
                    error: function(xhr, status, error) {
                        console.log('No results found.');
                        console.error(xhr.responseText);
                    }
                });
            } else {
                hideSearchData('placesEdit');
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

                    if (type === 'companies') {
                        listItem = $('<li>').addClass('custom-list-item').text(key.company_name);
                        listItem.click(function() {
                            $('#companyName').val(key.company_name);
                            selectedCompany = key.id;

                            hideSearchData('companies');
                        });
                    } else if (type === 'companiesEdit') {
                        listItem = $('<li>').addClass('custom-list-item').text(key.company_name);
                        listItem.click(function() {
                            $('#companyName2').val(key.company_name);
                            selectedCompanyEdit = key.id;

                            hideSearchData('companiesEdit');
                        });
                    } else if (type === 'stamps') {
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
                    } else if (type === 'floors') {
                        var listItem = $('<li>').addClass('custom-list-item').text(key.floor_number);
                        listItem.click(function() {
                            $('#floorNumber').val(key.floor_number);
                            selectedFloor = key.id;

                            hideSearchData('floors');
                        });
                    } else if (type === 'floorsEdit') {
                        var listItem = $('<li>').addClass('custom-list-item').text(key.floor_number);
                        listItem.click(function() {
                            $('#floorNumber2').val(key.floor_number);
                            selectedFloorEdit = key.id;

                            hideSearchData('floorsEdit');
                        });
                    } else if (type === 'places') {
                        var listItem = $('<li>').addClass('custom-list-item').text(key.place_name);
                        listItem.click(function() {
                            $('#placeName').val(key.place_name);
                            selectedPlace = key.id;

                            hideSearchData('places');
                        });
                    } else if (type === 'placesEdit') {
                        var listItem = $('<li>').addClass('custom-list-item').text(key.place_name);
                        listItem.click(function() {
                            $('#placeName2').val(key.place_name);
                            selectedPlaceEdit = key.id;

                            hideSearchData('placesEdit');
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

        $('#companySetupForm').submit(function(e) {
            clearErrors();
            e.preventDefault();
            var formData = new FormData(this);

            if (selectedCompany !== undefined && selectedCompany !== '') {
                formData.append('company_id', selectedCompany);
            }

            if (selectedStamp !== undefined && selectedStamp !== '') {
                formData.append('stamp_id', selectedStamp);
            }

            if (selectedFloor !== undefined && selectedFloor !== '') {
                formData.append('floor_id', selectedFloor);
            }

            if (selectedPlace !== undefined && selectedPlace !== '') {
                formData.append('place_id', selectedPlace);
            }

            $.ajax({
                type: "POST",
                url: "{{ route('insertCompanySetup') }}",
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

                        fetchCompanySetup();
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

                    clearCompanySetupForm('companySetupModal');
                },
                error: function(resp) {
                    if (resp.status === 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'add');
                    }
                },
                complete: function() {
                    hideSearchData('companies');
                    hideSearchData('stamps');
                    hideSearchData('floors');
                    hideSearchData('places');
                }
            })
        });


        $('#companySetupTable').on('click', '.editData', function() {
            var companySetupId = $(this).data('id');

            $.ajax({
                url: "{{ route('getCompanySetupById', ['id' => ':id']) }}".replace(':id', companySetupId),
                type: "GET",
                success: function(data) {
                    $('#editCompanySetupModal #id').val(data.companySetup.id);
                    $('#editCompanySetupModal #companyName2').val(data.companySetup.company_name);
                    $('#editCompanySetupModal #stampCode2').val(data.companySetup.stamp_code);
                    $('#editCompanySetupModal #placeName2').val(data.companySetup.place_name);
                    $('#editCompanySetupModal #floorNumber2').val(data.companySetup.floor_number);
                    $('#editCompanySetupModal #quota2').val(data.companySetup.total_quota);

                    selectedCompanyEdit = data.companySetup.company_id;
                    selectedStampEdit = data.companySetup.stamp_id;
                    selectedPlaceEdit = data.companySetup.place_id;
                    selectedFloorEdit = data.companySetup.floor_id;

                    openModal('editCompanySetupModal');
                },
                error: function(error) {
                    console.error('AJAX error:', error);
                }
            });
        });


        $('#editCompanySetupForm').submit(function(e) {
            clearErrors();
            e.preventDefault();
            var formData = new FormData(this);
            var companySetupId = formData.get('id');

            if (selectedCompanyEdit !== undefined && selectedCompanyEdit !== '') {
                formData.append('company_id', selectedCompanyEdit);
            }

            if (selectedStampEdit !== undefined && selectedStampEdit !== '') {
                formData.append('stamp_id', selectedStampEdit);
            }

            if (selectedFloorEdit !== undefined && selectedFloorEdit !== '') {
                formData.append('floor_id', selectedFloorEdit);
            }

            if (selectedPlaceEdit !== undefined && selectedPlaceEdit !== '') {
                formData.append('place_id', selectedPlaceEdit);
            }

            $.ajax({
                type: "POST",
                url: "{{ route('updateCompanySetup', ['id' => ':id']) }}".replace(':id', companySetupId),
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

                        fetchCompanySetup();
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

                    closeModal('editCompanySetupModal');
                },
                error: function(resp) {
                    if (resp.status == 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'edit');
                    }
                }
            });
        });

        $('#companySetupTable').on('click', '.deleteData', function() {
            var companySetupId = $(this).data('id');

            toastr.warning("ต้องการลบตั้งค่าบริษัทนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
                closeButton: true,
                positionClass: 'toast-top-right',
                timeOut: 0,
                onShown: function(toast) {
                    $('#confirmDelete').click(function() {
                        toastr.clear(toast);

                        $.ajax({
                            url: "{{ route('deleteCompanySetup', ['id' => ':id']) }}".replace(':id', companySetupId),
                            type: "DELETE",
                            success: function(resp) {
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

                                    fetchCompanySetup();
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
                            error: function(error) {
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


        $('#companySetupTable').on('click', '.reQuota', function() {
            var companySetupId = $(this).data('id');

            toastr.warning("ต้องการปรับโควต้าใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmReQuota'>ใช่</button>", null, {
                closeButton: true,
                positionClass: 'toast-top-right',
                timeOut: 0,
                onShown: function(toast) {
                    $('#confirmReQuota').click(function() {
                        toastr.clear(toast);

                        $.ajax({
                            url: "{{ route('reQuotaCompany', ['id' => ':id']) }}".replace(':id', companySetupId),
                            type: "POST",
                            success: function(resp) {
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

                                    fetchCompanySetup();
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
                            error: function(error) {
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