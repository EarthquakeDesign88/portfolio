import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class BigText extends StatelessWidget {
  final Color? color;
  final String text;
  final double size;
  final TextOverflow overFlow;

  const BigText(
      {Key? key,
      this.color = AppColors.blackColor,
      required this.text,
      this.size = 0,
      this.overFlow = TextOverflow.ellipsis})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Text(text,
        maxLines: 2,
        overflow: overFlow,
        style: TextStyle(
          // fontFamily: 'Roboto',
          color: color,
          fontSize: size == 0 ? Dimensions.font36 : size,
          fontWeight: FontWeight.w600,
        ));
  }
}
