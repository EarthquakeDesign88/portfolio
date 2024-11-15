@foreach($members as $member)
    @if($loop->first)
        <tr>
            <th>รหัสสมาชิก</th>
            <th>ชื่อสมาชิก</th>
            <th>เบอร์โทรศัพท์</th>
            <th>ป้ายทะเบียน</th>
            <th>บริษัท</th>
            <th>สถานที่</th>
            <th>ประเภทสมาชิก</th>
            <th>สถานะสมาชิก</th>
            <th>วันที่ออกบัตรสมาชิก</th>
            <th>วันที่หมดอายุสมาชิก</th>
        </tr>
    @endif
    <tr>
        <td>{{ $member->member_code }}</td>
        <td>{{ $member->first_name }} {{ $member->last_name }}</td>
        <td>{{ $member->phone }}</td>
        <td>{{ $member->license_plate }}</td>
        <td>{{ $member->company_name }}</td>
        <td>{{ $member->place_name }}</td>
        <td>{{ $member->member_type }}</td>
        <td>{{ $member->member_status == "active" ? "ใช้งานอยู่" : "ไม่ได้ใช้งาน" }}</td>
        <td>{{ $member->issue_date }}</td>
        <td>{{ $member->expiry_date }}</td>
    </tr>
@endforeach
