// import 'package:flutter/material.dart';
// import 'package:acs_community/utils/constants.dart';
// import 'package:acs_community/widgets/big_text.dart';
// import 'package:acs_community/widgets/small_text.dart';
// import 'package:acs_community/routes/route_helper.dart';
// import 'package:get/get.dart';
// import 'package:acs_community/backup/announcement_controller.dart';

// class BodyAnnouncement extends StatelessWidget {
//   // BodyAnnouncement({Key? key}) : super(key: key);

//   final AnnouncementController _announcementController =
//       Get.put(AnnouncementController());

//   @override
//   Widget build(BuildContext context) {
//     return TabBarView(
//       children: [
//         for (final announcementType in _announcementController.announcementTypes)
//           ListView.builder(
//             itemCount: _announcementController.getAnnouncementCount(announcementType),
//             itemBuilder: (context, index) {
//               final announcementList = _announcementController.getAnnouncementByType(announcementType, index);

//               final detailId = announcementList.id;
//               return InkWell(
//                 onTap: () {
//                   Get.toNamed(RouteHelper.getAnnouncementDetail(detailId));
//                 },
//                 child: Card(
//                   child: Column(
//                     crossAxisAlignment: CrossAxisAlignment.start,
//                     children: [
//                       Stack(
//                         children: [
//                           Image.network(announcementList.imagePath,
//                               width: double.infinity,
//                               height: 200,
//                               fit: BoxFit.cover),
//                           Positioned(
//                               top: 145,
//                               right: 16,
//                               child: Row(
//                                 children: [
//                                   Container(
//                                     decoration: BoxDecoration(
//                                         shape: BoxShape.rectangle,
//                                         color: AppColors.greyColor,
//                                         borderRadius: BorderRadius.circular(
//                                             Dimensions.radius15)),
//                                     padding: const EdgeInsets.all(10),
//                                     child: Row(
//                                       mainAxisAlignment:
//                                           MainAxisAlignment.center,
//                                       crossAxisAlignment:
//                                           CrossAxisAlignment.center,
//                                       children: [
//                                         const Icon(
//                                           Icons.handshake,
//                                           color: AppColors.blackColor,
//                                           size: 20,
//                                         ),
//                                         SizedBox(height: Dimensions.height5),
//                                         BigText(
//                                             text: announcementList.totalThank,
//                                             size: Dimensions.font14)
//                                       ],
//                                     ),
//                                   ),
//                                   SizedBox(width: Dimensions.width10),
//                                   Container(
//                                     decoration: BoxDecoration(
//                                         shape: BoxShape.rectangle,
//                                         color: AppColors.greyColor,
//                                         borderRadius: BorderRadius.circular(
//                                             Dimensions.radius15)),
//                                     padding: const EdgeInsets.all(8),
//                                     child: Row(
//                                       mainAxisAlignment:
//                                           MainAxisAlignment.center,
//                                       crossAxisAlignment:
//                                           CrossAxisAlignment.center,
//                                       children: [
//                                         const Icon(
//                                           Icons.remove_red_eye,
//                                           color: AppColors.blackColor,
//                                           size: 20,
//                                         ),
//                                         SizedBox(height: Dimensions.height5),
//                                         BigText(
//                                             text: announcementList.totalView,
//                                             size: Dimensions.font14)
//                                       ],
//                                     ),
//                                   )
//                                 ],
//                               )),
//                         ],
//                       ),
//                       SizedBox(height: Dimensions.height10),
//                       ListTile(
//                         title: BigText(
//                           text: announcementList.title,
//                           size: Dimensions.font20,
//                         ),
//                         subtitle: Column(
//                           children: [
//                             SizedBox(height: Dimensions.height5),
//                             Row(
//                                 mainAxisAlignment:
//                                     MainAxisAlignment.spaceBetween,
//                                 children: [
//                                   Align(
//                                       alignment: Alignment.centerLeft,
//                                       child: SmallText(
//                                           text: announcementList.subtitle)),
//                                   Align(
//                                     alignment: Alignment.centerRight,
//                                     child: SmallText(
//                                         text: announcementList.date),
//                                   ),
//                                 ]),
//                             SizedBox(height: Dimensions.height15),
//                           ],
//                         ),
//                       ),
//                     ],
//                   ),
//                 ),
//               );
//             }
//         ),
//       ],
//     );
//   }
// }
