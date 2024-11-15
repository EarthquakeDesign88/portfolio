import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/qr_stepper.dart';

class CommunityConfirmPage extends StatefulWidget {
  const CommunityConfirmPage({Key? key}) : super(key: key);

  @override
  State<CommunityConfirmPage> createState() => _CommunityConfirmPageState();
}

class _CommunityConfirmPageState extends State<CommunityConfirmPage> {
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
            title: BigText(text: "ยืนยันชุมชน", size: Dimensions.font20)),
        body: const QRStepper());
  }
}
