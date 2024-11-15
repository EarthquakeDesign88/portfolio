<div class="nk-sidebar">           
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">ระบบ</li>
            <li>
                <a href="{{ route('dashboard') }}" aria-expanded="false">
                    <i class="icon-speedometer menu-icon"></i><span class="nav-text">แดชบอร์ด</span>
                </a>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-envelope menu-icon"></i> <span class="nav-text">อีเมล</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('inbox') }}">กล่องจดหมายเข้า</a></li>
                </ul>
            </li>

            <li class="nav-label">การตั้งค่า</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-screen-tablet menu-icon"></i><span class="nav-text">งาน</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('positions') }}">ตำแหน่ง</a></li>
                    <li><a href="{{ route('departments') }}">แผนก</a></li>
                    <li><a href="{{ route('jobQualifications') }}">คุณสมบัติ</a></li>
                    <li><a href="{{ route('workModes') }}">รูปแบบงาน</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-user menu-icon"></i><span class="nav-text">บัญชีผู้ใช้</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('users') }}">ข้อมูลบัญชี</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>