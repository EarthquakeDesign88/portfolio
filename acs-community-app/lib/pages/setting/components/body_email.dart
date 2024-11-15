import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';

class BodyEmail extends StatelessWidget {
  const BodyEmail({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
      child: Column(
        children: [
          SizedBox(height: Dimensions.height10),
          Container(
            height: 150,
            width: 150,
            decoration: const BoxDecoration(
              image: DecorationImage(
                image: AssetImage("assets/icons/email.jpg")
              )
            ),
          ),
          SizedBox(height: Dimensions.height10),
          const BigText(text: "เพิ่มอีเมล", size: 24),
          SizedBox(height: Dimensions.height10),
          const SmallText(text: "การเพิ่มอีเมลหรือความสะดวก", size: 14),
          SizedBox(height: Dimensions.height5),
          const SmallText(text: "และความปลอดภัย สำหรับการเข้าสู่ระบบครั้งถัดไป", size: 14),
          SizedBox(height: Dimensions.height30),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
            child: Container(
              width: double.infinity,
              decoration: const BoxDecoration(
                border: Border(
                  bottom: BorderSide(color: AppColors.darkGreyColor),
                ),
              ),
              child: TextField(
                controller: TextEditingController(text: 'อีเมล'),
                decoration: const InputDecoration(
                  prefixIcon: Icon(
                    Icons.email_outlined,
                    color: AppColors.greyColor,
                  ),
                  focusedBorder: UnderlineInputBorder(
                    borderSide: BorderSide(color: AppColors.mainColor),
                  ),
                ),
                style: TextStyle(
                  fontSize: Dimensions.font16,
                ),
              ),
            ),
          ),
          SizedBox(height: Dimensions.height30),
          MainButton(
            text: "ยืนยัน",
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