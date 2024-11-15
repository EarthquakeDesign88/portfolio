import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class SmallText extends StatelessWidget {
  final Color? color;
  final String text;
  final double size;
  final double height;

  const SmallText({
    Key? key,
    this.color = AppColors.secondaryTextColor,
    required this.text,
    this.size = 0,
    this.height = 1.2
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Text(
      text,
      style: TextStyle(
        color: color,
        fontSize: size == 0 ? Dimensions.font16 : size,
        height: height
      )
    );
  }
}
