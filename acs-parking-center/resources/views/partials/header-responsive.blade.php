<div class="header-top-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="header-top-wraper">
                        <div class="row">
                            <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                <div class="menu-switcher-pro">
                                    <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                        <i class="icon nalika-menu-task"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                <div class="header-top-menu tabl-d-n hd-search-rp">
             
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul class="mobile-menu-nav">
                                <li><a data-toggle="collapse" data-target="#dashboard" href="#">แดชบอร์ด <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="dashboard" class="collapse dropdown-header-top">
                                        <li><a href="{{ route('dashboard') }}">ข้อมูลระบบ</a></li>
                                        <li><a href="{{ route('carDashboard') }}">ข้อมูลรถเข้าออก</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#car" href="#">ที่จอดรถ<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="car" class="collapse dropdown-header-top">
                                        <li><a href="{{ route('carIN') }}">ขาเข้า</a></li>
                                        <li><a href="{{ route('carOUT') }}">ขาออก</a></li>
                                        <li><a href="{{ route('recordParking') }}">บัตรจอดรถ</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#report" href="#">รายงาน <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="report" class="collapse dropdown-header-top">
                                        <li><a href="{{ route('dailyParkingFeeList') }}">ค่าที่จอดรถรายวัน</a></li>
                                        <li><a href="{{ route('monthlyStampSummaryList') }}">ตราประทับรายเดือน</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#setting" href="#">ตั้งค่าทั่วไป<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="setting" class="collapse dropdown-header-top">
                                        <li><a href="{{ route('placeList') }}">สถานที่</a></li>
                                        <li><a href="{{ route('floors') }}">ชั้น</a></li>
                                        <li><a href="{{ route('stamps') }}">ตราประทับ</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#company" href="#">บริษัท<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="company" class="collapse dropdown-header-top">
                                        <li><a href="{{ route('companySetup') }}">ตั้งค่าบริษัท</a></li>
                                        <li><a href="{{ route('companyList') }}">รายชื่อบริษัท</a></li>
                                        <li><a href="{{ route('companyStatus') }}">สถาะบริษัท</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#member" href="#">สมาชิก<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="member" class="collapse dropdown-header-top">
                                        <li><a href="{{ route('memberList') }}">รายชื่อสมาชิก</a></li>
                                        <li><a href="{{ route('memberTypes') }}">ประเภทสมาชิก</a></li>
                                    </ul>
                                </li>
                                </li>
                                <li><a data-toggle="collapse" data-target="#payment" href="#">การชำระเงิน<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                    <ul id="payment" class="collapse dropdown-header-top">
                                        <li><a href="#">รายการทำธุรกรรม</a></li>
                                        <li><a href="{{ route('paymentMethod') }}">วิธีชำระเงิน</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">ตั้งค่าระบบ<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a></li>
                                <li><a href="{{ route('logout') }}">ออกจากระบบ<span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a></li>
                            </ul>
                            </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu end -->