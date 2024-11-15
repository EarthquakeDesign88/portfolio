import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/bottom_navbar.dart';
import 'package:acs_community/services/qr_data.dart';
import 'package:acs_community/pages/auth_access/components/body_auth_access.dart';
import 'dart:math';

class AuthAccessPage extends StatefulWidget {
  const AuthAccessPage({Key? key}) : super(key: key);
  @override
  State<AuthAccessPage> createState() => _AuthAccessPageState();
}

class _AuthAccessPageState extends State<AuthAccessPage> {
  String generateRandomCode(int length) {
    final random = Random();
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    String code = '';

    for (int i = 0; i < length; i++) {
      final index = random.nextInt(chars.length);
      code += chars[index];
    }

    return code;
  }

  String qrData = '';
  int expirationTime = 3 * 40; // 2 minutes in seconds

  void generateQRCode() {
    String randomData = generateRandomCode(50);
    sendQRData(randomData);
    setState(() {
      qrData = randomData;
    });
  }

  @override
  void initState() {
    super.initState();
    generateQRCode();
  }

  int _currentIndex = 0;
  
  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(
          color: AppColors.darkGreyColor,
        ),
        centerTitle: true,
        title: BigText(text: "เข้าใช้งานลิฟท์", size: Dimensions.font20)
      ),
      body: BodyAuthAccess(qrData: qrData),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }
}
