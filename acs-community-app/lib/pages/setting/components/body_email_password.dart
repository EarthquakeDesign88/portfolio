import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class BodyEmailPassword extends StatelessWidget {
  const BodyEmailPassword({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
      child: Column(
        children: [
          SizedBox(height: Dimensions.height10),
          InkWell(
            onTap: () {
              Get.toNamed(RouteHelper.emailPersonalInfo);
            },
            child: Row(
              children: [
                CustomIcon(
                  height: Dimensions.width40,
                  width: Dimensions.width40,
                  bgColor: AppColors.secondaryColor,
                  iconColor: AppColors.mainColor,
                  icon: Icons.email_outlined,
                  shape: 'circle'
                ),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      BigText(
                        text: "อีเมล", size: Dimensions.font14
                      ),
                      SizedBox(height: Dimensions.height5),
                      SmallText(
                        text: "เพิ่มอีเมล",
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
          SizedBox(height: Dimensions.height20),
          Row(
            children: [
              CustomIcon(
                height: Dimensions.width40,
                width: Dimensions.width40,
                bgColor: AppColors.secondaryColor,
                iconColor: AppColors.mainColor,
                icon: Icons.lock,
                shape: 'circle'
              ),
              SizedBox(width: Dimensions.width20),
              Expanded(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    BigText(
                      text: "รหัสผ่าน", size: Dimensions.font14
                    ),
                    SizedBox(height: Dimensions.height5),
                    SmallText(
                      text: "เพิ่มรหัสผ่าน",
                      size: Dimensions.font14,
                      color: AppColors.darkGreyColor,
                    ),
                  ],
                ),
              ),
             
            ],
          ),
        ],
      ),
    );
  }
}