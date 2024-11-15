import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/announcement/components/body_announcement_detail.dart';

class AnnouncementDetailPage extends StatelessWidget {
  final int detailId;

  const AnnouncementDetailPage({Key? key, required this.detailId}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
        centerTitle: true,
        title: BigText(text: "ประกาศ", size: Dimensions.font20)
      ),
      backgroundColor: AppColors.whiteColor,
      body: BodyAnnouncementDetail(detailId: detailId)
    );
  }
}
