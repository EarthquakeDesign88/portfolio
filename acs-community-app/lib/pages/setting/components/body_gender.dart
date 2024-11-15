import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';

class BodyGender extends StatefulWidget {
  const BodyGender({super.key});

  @override
  State<BodyGender> createState() => _BodyGenderState();
}

class _BodyGenderState extends State<BodyGender> {
  String? selectedGender;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
      child: Column(
        children: [
          SizedBox(height: Dimensions.height10),
          Row(
            children: [
              Radio(
                value: "ผู้ชาย",
                groupValue: selectedGender,
                onChanged: (value) {
                  setState(() {
                    selectedGender = value.toString();
                  });
                },
                activeColor: AppColors.mainColor,
              ),
              SmallText(
                text: "ผู้ชาย",
                color: selectedGender == "ผู้ชาย"
                    ? AppColors.mainColor
                    : AppColors.darkGreyColor,
              )
            ],
          ),
          SizedBox(height: Dimensions.height5),
          Row(
            children: [
              Radio(
                value: "ผู้หญิง",
                groupValue: selectedGender,
                onChanged: (value) {
                  setState(() {
                    selectedGender = value.toString();
                  });
                },
                activeColor: AppColors.mainColor,
              ),
              SmallText(
                text: "ผู้หญิง",
                color: selectedGender == "ผู้หญิง"
                    ? AppColors.mainColor
                    : AppColors.darkGreyColor,
              )
            ],
          ),
          SizedBox(height: Dimensions.height5),
          Row(
            children: [
              Radio(
                value: "อื่นๆ",
                groupValue: selectedGender,
                onChanged: (value) {
                  setState(() {
                    selectedGender = value.toString();
                  });
                },
                activeColor: AppColors.mainColor,
              ),
              SmallText(
                text: "อื่นๆ",
                color: selectedGender == "อื่นๆ"
                    ? AppColors.mainColor
                    : AppColors.darkGreyColor,
              )
            ],
          ),
          SizedBox(height: Dimensions.height20),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: Dimensions.width20),
            child: Row(
              children: [
                const SmallText(text: "ท่านสามารถอ่านข้อมูลเพิ่มเติม", size: 13),
                SizedBox(width: Dimensions.width5),
                const BigText(
                  text: "ทำไมข้อมูลนี้ถึงสำคัญ",
                  size: 13,
                  color: AppColors.mainColor
                ),
              ],
            ),
          ),
          SizedBox(height: Dimensions.height30),
          const MainButton(text: "ยืนยัน", bgColor: AppColors.mainColor, borderColor: AppColors.mainColor, textColor: AppColors.whiteColor)
        ],
      ),
    );
  }
}
