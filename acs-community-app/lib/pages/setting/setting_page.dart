import 'package:acs_community/utils/constants.dart';
import 'package:flutter/material.dart';
import 'package:acs_community/widgets/bottom_navbar.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/setting/components/body.dart';

class SettingPage extends StatefulWidget {
  const SettingPage({Key? key}) : super(key: key);

  @override
  State<SettingPage> createState() => _SettingPageState();
}

class _SettingPageState extends State<SettingPage> {
  int _currentIndex = 0;
  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.menuColor,
      appBar: AppBar(
          elevation: 0,
          backgroundColor: AppColors.whiteColor,
          iconTheme: const IconThemeData(
            color: AppColors.darkGreyColor,
          ),
          automaticallyImplyLeading: false,
          centerTitle: true,
          title: BigText(text: "การตั้งค่า", size: Dimensions.font20)),
      body: const Body(),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }
}
