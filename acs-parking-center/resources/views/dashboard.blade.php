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
                                        <h2>ข้อมูลระบบ</h2>
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

<div class="analysis-progrebar-area mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analysis-progrebar reso-mg-b-30">
                    <div class="analysis-progrebar-content">
                        <h5>พื้นที่ทั้งหมด</h5>
                        <h2><span class="counter">{{$totalSpaceGB}}</span>GB</h2>
                        <div class="progress progress-mini">
                            <div style="width: 100%;" class="progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analysis-progrebar reso-mg-b-30">
                    <div class="analysis-progrebar-content">
                        <h5>พื้นที่ว่าง</h5>
                        <h2><span class="counter">{{$freeSpaceGB}}</span>GB</h2>
                        <div class="progress progress-mini">
                            <div style="width: {{$freeSpacePercentage}}%" class="progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analysis-progrebar reso-mg-b-30 res-mg-t-30">
                    <div class="analysis-progrebar-content">
                        <h5>ใช้ไปแล้ว</h5>
                        <h2><span class="counter">{{$usedSpaceGB}}</span>GB</h2>
                        <div class="progress progress-mini">
                            <div style="width: {{$usedSpacePercentage}}%" class="progress-bar progress-bar-danger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analysis-progrebar res-mg-t-30">
                    <div class="analysis-progrebar-content">
                        <h5>คงเหลือ</h5>
                        <h2><span class="counter">{{$freeSpacePercentage}}</span>%</h2>
                        <div class="progress progress-mini">
                            <div style="width: {{$freeSpacePercentage}}%" class="progress-bar progress-bar-danger"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="analytics-sparkle-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Visits in last 24h</h5>
                        <h2 class="counter">5600</h2>
                        <div id="sparkline22"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30">
                    <div class="analytics-content">
                        <h5>Visits week</h5>
                        <h2 class="counter">3400</h2>
                        <div id="sparkline23"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line reso-mg-b-30 res-mg-t-30">
                    <div class="analytics-content">
                        <h5>Last month</h5>
                        <h2 class="counter">3300</h2>
                        <div id="sparkline24"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-sparkle-line res-mg-t-30">
                    <div class="analytics-content">
                        <h5>Avarage time</h5>
                        <h2>00:06:40</h2>
                        <div id="sparkline25"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="analysis-rounded-area mg-tb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-rounded reso-mg-b-30">
                    <div class="analytics-rounded-content">
                        <h5>Percentage distribution</h5>
                        <h2><span class="counter">40</span>/20</h2>
                        <div class="text-center">
                            <div id="sparkline51"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-rounded reso-mg-b-30">
                    <div class="analytics-rounded-content">
                        <h5>Percentage division</h5>
                        <h2><span class="counter">140</span>/<span class="counter">54</span></h2>
                        <div class="text-center">
                            <div id="sparkline52"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-rounded reso-mg-b-30 res-mg-t-30">
                    <div class="analytics-rounded-content">
                        <h5>Percentage Counting</h5>
                        <h2>2345/311</h2>
                        <div class="text-center">
                            <div id="sparkline53"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="analytics-rounded res-mg-t-30">
                    <div class="analytics-rounded-content">
                        <h5>Percentage Sequence</h5>
                        <h2>780/56</h2>
                        <div class="text-center">
                            <div id="sparkline54"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

@endsection
