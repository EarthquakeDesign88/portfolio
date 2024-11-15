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
                        ตารางแสดงรถ(ขาเข้า)
                        <span class="data-count"></span>
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
          <table id="carInTable">
            <thead>
              <tr>
                <th>รหัสบัตรจอดรถ</th>
                <th>ประเภท</th>
                <th>ทะเบียนรถ</th>
                <th>วันที่เข้า</th>
                <th>เวลาเข้า</th>
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
  $("document").ready(function() {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#carInTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('carIN') }}",
      columns: [
        { data: 'parking_pass_code', name: 'parking_pass_code'},
        { data: 'parking_pass_type', name: 'parking_pass_type'},
        {
          data: 'license_plate_path',
          name: 'license_plate_path',
          render: function(data, type, row) {
            return '<img src="' + data + '" alt="License Plate" style="width: 100px; height: auto;">';
          }
        },
        { data: 'carin_datetime_date', name: 'carin_datetime_date'},
        { data: 'carin_datetime_hms', name: 'carin_datetime_hms'},
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

  });
</script>
@endsection
