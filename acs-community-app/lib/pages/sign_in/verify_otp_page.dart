import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/code_textfield.dart';
import 'package:acs_community/widgets/sign_button.dart';
import 'package:acs_community/widgets/text_underline.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class VerifyOtpPage extends StatefulWidget {
  const VerifyOtpPage({Key? key}) : super(key: key);

  @override
  State<VerifyOtpPage> createState() => _VerifyOtpPageState();
}

class _VerifyOtpPageState extends State<VerifyOtpPage> {
  //Use for callback code_textfield widget
  bool isButtonEnabled = false;
  void updateButtonState(bool isEnabled) {
    setState(() {
      isButtonEnabled = isEnabled;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
        centerTitle: true,
      ),
      backgroundColor: AppColors.whiteColor,
      body: SingleChildScrollView(
        child: Center(
          child: Column(
            children: [
              SizedBox(height: Dimensions.height30),
              Container(
                height: 150,
                width: 150,
                decoration: const BoxDecoration(
                    image: DecorationImage(
                        image: AssetImage("assets/icons/pin.png"))),
              ),
              SizedBox(height: Dimensions.height30),
              BigText(text: "ยืนยันรหัส", size: Dimensions.font26),
              SizedBox(height: Dimensions.height10),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const SmallText(text: "รหัสยืนยันถูกส่งไปยัง"),
                  SizedBox(width: Dimensions.width5),
                  BigText(
                      text: "+66828951234",
                      size: Dimensions.font14,
                      color: AppColors.darkGreyColor)
                ],
              ),
              SizedBox(height: Dimensions.height30),
              CodeTextField(
                  length: 6,
                  isButtonEnabled: isButtonEnabled,
                  updateButtonState: updateButtonState),
              SizedBox(height: Dimensions.height5),
              SmallText(
                  text: "(เลขอ้างอิง: 3xJp)",
                  size: Dimensions.font16,
                  color: AppColors.darkGreyColor),
              SizedBox(height: Dimensions.height30),
              SignButton(
                text: "ยืนยันรหัส",
                textColor: AppColors.darkGreyColor,
                bgColor: AppColors.greyColor,
                borderColor: AppColors.greyColor,
                routeTo: () {
                  Get.toNamed(RouteHelper.home);
                },
              ),
              SizedBox(height: Dimensions.height10),
              const TextUnderline(
                  text: "ส่งรหัสยืนยันไปที่เบอร์โทรศัพท์อีกครั้ง"),
              SizedBox(height: Dimensions.height10),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const SmallText(
                      text: "ต้องการความช่วยเหลือ ติดต่อ LINE",
                      color: AppColors.darkGreyColor),
                  SizedBox(width: Dimensions.width10),
                  TextUnderline(
                      text: "acscommunity", sizeText: Dimensions.font14),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
