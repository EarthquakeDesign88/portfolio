import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
// import 'package:acs_community/widgets/image_input_box.dart';
import 'package:acs_community/widgets/main_button.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/text_underline.dart';

class BodyName extends StatelessWidget {
  const BodyName({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
      child: SingleChildScrollView(
        child: Column(
          children: [
            SizedBox(height: Dimensions.height10),
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
                  controller: TextEditingController(text: 'ทดสอบ'),
                  decoration: const InputDecoration(
                    prefixIcon: Icon(
                      Icons.person,
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
            SizedBox(height: Dimensions.height15),
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
                  controller: TextEditingController(text: 'ระบบ'),
                  decoration: const InputDecoration(
                    prefixIcon: Icon(
                      Icons.person,
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
            SizedBox(height: Dimensions.height20),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
              child: const Align(
                alignment: Alignment.centerLeft,
                child: TextUnderline(
                  text: "นโยบายการใช้ชื่อและนามสกุลจริง",
                  textColor: AppColors.mainColor,
                  borderColor: AppColors.mainColor,
                  isBold: false,
                ),
              ),
            ),
            SizedBox(height: Dimensions.height20),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
              child: Align(
                alignment: Alignment.centerLeft,
                child: BigText(
                text: "รูปแบบหลักฐานการยืนยัน", size: Dimensions.font18)
              ),
            ),
            SizedBox(height: Dimensions.height10),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
              child: Align(
                  alignment: Alignment.centerLeft,
                  child: SmallText(
                      text: "เช่น บัตรประชาชน พาสปอร์ต หรือใบเสร็จรับเงินที่มีชื่อและนามสกุลของคุณปรากฏอยู่ (อย่างใดอย่างหนึ่ง)",
                      size: Dimensions.font14)),
            ),
            SizedBox(height: Dimensions.height5),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
              child: Align(
                  alignment: Alignment.centerLeft,
                  child: SmallText(
                    text:
                        "หมายเหตุ ใช้เพื่อการยืนยันในครั้งนี้เท่านั้น จะไม่ถูกบันทึกเก็บไว้",
                    size: Dimensions.font14,
                    color: Colors.red,
                  )),
            ),
            SizedBox(height: Dimensions.height10),
            // Padding(
            //   padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
            //   child:
            //       Align(alignment: Alignment.centerLeft, child: ImageInputBox()),
            // ),
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
      ),
    );
  }
}
