import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
// import 'package:acs_community/widgets/main_button.dart';
import 'package:acs_community/pages/suggestion/components/body_suggestion.dart';

class SuggestionPage extends StatelessWidget {
  const SuggestionPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
          elevation: 0,
          backgroundColor: AppColors.whiteColor,
          iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
          centerTitle: true,
          title: BigText(text: "ข้อเสนอแนะ", size: Dimensions.font20)),
      backgroundColor: AppColors.whiteColor,
      body: const BodySuggestion(),
    );
  }
}
