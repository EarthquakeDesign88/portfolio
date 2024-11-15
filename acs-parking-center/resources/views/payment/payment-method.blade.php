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
                        วิธีการชำระเงิน
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
          <table id="paymentMethodTable">
            <thead>
              <tr>
                <th width="50%">ลำดับ</th>
                <th width="50%">วิธีการชำระเงิน</th>
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

  function updateCount(count) {
    $('.data-count').text(count);
  }

  function updateTable(places) {
    $('#paymentMethodTable tbody').empty();

    $.each(paymentMethods, function(index, paymentMethod) {
        var row = "<tr>" +
            "<td data-label=''><span class='ellipsis-tb'>" + index + "</span></td>" +
            "<td data-label=''><span class='ellipsis-tb'>" + paymentMethod.payment_method + "</span></td>" +
          "</tr>";
            
        $('#paymentMethodTable tbody').append(row);
    });

    updateCount(paymentMethods.length);
  }

  function fetchPaymentMethods() {
    $.ajax({
      type: "GET",
      url: "{{ route('getPaymentMethods') }}",
      success: function(data) {
        updateTable(data.paymentMethods);
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

    fetchPaymentMethods();

    var table = $('#paymentMethodTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('paymentMethod') }}",
      columns: [
        { data: 'id', name: 'id'},
        { data: 'payment_method', name: 'payment_method'},
      
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

    
  });
</script>


@endsection