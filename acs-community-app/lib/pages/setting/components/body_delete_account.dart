import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';

class BodyDeleteAccount extends StatefulWidget {
  const BodyDeleteAccount({Key? key}) : super(key: key);

  @override
  State<BodyDeleteAccount> createState() => _BodyDeleteAccountState();
}

class _BodyDeleteAccountState extends State<BodyDeleteAccount> {
  bool isChecked = false;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
          child: Column(
            children: [
              SizedBox(height: Dimensions.height80),
              Container(
                height: 150,
                width: 150,
                decoration: const BoxDecoration(
                    image: DecorationImage(
                        image: AssetImage("assets/icons/delete_account.png"))),
              ),
              SizedBox(height: Dimensions.height10),
              const SmallText(
                  text: "การลบบัญชีผู้ใช้นี้ จะรวมถึงการลบบัญชี", size: 16),
              SizedBox(height: Dimensions.height5),
              const SmallText(text: "ออกจากทุกชุมชนที่คุณมี", size: 16),
              SizedBox(height: Dimensions.height5),
              const SmallText(
                  text: "โดยจะไม่สามารถเรียกคืนประวัติการใช้งาน", size: 16),
              SizedBox(height: Dimensions.height5),
              const SmallText(text: "ข้อความ รูปภาพ และวิดีโอใดๆได้", size: 16),
              SizedBox(height: Dimensions.height20),
              const SmallText(
                  text: "หากต้องการออกชุมชนปัจจุบัน (เช่น ย้ายบ้าน)", size: 16),
              SizedBox(height: Dimensions.height5),
              Row(mainAxisAlignment: MainAxisAlignment.center, children: [
                const SmallText(text: "สามารถทำรายการได้ที่", size: 16),
                SizedBox(width: Dimensions.width5),
                const BigText(text: "\"ห้องของฉัน\"", size: 16),
              ]),
              Row(mainAxisAlignment: MainAxisAlignment.center, children: [
                const SmallText(text: "เลือก", size: 16),
                SizedBox(width: Dimensions.width5),
                const BigText(
                  text: "\"ออกจากบ้านเลขที่นี้\"",
                  size: 16,
                )
              ]),
            ],
          ),
        ),
        Expanded(
          child: Align(
            alignment: Alignment.bottomLeft,
            child: Padding(
              padding: EdgeInsets.only(left: Dimensions.width10),
              child: Row(
                children: [
                  Transform.scale(
                    scale: 1.5, // Adjust the scale factor as needed
                    child: Checkbox(
                      value: isChecked,
                      onChanged: (value) {
                        setState(() {
                          isChecked = value!;
                        });
                      },
                      activeColor: AppColors.closeColor,
                    ),
                  ),
                  const SmallText(text: "ข้าพเจ้าตกลงและยอมรับ"),
                ],
              ),
            ),
          ),
        ),
        MainButton(
          text: "ยืนยันการลบบัญชีผู้ใช้",
          bgColor: isChecked ? AppColors.closeColor : AppColors.greyColor,
          borderColor: isChecked ? AppColors.closeColor : AppColors.greyColor,
          textColor: isChecked ? AppColors.whiteColor : AppColors.darkGreyColor,
        ),
      ],
    );
  }
}
