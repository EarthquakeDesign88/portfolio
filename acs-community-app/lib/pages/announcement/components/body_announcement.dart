import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/announcement_controller.dart';

class BodyAnnouncement extends StatelessWidget {
  final AnnouncementController _announcementController = Get.find();

  @override
  Widget build(BuildContext context) {
    _announcementController.fetchAnnouncements();

    return Obx(() {
      if (_announcementController.announcementLists.isEmpty) {
        return const Center(
          child: CircularProgressIndicator(),
        );
      } else {
        return TabBarView(children: [
          for (final announcementType in _announcementController.announcementTypes)
            ListView.builder(
              itemCount: _announcementController.getAnnouncementCount(announcementType),
              itemBuilder: (context, index) {
                final announcementList = _announcementController.getAnnouncementByType(announcementType, index);
                final detailId = announcementList.id;

                return InkWell(
                  onTap: () {
                    Get.toNamed(RouteHelper.getAnnouncementDetail(detailId));
                  },
                  child: Card(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Stack(
                          children: [
                            Image.network(announcementList.imagePath,
                                width: double.infinity,
                                height: 200,
                                fit: BoxFit.cover),
                            Positioned(
                                top: 145,
                                right: 16,
                                child: Row(
                                  children: [
                                    Container(
                                      decoration: BoxDecoration(
                                          shape: BoxShape.rectangle,
                                          color: AppColors.greyColor,
                                          borderRadius: BorderRadius.circular(
                                              Dimensions.radius15)),
                                      padding: const EdgeInsets.all(10),
                                      child: Row(
                                        mainAxisAlignment:
                                            MainAxisAlignment.center,
                                        crossAxisAlignment:
                                            CrossAxisAlignment.center,
                                        children: [
                                          const Icon(
                                            Icons.handshake,
                                            color: AppColors.blackColor,
                                            size: 20,
                                          ),
                                          SizedBox(height: Dimensions.height5),
                                          BigText(
                                              text: announcementList.totalThank.toString(),
                                              size: Dimensions.font14)
                                        ],
                                      ),
                                    ),
                                    SizedBox(width: Dimensions.width10),
                                    Container(
                                      decoration: BoxDecoration(
                                        shape: BoxShape.rectangle,
                                        color: AppColors.greyColor,
                                        borderRadius: BorderRadius.circular(Dimensions.radius15)),
                                      padding: const EdgeInsets.all(8),
                                      child: Row(
                                        mainAxisAlignment:
                                            MainAxisAlignment.center,
                                        crossAxisAlignment:
                                            CrossAxisAlignment.center,
                                        children: [
                                          const Icon(
                                            Icons.remove_red_eye,
                                            color: AppColors.blackColor,
                                            size: 20,
                                          ),
                                          SizedBox(height: Dimensions.height5),
                                          BigText(
                                              text: announcementList.totalView.toString(),
                                              size: Dimensions.font14)
                                        ],
                                      ),
                                    )
                                  ],
                                )),
                          ],
                        ),
                        SizedBox(height: Dimensions.height10),
                        ListTile(
                          title: BigText(
                            text: announcementList.title,
                            size: Dimensions.font20,
                          ),
                          subtitle: Column(
                            children: [
                              SizedBox(height: Dimensions.height5),
                              Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Align(
                                        alignment: Alignment.centerLeft,
                                        child: SmallText(
                                            text: announcementList.subtitle)),
                                    Align(
                                      alignment: Alignment.centerRight,
                                      child: SmallText(
                                          text: announcementList.date),
                                    ),
                                  ]),
                              SizedBox(height: Dimensions.height15),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              }),
        ]);
      }
    });
  }
}
