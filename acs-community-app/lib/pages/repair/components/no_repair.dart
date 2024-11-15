import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/main_button.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class NoRepair extends StatelessWidget {
  final String textTab1, textTab2;

  const NoRepair({Key? key, required this.textTab1, required this.textTab2})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return TabBarView(children: [
      Column(
        children: [
          Expanded(
            child: ListView(
              children: [
                Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      SizedBox(height: Dimensions.height60),
                      const Icon(
                        Icons.construction_outlined,
                        color: AppColors.greyColor,
                        size: 150,
                      ),
                      BigText(text: textTab1, size: Dimensions.font22)
                    ],
                  ),
                ),
              ],
            ),
          ),
          Align(
            alignment: Alignment.bottomCenter,
            child: MainButton(
              icon: Icons.add,
              text: "เพิ่มรายการซ่อม",
              routeTo: () {
                Get.toNamed(RouteHelper.requestRepair);
              },
            ),
          ),
        ],
      ),
      Column(children: [
        Expanded(
          child: ListView(
            children: [
              Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    SizedBox(height: Dimensions.height60),
                    const Icon(
                      Icons.construction_outlined,
                      color: AppColors.greyColor,
                      size: 150,
                    ),
                    BigText(text: textTab2, size: Dimensions.font22)
                  ],
                ),
              ),
            ],
          ),
        ),
      ]),
    ]);
  }
}
