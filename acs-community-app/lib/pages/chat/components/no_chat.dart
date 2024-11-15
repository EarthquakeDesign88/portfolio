import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';

class NoChat extends StatelessWidget {
  const NoChat({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Expanded(
          child: Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(
                  Icons.chat_outlined,
                  color: AppColors.greyColor,
                  size: 150,
                ),
                SizedBox(height: Dimensions.height20), // Add empty space
                BigText(text: "ไม่มีข้อความแชทกับนิติฯ", size: Dimensions.font22),
                SizedBox(height: Dimensions.height10),
                const SmallText(text: "คุณสามารถสื่อสารกับนิติฯ แบบส่วนตัวผ่านช่องทางนี้"),
              ],
            ),
          ),
        ),
        Padding(
          padding: const EdgeInsets.all(8.0),
          child: Row(
            children: [
              IconButton(
                icon: const Icon(Icons.camera_alt),
                color: AppColors.mainColor,
                onPressed: () {
          
                },
              ),
              IconButton(
                icon: const Icon(Icons.image),
                color: AppColors.mainColor,
                onPressed: () {
  
                },
              ),
              const Expanded(
                child: TextField(
                  decoration: InputDecoration(
                    hintText: 'พิมพ์ข้อความ',
                  ),
                ),
              ),
              IconButton(
                icon: const Icon(Icons.send),
                onPressed: () {

                },
              ),
            ],
          ),
        ),
      ],
    );
  }
}