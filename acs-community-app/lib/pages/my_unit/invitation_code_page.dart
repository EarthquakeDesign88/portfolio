import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/my_unit/components/body_invitation_code.dart';

class InvitationCodePage extends StatelessWidget {
  const InvitationCodePage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
        centerTitle: true,
        title: BigText(
          text: "รหัสเชิญผู้อยู่อาศัยใหม่", 
          size: Dimensions.font20
        )
      ),
      backgroundColor: AppColors.whiteColor,
      body: const BodyInvitationCode(),
    );
  }
}
