import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/utils/app_constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/sign_button.dart';
import 'package:acs_community/widgets/social_card.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class SignInPage extends StatefulWidget {
  const SignInPage({Key? key}) : super(key: key);

  @override
  State<SignInPage> createState() => _SignInPageState();
}

class _SignInPageState extends State<SignInPage> {
  @override
  Widget build(BuildContext context) {
    Dimensions.init(context); // Initialize constants
    final currentYear = DateTime.now().year;

    return Scaffold(
        resizeToAvoidBottomInset: false,
        backgroundColor: AppColors.whiteColor,
        appBar: AppBar(
          elevation: 0,
          backgroundColor: AppColors.whiteColor,
          actions: [
            Padding(
              padding: EdgeInsets.only(right: Dimensions.width15),
              child: IconButton(
                icon: Icon(
                  Icons.language,
                  size: Dimensions.iconSize40,
                  color: AppColors.mainColor,
                ),
                onPressed: () {
                  Navigator.pop(context);
                },
              ),
            ),
          ],
        ),
        body: SafeArea(
            child: SingleChildScrollView(
          child: SizedBox(
            width: double.infinity,
            height: MediaQuery.of(context).size.height,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: <Widget>[
                      Column(
                        children: <Widget>[
                          const BigText(text: "เข้าสู่ระบบ"),
                          SizedBox(height: Dimensions.height10),
                          Container(
                            width: 320, // Set the desired width
                            decoration: const BoxDecoration(
                              border: Border(
                                bottom: BorderSide(color: AppColors.blackColor),
                              ),
                            ),
                            child: TextField(
                              decoration: const InputDecoration(
                                hintText: 'เบอร์โทรศัพท์หรืออีเมล',
                                border: InputBorder.none,
                              ),
                              style: TextStyle(
                                fontSize: Dimensions.font16,
                              ),
                            ),
                          ),
                          SizedBox(height: Dimensions.height10),
                          SignButton(
                            text: "เข้าสู่ระบบ",
                            textColor: AppColors.whiteColor,
                            bgColor: AppColors.greyColor,
                            borderColor: AppColors.greyColor,
                            routeTo: () {
                              Get.toNamed(RouteHelper.confirmSignIn);
                            },
                          ),
                          SizedBox(height: Dimensions.height10),
                          const SmallText(
                              text: "หรือ", color: AppColors.greyColor),
                          SizedBox(height: Dimensions.height2),
                          SocialCard(
                              text: "เข้าสู่ระบบด้วย Facebook",
                              icon: Icons.facebook_outlined,
                              color: AppColors.facebookColor,
                              routeTo: () {
                                Get.toNamed(RouteHelper.inprogressSignIn);
                              }),
                          SizedBox(height: Dimensions.height2),
                          SocialCard(
                              text: "ลงชื่อเข้าด้วย Google",
                              icon: Icons.g_mobiledata,
                              color: AppColors.gmailColor,
                              routeTo: () {
                                Get.toNamed(RouteHelper.inprogressSignIn);
                              }),
                          SizedBox(height: Dimensions.height2),
                          SocialCard(
                              text: "ลงชื่อเข้าด้วย Apple",
                              icon: Icons.apple,
                              color: AppColors.appleColor,
                              routeTo: () {
                                Get.toNamed(RouteHelper.inprogressSignIn);
                              }),
                          SizedBox(height: Dimensions.height15),
                          GestureDetector(
                              onTap: () {
                                Get.toNamed(RouteHelper.signUp);
                              },
                              child: const SmallText(
                                  text: "ผู้ใช้ใหม่กด ลงทะเบียน",
                                  color: AppColors.primaryTextColor)),
                          SizedBox(height: Dimensions.height5),
                          Column(
                            children: [
                              SmallText(
                                  text:
                                      "ต้องการความช่วยเหลือ ติดต่อ LINE ${AppConstants.lineContact}",
                                  size: Dimensions.font14,
                                  color: AppColors.greyColor),
                              SizedBox(height: Dimensions.height5),
                              SmallText(
                                  text: "version ${AppConstants.appVersion}",
                                  size: Dimensions.font14,
                                  color: AppColors.greyColor),
                              SizedBox(height: Dimensions.height5),
                              SmallText(
                                  text:
                                      "©$currentYear acs.app All rights reserved",
                                  size: Dimensions.font14,
                                  color: AppColors.greyColor),
                            ],
                          )
                        ],
                      ),
                    ],
                  ),
                )
              ],
            ),
          ),
        )
      )
    );
  }
}
