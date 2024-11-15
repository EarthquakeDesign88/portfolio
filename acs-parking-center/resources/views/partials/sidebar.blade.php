<div class="left-sidebar-pro" >
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <div style="text-align: left;padding: 5px 10px; display: flex; align-items: flex-end;">
                <img class="main-logo" src="{{ asset('assets/img/logo/logo-acs.png') }}" alt="Logo" height="40" width="40"/> 
                <span style="font-size: 18px;">&nbsp;&nbsp;&nbsp;<b>ACS Parking</b></span>
            </div>
            @if(Auth::check())
                <p>{{ Auth::user()->name }}</p>
            @else
                <p>Guest</p>
            @endif
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                    <li class="{request()-> routeIs('dashboard')? 'active' : '' }}">
                        <a class="has-arrow" href="index.html">
                            <i class="fa fa-pie-chart"></i>
                            <span class="mini-click-non">แดชบอร์ด</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="true">
                            <li class="{request()-> routeIs('dashboard')? 'active' : '' }}"><a title="System Management" href="{{ route('dashboard') }}"><span class="mini-sub-pro">ข้อมูลระบบ</span></a></li>
                            <li class="{request()-> routeIs('carDashboard')? 'active' : '' }}"><a title="Car Management" href="{{ route('carDashboard') }}"><span class="mini-sub-pro">ข้อมูลรถเข้าออก</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-car"></i> <span class="mini-click-non">ที่จอดรถ</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="{request()-> routeIs('carIN')? 'active' : '' }}"><a title="Car IN" href="{{ route('carIN') }}"><span class="mini-sub-pro">ขาเข้า</span></a></li>
                            <li class="{request()-> routeIs('carOUT')? 'active' : '' }}"><a title="Car OUT" href="{{ route('carOUT') }}"><span class="mini-sub-pro">ขาออก</span></a></li>
                            <li class="{request()-> routeIs('recordParking')? 'active' : '' }}"><a title="Record Parking" href="{{ route('recordParking') }}"><span class="mini-sub-pro">บัตรจอดรถ</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-file"></i> <span class="mini-click-non">รายงาน</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="{request()-> routeIs('dailyParkingFeeList')? 'active' : '' }}"><a title="Daily Parking Report" href="{{ route('dailyParkingFeeList') }}"><span class="mini-sub-pro">ค่าที่จอดรถรายวัน</span></a></li>
                            <li class="{request()-> routeIs('monthlyStampSummaryList')? 'active' : '' }}"><a title="Stamp Summary Report" href="{{ route('monthlyStampSummaryList') }}"><span class="mini-sub-pro">ตราประทับรายเดือน</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-star"></i> <span class="mini-click-non">ตั้งค่าทั่วไป</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li>  
                                <a class="{request()-> routeIs('placeList')? 'active' : '' }}" href="{{ route('placeList') }}" aria-expanded="false"><span class="mini-click-non">สถานที่</span></a>
                            </li>
                            <li class="{request()-> routeIs('floors')? 'active' : '' }}">
                                <a title="Floor" href="{{ route('floors') }}"><span class="mini-sub-pro">ชั้น</span></a>
                            </li>
                            <li>
                                <a class="{request()-> routeIs('stamps')? 'active' : '' }}" href="{{ route('stamps') }}" aria-expanded="false"><span class="mini-click-non">ตราประทับ</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-building"></i> <span class="mini-click-non">บริษัท</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="{request()-> routeIs('companySetup')? 'active' : '' }}"><a title="Company Setup" href="{{ route('companySetup') }}"><span class="mini-sub-pro">ตั้งค่าบริษัท</span></a></li>
                            <li class="{request()-> routeIs('companyList')? 'active' : '' }}"><a title="Company List" href="{{ route('companyList') }}"><span class="mini-sub-pro">รายชื่อบริษัท</span></a></li>
                            <li class="{request()-> routeIs('companyStatus')? 'active' : '' }}"><a title="Status" href="{{ route('companyStatus') }}"><span class="mini-sub-pro">สถาะบริษัท</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-users"></i> <span class="mini-click-non">สมาชิก</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="{request()-> routeIs('memberList')? 'active' : '' }}"><a title="Member List" href="{{ route('memberList') }}"><span class="mini-sub-pro">รายชื่อสมาชิก</span></a></li>
                            <li class="{request()-> routeIs('memberTypes')? 'active' : '' }}"><a title="Member Type" href="{{ route('memberTypes') }}"><span class="mini-sub-pro">ประเภทสมาชิก</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-credit-card"></i> <span class="mini-click-non">การชำระเงิน</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="{request()-> routeIs('placeList')? 'active' : '' }}"><a title="Transaction" href="#"><span class="mini-sub-pro">รายการทำธุรกรรม</span></a></li>
                        </ul>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li class="{request()-> routeIs('paymentMethod')? 'active' : '' }}"><a title="Payment Method" href="{{ route('paymentMethod') }}"><span class="mini-sub-pro">วิธีชำระเงิน</span></a></li>
                        </ul>
                    </li>
                    <li class="#">
                        <a class="has-arrow" href="#">
                            <i class="fa fa-cog"></i>
                            <span class="mini-click-non">ตั้งค่าระบบ</span>
                        </a>
                    </li>
                    <li class="#">
                        <a class="has-arrow" href="{{ route('logout') }}">
                            <i class="fa fa-sign-out"></i>
                            <span class="mini-click-non">ออกจากระบบ</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>
</div>
