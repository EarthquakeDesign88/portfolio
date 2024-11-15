import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/loading_percent_horizontal.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class BodyPersonalInfo extends StatefulWidget {
  const BodyPersonalInfo({Key? key}) : super(key: key);

  @override
  State<BodyPersonalInfo> createState() => _BodyPersonalInfoState();
}

class _BodyPersonalInfoState extends State<BodyPersonalInfo> {
  String gender = "male";
  bool switchValueFacebook = false;
  bool switchValueApple = false;
  bool switchValueGoogle = false;

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        SizedBox(height: Dimensions.height20),
        const Center(
          child: Stack(
            children: [
              CustomIcon(
                width: 120,
                height: 120,
                bgColor: AppColors.greyColor,
                iconColor: AppColors.darkGreyColor,
                icon: Icons.person_outline_outlined,
                iconSize: 80,
                shape: 'circle',
              ),
              Positioned(
                top: 80,
                left: 80,
                child: CustomIcon(
                  width: 40,
                  height: 40,
                  bgColor: AppColors.darkGreyColor,
                  iconColor: AppColors.whiteColor,
                  icon: Icons.camera_alt,
                  iconSize: 20,
                  shape: 'circle',
                ),
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height5),
        Center(child: BigText(text: "ทดสอบ ระบบ", size: Dimensions.font20)),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Container(
            width: MediaQuery.of(context).size.width,
            height: Dimensions.height70,
            decoration: BoxDecoration(
              color: const Color.fromARGB(255, 251, 230, 153),
              borderRadius: BorderRadius.circular(Dimensions.radius15),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Padding(
                  padding: EdgeInsets.only(left: Dimensions.width20),
                  child: const CustomIcon(
                    icon: Icons.perm_contact_calendar_outlined,
                    bgColor: Color.fromARGB(255, 255, 218, 82),
                    iconColor: AppColors.whiteColor,
                  ),
                ),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    children: [
                      SizedBox(height: Dimensions.height10),
                      Padding(
                        padding: EdgeInsets.only(right: Dimensions.width15),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            BigText(
                              text: "มาเพิ่มข้อมูลกันอีกนิดนะ",
                              size: Dimensions.font14
                            ),
                            const CustomIcon(
                              icon: Icons.warning,
                              width: 25,
                              height: 25,
                              iconSize: 20,
                              bgColor: AppColors.whiteColor,
                              iconColor: AppColors.darkGreyColor,
                            ),
                            BigText(text: "75 %", size: Dimensions.font18)
                          ],
                        ),
                      ),
                      SizedBox(height: Dimensions.height10),
                      const Row(children: [
                        LoadingPercentHorizontal(
                          percent: 75, progressColor: Colors.orangeAccent
                        ),
                      ])
                    ],
                  ),
                )
              ],
            ),
          ),
        ),
        SizedBox(height: Dimensions.height20),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              InkWell(
                onTap: (){
                  Get.toNamed(RouteHelper.namePersonalInfo);
                },
                child: Row(
                  children: [
                    CustomIcon(
                      height: Dimensions.width40,
                      width: Dimensions.width40,
                      bgColor: AppColors.secondaryColor,
                      iconColor: AppColors.mainColor,
                      icon: Icons.person,
                      shape: 'circle'
                    ),
                    SizedBox(width: Dimensions.width20),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(
                              text: "ชื่อ-นามสกุล", size: Dimensions.font14),
                          SizedBox(height: Dimensions.height5),
                          SmallText(
                            text: "ทดสอบ ระบบ",
                            size: Dimensions.font14,
                            color: AppColors.mainColor,
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
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              InkWell(
                onTap: (){
                  Get.toNamed(RouteHelper.genderPersonalInfo);
                },
                child: Row(
                  children: [
                    CustomIcon(
                        height: Dimensions.width40,
                        width: Dimensions.width40,
                        bgColor: AppColors.secondaryColor,
                        iconColor: AppColors.mainColor,
                        icon: gender == "male" ? Icons.male : Icons.female,
                        shape: 'circle'),
                    SizedBox(width: Dimensions.width20),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(text: "เพศ", size: Dimensions.font14),
                          SizedBox(height: Dimensions.height5),
                          SmallText(
                            text: gender == "male" ? "ชาย" : "หญิง",
                            size: Dimensions.font14,
                            color: AppColors.mainColor,
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
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              InkWell(
                onTap: (){
                  Get.toNamed(RouteHelper.birthDatePersonalInfo);
                },
                child: Row(
                  children: [
                    CustomIcon(
                        height: Dimensions.width40,
                        width: Dimensions.width40,
                        bgColor: AppColors.secondaryColor,
                        iconColor: AppColors.mainColor,
                        icon: Icons.card_giftcard,
                        shape: 'circle'),
                    SizedBox(width: Dimensions.width20),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(text: "วันเกิด", size: Dimensions.font14),
                          SizedBox(height: Dimensions.height5),
                          SmallText(
                            text: "18/01/1990",
                            size: Dimensions.font14,
                            color: AppColors.mainColor,
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
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.only(left: Dimensions.width15),
          child: BigText(text: "ข้อมูลการเข้าระบบ", size: Dimensions.font18),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              InkWell(
                onTap: (){
                  Get.toNamed(RouteHelper.phoneNumberPersonalInfo);
                },
                child: Row(
                  children: [
                    CustomIcon(
                        height: Dimensions.width40,
                        width: Dimensions.width40,
                        bgColor: AppColors.secondaryColor,
                        iconColor: AppColors.mainColor,
                        icon: Icons.mobile_screen_share,
                        shape: 'circle'),
                    SizedBox(width: Dimensions.width20),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(text: "เบอร์โทรศัพท์", size: Dimensions.font14),
                          SizedBox(height: Dimensions.height5),
                          SmallText(
                            text: "0825692462",
                            size: Dimensions.font14,
                            color: AppColors.mainColor,
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
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              InkWell(
                onTap: (){
                  Get.toNamed(RouteHelper.emailPasswordPersonalInfo);
                },
                child: Row(
                  children: [
                    CustomIcon(
                        height: Dimensions.width40,
                        width: Dimensions.width40,
                        bgColor: AppColors.secondaryColor,
                        iconColor: AppColors.mainColor,
                        icon: Icons.email_outlined,
                        shape: 'circle'),
                    SizedBox(width: Dimensions.width20),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(
                              text: "อีเมลและรหัสผ่าน", size: Dimensions.font14),
                          SizedBox(height: Dimensions.height5),
                          SmallText(
                            text: "เพิ่มอีเมล",
                            size: Dimensions.font14,
                            color: AppColors.mainColor,
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
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              Row(
                children: [
                  CustomIcon(
                      height: Dimensions.width40,
                      width: Dimensions.width40,
                      bgColor: AppColors.facebookColor,
                      iconColor: AppColors.whiteColor,
                      icon: Icons.facebook_outlined,
                      shape: 'circle'),
                  SizedBox(width: Dimensions.width20),
                  Expanded(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.start,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        BigText(text: "Facebook", size: Dimensions.font14),
                        SizedBox(height: Dimensions.height5),
                        SmallText(
                          text: "เพิ่มการเข้าสู่ระบบด้วย Facebook",
                          size: Dimensions.font14,
                          color: AppColors.darkGreyColor,
                        ),
                      ],
                    ),
                  ),
                  Align(
                    alignment: Alignment.centerRight,
                    child: Switch(
                      value: switchValueFacebook,
                      onChanged: (value) {
                        setState(() {
                          switchValueFacebook = value;
                        });
                      },
                      activeColor: AppColors.mainColor,
                      inactiveThumbColor: AppColors.darkGreyColor,
                      inactiveTrackColor: AppColors.greyColor,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              Row(
                children: [
                  CustomIcon(
                      height: Dimensions.width40,
                      width: Dimensions.width40,
                      bgColor: AppColors.appleColor,
                      iconColor: AppColors.whiteColor,
                      icon: Icons.apple_outlined,
                      shape: 'circle'),
                  SizedBox(width: Dimensions.width20),
                  Expanded(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.start,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        BigText(text: "Apple ID", size: Dimensions.font14),
                        SizedBox(height: Dimensions.height5),
                        SmallText(
                          text: "เพิ่มการเข้าสู่ระบบด้วย Apple ID",
                          size: Dimensions.font14,
                          color: AppColors.darkGreyColor,
                        ),
                      ],
                    ),
                  ),
                  Align(
                    alignment: Alignment.centerRight,
                    child: Switch(
                      value: switchValueApple,
                      onChanged: (value) {
                        setState(() {
                          switchValueApple = value;
                        });
                      },
                      activeColor: AppColors.mainColor,
                      inactiveThumbColor: AppColors.darkGreyColor,
                      inactiveTrackColor: AppColors.greyColor,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              Row(
                children: [
                  CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.whiteColor,
                    iconColor: AppColors.gmailColor,
                    icon: Icons.g_mobiledata,
                    shape: 'circle'
                  ),
                  SizedBox(width: Dimensions.width20),
                  Expanded(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.start,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        BigText(text: "Google", size: Dimensions.font14),
                        SizedBox(height: Dimensions.height5),
                        SmallText(
                          text: "เพิ่มการเข้าสู่ระบบด้วย Google",
                          size: Dimensions.font14,
                          color: AppColors.darkGreyColor,
                        ),
                      ],
                    ),
                  ),
                  Align(
                    alignment: Alignment.centerRight,
                    child: Switch(
                      value: switchValueGoogle,
                      onChanged: (value) {
                        setState(() {
                          switchValueGoogle = value;
                        });
                      },
                      activeColor: AppColors.mainColor,
                      inactiveThumbColor: AppColors.darkGreyColor,
                      inactiveTrackColor: AppColors.greyColor,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.only(left: Dimensions.width15),
          child: BigText(text: "การลบบัญชีผู้ให้", size: Dimensions.font18),
        ),
        SizedBox(height: Dimensions.height15),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Column(
            children: [
              InkWell(
                onTap: (){
                  Get.toNamed(RouteHelper.deleteAccountPersonalInfo);
                },
                child: Row(
                  children: [
                    CustomIcon(
                        height: Dimensions.width40,
                        width: Dimensions.width40,
                        bgColor: AppColors.secondaryColor,
                        iconColor: AppColors.mainColor,
                        icon: Icons.perm_contact_calendar_outlined,
                        shape: 'circle'),
                    SizedBox(width: Dimensions.width20),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(text: "ลบบัญชีผู้ใช้", size: Dimensions.font14),
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
            ],
          ),
        ),
        SizedBox(height: Dimensions.height15),
      ],
    );
  }
}
