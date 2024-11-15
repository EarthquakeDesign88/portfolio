import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/facility/components/body_facility.dart';

class FacilityPage extends StatefulWidget {
  const FacilityPage({Key? key}) : super(key: key);

  @override
  State<FacilityPage> createState() => _FacilityPageState();
}

class _FacilityPageState extends State<FacilityPage> {
  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          elevation: 0,
          backgroundColor: AppColors.whiteColor,
          iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
          centerTitle: true,
          title: BigText(text: "จองส่วนกลาง", size: Dimensions.font20),
          bottom: TabBar(
            labelColor: AppColors.blackColor,
            indicatorColor: AppColors.mainColor,
            tabs: [
              Tab(
                child: BigText(text: "ส่วนกลาง", size: Dimensions.font18)
              ),
              Tab(
                child: BigText(text: "การจองของฉัน", size: Dimensions.font18)
              ), //
            ]
          ),
        ),
        backgroundColor: AppColors.menuColor,
        body: BodyFacility(),
      )
    );
  }
}
