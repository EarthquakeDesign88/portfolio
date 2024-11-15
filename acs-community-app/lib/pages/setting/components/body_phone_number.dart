import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:intl_phone_number_input/intl_phone_number_input.dart';
import 'package:acs_community/widgets/main_button.dart';

class BodyPhoneNumber extends StatefulWidget {
  const BodyPhoneNumber({Key ? key}) : super(key: key);

  @override
  State<BodyPhoneNumber> createState() => _BodyPhoneNumberState();
}

class _BodyPhoneNumberState extends State<BodyPhoneNumber> {
  @override
  Widget build(BuildContext context) {
    String phoneNumber = '0824552296';

    return SingleChildScrollView(
      child: Padding(
        padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
        child: Column(
          children: [
            SizedBox(height: Dimensions.height30),
            Container(
              height: 150,
              width: 150,
              decoration: const BoxDecoration(
                  image: DecorationImage(
                      image: AssetImage("assets/icons/otp.jpg"))),
            ),
            SizedBox(height: Dimensions.height10),
            const BigText(text: "แก้ไขเบอร์โทรศัพท์", size: 24),
            SizedBox(height: Dimensions.height10),
            const SmallText(text: "แก้ไขเบอร์โทรศัพท์สำหรับการเข้าสู่ระบบครั้งถัดไป", size: 14),
            SizedBox(height: Dimensions.height40),
            Column(
              children: <Widget>[
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
                  initialValue:PhoneNumber(
                    phoneNumber: phoneNumber,
                    isoCode: 'TH'
                  ), // Set initial country code
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
              MainButton(
                text: "ส่งรหัส OTP",
                textColor:  phoneNumber != '' ? AppColors.whiteColor : AppColors.darkGreyColor,
                bgColor: phoneNumber != '' ? AppColors.mainColor : AppColors.greyColor,
                borderColor: phoneNumber != '' ? AppColors.mainColor : AppColors.greyColor,
                routeTo: () {},
              ),
            ]),
          ],
        ),
      ),
    );
  }
}
