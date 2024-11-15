import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/bottom_line.dart';

class BodyNotification extends StatefulWidget {
  const BodyNotification({super.key});

  @override
  State<BodyNotification> createState() => _BodyNotificationState();
}

class _BodyNotificationState extends State<BodyNotification> {
  bool switchFacilityBooking = false;
  bool switchAnnouncement = false;
  bool switchParcel = false;
  bool switchRepair = false;
  bool switchPaymentReminder = false;
  bool switchChat = false;
  bool switchSocial = false;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 475,
      color: AppColors.whiteColor,
      child: Padding(
        padding: EdgeInsets.only(left: Dimensions.width15),
        child: Column(
          children: [
            SizedBox(height: Dimensions.height10),
            Align(
                alignment: Alignment.centerLeft,
                child:
                    BigText(text: "เสียงและการสั่น", size: Dimensions.font18)),
            SizedBox(height: Dimensions.height20),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.spoke,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "จองส่วนกลาง",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchFacilityBooking,
                    onChanged: (value) {
                      setState(() {
                        switchFacilityBooking = value;
                      });
                    },
                    activeColor: AppColors.mainColor,
                    inactiveThumbColor: AppColors.darkGreyColor,
                    inactiveTrackColor: AppColors.greyColor,
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height5),
            const BottomLine(),
            SizedBox(height: Dimensions.height5),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.notifications,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "ประกาศ",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchAnnouncement,
                    onChanged: (value) {
                      setState(() {
                        switchAnnouncement = value;
                      });
                    },
                    activeColor: AppColors.mainColor,
                    inactiveThumbColor: AppColors.darkGreyColor,
                    inactiveTrackColor: AppColors.greyColor,
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height5),
            const BottomLine(),
            SizedBox(height: Dimensions.height5),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.picture_in_picture,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "พัสดุ",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchParcel,
                    onChanged: (value) {
                      setState(() {
                        switchParcel = value;
                      });
                    },
                    activeColor: AppColors.mainColor,
                    inactiveThumbColor: AppColors.darkGreyColor,
                    inactiveTrackColor: AppColors.greyColor,
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height5),
            const BottomLine(),
            SizedBox(height: Dimensions.height5),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.construction_outlined,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "แจ้งซ่อม",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchRepair,
                    onChanged: (value) {
                      setState(() {
                        switchRepair = value;
                      });
                    },
                    activeColor: AppColors.mainColor,
                    inactiveThumbColor: AppColors.darkGreyColor,
                    inactiveTrackColor: AppColors.greyColor,
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height5),
            const BottomLine(),
            SizedBox(height: Dimensions.height5),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.access_alarm,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "แจ้งเตือนค่าใช้จ่าย",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchPaymentReminder,
                    onChanged: (value) {
                      setState(() {
                        switchPaymentReminder = value;
                      });
                    },
                    activeColor: AppColors.mainColor,
                    inactiveThumbColor: AppColors.darkGreyColor,
                    inactiveTrackColor: AppColors.greyColor,
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height5),
            const BottomLine(),
            SizedBox(height: Dimensions.height5),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.chat_outlined,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "แชท",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchChat,
                    onChanged: (value) {
                      setState(() {
                        switchChat = value;
                      });
                    },
                    activeColor: AppColors.mainColor,
                    inactiveThumbColor: AppColors.darkGreyColor,
                    inactiveTrackColor: AppColors.greyColor,
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height5),
            const BottomLine(),
            SizedBox(height: Dimensions.height5),
            Row(
              children: [
                CustomIcon(
                    height: Dimensions.width40,
                    width: Dimensions.width40,
                    bgColor: AppColors.secondaryColor,
                    iconColor: AppColors.mainColor,
                    icon: Icons.people_sharp,
                    shape: 'circle'),
                SizedBox(width: Dimensions.width20),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SmallText(
                        text: "โซเชียล",
                        size: Dimensions.font16,
                      ),
                    ],
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: Switch(
                    value: switchSocial,
                    onChanged: (value) {
                      setState(() {
                        switchSocial = value;
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
    );
  }
}
