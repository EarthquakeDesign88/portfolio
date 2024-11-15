import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/setting/components/body_faq_detail.dart';

class FaqDetailPage extends StatelessWidget {
  final int faqId;

  FaqDetailPage({
    Key? key, 
    required this.faqId
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
        centerTitle: true,
        title: BigText(text: "คำถามที่พบบ่อย", size: Dimensions.font20),
      ),
      backgroundColor: AppColors.whiteColor,
      body: BodyFaqDetail(faqId: faqId),
    );
  }
}
