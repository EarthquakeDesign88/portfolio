import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'dart:async';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:logger/logger.dart';

class BodyAuthAccess extends StatefulWidget {
  final String qrData;

  const BodyAuthAccess({
    Key? key,
    required this.qrData,
  }) : super(key: key);

  @override
  _BodyAuthAccessState createState() => _BodyAuthAccessState();
}

class _BodyAuthAccessState extends State<BodyAuthAccess> {
  int remainingSeconds = 120; // 2 minutes in seconds
  late Timer countdownTimer;
  bool isQRCodeAuthenticated = false;

  @override
  void initState() {
    super.initState();
    startCountdown();
    checkQRCodeStatus();
  }

  void startCountdown() {
    countdownTimer = Timer.periodic(const Duration(seconds: 1), (timer) {
      setState(() {
        remainingSeconds--;
      });
      if (remainingSeconds <= 0) {
        timer.cancel(); // Stop the timer when countdown finishes
      }
    });
  }

  void showAuthenticatedDialog() {
    showDialog(
      context: context,
      barrierDismissible: false, 
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Authenticated successfully'),
          content: const Text('ยืนยันตัวตนสำเร็จ'),
          actions: <Widget>[
            TextButton(
              child: const Text('OK'),
              onPressed: () {
                Get.toNamed(RouteHelper.home);
              },
            ),
          ],
        );
      },
    );
  }

  void showQRCodeExpiredDialog() {
    showDialog(
      context: context,
      barrierDismissible: false, 
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('QR Code Expired'),
          content: const Text('QR Code หมดอายุ ไม่สามารถใช้งานได้'),
          actions: <Widget>[
            TextButton(
              child: const Text('OK'),
              onPressed: () {
                Get.toNamed(RouteHelper.home);
              },
            ),
          ],
        );
      },
    );
  }

  Future<void> checkQRCodeStatus() async {
    final Logger logger = Logger();
    const endpoint = 'https://www.eptg-acsc.co.th/app-backend/api/check_status.php?qr_data=';
    
    try {
      await Future.doWhile(() async {
        final res = await http.get(Uri.parse('$endpoint${widget.qrData}'));

        if (res.statusCode == 200) {
          final responseJson = jsonDecode(res.body);
          final status = responseJson['status'];

          if (status == 'Authenticated successfully') {
            setState(() {
              isQRCodeAuthenticated = true; // Update the state
            });
            showAuthenticatedDialog();
            return false; // Stop checking
          }
        }

        // Continue checking every second until the timeout or authenticated
        await Future.delayed(const Duration(seconds: 1));
        return remainingSeconds > 0;
      });

      // If the loop exits, it means the QR code status did not change
      if (!isQRCodeAuthenticated) {
        showQRCodeExpiredDialog();
      }
    } catch (err) {
      logger.e('Error checking QR code status: $err');
    }
  }

  @override
  void dispose() {
    countdownTimer.cancel(); // Cancel the timer when the widget is disposed
    super.dispose();
  }

  String formatDuration(Duration duration) {
    String twoDigits(int n) {
      if (n >= 10) return "$n";
      return "0$n";
    }

    String minutes = twoDigits(duration.inMinutes);
    String seconds = twoDigits(duration.inSeconds.remainder(60));
    return "$minutes:$seconds";
  }

  @override
  Widget build(BuildContext context) {
    if (remainingSeconds > 0) {
      return buildWidgetWhenTimerIsRunning();
    } else {
      return buildWidgetWhenTimerExpires();
    }
  }

  Widget buildWidgetWhenTimerIsRunning() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          BigText(text: "QR Code เพื่อใช้ยืนยันตัวตน", size: Dimensions.font20),
          QrImageView(
            data: widget.qrData,
            size: 200,
          ),
          SizedBox(height: Dimensions.height20),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              SmallText(
                text: "โปรดสแกน QR Code ภายใน ",
                size: Dimensions.font16,
                color: AppColors.blackColor,
              ),
              SizedBox(width: Dimensions.width5),
              BigText(
                text: "${formatDuration(Duration(seconds: remainingSeconds))}",
                size: Dimensions.font16,
                color: AppColors.mainColor,
              ),
            ],
          ),
          SizedBox(height: Dimensions.height10),
          Visibility(
            visible: remainingSeconds >
                0, // Hide if remainingSeconds is zero or less
            child: SmallText(
              text: "นำ QR Code แนบที่เครื่องสแกน",
              size: Dimensions.font16,
              color: AppColors.blackColor,
            ),
          ),
          SizedBox(height: Dimensions.height20),
        ],
      ),
    );
  }

  Widget buildWidgetWhenTimerExpires() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          BigText(text: "QR Code เพื่อใช้ยืนยันตัวตน", size: Dimensions.font20),
          QrImageView(
            data: widget.qrData,
            size: 200,
          ),
          SizedBox(height: Dimensions.height20),
          BigText(
            text: "QR Code หมดอายุ ไม่สามารถใช้งานได้",
            size: Dimensions.font16,
            color: Colors.red,
          ),
          SizedBox(height: Dimensions.height20),
        ],
      ),
    );
  }
}
