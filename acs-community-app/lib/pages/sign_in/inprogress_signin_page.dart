import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/sign_button.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class InprogressSignInPage extends StatelessWidget {
  const InprogressSignInPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    Dimensions.init(context); //
    return Scaffold(
      body: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const BigText(text: "อีกนิดนึงนะ"),
          Container(
            height: MediaQuery.of(context).size.height / 3,
            decoration: const BoxDecoration(
                image: DecorationImage(
                    image: AssetImage("assets/icons/inprogress_signin.jpg"))),
          ),
          SizedBox(height: Dimensions.height10),
          BigText(text: "ชื่อจริง", size: Dimensions.font26),
          SizedBox(height: Dimensions.height10),
          const SmallText(text: "โปรดยืนยันชุมชนของคุณ"),
          SizedBox(height: Dimensions.height30),
          Column(children: <Widget>[
            SignButton(
              text: "ถัดไป",
              textColor: AppColors.mainColor,
              bgColor: AppColors.whiteColor,
              borderColor: AppColors.mainColor,
              routeTo: () {
                Get.toNamed(RouteHelper.communityConfirm);
              },
            ),
            SizedBox(height: Dimensions.height10),
            SignButton(
              text: "ออกจากระบบ",
              textColor: AppColors.secondaryTextColor,
              bgColor: AppColors.greyColor,
              borderColor: AppColors.greyColor,
              routeTo: () {
                Get.toNamed(RouteHelper.welcome);
              },
            ),
          ]),
        ],
      ),
    );
  }
}
