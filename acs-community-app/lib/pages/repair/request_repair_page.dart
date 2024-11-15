import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/repair/components/body_request_repair.dart';

class RequestRepairPage extends StatelessWidget {
  const RequestRepairPage({super.key});

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
          title: BigText(text: "รายละเอียด", size: Dimensions.font20)),
      body: const BodyRequestRepair(),
    );
  }
}
