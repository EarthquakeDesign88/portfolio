import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';

class BodyAppVersion extends StatelessWidget {
  const BodyAppVersion({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
      child: Column(
        children: [
          SizedBox(height: Dimensions.height20),
          Container(
            height: 150,
            width: 150,
            decoration: const BoxDecoration(
                image: DecorationImage(
                    image: AssetImage("assets/icons/app_version.jpg"))),
          ),
          SizedBox(height: Dimensions.height10),
          const BigText(text: "คุณได้รับอัปเดตแอปฯ", size: 24),
          SizedBox(height: Dimensions.height10),
          const BigText(text: "เป็นเวอร์ชันล่าสุดแล้ว", size: 24),
          SizedBox(height: Dimensions.height30),
          const SmallText(text: "แอปฯเวอร์ชัน 1.0.1", size: 18),
          SizedBox(height: Dimensions.height30),
          MainButton(
            text: "อัปเดต",
            textColor: AppColors.secondaryTextColor,
            bgColor: AppColors.greyColor,
            borderColor: AppColors.greyColor,
            routeTo: () {},
          ),
        ],
      ),
    );
  }
}
