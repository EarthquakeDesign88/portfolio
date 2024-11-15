import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:acs_community/pages/my_unit/components/edit_role_content.dart';

class BodyMyUnit extends StatefulWidget {
  const BodyMyUnit({Key? key}) : super(key: key);

  @override
  State<BodyMyUnit> createState() => _BodyMyUnitState();
}

class _BodyMyUnitState extends State<BodyMyUnit> {
  String? selectedRole;

  void showEditRoleDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Center(child: BigText(text: "แก้ไขบทบาท", size: Dimensions.font22)),
          content: EditRoleContent(
            onRoleChanged: (role) {
              setState(() {
                selectedRole = role;
              });
            },
            selectedRole: selectedRole,
          ),
        );
      },
    );
  }

  void showLeaveUnitDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          content: Container(
            width: 400,
            height: 280,
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
                BigText(text: "ยืนยันการย้ายออก", size: Dimensions.font22),
                SizedBox(height: Dimensions.height20),
                const SmallText(
                    text: "บ้านเลขที่ 3300/25",
                    color: Color.fromARGB(255, 245, 102, 102)),
                SizedBox(height: Dimensions.height10),
                const SmallText(
                    text: "ตึกช้าง", color: Color.fromARGB(255, 245, 102, 102)),
                SizedBox(height: Dimensions.height20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    SizedBox(
                      height: 40,
                      width: 120,
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
                              color: AppColors.greyColor,
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
                        width: 120,
                        child: MaterialButton(
                          height: Dimensions.height50,
                          color: const Color.fromARGB(255, 245, 102, 102),
                          onPressed: () {},
                          shape: RoundedRectangleBorder(
                            borderRadius:
                                BorderRadius.circular(Dimensions.radius10),
                            side: const BorderSide(
                              color: Color.fromARGB(255, 245, 102, 102),
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

  bool isPopupVisible = false;
  void showCustomUnit() {
    setState(() {
      isPopupVisible = true;
    });

    showModalBottomSheet<void>(
      context: context,
      builder: (BuildContext context) {
        return Container(
          height: 200,
          color: AppColors.whiteColor,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Padding(
                  padding: EdgeInsets.only(left: Dimensions.width30),
                  child: Column(
                    children: [
                      GestureDetector(
                        onTap: () {
                          Navigator.pop(context);
                          showEditRoleDialog(context);
                        },
                        child: Row(children: [
                          Container(
                              height: Dimensions.height40,
                              width: Dimensions.width40,
                              decoration: const BoxDecoration(
                                shape: BoxShape.circle,
                                color: AppColors.mainColor,
                              ),
                              child: Icon(
                                Icons.edit,
                                color: AppColors.whiteColor,
                                size: Dimensions.iconSize30,
                              )),
                          SizedBox(width: Dimensions.width15),
                          Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              BigText(
                                text: "แก้ไขบทบาท",
                                color: AppColors.blackColor,
                                size: Dimensions.font16
                              ),
                              SizedBox(height: Dimensions.height5),
                              SmallText(
                                text:
                                    "เจ้าของกรรมสิทธิ์, ผู้ร่วมอยู่อาศัย, ผู้เช่า",
                                size: Dimensions.font14
                              )
                            ],
                          )
                        ]),
                      ),
                      SizedBox(height: Dimensions.height20),
                      GestureDetector(
                        onTap: () {
                          Navigator.pop(context);
                          showLeaveUnitDialog(context);
                        },
                        child: Row(children: [
                          Container(
                            height: Dimensions.height40,
                            width: Dimensions.width40,
                            decoration: const BoxDecoration(
                              shape: BoxShape.circle,
                              color: AppColors.mainColor,
                            ),
                            child: Icon(
                              Icons.home,
                              color: AppColors.whiteColor,
                              size: Dimensions.iconSize30,
                            )
                          ),
                          SizedBox(width: Dimensions.width15),
                          Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              BigText(
                                text: "ออกจากบ้านเลขที่นี้",
                                color: AppColors.blackColor,
                                size: Dimensions.font16
                              ),
                              SizedBox(height: Dimensions.height5),
                              SmallText(
                                text: "ท่านจะไม่ได้อยู่ในบ้านเลขที่นี้อีกต่อไป",
                                size: Dimensions.font14
                              )
                            ],
                          )
                        ]),
                      ),
                    ],
                  )
                ),
            ],
          ),
        );
      },
    ).whenComplete(() {
      setState(() {
        isPopupVisible = false;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    return Center(
      child: SizedBox(
        width: MediaQuery.of(context).size.width - 40,
        child: Column(
          children: [
            SizedBox(height: Dimensions.height10),
            Row(children: [
              CustomIcon(
                  icon: Icons.home,
                  height: Dimensions.height50,
                  width: Dimensions.width50),
              SizedBox(width: Dimensions.width15),
              BigText(text: "3300/25", size: Dimensions.font20)
            ]),
            SizedBox(height: Dimensions.height20),
            Container(
              height: Dimensions.height80,
              color: const Color.fromARGB(255, 209, 245, 233),
              child: Padding(
                padding: const EdgeInsets.all(8.0),
                child: Row(
                    // mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      CustomIcon(
                        icon: Icons.person,
                        height: Dimensions.height50,
                        width: Dimensions.width50,
                        iconColor: AppColors.darkGreyColor,
                        bgColor: AppColors.greyColor,
                      ),
                      Expanded(
                        child: Padding(
                          padding: EdgeInsets.only(left: Dimensions.width20),
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              BigText(
                                  text: "ทดสอบ ระบบ", size: Dimensions.font16),
                              SizedBox(height: Dimensions.height5),
                              SmallText(
                                  text: "ผู้ร่วมอาศัย",
                                  size: Dimensions.font14),
                            ],
                          ),
                        ),
                      ),
                      Padding(
                        padding: EdgeInsets.only(right: Dimensions.width10),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: GestureDetector(
                            onTap: () {
                              showCustomUnit();
                            },
                            child: const Icon(
                              Icons.more_horiz,
                              color: AppColors.darkGreyColor
                            ),
                          ),
                        ),
                      ),
                    ]),
              ),
            ),
            SizedBox(height: Dimensions.height10),
            MaterialButton(
                height: Dimensions.height50,
                onPressed: () {
                  Get.toNamed(RouteHelper.invitationCode);
                },
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(Dimensions.radius10),
                  side: BorderSide(
                    color: AppColors.mainColor,
                    width: Dimensions.width2,
                  ),
                ),
                child:
                    Row(mainAxisAlignment: MainAxisAlignment.center, children: [
                  const Icon(
                    Icons.add,
                    color: AppColors.whiteColor,
                  ),
                  SizedBox(width: Dimensions.width2),
                  BigText(
                      text: "เพิ่มสมาชิก/ดูรหัสเชิญ",
                      size: Dimensions.font18,
                      color: AppColors.mainColor),
                ])),
            SizedBox(height: Dimensions.height20),
            SizedBox(
              width: MediaQuery.of(context).size.width - 100,
              child: const BottomLine(width: 0.2),
            ),
            SizedBox(height: Dimensions.height20),
          ],
        ),
      ),
    );
  }
}
