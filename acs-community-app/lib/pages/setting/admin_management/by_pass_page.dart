import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:flutter/material.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'dart:math';
import 'package:acs_community/widgets/main_button.dart';

class ByPassPage extends StatefulWidget {
  const ByPassPage({Key? key}) : super(key: key);

  @override
  State<ByPassPage> createState() => _ByPassPageState();
}

class _ByPassPageState extends State<ByPassPage> {
  late String qrData;

  @override
  void initState() {
    super.initState();
    qrData = generateQRData("byPass");
  }

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

  String generateQRData(String originalData) {
    String preHash = generateRandomCode(10);
    String lastHash = generateRandomCode(34);
    String qrData = preHash + originalData + lastHash;

    return qrData;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        iconTheme: const IconThemeData(color: Colors.grey),
        centerTitle: true,
        title: const Text("Bypass", style: TextStyle(fontSize: 20)),
      ),
      backgroundColor: Colors.white,
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          children: [
             GestureDetector(
              onTap: () {
                setState(() {
                  qrData = generateQRData("byPass");
                });
              },
              child: const MainButton(
                text: "Re Generate",
                textColor: AppColors.whiteColor,
                bgColor: AppColors.mainColor,
                borderColor: AppColors.mainColor,
              ),
            ),
            QrImageView(
              data: qrData,
              size: 200,
            ),
            const SizedBox(height: 20),
            const Text(
              "Scan this QR Code to bypass",
              style: TextStyle(fontSize: 18),
            ),
          ],
        ),
      ),
    );
  }
}
