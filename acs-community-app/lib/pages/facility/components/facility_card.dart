import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/models/facility_model.dart';

class FacilityCard extends StatelessWidget {
  final Facility facilityList;
  final VoidCallback? onTap;

  const FacilityCard({required this.facilityList, this.onTap});

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width5),
          child: Container(
            width: MediaQuery.of(context).size.width / 2.2,
            height: 150,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(Dimensions.radius10),
              image: DecorationImage(
                image: NetworkImage(facilityList.imagePath),
                fit: BoxFit.cover,
              ),
            ),
            child: Align(
              alignment: Alignment.center,
              child: Padding(
                padding: EdgeInsets.only(top: Dimensions.height80),
                child: Column(
                  children: [
                    BigText(
                      text: facilityList.title,
                      size: Dimensions.font20,
                      color: AppColors.whiteColor,
                    ),
                    SmallText(
                      text: facilityList.subtitle,
                      color: AppColors.whiteColor,
                    )
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
