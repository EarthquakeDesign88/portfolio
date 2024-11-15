import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/property_management_controller.dart';
import 'package:acs_community/models/property_management_model.dart';

class BodyPropertyManagement extends StatelessWidget {
  final PropertyManagementController _propertyManagementController = Get.find();

  @override
  Widget build(BuildContext context) {
    _propertyManagementController.fetchJuristicInfo();

    return Obx(() {
      if (_propertyManagementController.propertyManagementLists.isEmpty) {
        return const Center(
          child: CircularProgressIndicator(),
        );
      } else {
        PropertyManagement property = _propertyManagementController.propertyManagementLists.first;

        return Column(
          children: [
            SizedBox(height: Dimensions.height30),
            Padding(
              padding: EdgeInsets.only(left: Dimensions.width15),
              child: Row(
                children: [
                  SmallText(
                      text: "ติดต่อ",
                      color: AppColors.blackColor,
                      size: Dimensions.font18)
                ],
              ),
            ),
            SizedBox(height: Dimensions.height20),
            Padding(
              padding: const EdgeInsets.fromLTRB(15, 0, 20, 0),
              child: Column(
                children: [
                  Row(
                    children: [
                      Stack(
                        children: [
                          Container(
                            width: Dimensions.width50,
                            height: Dimensions.height50,
                            decoration: const BoxDecoration(
                                shape: BoxShape.circle,
                                color: AppColors.lineColor),
                          ),
                          const Positioned.fill(
                            child: Icon(
                              Icons.line_style_rounded,
                              color: Colors.white,
                            ),
                          )
                        ],
                      ),
                      SizedBox(width: Dimensions.width20),
                      Expanded(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.start,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            BigText(
                                text: property.contactLine,
                                size: Dimensions.font14),
                            SizedBox(height: Dimensions.height5),
                            SmallText(
                                text: "Line Official Account หรือ Line@ ของ",
                                size: Dimensions.font14),
                            SizedBox(height: Dimensions.height5),
                            SmallText(
                                text: "นิติบุคคล", size: Dimensions.font14),
                          ],
                        ),
                      ),
                      Align(
                        alignment: Alignment.centerRight,
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          crossAxisAlignment: CrossAxisAlignment.end,
                          children: [
                            Row(
                              children: [
                                BigText(
                                  text: "ติดต่อ",
                                  color: AppColors.mainColor,
                                  size: Dimensions.font16,
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  SizedBox(height: Dimensions.height15),
                  const BottomLine(),
                  SizedBox(height: Dimensions.height15),
                  Row(
                    children: [
                      Stack(
                        children: [
                          Container(
                            width: Dimensions.width50,
                            height: Dimensions.height50,
                            decoration: const BoxDecoration(
                                shape: BoxShape.circle, color: Colors.red),
                          ),
                          const Positioned.fill(
                            child: Icon(
                              Icons.phone,
                              color: Colors.white,
                            ),
                          )
                        ],
                      ),
                      SizedBox(width: Dimensions.width20),
                      Expanded(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.start,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            BigText(
                                text: property.contactNumber,
                                size: Dimensions.font14),
                            SizedBox(height: Dimensions.height5),
                            SmallText(
                                text: "เบอร์โทรศัพท์หลักของนิติบุคคล",
                                size: Dimensions.font14),
                            SizedBox(height: Dimensions.height5),
                          ],
                        ),
                      ),
                      Align(
                        alignment: Alignment.centerRight,
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          crossAxisAlignment: CrossAxisAlignment.end,
                          children: [
                            Row(
                              children: [
                                BigText(
                                  text: "โทร",
                                  color: AppColors.mainColor,
                                  size: Dimensions.font16,
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  SizedBox(height: Dimensions.height15),
                  const BottomLine(),
                  SizedBox(height: Dimensions.height15),
                  Row(
                    children: [
                      Stack(
                        children: [
                          Container(
                            width: Dimensions.width50,
                            height: Dimensions.height50,
                            decoration: const BoxDecoration(
                                shape: BoxShape.circle,
                                color: Colors.blueAccent),
                          ),
                          const Positioned.fill(
                            child: Icon(
                              Icons.email,
                              color: Colors.white,
                            ),
                          )
                        ],
                      ),
                      SizedBox(width: Dimensions.width20),
                      Expanded(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.start,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            BigText(
                                text: property.contactEmail,
                                size: Dimensions.font14),
                            SizedBox(height: Dimensions.height5),
                            SmallText(
                                text: "อีเมลหลัก ของนิติบุคคล",
                                size: Dimensions.font14),
                            SizedBox(height: Dimensions.height5),
                          ],
                        ),
                      ),
                      Align(
                        alignment: Alignment.centerRight,
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          crossAxisAlignment: CrossAxisAlignment.end,
                          children: [
                            Row(
                              children: [
                                BigText(
                                  text: "ติดต่อ",
                                  color: AppColors.mainColor,
                                  size: Dimensions.font16,
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  SizedBox(height: Dimensions.height15),
                  const BottomLine(),
                  SizedBox(height: Dimensions.height15),
                ],
              ),
            )
          ],
        );
      }
    });
  }
}
