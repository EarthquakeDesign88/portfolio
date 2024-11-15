import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:intl_phone_number_input/intl_phone_number_input.dart';
import 'package:acs_community/widgets/sign_button.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class ConfirmSigninPage extends StatefulWidget {
  const ConfirmSigninPage({Key? key}) : super(key: key);

  @override
  State<ConfirmSigninPage> createState() => _ConfirmSigninPageState();
}

class _ConfirmSigninPageState extends State<ConfirmSigninPage> {
  String phoneNumber = '';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SizedBox(height: Dimensions.height70),
            Container(
              height: MediaQuery.of(context).size.height / 3,
              decoration: const BoxDecoration(
                  image: DecorationImage(
                      image: AssetImage("assets/icons/otp.jpg"))),
            ),
            const BigText(text: "เข้าสู่ระบบ"),
            SizedBox(height: Dimensions.height10),
            const SmallText(text: "ยืนยันเบอร์โทรศัพท์สำหรับการเข้าสู่ระบบ"),
            SizedBox(height: Dimensions.height50),
            Column(children: <Widget>[
              SizedBox(
                width: 320,
                child: InternationalPhoneNumberInput(
                  onInputChanged: (PhoneNumber number) {
                    setState(() {
                      phoneNumber = number.phoneNumber!;
                    });
                  },
                  selectorConfig: const SelectorConfig(
                    selectorType: PhoneInputSelectorType.BOTTOM_SHEET,
                  ),
                  ignoreBlank: false,
                  autoValidateMode: AutovalidateMode.onUserInteraction,
                  selectorTextStyle:
                      const TextStyle(color: AppColors.blackColor),
                  initialValue:
                      PhoneNumber(isoCode: 'TH'), // Set initial country code
                  textFieldController: TextEditingController(),
                  formatInput: true,
                  keyboardType: const TextInputType.numberWithOptions(
                      signed: true, decimal: true),
                  inputDecoration: const InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'กรุณากรอกเบอร์โทรศัพท์',
                  ),
                ),
              ),
              SizedBox(height: Dimensions.height40),
              SignButton(
                text: "ยืนยันรหัส OTP",
                textColor: AppColors.whiteColor,
                bgColor: AppColors.mainColor,
                borderColor: AppColors.mainColor,
                routeTo: () {
                  Get.toNamed(RouteHelper.verifyOtp);
                },
              ),
            ]),
          ],
        ),
      ),
    );
  }
}
