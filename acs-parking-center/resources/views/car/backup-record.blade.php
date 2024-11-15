@extends('layouts.master')

@section('content')

<style>
    #previewParkingRecordModal .modal-content {
        max-width: 400px; 
        margin: auto; 
    }
    .badge-danger {
        display: inline-block;
        min-width: 10px;
        padding: 3px 7px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color: #f30000 !important;
    }
</style>


<div class="header-advance-area">
    @include('partials.header')

    <div class="breadcome-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcome-list">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-ctn">
                                    <h2>กรอกข้อมูลบัตรจอดรถ Manual</h2>
                                        <div class="container">
                                            <form id="recordParkingForm" style="margin-top: 10px;"method="post" action="javascript:void(0)">
                                                <div class="row text-white">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">     
                                                        <label for="parkingPaSsCode">รหัสบัตรจอดรถ:</label>
                                                        <input type="text" class="form-control" id="parkingPassCode" name="parking_pass_code" maxlength="13">   
                                                        <div id="parkingPassCodeError"></div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">     
                                                        <label for="licensePlate">ป้ายทะเบียนรถ:</label>
                                                        <input type="text" class="form-control" id="licensePlate" name="license_plate" maxlength="10">   
                                                        <div id="licensePlateError"></div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                                        <label for="stampCode" class="form-label">รหัสตราประทับ:</label>
                                                        <input type="text" class="form-control" id="stampCode" name="stamp_id" autocomplete="off">
                                                        <div id="stampCodeError"></div>
                                                        <ul id="stamps"></ul>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                                        <label for="stampQty" class="form-label">จำนวนตราประทับ:</label>
                                                        <input type="number" class="form-control" id="stampQty" name="stamp_qty" autocomplete="off" min="1" max="6" maxlength="1">
                                                        <div id="stampQtyError"></div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                                        <label for="carinDatetime">เวลาเข้า:</label>
                                                        <input type="datetime-local" class="form-control" id="carinDatetime" name="carin_datetime" step="1">
                                                        <div id="carinDatetimeError"></div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                                        <label for="caroutDatetime">เวลาออก:</label>
                                                        <input type="datetime-local" class="form-control" id="caroutDatetime" name="carout_datetime" step="1">
                                                        <div id="caroutDatetimeError"></div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                                        <label>วิธีการชำระเงิน:</label><br>
                                                        <div class="row">
                                                            @foreach($paymentMethods as $paymentMethod)
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <input type="radio" id="paymentMethod{{ $paymentMethod->id }}" name="payment_method_id" value="{{ $paymentMethod->id }}">
                                                                    <label for="paymentMethod{{ $paymentMethod->id }}">{{ $->payment_methodpaymentMethod }}</label>
                                                                </div>
                                                            @endforeach
                                                            <div id="paymentMethodError"></div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
                                                        <label for="parkingFee">ยอดชำระทั้งสิ้น:</label>
                                                        <input type="text" class="form-control" id="parkingFee" name="fee" autocomplete="off">
                                                        <div id="parkingFeeError"></div>
                                                    </div>

                                                </div>
                                                
                                      
                                                <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal for Preview -->
<div id="previewParkingRecordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="previewParkingRecordLabel">ตัวอย่างก่อนบันทึกข้อมูล</h4>
        </div>
        <div class="modal-body">
        <button type="button" class="close" onclick="closeModal('previewParkingRecordModal')">&times;</button>
            <img src="{{ asset('assets/img/parking/elephant_tower_new.jpg') }}" height="100" width="200">
           
            <div id="previewContent" class="mt-2">
            
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="submitButton" id="confirmSaveBtn">ยืนยันบันทึก</button>
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

    function clearrecordParkingForm() {
        $('#recordParkingForm').trigger("reset");
        clearErrors();
    }

    function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'parking_pass_code') {
                $('#parkingPassCodeError').html('<div class="text-error">' + value[0] + '</div>');
            }
            else if (key === 'license_plate') {
                $('#licensePlateError').html('<div class="text-error">' + value[0] + '</div>');
            }
            else if (key === 'stamp_id') {
                $('#stampCodeError').html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'stamp_qty') {
                $('#stampQtyError').html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'carin_datetime') {
                $('#carinDatetimeError').html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'carout_datetime') {
                $('#caroutDatetimeError').html('<div class="text-error">' + value[0] + '</div>');
            }
            else if (key === 'payment_method_id') {
                $('#paymentMethodError').html('<div class="text-error">' + value[0] + '</div>');
            }
            else if (key === 'fee') {
                $('#parkingFeeError').html('<div class="text-error">' + value[0] + '</div>');
            }
        });
    }




    $("document").ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var selectedStamp;

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
                    } 

                    searchResults.append(listItem);
                });
            }

            searchResults.fadeIn();
        }

        function hideSearchData(type) {
            $('#' + type).fadeOut();
        }


        $('#recordParkingForm').submit(function(event) {
            event.preventDefault();

            var formData = $('#recordParkingForm').serialize();

            // Update preview modal content with form data
            $('#previewContent').html('<p>Loading...</p>'); 

            // Show the modal
            openModal('previewParkingRecordModal');

            if (selectedStamp !== undefined && selectedStamp !== '') {
                formData.append('stamp_id', selectedStamp);
            }


            $.ajax({
                url: "{{ route('previewRecordParking') }}", 
                method: "POST",
                data: formData,
                success: function(response) {
                    $('#previewContent').html(response); 
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#previewContent').html('<p>ไม่พบข้อมูล กรุณากรอกข้อมูล</p>'); 
                }
            });
        });


        $('#confirmSaveBtn').click(function() {
            var formData = $('#recordParkingForm').serialize();

            closeModal('previewParkingRecordModal');

            $.ajax({
                type: "POST",
                url: "{{ route('insertRecordParking') }}",
                data: formData,
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

                    clearrecordParkingForm();
                },
                error: function(resp) {
                    if (resp.status === 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'add');
                    }
                },
            });
        });

    });


</script>


@endsection