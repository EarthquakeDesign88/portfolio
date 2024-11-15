import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/pages/chat/components/body_chat.dart';

class ChatPage extends StatelessWidget {
  const ChatPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(
          color: AppColors.darkGreyColor,
        ),
        centerTitle: false,
        flexibleSpace: FlexibleSpaceBar(
          titlePadding: EdgeInsets.only(left: Dimensions.width50),
          title: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(height: Dimensions.height15),
              BigText(text: "นิติฯ Elephant Tower", size: Dimensions.font18),
              SizedBox(height: Dimensions.height5),
              SmallText(
                text: "สามารถแชทได้ตั้งแต่ 08:40 - 17.30",
                size: Dimensions.font14,
                color: AppColors.mainColor,
              ),
            ],
          ),
        ),
      ),
      body: const BodyChat()
    );
  }
}
