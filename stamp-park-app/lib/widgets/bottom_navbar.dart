import 'package:flutter/material.dart';
import 'package:stamp_park/utils/constants.dart';
import 'package:get/get.dart';
import 'package:stamp_park/routes/route_helper.dart';

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
          Get.offNamed(RouteHelper.home);
        } 
        else if (index == 1) {
          Get.offNamed(RouteHelper.condition);
        }
        else if (index == 2) {
          Get.offNamed(RouteHelper.howToUse);
        }
        else if (index == 3) {
          Get.offNamed(RouteHelper.stampHistory);
        }
      },
      type: BottomNavigationBarType.fixed,
      selectedItemColor: AppColors.mainColor,
      unselectedItemColor: Colors.grey,
      backgroundColor: AppColors.whiteColor,
      items: const [
        BottomNavigationBarItem(
          icon: Icon(Icons.place),
          label: 'ประทับตรา',
        ),
         BottomNavigationBarItem(
          icon: Icon(Icons.description),
          label: 'เงื่อนไข',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.help_outline),
          label: 'วิธีการใช้งาน',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.history),
          label: 'ประวัติ',
        ),
      ],
    );
  }
}
