import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/small_text.dart';

class Footer extends StatelessWidget {
  final String text;
  final String imagePath;

  const Footer({Key? key, required this.text, required this.imagePath})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      color: Colors.grey[200],
      padding: const EdgeInsets.all(16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Image.asset(
            imagePath,
            width: 24,
            height: 24,
          ),
          SizedBox(width: Dimensions.width5),
          SmallText(
            text: text,
            size: Dimensions.font14,
            color: AppColors.blackColor,
          ),
        ],
      ),
    );
  }
}
