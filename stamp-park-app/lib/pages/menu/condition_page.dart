import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:stamp_park/routes/route_helper.dart';
import 'package:stamp_park/services/auth_service.dart';
import 'package:stamp_park/utils/constants.dart';
import 'package:stamp_park/widgets/big_text.dart';
import 'package:stamp_park/widgets/small_text.dart';
import 'package:stamp_park/widgets/bottom_navbar.dart';

class ConditionPage extends StatefulWidget {
  const ConditionPage({Key? key}) : super(key: key);

  @override
  State<ConditionPage> createState() => _ConditionPageState();
}

class _ConditionPageState extends State<ConditionPage> {
  final AuthService authService = AuthService();
  int _currentIndex = 1;

  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.whiteColor,
      appBar: AppBar(
        title: const Text(
          "Stamp Park",
          style: TextStyle(color: AppColors.whiteColor),
        ),
        backgroundColor: AppColors.mainColor,
        // actions: [
        //   IconButton(
        //     icon: const Icon(Icons.notifications),
        //     onPressed: () {
        //       // Handle notifications
        //     },
        //   ),
        // ],
        iconTheme: IconThemeData(color: AppColors.whiteColor),
      ),
      drawer: Drawer(
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
                    'assets/images/logo.png',
                    width: Dimensions.width80,
                    height: Dimensions.height80,
                  ),
                  SizedBox(height: Dimensions.height10),
                  const Text(
                    "Stamp Park",
                    style: TextStyle(
                      fontSize: 20,
                      color: Colors.white,
                    ),
                  ),
                ],
              ),
            ),
            ListTile(
              title: SmallText(text: "ประทับตรา", size: Dimensions.font18),
              onTap: () {
                Get.toNamed(RouteHelper.home);
              },
            ),
            ListTile(
              title:
                  SmallText(text: "ประวัติการสแตมป์", size: Dimensions.font18),
              onTap: () {
                Get.toNamed(RouteHelper.stampHistory);
              },
            ),
            ListTile(
              title: SmallText(text: "ออกจากระบบ", size: Dimensions.font18),
              onTap: () async {
                await authService.logout();
                Future.delayed(Duration(milliseconds: 100), () {
                  Get.offAllNamed(RouteHelper.login);
                });
              },
            ),
          ],
        ),
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              BigText(text: "เงื่อนไข", color: AppColors.blackColor),
              Card(
                elevation: 4,
                margin: EdgeInsets.symmetric(vertical: Dimensions.height10),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text:
                            "1. ค่าบริการที่จอดรถยนต์ จอดรถฟรี 15 นาทีแรก ชั่วโมงต่อไป คิดค่าบริการ 20 บาท/ชั่วโมง เศษของชั่วโมงคิดเป็น 1 ชั่วโมง",
                        size: Dimensions.font18,
                      ),
                      SizedBox(height: Dimensions.height10),
                      SmallText(
                        text: "2. ประทับตราจอดรถฟรี 2 ชั่วโมง/ 1 ดวง",
                        size: Dimensions.font18,
                      ),
                      SizedBox(height: Dimensions.height10),
                      SmallText(
                        text:
                            "3. จอดค้างคืนคิดค่าปรับคันละ 500 บาท และคิดค่าจอดตามระเบียบ",
                        size: Dimensions.font18,
                      ),
                      SizedBox(height: Dimensions.height10),
                      SmallText(
                        text:
                            "4. บัตรหายปรับ 300 บาท และคิดค่าจอดตั้งแต่นาทีแรก และต้องแสดงหลักฐานการเป็นเจ้าของรถจึงจะยินยอมให้นำรถออก",
                        size: Dimensions.font18,
                      ),
                      SizedBox(height: Dimensions.height10),
                      SmallText(
                        text:
                            "5. บัตรนี้ไม่ถือเป็นการรับฝากรถ ทางบริษัทฯ ไม่รับผิดชอบในความเสียหายหรือสูญหายใดๆ ต่อรถ อุปกรณ์ควบและทรัพย์สินทุกกรณี",
                        size: Dimensions.font18,
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }
}
