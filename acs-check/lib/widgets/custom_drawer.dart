import 'package:flutter/material.dart';
import 'package:acs_check/utils/constants.dart';
import 'package:acs_check/widgets/small_text.dart';
import 'package:get/get.dart';
import 'package:acs_check/routes/route_helper.dart';
import 'package:acs_check/services/auth_service.dart';

class CustomDrawer extends StatelessWidget {
  final String? firstName;
  final String? lastName;
  final AuthService authService; 

  const CustomDrawer({
    Key? key,
    this.firstName,
    this.lastName,   
    required this.authService,
  }) : super(key: key); 

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        children: [
          DrawerHeader(
            decoration: const BoxDecoration(
              color: AppColors.mainColor,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Image.asset(
                  'assets/images/app.png',
                  width: Dimensions.width80,
                  height: Dimensions.height80,
                ),
                SizedBox(height: Dimensions.height10),
                const Text(
                  "ACS Check",
                  style: TextStyle(
                    fontSize: 20,
                    color: Colors.white,
                    fontWeight: FontWeight.bold, 
                  ),
                ),
              ],
            ),
          ),
          ListTile(
            leading: const Icon(Icons.account_circle),
            title: SmallText(
              text: "${firstName ?? 'First Name'} ${lastName ?? 'Last Name'}", 
              size: Dimensions.font18,
            ),
          ),
          ListTile(
            title: SmallText(text: "ตารางงาน", size: Dimensions.font18),
            onTap: () {
              Get.toNamed(RouteHelper.workSchedule);
            },
          ),
          ListTile(
            title: SmallText(text: "ประวัติตรวจงาน", size: Dimensions.font18),
            onTap: () {
              Get.offNamed(RouteHelper.historyJob);
            },
          ),
          ListTile(
            title: SmallText(text: "Logout", size: Dimensions.font18),
            onTap: () async {
              await authService.logout();
              Future.delayed(const Duration(milliseconds: 100), () {
                Get.offAllNamed(RouteHelper.login);
              });
            },
          ),
        ],
      ),
    );
  }
}