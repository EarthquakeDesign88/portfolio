// import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/main_button.dart';
import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
// import 'package:acs_community/widgets/image_input_box.dart';

class BodyAttachProofPayment extends StatelessWidget {
  const BodyAttachProofPayment({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        SizedBox(height: Dimensions.height20),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const SmallText(text: "ชุมชน", color: AppColors.blackColor),
              BigText(
                text: "ACS Community",
                size: Dimensions.font16,
                color: AppColors.blackColor
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const SmallText(text: "บ้านเลขที่", color: AppColors.blackColor),
              BigText(
                text: "3300/25",
                size: Dimensions.font16,
                color: AppColors.blackColor
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const SmallText(text: "รายการ", color: AppColors.blackColor),
              BigText(
                text: "ค่าส่วนกลาง",
                size: Dimensions.font16,
                color: AppColors.blackColor
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const SmallText(
                  text: "เจ้าของกรรมสิทธิ์", color: AppColors.blackColor),
              BigText(
                text: "นายทดสอบ ระบบ",
                size: Dimensions.font16,
                color: AppColors.blackColor
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const SmallText(
                  text: "ยอดรวมที่ต้องชำระ (บาท)", color: AppColors.blackColor),
              BigText(
                text: "12,000.50",
                size: Dimensions.font16,
                color: AppColors.blackColor
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height20),
        Center(
          child: BigText(
              text: "หลักฐานการโอนเงิน",
              size: Dimensions.font16,
              color: Colors.redAccent),
        ),
        SizedBox(height: Dimensions.height20),
        // Padding(
        //   padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
        //   child: Stack(
        //     children: [
        //       ImageInputBox(height: 200, width: 330, iconSize: 80),
        //       Positioned(
        //         top: 140,
        //         right: 35,
        //         child: CustomIcon(
        //           icon: Icons.camera_alt_outlined,
        //           bgColor: AppColors.darkGreyColor,
        //           iconColor: AppColors.whiteColor,
        //           width: 50,
        //           height: 50,
        //         ),
        //       ),
        //     ],
        //   ),
        // ),
        SizedBox(height: Dimensions.height20),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: BigText(
              text: "รายละเอียด",
              color: AppColors.blackColor,
              size: Dimensions.font16),
        ),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Container(
            width: 320, // Set the desired width
            decoration: const BoxDecoration(
              border: Border(
                bottom: BorderSide(color: AppColors.greyColor),
              ),
            ),
            child: TextField(
              decoration: const InputDecoration(
                hintText: 'พิมพ์รายละเอียดเพิ่มเติม',
                border: InputBorder.none,
              ),
              style: TextStyle(
                fontSize: Dimensions.font16,
              ),
            ),
          ),
        ),
        SizedBox(height: Dimensions.height50),
        const MainButton(
          text: "ตกลง",
          textColor: AppColors.blackColor,
          bgColor: AppColors.greyColor,
          borderColor: AppColors.greyColor,
        )
      ],
    );
  }
}
