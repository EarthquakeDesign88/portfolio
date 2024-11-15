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
                                        <h2>ข้อมูลรถเข้า-ออก ประจำวัน</h2>
                                        <p>สรุปข้อมูลประจำวันที่ <span class="bread-ntd">{{ $today }}</span></p>
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

<div class="section-admin container-fluid">
    <div class="row admin text-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="admin-content analysis-progrebar-ctn res-mg-t-15">
                        <h4 class="text-left text-uppercase"><b>จำนวนรถทั้งหมด</b></h4>
                        <div class="row vertical-center-box vertical-center-box-tablet">
                            <div class="col-xs-3 mar-bot-15 text-left">
                                <label class="label bg-green">{{ $totalCarsPercentage }}% <i class="fa {{ $totalCarsPercentage >= 0 ? 'fa-level-up' : 'fa-level-down' }}" aria-hidden="true"></i></label>
                            </div>
                            <div class="col-xs-9 cus-gh-hd-pro">
                                <h2 class="text-right no-margin">{{$totalCars}}</h2>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 78%;" class="progress-bar bg-green"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                    <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                        <h4 class="text-left text-uppercase"><b>จำนวนรถเข้า</b></h4>
                        <div class="row vertical-center-box vertical-center-box-tablet">
                            <div class="text-left col-xs-3 mar-bot-15">
                                <label class="label bg-red">{{ $carInPercentage }}% <i class="fa {{ $carInPercentage >= 0 ? 'fa-level-up' : 'fa-level-down' }}" aria-hidden="true"></i></label>
                            </div>
                            <div class="col-xs-9 cus-gh-hd-pro">
                                <h2 class="text-right no-margin">{{$countCarIn}}</h2>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 38%;" class="progress-bar progress-bar-danger bg-red"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                        <h4 class="text-left text-uppercase"><b>จำนวนรถออก</b></h4>
                        <div class="row vertical-center-box vertical-center-box-tablet">
                            <div class="text-left col-xs-3 mar-bot-15">
                                <label class="label bg-blue">{{ $carOutPercentage }}% <i class="fa {{ $carOutPercentage >= 0 ? 'fa-level-up' : 'fa-level-down' }}" aria-hidden="true"></i></label>
                            </div>
                            <div class="col-xs-9 cus-gh-hd-pro">
                                <h2 class="text-right no-margin">{{$countCarOut}}</h2>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 60%;" class="progress-bar bg-blue"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                        <h4 class="text-left text-uppercase"><b>รายได้วันนี้</b></h4>
                        <div class="row vertical-center-box vertical-center-box-tablet">
                            <div class="text-left col-xs-3 mar-bot-15">
                                <label class="label bg-purple">{{ $sumFeePercentage }}%<i class="fa {{ $sumFeePercentage >= 0 ? 'fa-level-up' : 'fa-level-down' }}" aria-hidden="true"></i></label>
                            </div>
                            <div class="col-xs-9 cus-gh-hd-pro">
                                <h2 class="text-right no-margin">{{$sumFee}}</h2>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 60%;" class="progress-bar bg-purple"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="static-table-area mg-t-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="sparkline8-list">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1 style="color: aliceblue;">ข้อมูลขาเข้า</h1>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="static-table-list" style="color: aliceblue;">
                                <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ป้ายทะเบียน</th>
                                            <th>เวลาเข้า</th>
                                            <th>ประเภทบัตรจอดรถ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($carIn as $car)
                                       <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $car->license_plate != '' ? $car->license_plate : 'ตรวจสอบไม่ได้' }}</td>
                                            <td>{{ $car->carin_datetime }}</td>
                                            <td>{{ $car->parking_pass_type == '1' ? 'สมาชิก' : 'บุคคลทั่วไป' }}</td>
                                        </tr>

                                       @endforeach
                                     
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="sparkline8-list">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1 style="color: aliceblue;">ข้อมูลขาออก</h1>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="static-table-list" style="color: aliceblue;">
                               <div class="table-container">
                               <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ป้ายทะเบียน</th>
                                            <th>เวลาออก</th>
                                            <th>ประเภทบัตรจอดรถ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($carOut as $car)
                                       <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $car->license_plate != '' ? $car->license_plate : 'ตรวจสอบไม่ได้' }}</td>
                                            <td>{{ $car->carout_datetime }}</td>
                                            <td>{{ $car->parking_pass_type == '1' ? 'สมาชิก' : 'บุคคลทั่วไป' }}</td>
                                        </tr>

                                       @endforeach
                                    </tbody>
                                </table>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection