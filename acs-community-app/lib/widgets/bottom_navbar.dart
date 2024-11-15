import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class BottomNavbar extends StatefulWidget {
  final int currentIndex;
  final ValueChanged<int> onTabChanged;

  const BottomNavbar({
    Key? key,
    required this.currentIndex,
    required this.onTabChanged,
  }) : super(key: key);

  @override
  State<BottomNavbar> createState() => _BottomNavbarState();
}

class _BottomNavbarState extends State<BottomNavbar> {
  @override
  Widget build(BuildContext context) {
    return BottomNavigationBar(
      currentIndex: widget.currentIndex,
      onTap: (int index) {
        if (index == 0) {
          Get.toNamed(RouteHelper.social);
        } else if (index == 1) {
          Get.toNamed(RouteHelper.home);
        } else if (index == 2) {
          Get.toNamed(RouteHelper.announcement);
        } else if (index == 3) {
          Get.toNamed(RouteHelper.setting);
        }
      },
      type: BottomNavigationBarType.fixed,
      fixedColor: AppColors.blackColor,
      backgroundColor: AppColors.whiteColor,
      items: const [
        BottomNavigationBarItem(
          icon: Icon(
            Icons.people_sharp,
            color: AppColors.mainColor,
          ),
          label: 'โซเชียล',
        ),
        BottomNavigationBarItem(
          icon: Icon(
            Icons.home,
            color: AppColors.mainColor,
          ),
          label: 'หน้าหลัก',
        ),
        BottomNavigationBarItem(
          icon: Icon(
            Icons.notifications,
            color: AppColors.mainColor,
          ),
          label: 'ประกาศ',
        ),
        BottomNavigationBarItem(
          icon: Icon(
            Icons.settings,
            color: AppColors.mainColor,
          ),
          label: 'ตั้งค่า',
        ),
      ],
    );
  }
}
