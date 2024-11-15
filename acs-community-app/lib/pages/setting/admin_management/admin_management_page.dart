import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/setting/components/body_admin_management.dart';

class AdminManagementPage extends StatelessWidget {
  const AdminManagementPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          elevation: 0,
          backgroundColor: AppColors.whiteColor,
          iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
          centerTitle: true,
          title: BigText(text: "สำหรับผู้ดูแลระบบ", size: Dimensions.font20)),
      backgroundColor: AppColors.whiteColor,
      body: const BodyAdminManagement(),
    );
  }
}
