import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/facility_controller.dart';
import 'package:acs_community/models/facility_model.dart';

class BodyFacilityBooking extends StatelessWidget {
  final int facilityId;

  const BodyFacilityBooking({Key? key, required this.facilityId}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final FacilityController facilityController = Get.find();
    final Facility? facility = facilityController.bookingById(facilityId);

    return Stack(
      children: [
      Container(
        width: MediaQuery.of(context).size.width,
        height: 200,
        decoration: BoxDecoration(
          image: DecorationImage(
            image: NetworkImage(
              facility?.imagePath ??
                  'https://images.unsplash.com/photo-1594322436404-5a0526db4d13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1129&q=80',
            ),
            fit: BoxFit.cover,
          ),
        ),
        child: Padding(
          padding: EdgeInsets.only(bottom: Dimensions.height10),
          child: Align(
            alignment: Alignment.bottomCenter,
            child: BigText(
              text: facility?.title ?? 'ไม่พบข้อมูล',
              size: Dimensions.font26,
              color: AppColors.whiteColor
            )
          ),
        ),
      ),
      SizedBox(height: Dimensions.height15),
      Padding(
        padding: const EdgeInsets.only(top: 200),
        child: Column(children: [
          SizedBox(height: Dimensions.height10),
          const Align(
            alignment: Alignment.center,
            child: BigText(
                text: "ระเบียบการใช้บริการ",
                size: 22,
                color: AppColors.blackColor
            )
          ),
          SizedBox(height: Dimensions.height10),
          Padding(
            padding: EdgeInsets.only(left: Dimensions.width15),
            child: Align(
              alignment: Alignment.bottomLeft,
              child: SmallText(
                text: facility?.rule ?? '',
                color: AppColors.blackColor
              )
            ),
          ),
        ]),
      )
    ]);
  }
}
