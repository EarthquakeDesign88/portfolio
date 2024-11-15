import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/pages/setting/components/body_phone_number.dart';

class PhoneNumberPersonalInfoPage extends StatefulWidget {
  const PhoneNumberPersonalInfoPage({Key? key}) : super(key: key);

  @override
  State<PhoneNumberPersonalInfoPage> createState() =>
      _PhoneNumberPersonalInfoPageState();
}

class _PhoneNumberPersonalInfoPageState extends State<PhoneNumberPersonalInfoPage> {

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
      body: const BodyPhoneNumber()
    );
  }
}
