@extends('layouts.master')

@section('content')

<style>
    .badge-success {
        background-color: #00af84 !important;
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
                    <h4> 
                        ประวัติบัตรจอดรถ Manual
                        <span>
                            <h4 class="data-count badge badge-success"></h4>
                        </span>

                    </h4>
                    <div class="add-product">
                        <a href="{{ route('recordParking') }}">ย้อนกลับ</a>
                    </div>

               
                    <table id="parkingRecordTable">
                        <thead>
                            <tr>
                                <th>รหัสบัตรจอดรถ</th>
                                <th>รหัสตราประทับ</th>
                                <th>จำนวนตราประทับ</th>
                                <th>เวลาเข้า</th>
                                <th>เวลาออก</th>
                                <th>ค่าที่จอดรถ</th>
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




<script src="{{ asset('assets/lib/jquery/dist/jquery.min.js') }}"></script>



<script type="text/javascript">
    function updateTable(recordParkings) {
        $('#parkingRecordTable tbody').empty();

        $.each(recordParkings, function(index, recordParking) {
            var row = "<tr>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.parking_pass_code ? recordParking.parking_pass_code : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.stamp_id ? recordParking.stamp_code : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.stamp_qtye ? recordParking.stamp_qty : 'ไม่พบข้อมูล')  + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.carin_datetime ? recordParking.carin_datetime : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.carout_datetime ? recordParking.carout_datetime : 'ไม่พบข้อมูล') + "</span></td>" +
                "<td data-label=''><span class='ellipsis-tb'>" + (recordParking.fee ? recordParking.fee : 'ไม่พบข้อมูล')  + "</span></td>" +
                "</tr>";

            $('#parkingRecordTable tbody').append(row);
        });

        updateCount(recordParkings.length);
    }

    function updateCount(count) {
        $('.data-count').text(count);
    }

    function fetchHistoryRecordManual() {
        $.ajax({
            type: "GET",
            url: "{{ route('getHistoryRecordManual') }}",
            success: function(data) {
                updateTable(data.recordParkings);
            },
            errror: function(error) {
                console.error('AJAX error:', error);
            }
        });
    }

    $("document").ready(function() {
        fetchHistoryRecordManual();

        var table = $('#parkingRecordTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('historyRecordManual') }}",
            columns: [
                {
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
                        return data ? data : 'ไม่มีรหัสตราประทับ';
                    }
                },
                {
                    data: 'stamp_qty',
                    name: 'stamp_qty',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่มีจำนวนตราประทับ';
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
                    data: 'fee',
                    name: 'fee',
                    render: function(data, type, row) {
                        return data ? data : 'ไม่พบข้อมูล';
                    }
                },
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
    });



</script>


@endsection