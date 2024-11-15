import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class BodyAdminManagement extends StatelessWidget {
  const BodyAdminManagement({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
      child: Column(
        children: [
          SizedBox(height: Dimensions.height20),
          MainButton(
            text: "By Pass",
            textColor: AppColors.secondaryTextColor,
            bgColor: AppColors.greyColor,
            borderColor: AppColors.greyColor,
            routeTo: () {
              Get.toNamed(RouteHelper.byPass);
            },
          ),
        ],
      ),
    );
  }
}
