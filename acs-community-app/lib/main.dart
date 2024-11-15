// import 'package:acs_community/controllers/phone_book_controller.dart';
import 'package:acs_community/pages/home/home_page.dart';
// import 'package:acs_community/pages/welcome/welcome_page.dart';
import 'package:flutter/material.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:get/get.dart';
// import 'package:intl/date_symbol_data_local.dart';
import 'package:acs_community/controllers/facility_controller.dart';
import 'package:acs_community/controllers/property_management_controller.dart';
import 'package:acs_community/controllers/faq_controller.dart';

void main() async {
  // WidgetsFlutterBinding.ensureInitialized(); // Initialize GetX
  // initializeDateFormatting('th');

  Get.put(FacilityController());
  Get.put(PropertyManagementController());
  Get.put(FaqController());


  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    Dimensions.init(context);

    return GetMaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'ACS Community',
      // initialRoute: RouteHelper.getWelcome(),
      home: const HomePage(),
      getPages: RouteHelper.routes
    );
  }
}
