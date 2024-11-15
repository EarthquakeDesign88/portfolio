import 'package:flutter/material.dart';
import 'package:stamp_park/pages/home/home_page.dart';
import 'package:stamp_park/pages/auth/login_page.dart';
import 'package:stamp_park/services/auth_service.dart';
import 'package:stamp_park/utils/constants.dart';
import 'package:get/get.dart';
import 'package:stamp_park/routes/route_helper.dart';
import 'package:stamp_park/controllers/stamp_controller.dart';

void main() async {
  Get.put(StampController());

  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  final AuthService authService = AuthService();

  @override
  Widget build(BuildContext context) {
    Dimensions.init(context);

    return GetMaterialApp(
      debugShowCheckedModeBanner: false,
      title: "Stamp Park",
      theme: ThemeData(
        primaryColor: AppColors.mainColor,
      ),
      home: FutureBuilder<bool>(
        future: authService.isLoggedIn(),
        builder: (context, snapshot) {
          // if (snapshot.connectionState == ConnectionState.waiting) {
          //    return Center(child: CircularProgressIndicator());
          // } else {
          //   final isLoggedIn = snapshot.data ?? false;
          //   return isLoggedIn ? HomePage() : LogInPage();
          // }

          final isLoggedIn = snapshot.data ?? false;
          return isLoggedIn ? HomePage() : LogInPage();
        },
      ),
      getPages: RouteHelper.routes
    );
  }
}
