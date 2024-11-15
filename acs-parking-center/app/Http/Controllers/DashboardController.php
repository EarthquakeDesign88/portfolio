<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
       $drivePath = '/';

       $totalSpace = disk_total_space($drivePath);
       $freeSpace = disk_free_space($drivePath);
       $usedSpace = $totalSpace - $freeSpace;

       $usedSpacePercentage = ($usedSpace / $totalSpace) * 100;
       $freeSpacePercentage = number_format(100 - $usedSpacePercentage, 0);

       $totalSpaceGB = round($totalSpace / (1024 * 1024 * 1024), 2);
       $freeSpaceGB = round($freeSpace / (1024 * 1024 * 1024), 2);
       $usedSpaceGB = round($usedSpace / (1024 * 1024 * 1024), 2);



       // ส่งข้อมูลไปยัง view สำหรับแสดงบนหน้าแดชบอร์ด
       return view('dashboard', compact(
        'totalSpaceGB', 'freeSpaceGB', 'usedSpaceGB', 'usedSpacePercentage', 'freeSpacePercentage'
      ));
    }
}
