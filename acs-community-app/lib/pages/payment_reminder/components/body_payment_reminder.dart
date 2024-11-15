import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class BodyPaymentReminder extends StatelessWidget {
  const BodyPaymentReminder({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      color: AppColors.menuColor,
      child: ListView(
        children: [
          SizedBox(height: Dimensions.height20),
          InkWell(
            onTap: () {
              Get.toNamed(RouteHelper.paymentReminderDetail);
            },
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
              child: Container(
                height: 200,
                width: 200,
                decoration: BoxDecoration(
                  color: AppColors.whiteColor,
                  borderRadius: BorderRadius.circular(Dimensions.radius15),
                  border: Border.all(
                    color: AppColors.whiteColor,
                    width: 2.0,
                  ),
                ),
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SizedBox(height: Dimensions.height10),
                      const Expanded(
                        child: SmallText(
                            text:
                                "บ้านเลขที่ 3300/25 มียอดรวมที่ต้องชำระค่าส่วนกลาง 12,000.50 บาท กรุณาชำระยอดดังกล่าว ภายในวันที่ 30 ก.ค. 2566",
                            color: AppColors.blackColor),
                      ),
                      SizedBox(height: Dimensions.height10),
                      Expanded(
                        child: Row(
                          children: [
                            BigText(
                              text: "ดูการชำระเงิน/แนบหลักฐาน",
                              color: AppColors.mainColor,
                              size: Dimensions.font16
                            ),
                            CustomIcon(
                              height: Dimensions.width20,
                              width: Dimensions.width20,
                              bgColor: AppColors.whiteColor,
                              iconColor: AppColors.mainColor,
                              icon: Icons.chevron_right,
                            )
                          ],
                        ),
                      )
                    ],
                  ),
                ),
              ),
            ),
          )
        ],
      ),
    );
  }
}
