import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';

class BodyBirthDate extends StatelessWidget {
  const BodyBirthDate({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
        padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
        child: Column(
          children: [
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
                  controller: TextEditingController(text: '18 มกราคม 1990'),
                  decoration: const InputDecoration(
                    prefixIcon: Icon(
                      Icons.card_giftcard,
                      color: AppColors.mainColor,
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
            const SmallText(text: "ผู้ใช้คนอื่นจะไม่เห็นวันเกิดของท่าน", size: 14),
            SizedBox(height: Dimensions.height10),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const SmallText(text: "ท่านสามารถอ่านข้อมูลเพิ่มเติม", size: 14),
                SizedBox(width: Dimensions.width5),
                const BigText(
                  text: "ทำไมข้อมูลนี้ถึงสำคัญ",
                  size: 14,
                  color: AppColors.mainColor
                ),
              ],
            ),
            SizedBox(height: Dimensions.height30),
            const MainButton(text: "ยืนยัน", bgColor: AppColors.mainColor, borderColor: AppColors.mainColor, textColor: AppColors.whiteColor)
          ],
        )
        
        
        );
  }
}
