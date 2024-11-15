import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/faq_controller.dart';
import 'package:acs_community/models/faq_model.dart';

class BodyFaqDetail extends StatelessWidget {
  final int faqId;
  BodyFaqDetail({Key? key, required this.faqId}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final FaqController _faqController = Get.find();
    final Faq? faq = _faqController.fetchFaqAnswer(faqId);

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width20),
      child: Column(
        children: [
          SizedBox(height: Dimensions.height20),
          Align(
              alignment: Alignment.centerLeft, child: SmallText(text: faq?.question ?? 'ไม่พบข้อมูล')),
          SizedBox(height: Dimensions.height10),
          SmallText(text: faq?.answer ?? 'ไม่พบข้อมูล'),
          Expanded(
            child: Container(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  BottomLine(),
                  SizedBox(height: Dimensions.height10),
                  const SmallText(text: "ข้อมูลนี้ตอบคำถามของคุณหรือเปล่า?"),
                  SizedBox(height: Dimensions.height10),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      const CustomIcon(
                        icon: Icons.check,
                        bgColor: AppColors.whiteColor,
                        iconColor: AppColors.blackColor,
                        iconSize: 30,
                      ),
                      SmallText(text: "ใช่"),
                      SizedBox(width: Dimensions.width10),
                      const CustomIcon(
                        icon: Icons.dangerous_outlined,
                        bgColor: AppColors.whiteColor,
                        iconColor: AppColors.blackColor,
                        iconSize: 30,
                      ),
                      const SmallText(text: "ไม่"),
                    ],
                  ),
                  SizedBox(height: Dimensions.height20),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
