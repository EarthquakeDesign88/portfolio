import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:stamp_park/routes/route_helper.dart';
import 'package:stamp_park/services/auth_service.dart';
import 'package:stamp_park/utils/constants.dart';
import 'package:stamp_park/widgets/big_text.dart';
import 'package:stamp_park/widgets/small_text.dart';
import 'package:stamp_park/widgets/bottom_navbar.dart';

class HowToUsePage extends StatefulWidget {
  const HowToUsePage({Key? key}) : super(key: key);

  @override
  State<HowToUsePage> createState() => _HowToUsePageState();
}

class _HowToUsePageState extends State<HowToUsePage> {
  final AuthService authService = AuthService();
  int _currentIndex = 2;

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
              BigText(text: "วิธีใช้งาน", color: AppColors.blackColor),
              SizedBox(height: Dimensions.height10),
              Card(
                elevation: 4,
                margin: EdgeInsets.symmetric(vertical: Dimensions.height10),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      ListTile(
                        leading:
                            Icon(Icons.book_online, color: AppColors.mainColor),
                        title: SmallText(
                          text: "1. ไปที่เมนูประทับตรา แล้วกดปุ่มสแกนบัตรจอดรถ",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading: Icon(Icons.qr_code_scanner,
                            color: AppColors.mainColor),
                        title: SmallText(
                          text: "2. สแกนบาร์โค้ดบัตรจอดรถ",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading: Icon(Icons.check, color: AppColors.mainColor),
                        title: SmallText(
                          text:
                              "3. ตรวจสอบรหัสบัตรจอดรถว่าตรงกับหน้าจอหรือไม่ หากไม่ตรงให้ทำการสแกนใหม่อีกครั้ง",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading:
                            Icon(Icons.access_time, color: AppColors.mainColor),
                        title: SmallText(
                          text:
                              "4. เมื่อรหัสบัตรจอดรถตรงกับหน้าจอแล้ว ให้ทำการคำนวณเวลาจอดรถ",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading:
                            Icon(Icons.calculate, color: AppColors.mainColor),
                        title: SmallText(
                          text:
                              "เช่น เข้าเวลา 12.58 น. คาดว่าจะออกเวลา 16.00 น. ใช้เวลาจอดรถประมาณ 3 ชั่วโมง",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading: Icon(Icons.local_activity,
                            color: AppColors.mainColor),
                        title: SmallText(
                          text:
                              "5. เลือกจำนวนตราประทับ ตามตัวอย่างข้างต้น ให้ทำการประทับตรา 2 ดวง (1 ดวง = 2 ชั่วโมง)",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading: Icon(Icons.local_parking,
                            color: AppColors.mainColor),
                        title: SmallText(
                          text: "6. กดปุ่มประทับตรา",
                          size: Dimensions.font18,
                        ),
                      ),
                      ListTile(
                        leading:
                            Icon(Icons.exit_to_app, color: AppColors.mainColor),
                        title: SmallText(
                          text:
                              "7. ยื่นบัตรจอดรถนี้ที่ทางออก เมื่อมีการนำรถออก",
                          size: Dimensions.font18,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              SizedBox(height: Dimensions.height10),
              BigText(
                  text: "หมายเหตุ",
                  color: AppColors.blackColor,
                  size: Dimensions.font20),
              SizedBox(height: Dimensions.height10),
              Card(
                elevation: 4,
                margin: EdgeInsets.symmetric(vertical: 10),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: SmallText(
                    text:
                        "ทั้งนี้เงื่อนไขการกำหนดจำนวนตราประทับ ขึ้นอยู่กับนโยบายของแต่ละบริษัท",
                    size: Dimensions.font18,
                    color: AppColors.blackColor,
                  ),
                ),
              ),
              SizedBox(height: Dimensions.height20),
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
