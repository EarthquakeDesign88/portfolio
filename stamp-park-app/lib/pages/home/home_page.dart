import 'package:flutter/material.dart';
import 'package:stamp_park/utils/constants.dart';
import 'package:stamp_park/widgets/bottom_navbar.dart';
import 'package:stamp_park/widgets/big_text.dart';
import 'package:stamp_park/widgets/small_text.dart';
import 'package:stamp_park/services/auth_service.dart';
import 'package:stamp_park/services/stamp_service.dart';
import 'package:simple_barcode_scanner/simple_barcode_scanner.dart';
import 'package:get/get.dart';
import 'package:stamp_park/routes/route_helper.dart';
import 'package:stamp_park/models/stamp_model.dart';

class HomePage extends StatefulWidget {
  const HomePage({Key? key}) : super(key: key);

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final AuthService authService = AuthService();
  String scannedCode = '';
  int selectedStampQuantity = 0;

  int _currentIndex = 0;

  String? username;
  String? stampCode;
  String? stampCondition;
  String? companyName;

  bool isLoading = false;

  Widget _buildLoading() {
    return CircularProgressIndicator();
  }

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  void _loadUserData() async {
    final storedUsername = await authService.getUsername();
    final storedStampCode = await authService.getStampCode();
    final storedStampCondition = await authService.getStampCondition();
    final storedCompanyName = await authService.getCompanyName();

    setState(() {
      username = storedUsername;
      stampCode = storedStampCode;
      stampCondition = storedStampCondition;
      companyName = storedCompanyName;
    });
  }

  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
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
                    'assets/images/logo.png', // Replace with the actual path to your logo image
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
              leading: const Icon(Icons.account_circle),
              title: SmallText(text: "$username", size: Dimensions.font18),
            ),
            ListTile(
              title: SmallText(text: "ประทับตรา", size: Dimensions.font18),
              onTap: () {
                Get.toNamed(RouteHelper.home);
              },
            ),
            ListTile(
              title: SmallText(text: "ประวัติสแตมป์", size: Dimensions.font18),
              onTap: () {
                Get.toNamed(RouteHelper.stampHistory);
              },
            ),
            ListTile(
              title: SmallText(text: "ออกจากระบบ", size: Dimensions.font18),
              onTap: () async {
                await authService.logout();
                Future.delayed(const Duration(milliseconds: 100), () {
                  Get.offAllNamed(RouteHelper.login);
                });
              },
            ),
          ],
        ),
      ),
      body: Builder(
        builder: (context) => Stack(
          children: [
            Align(
              alignment: Alignment.center,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  BigText(
                      text: "รหัสตราประทับ $stampCode",
                      size: Dimensions.font30),
                  SizedBox(height: Dimensions.height20),
                  SmallText(text: "บริษัท $companyName"),
                  SizedBox(height: Dimensions.height20),
                  ElevatedButton(
                    onPressed: () async {
                      var res = await Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) =>
                                const SimpleBarcodeScannerPage(),
                          ));
                      setState(() {
                        if (res is String) {
                          scannedCode = res;
                        }
                      });
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.mainColor,
                      elevation: 3,
                      padding: const EdgeInsets.all(16.0),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8.0),
                      ),
                    ),
                    child: const SmallText(
                        text: "สแกนบัตรจอดรถ", color: AppColors.whiteColor),
                  ),
                  SizedBox(height: Dimensions.height20),
                  Visibility(
                    visible: scannedCode.isNotEmpty,
                    child: Column(
                      children: [
                        SmallText(
                          text: "รหัส Barcode: $scannedCode",
                          size: Dimensions.font20,
                          color: AppColors.successColor,
                        ),
                        SizedBox(height: Dimensions.height10),
                        BigText(
                            text: "เลือกจำนวนตราประทับ",
                            size: Dimensions.font20),
                        SizedBox(height: Dimensions.height10),
                        Wrap(
                          spacing: 10.0,
                          children: List.generate(
                            6,
                            (index) => GestureDetector(
                              onTap: () {
                                setState(() {
                                  selectedStampQuantity = index + 1;
                                });
                              },
                              child: Container(
                                width: 40.0,
                                height: 40.0,
                                decoration: BoxDecoration(
                                  border: Border.all(
                                    color: AppColors.blackColor,
                                    width: 1.0,
                                  ),
                                  borderRadius: BorderRadius.circular(8.0),
                                  color: selectedStampQuantity == index + 1
                                      ? AppColors.mainColor
                                      : null,
                                ),
                                child: Center(
                                  child: Text(
                                    '${index + 1}',
                                    style: TextStyle(
                                      fontSize: 18,
                                      color: selectedStampQuantity == index + 1
                                          ? AppColors.whiteColor
                                          : AppColors.blackColor,
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                        SizedBox(height: Dimensions.height10),
                        isLoading
                            ? _buildLoading()
                            : ElevatedButton(
                                onPressed: () async {
                                  if (scannedCode.isNotEmpty &&
                                      selectedStampQuantity > 0) {
                                    final stamp = Stamp(
                                      visitorCode: scannedCode,
                                      stampCode: stampCode!,
                                      numStamp: selectedStampQuantity,
                                      stampCount: 1,
                                      recorderName: username!,
                                      stampDatetime: DateTime.now().toString(),
                                    );

                                    setState(() {
                                      isLoading = true;
                                    });

                                    final result = await StampService()
                                        .saveStampInfo(stamp);

                                    setState(() {
                                      isLoading = false;
                                    });

                                    // Check the result and show a message
                                    if (result['success']) {
                                      ScaffoldMessenger.of(context)
                                          .showSnackBar(
                                        SnackBar(
                                          content: Text(result['message']),
                                          duration: Duration(seconds: 2),
                                          backgroundColor: Colors.green,
                                        ),
                                      );
                                    } else {
                                      ScaffoldMessenger.of(context)
                                          .showSnackBar(
                                        SnackBar(
                                          content: Text(result['message']),
                                          duration: Duration(seconds: 2),
                                          backgroundColor: Colors.green,
                                        ),
                                      );
                                    }
                                    setState(() {
                                      scannedCode = '';
                                      selectedStampQuantity = 0;
                                    });
                                  } else {
                                    ScaffoldMessenger.of(context).showSnackBar(
                                      const SnackBar(
                                        content: Text(
                                            "โปรดสแกน Barcode บัตรจอดรถ และเลือกจำนวนตราประทับ"),
                                        duration: Duration(seconds: 2),
                                        backgroundColor: Colors.red,
                                      ),
                                    );
                                  }
                                },
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.green,
                                  elevation: 3,
                                  padding: const EdgeInsets.all(16.0),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(8.0),
                                  ),
                                ),
                                child: const SmallText(
                                    text: "ประทับตรา",
                                    color: AppColors.whiteColor),
                              ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            // Positioned(
            //   bottom: 0,
            //   left: 0,
            //   right: 0,
            //   child: Column(
            //     children: [
            //       Row(
            //         mainAxisAlignment: MainAxisAlignment.center,
            //         children: [
            //           ElevatedButton(
            //             onPressed: () {
            //               Get.toNamed(RouteHelper.condition);
            //             },
            //             style: ElevatedButton.styleFrom(
            //               backgroundColor: AppColors.whiteColor,
            //               elevation: 3,
            //               padding: const EdgeInsets.all(16.0),
            //               shape: RoundedRectangleBorder(
            //                 borderRadius: BorderRadius.circular(8.0),
            //                 side: BorderSide(color: AppColors.mainColor),
            //               ),
            //             ),
            //             child: SmallText(
            //               text: "เงื่อนไข",
            //               color: AppColors.mainColor,
            //               size: Dimensions.font20,
            //             ),
            //           ),
            //           SizedBox(width: Dimensions.width10),
            //           ElevatedButton(
            //             onPressed: () {
            //               Get.toNamed(RouteHelper.howToUse);
            //             },
            //             style: ElevatedButton.styleFrom(
            //               backgroundColor: AppColors.whiteColor,
            //               elevation: 3,
            //               padding: const EdgeInsets.all(16.0),
            //               shape: RoundedRectangleBorder(
            //                 borderRadius: BorderRadius.circular(8.0),
            //                 side: BorderSide(color: AppColors.mainColor),
            //               ),
            //             ),
            //             child: SmallText(
            //               text: "วิธีใช้งาน",
            //               color: AppColors.mainColor,
            //               size: Dimensions.font20,
            //             ),
            //           ),
            //         ],
            //       ),
            //       SizedBox(height: Dimensions.height30),
            //     ],
            //   ),
            // ),
          ],
        ),
      ),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }
}
