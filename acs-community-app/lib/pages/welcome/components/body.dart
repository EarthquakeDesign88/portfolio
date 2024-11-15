import 'package:flutter/material.dart';
import 'package:acs_community/utils/app_constants.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class Body extends StatelessWidget {
  const Body({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Container(
        width: double.infinity,
        height: MediaQuery.of(context).size.height,
        padding: EdgeInsets.symmetric(
            horizontal: Dimensions.width30, vertical: Dimensions.height50),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: <Widget>[
            Column(
              children: [
                const BigText(
                    text: "ยินดีต้อนรับเข้าสู่", color: AppColors.mainColor),
                SizedBox(height: Dimensions.height20),
                SmallText(text: AppConstants.appName, size: Dimensions.font20)
              ],
            ),
            Container(
              height: MediaQuery.of(context).size.height / 3,
              decoration: const BoxDecoration(
                  image: DecorationImage(
                      image: AssetImage("assets/images/tuk-chang.jpeg"))),
            ),
            Column(
              children: <Widget>[
                MaterialButton(
                    minWidth: double.infinity,
                    height: Dimensions.height60,
                    onPressed: () {
                      Get.toNamed(RouteHelper.signIn);
                    },
                    shape: RoundedRectangleBorder(
                      side: const BorderSide(
                        color: AppColors.mainColor,
                      ),
                      borderRadius: BorderRadius.circular(Dimensions.radius50),
                    ),
                    child: BigText(
                        text: "เข้าสู่ระบบ",
                        size: Dimensions.font18,
                        color: AppColors.mainColor)),
                SizedBox(height: Dimensions.height20),
                MaterialButton(
                    minWidth: double.infinity,
                    height: Dimensions.height60,
                    color: AppColors.mainColor,
                    onPressed: () {
                      Get.toNamed(RouteHelper.signUp);
                    },
                    shape: RoundedRectangleBorder(
                      side: const BorderSide(
                        color: AppColors.mainColor,
                      ),
                      borderRadius: BorderRadius.circular(Dimensions.radius50),
                    ),
                    child: BigText(
                        text: "ลงทะเบียน",
                        size: Dimensions.font18,
                        color: AppColors.whiteColor))
              ],
            )
          ],
        ),
      ),
    );
  }
}
