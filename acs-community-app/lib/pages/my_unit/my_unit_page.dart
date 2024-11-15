import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/main_button.dart';
import 'package:acs_community/pages/my_unit/components/body_my_unit.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class MyUnitPage extends StatelessWidget {
  const MyUnitPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(
          color: AppColors.darkGreyColor,
        ),
        centerTitle: true,
        title: BigText(text: "ห้องของฉัน", size: Dimensions.font20)
      ),
      body: const BodyMyUnit(),
      bottomNavigationBar: MainButton(
        text: "เพิ่มห้อง",
        icon: Icons.add,
        routeTo: () {
          Get.toNamed(RouteHelper.addUnit);
        },
      )
    );
  }
}
