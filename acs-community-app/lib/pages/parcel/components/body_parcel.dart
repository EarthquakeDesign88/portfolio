import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/parcel_controller.dart';
import 'package:acs_community/functions/format_datetime.dart';

class BodyParcel extends StatelessWidget {
  final ParcelController _parcelController = Get.put(ParcelController());

  @override
  Widget build(BuildContext context) {
    return TabBarView(children: [
      for (final parcelStatus in _parcelController.parcelStatus)
        ListView.builder(
          itemCount: _parcelController.getParcelCount(parcelStatus),
          itemBuilder: (context, index) {
            final parcelList = _parcelController.getParcelByStatus(parcelStatus, index);
            final parcelId = parcelList.id;

            return Column(children: [
              SizedBox(height: Dimensions.height10),
              GestureDetector(
                onTap: () {
                  Get.toNamed(RouteHelper.getNewParcel(parcelId));
                },
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
                  child: Container(
                    width: MediaQuery.of(context).size.width,
                    height: Dimensions.height80,
                    decoration: BoxDecoration(
                      color: AppColors.whiteColor,
                      borderRadius: BorderRadius.circular(Dimensions.radius15),
                    ),
                    child: Row(
                      children: [
                        Container(
                          width: Dimensions.width80,
                          height: Dimensions.height80,
                          decoration: BoxDecoration(
                            borderRadius:
                                BorderRadius.circular(Dimensions.radius15),
                            image: DecorationImage(
                              image: parcelList.fileDocument != '' &&
                                      parcelList.fileDocument.isNotEmpty
                                  ? AssetImage(parcelList.fileDocument)
                                  : const AssetImage('assets/images/s1.jpg'),
                              fit: BoxFit.cover,
                            ),
                          ),
                        ),
                        Expanded(
                          child: Padding(
                            padding: EdgeInsets.only(left: Dimensions.width5),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                BigText(
                                    text: parcelList.owner,
                                    size: Dimensions.font16),
                                SizedBox(height: Dimensions.height5),
                                SmallText(
                                    text: "รับโดย: " + parcelList.collectedBy,
                                    size: Dimensions.font14),
                              ],
                            ),
                          ),
                        ),
                        Expanded(
                          child: Padding(
                            padding: EdgeInsets.only(right: Dimensions.width5),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.end,
                              children: [
                                Row(
                                    mainAxisAlignment: MainAxisAlignment.end,
                                    crossAxisAlignment: CrossAxisAlignment.end,
                                    children: [
                                      BigText(
                                          text: parcelList.unitNo,
                                          size: Dimensions.font16),
                                      SizedBox(width: Dimensions.width10),
                                      const Icon(
                                        Icons.home,
                                        color: AppColors.mainColor,
                                      )
                                    ]),
                                SizedBox(height: Dimensions.height5),
                                SmallText(
                                    text: formatDateTime(
                                        parcelList.collectedDateTime),
                                    size: 13),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ]);
          },
        ),
    ]);
  }
}
