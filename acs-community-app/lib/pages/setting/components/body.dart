import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class Body extends StatefulWidget {
  const Body({Key? key}) : super(key: key);

  @override
  State<Body> createState() => _BodyState();
}

class _BodyState extends State<Body> {
  void clearNotification(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          content: Container(
            width: 400,
            height: 250,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(Dimensions.radius15),
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Stack(
                  children: [
                    Container(
                      width: Dimensions.width80,
                      height: Dimensions.height80,
                      decoration: const BoxDecoration(
                          shape: BoxShape.circle,
                          color: AppColors.secondaryColor),
                    ),
                    Positioned.fill(
                      child: Icon(
                        Icons.recycling_outlined,
                        color: AppColors.mainColor,
                        size: Dimensions.iconSize40,
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height10),
                BigText(text: "ล้างการแจ้งเตือน", size: Dimensions.font22),
                SizedBox(height: Dimensions.height20),
                const SmallText(text: "ยืนยันการล้างการแจ้งเตือน"),
                SizedBox(height: Dimensions.height20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    SizedBox(
                      height: 40,
                      width: 135,
                      child: MaterialButton(
                          height: Dimensions.height50,
                          color: AppColors.whiteColor,
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          shape: RoundedRectangleBorder(
                            borderRadius:
                                BorderRadius.circular(Dimensions.radius10),
                            side: BorderSide(
                              color: AppColors.mainColor,
                              width: Dimensions.width2,
                            ),
                          ),
                          child: BigText(
                            text: "ยกเลิก",
                            size: Dimensions.font18,
                            color: AppColors.mainColor
                          )
                        )
                      ),
                    SizedBox(
                        height: 40,
                        width: 135,
                        child: MaterialButton(
                            height: Dimensions.height50,
                            color: AppColors.mainColor,
                            onPressed: () {
                              Navigator.pop(context);
                            },
                            shape: RoundedRectangleBorder(
                              borderRadius:
                                  BorderRadius.circular(Dimensions.radius10),
                              side: const BorderSide(
                                color: AppColors.mainColor,
                                width: 2.0,
                              ),
                            ),
                            child: BigText(
                                text: "ยืนยัน",
                                size: Dimensions.font18,
                                color: AppColors.whiteColor)))
                  ],
                )
              ],
            ),
          ),
        );
      },
    );
  }

  void logout(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          content: Container(
            width: 400,
            height: 250,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(Dimensions.radius15),
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Stack(
                  children: [
                    Container(
                      width: Dimensions.width80,
                      height: Dimensions.height80,
                      decoration: const BoxDecoration(
                        shape: BoxShape.circle,
                        color: Color.fromARGB(255, 249, 205, 205)
                      ),
                    ),
                    Positioned.fill(
                      child: Icon(
                        Icons.exit_to_app,
                        color: const Color.fromARGB(255, 245, 102, 102),
                        size: Dimensions.iconSize40,
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height10),
                BigText(text: "ออกจากระบบ", size: Dimensions.font22),
                SizedBox(height: Dimensions.height20),
                const SmallText(text: "ยืนยันการออกจากระบบ"),
                SizedBox(height: Dimensions.height20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    SizedBox(
                      height: 40,
                      width: 135,
                      child: MaterialButton(
                          height: Dimensions.height50,
                          color: AppColors.whiteColor,
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          shape: RoundedRectangleBorder(
                            borderRadius:
                                BorderRadius.circular(Dimensions.radius10),
                            side: BorderSide(
                              color: AppColors.whiteColor,
                              width: Dimensions.width2,
                            ),
                          ),
                          child: BigText(
                            text: "ยกเลิก",
                            size: Dimensions.font18,
                            color: AppColors.blackColor
                          )
                        )
                      ),
                    SizedBox(
                      height: 40,
                      width: 135,
                      child: MaterialButton(
                        height: Dimensions.height50,
                        color: const Color.fromARGB(255, 245, 102, 102),
                        onPressed: () {
                          Navigator.pop(context);
                        },
                        shape: RoundedRectangleBorder(
                          borderRadius:
                              BorderRadius.circular(Dimensions.radius10),
                          side: const BorderSide(
                            color: const Color.fromARGB(255, 245, 102, 102),
                            width: 2.0,
                          ),
                        ),
                        child: BigText(
                          text: "ยืนยัน",
                          size: Dimensions.font18,
                          color: AppColors.whiteColor
                        )
                      )
                    )
                  ],
                )
              ],
            ),
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        Column(
          children: [
            SizedBox(height: Dimensions.height2),
            Container(
                width: double.infinity,
                height: 205,
                color: AppColors.whiteColor,
                child: Column(
                  children: [
                    SizedBox(height: Dimensions.height15),
                    Padding(
                      padding: EdgeInsets.only(left: Dimensions.width15),
                      child: Align(
                        alignment: Alignment.centerLeft,
                        child: BigText(
                            text: "การตั้งค่า",
                            color: AppColors.blackColor,
                            size: Dimensions.font16),
                      ),
                    ),
                    SizedBox(height: Dimensions.height15),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.personalInfo);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                                height: Dimensions.width40,
                                width: Dimensions.width40,
                                bgColor: AppColors.secondaryColor,
                                iconColor: AppColors.mainColor,
                                icon: Icons.person,
                                shape: 'circle'),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                      text: "ข้อมูลส่วนตัว",
                                      size: Dimensions.font14),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    InkWell(
                      onTap: () {
                        clearNotification(context);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                                height: Dimensions.width40,
                                width: Dimensions.width40,
                                bgColor: AppColors.secondaryColor,
                                iconColor: AppColors.mainColor,
                                icon: Icons.recycling_outlined,
                                shape: 'circle'),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                      text: "ล้างการแจ้งเตือน",
                                      size: Dimensions.font14),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.notifications);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                                height: Dimensions.width40,
                                width: Dimensions.width40,
                                bgColor: AppColors.secondaryColor,
                                iconColor: AppColors.mainColor,
                                icon: Icons.notifications_outlined,
                                shape: 'circle'),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                      text: "การแจ้งเตือน",
                                      size: Dimensions.font14),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    // SizedBox(height: Dimensions.height10),
                  ],
                )),
            SizedBox(height: Dimensions.height10),
            Container(
                width: double.infinity,
                height: 435,
                color: AppColors.whiteColor,
                child: Column(
                  children: [
                    SizedBox(height: Dimensions.height15),
                    Padding(
                      padding: EdgeInsets.only(left: Dimensions.width15),
                      child: Align(
                        alignment: Alignment.centerLeft,
                        child: BigText(
                            text: "การใช้งาน",
                            color: AppColors.blackColor,
                            size: Dimensions.font16),
                      ),
                    ),
                    SizedBox(height: Dimensions.height15),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.appVersion);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                                height: Dimensions.width40,
                                width: Dimensions.width40,
                                bgColor: AppColors.secondaryColor,
                                iconColor: AppColors.mainColor,
                                icon: Icons.apps_outage,
                                shape: 'circle'),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                      text: "แอปฯ เวอร์ชัน 1.0.1",
                                      size: Dimensions.font14),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.faq);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                                height: Dimensions.width40,
                                width: Dimensions.width40,
                                bgColor: AppColors.secondaryColor,
                                iconColor: AppColors.mainColor,
                                icon: Icons.question_answer,
                                shape: 'circle'),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                    text: "คำถามที่พบบ่อย",
                                    size: Dimensions.font14
                                  ),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    Padding(
                      padding: EdgeInsets.only(left: Dimensions.width20),
                      child: Row(
                        children: [
                          CustomIcon(
                            height: Dimensions.width40,
                            width: Dimensions.width40,
                            bgColor: AppColors.secondaryColor,
                            iconColor: AppColors.mainColor,
                            icon: Icons.language,
                            shape: 'circle'
                          ),
                          SizedBox(width: Dimensions.width20),
                          Expanded(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.start,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                BigText(text: "ภาษา", size: Dimensions.font14),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                    // SizedBox(height: Dimensions.height2),
                    // BottomLine(),
                    // SizedBox(height: Dimensions.height2),
                    // Padding(
                    //   padding: EdgeInsets.only(left: Dimensions.width20),
                    //   child: Row(
                    //     children: [
                    //       CustomIcon(
                    //           height: Dimensions.width40,
                    //           width: Dimensions.width40,
                    //           bgColor: AppColors.secondaryColor,
                    //           iconColor: AppColors.mainColor,
                    //           icon: Icons.camera,
                    //           shape: 'circle'),
                    //       SizedBox(width: Dimensions.width20),
                    //       Expanded(
                    //         child: Column(
                    //           mainAxisAlignment: MainAxisAlignment.start,
                    //           crossAxisAlignment: CrossAxisAlignment.start,
                    //           children: [
                    //             BigText(
                    //                 text: "ตั้งค่าการใช้งานกล้อง",
                    //                 size: Dimensions.font14),
                    //           ],
                    //         ),
                    //       )
                    //     ],
                    //   ),
                    // ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.privacyPolicy);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                              height: Dimensions.width40,
                              width: Dimensions.width40,
                              bgColor: AppColors.secondaryColor,
                              iconColor: AppColors.mainColor,
                              icon: Icons.security,
                              shape: 'circle'
                            ),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                    text: "นโยบายความเป็นส่วนตัว",
                                    size: Dimensions.font14
                                  ),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.termService);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                              height: Dimensions.width40,
                              width: Dimensions.width40,
                              bgColor: AppColors.secondaryColor,
                              iconColor: AppColors.mainColor,
                              icon: Icons.list,
                              shape: 'circle'
                            ),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                      text: "เงื่อนไขการใช้บริการ",
                                      size: Dimensions.font14),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    InkWell(
                      onTap: () {
                        Get.toNamed(RouteHelper.settingSuggestion);
                      },
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: Row(
                          children: [
                            CustomIcon(
                              height: Dimensions.width40,
                              width: Dimensions.width40,
                              bgColor: AppColors.secondaryColor,
                              iconColor: AppColors.mainColor,
                              icon: Icons.reviews,
                              shape: 'circle'
                            ),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                    text: "ข้อเสนอแนะสำหรับผู้พัฒนาแอปฯ",
                                    size: Dimensions.font14
                                  ),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    Padding(
                      padding: EdgeInsets.only(left: Dimensions.width20),
                      child: Row(
                        children: [
                          CustomIcon(
                            height: Dimensions.width40,
                            width: Dimensions.width40,
                            bgColor: AppColors.secondaryColor,
                            iconColor: AppColors.mainColor,
                            icon: Icons.home_filled,
                            shape: 'circle'
                          ),
                          SizedBox(width: Dimensions.width20),
                          Expanded(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.start,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                BigText(
                                  text: "ติดต่อทีมงานผู้พัฒนาแอปฯ",
                                  size: Dimensions.font14
                                ),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                    Padding(
                      padding: EdgeInsets.only(left: Dimensions.width20),
                      child: InkWell(
                        onTap: () {
                          logout(context);
                        },
                        child: Row(
                          children: [
                            CustomIcon(
                              height: Dimensions.width40,
                              width: Dimensions.width40,
                              bgColor: AppColors.secondaryColor,
                              iconColor: AppColors.mainColor,
                              icon: Icons.exit_to_app,
                              shape: 'circle'
                            ),
                            SizedBox(width: Dimensions.width20),
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                    text: "ออกจากระบบ",
                                    size: Dimensions.font14
                                  ),
                                ],
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                  ],
                )
              ),
          ],
        )
      ],
    );
  }
}
