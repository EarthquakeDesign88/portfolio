import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/faq_controller.dart';

class BodyFaq extends StatelessWidget {
  final FaqController _faqController = Get.find();

  @override
  Widget build(BuildContext context) {
    _faqController.fetchFaq();

    return Obx(() {
      if (_faqController.faqLists.isEmpty) {
        return const Center(
          child: CircularProgressIndicator(),
        );
      } else {
        return Container(
          height: 480,
          color: AppColors.whiteColor,
          child: Padding(
            padding: EdgeInsets.only(left: Dimensions.width15),
            child: ListView.builder(
              itemCount: _faqController.getFaqCount(),
              itemBuilder: (context, index) {
                final faq = _faqController.faqLists[index];
                return Column(
                  children: [
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.getAnswer(faq.id));
                      },
                      child: Row(
                        children: [
                          Expanded(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.start,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                BigText(
                                  text: faq.question,
                                  size: Dimensions.font14,
                                  color: AppColors.darkGreyColor,
                                ),
                              ],
                            ),
                          ),
                          Align(
                            alignment: Alignment.centerRight,
                            child: CustomIcon(
                              height: Dimensions.width50,
                              width: Dimensions.width50,
                              bgColor: AppColors.whiteColor,
                              iconColor: AppColors.darkGreyColor,
                              icon: Icons.chevron_right,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const BottomLine(),
                  ],
                );
              },
            ),
          ),
        );
      }
    });
  }
}
