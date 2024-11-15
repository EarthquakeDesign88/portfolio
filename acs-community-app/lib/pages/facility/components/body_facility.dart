import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/pages/facility/components/no_booking.dart';
import 'package:acs_community/pages/facility/components/facility_card.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/facility_controller.dart';

class BodyFacility extends StatelessWidget {
  final FacilityController _facilityController = Get.find();

  @override
  Widget build(BuildContext context) {
    _facilityController.fetchFacilities();

    return Obx(() {
      if (_facilityController.facilityLists.isEmpty) {
        return const Center(
          child: CircularProgressIndicator(),
        );
      } else {
        return TabBarView(children: [
          ListView.builder(
            itemCount: (_facilityController.facilityLists.length / 2).ceil(),
            itemBuilder: (context, index) {
              final latestFacilityId =
                  _facilityController.facilityLists.last.id;
              final facility1 = _facilityController.facilityLists[index * 2];
              final facility2 =
                  (index * 2 + 1) < _facilityController.facilityLists.length
                      ? _facilityController.facilityLists[index * 2 + 1]
                      : null;

              return Column(
                children: [
                  SizedBox(height: Dimensions.height10),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      FacilityCard(
                        facilityList: facility1,
                        onTap: () {
                          Get.toNamed(
                              RouteHelper.getFacilityBooking(facility1.id));
                        },
                      ),
                      if (facility2 != null)
                        FacilityCard(
                          facilityList: facility2,
                          onTap: () {
                            Get.toNamed(
                                RouteHelper.getFacilityBooking(facility2.id));
                          },
                        )
                      else
                        Expanded(child: Container())
                    ],
                  ),
                  if (latestFacilityId == facility1.id ||
                      (facility2 != null && latestFacilityId == facility2.id))
                    SizedBox(height: Dimensions.height10)
                ],
              );
            },
          ),
          ListView(
            children: const <Widget>[NoBooking()],
          )
        ]);
      }
    });
  }
}
