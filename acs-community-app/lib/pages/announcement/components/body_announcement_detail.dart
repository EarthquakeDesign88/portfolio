import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/announcement_controller.dart';
import 'package:acs_community/models/announcement_model.dart';

class BodyAnnouncementDetail extends StatelessWidget {
  final int detailId;

  const BodyAnnouncementDetail({Key? key, required this.detailId}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final AnnouncementController announcementController = Get.find();
    final Announcement? announcement = announcementController.getAnnouncementById(detailId);

    return Container(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: MediaQuery.of(context).size.width,
            height: 200,
            child: Image.network(
              announcement?.imagePath ??
                  'https://images.unsplash.com/photo-1594322436404-5a0526db4d13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1129&q=80',
              fit: BoxFit.cover,
            ),
          ),
          Padding(
            padding: EdgeInsets.only(left: Dimensions.width15),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(height: Dimensions.height15),
                BigText(
                  text: announcement?.title ?? 'ไม่มีประกาศ',
                  size: Dimensions.font22
                ),
                SizedBox(height: Dimensions.height15),
                SmallText(text: announcement?.date ?? 'ไม่พบข้อมูล'),
                SizedBox(height: Dimensions.height15),
                SmallText(
                    text: announcement?.subtitle ?? 'ไม่พบรายละเอียด',
                    color: AppColors.blackColor),
              ],
            ),
          ),
          SizedBox(height: Dimensions.height30),
          Padding(
            padding: EdgeInsets.only(left: Dimensions.width15),
            child: Row(
              children: [
                Icon(
                  Icons.card_membership,
                  color: AppColors.mainColor,
                  size: Dimensions.iconSize30,
                ),
                SizedBox(width: Dimensions.width10),
                const SmallText(text: "ฝ่ายบริหารอาคาร"),
                SizedBox(width: Dimensions.width80),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    const Icon(
                      Icons.handshake,
                      color: AppColors.mainColor,
                      size: 20,
                    ),
                    SizedBox(height: Dimensions.height5),
                    BigText(
                      text: announcement?.totalThank.toString() ?? '0',
                      size: Dimensions.font14,
                      color: AppColors.mainColor,
                    )
                  ],
                ),
                SizedBox(width: Dimensions.width20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    const Icon(
                      Icons.remove_red_eye,
                      color: AppColors.mainColor,
                      size: 20,
                    ),
                    SizedBox(height: Dimensions.height5),
                    BigText(
                      text: announcement?.totalView.toString() ?? '0',
                      size: Dimensions.font14,
                      color: AppColors.mainColor,
                    )
                  ],
                ),
              ],
            ),
          ),
          SizedBox(height: Dimensions.height10),
          const BottomLine(),
          SizedBox(height: Dimensions.height10),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Icon(
                Icons.handshake,
                color: AppColors.greyColor,
                size: Dimensions.iconSize30,
              ),
              SizedBox(height: Dimensions.height5),
              BigText(
                text: "ขอบคุณ",
                size: Dimensions.font16,
                color: AppColors.greyColor,
              )
            ],
          ),
          SizedBox(height: Dimensions.height10),
          const BottomLine(),
        ],
      ),
    );
  }
}
