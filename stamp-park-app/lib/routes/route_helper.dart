import 'package:get/get.dart';
import 'package:stamp_park/pages/auth/login_page.dart';
import 'package:stamp_park/pages/home/home_page.dart';
import 'package:stamp_park/pages/menu/stamp_history_page.dart';
import 'package:stamp_park/pages/menu/condition_page.dart';
import 'package:stamp_park/pages/menu/how_to_use_page.dart';


class RouteHelper {
  static String initial = "/";
  static String login = "/login";
  static String home = "/home";
  static String stampHistory = "/stampHistory";
  static String condition = "/condition";
  static String howToUse = "/howToUse";

  static List<GetPage> routes = [
    GetPage(name: home, page: () => HomePage()),
    GetPage(name: login, page: () => LogInPage()),
    GetPage(name: stampHistory, page: () => StampHistoryPage()),
    GetPage(name: condition, page: () => ConditionPage()),
    GetPage(name: howToUse, page: () => HowToUsePage()),
  ];
}
