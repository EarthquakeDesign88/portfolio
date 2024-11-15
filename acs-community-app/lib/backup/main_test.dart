// import 'package:acs_community/pages/home/home_page.dart';
// import 'package:acs_community/widgets/small_text.dart';
// // import 'package:acs_community/pages/welcome/welcome_page.dart';
// import 'package:flutter/material.dart';
// import 'package:acs_community/routes/route_helper.dart';
// import 'package:acs_community/utils/constants.dart';
// import 'package:get/get.dart';
// import 'package:intl/date_symbol_data_local.dart';
// import 'package:acs_community/controllers/covid_controller.dart';

// void main() {
//   WidgetsFlutterBinding.ensureInitialized(); // Initialize GetX
//   initializeDateFormatting('th');
//   Get.put(CovidController());

//   runApp(MyApp());
// }

// class MyApp extends StatelessWidget {
//   MyApp({Key? key}) : super(key: key);
//   final CovidController _covidController = Get.find();

//   // This widget is the root of your application.
//   @override
//   Widget build(BuildContext context) {
//     Dimensions.init(context);
//     _covidController.fetchCovid();

//     return GetMaterialApp(
//       debugShowCheckedModeBanner: false,
//       title: 'ACS Community',
//       // initialRoute: RouteHelper.getWelcome(),
//       home: const HomePage(),
//       getPages: RouteHelper.routes
//     );

  //  return MaterialApp(
  //     debugShowCheckedModeBanner: false,
  //     title: 'ACS Community',
  //     home: Scaffold(
  //       body: Container(
  //         height: MediaQuery.of(context).size.height,
  //         width: MediaQuery.of(context).size.width,
  //         child: SingleChildScrollView(
  //           child: Obx(() {
  //             if (_covidController.covidLists.isEmpty) {
  //               return const Center(
  //                 child: CircularProgressIndicator(),
  //               );
  //             } else {
  //               return Column(
  //                 mainAxisAlignment: MainAxisAlignment.center,
  //                 children: [
  //                   SizedBox(height: Dimensions.height80),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].year.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].weekNum.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].newCase.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].totalCase.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].newCaseExcludeabroad.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].totalCaseExcludeabroad.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].newRecovered.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].totalRecovered.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].newDeath.toString(),
  //                   ),
  //                   SmallText(
  //                     text: _covidController.covidLists[0].totalDeath.toString(),
  //                   ),
  //                 ],
  //               );
  //             }
  //           }),
  //         ),
  //       ),
  //     ),
  //   );

//   }
// }
