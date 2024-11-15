import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/sign_button.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class SignUpPage extends StatefulWidget {
  const SignUpPage({Key? key}) : super(key: key);

  @override
  State<SignUpPage> createState() => _SignUpPageState();
}

class _SignUpPageState extends State<SignUpPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(
          color: AppColors.darkGreyColor, // Set the color for the back button
        ),
        centerTitle: false,
      ),
      body: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              height: MediaQuery.of(context).size.height / 3,
              decoration: const BoxDecoration(
                  image: DecorationImage(
                      image: AssetImage("assets/icons/signup.jpg"))),
            ),
            const BigText(text: "ลงทะเบียน"),
            SizedBox(height: Dimensions.height10),
            const SmallText(text: "ลงทะเบียนเบอร์โทรศัพท์หรืออีเมล"),
            SizedBox(height: Dimensions.height30),
            Column(children: <Widget>[
              Container(
                width: 320, // Set the desired width
                decoration: const BoxDecoration(
                  border: Border(
                    bottom: BorderSide(color: AppColors.blackColor),
                  ),
                ),
                child: TextField(
                  decoration: const InputDecoration(
                    hintText: 'กรุณากรอกเบอร์โทรศัพท์หรืออีเมล',
                    border: InputBorder.none,
                  ),
                  style: TextStyle(
                    fontSize: Dimensions.font16,
                  ),
                ),
              ),
              SizedBox(height: Dimensions.height10),
              Column(children: <Widget>[
                SignButton(
                  text: "ยืนยัน",
                  textColor: AppColors.secondaryTextColor,
                  bgColor: AppColors.greyColor,
                  borderColor: AppColors.greyColor,
                  routeTo: () {
                    Get.toNamed(RouteHelper.home);
                  },
                ),
              ]),
            ]),
          ],
        ),
      ),
    );
  }
}
