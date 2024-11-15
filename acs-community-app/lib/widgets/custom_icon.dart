import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class CustomIcon extends StatelessWidget {
  final double? height;
  final double? width;
  final Color? bgColor;
  final Color? iconColor;
  final IconData? icon;
  final double? iconSize;
  final Function? routeTo;
  final String? shape;

  const CustomIcon(
      {Key? key,
      this.height = 40,
      this.width = 40,
      this.bgColor = AppColors.mainColor,
      this.iconColor = AppColors.whiteColor,
      this.icon,
      this.iconSize = 30,
      this.shape = 'circle',
      this.routeTo})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    BoxShape boxShape =
        shape == 'circle' ? BoxShape.circle : BoxShape.rectangle;

    return Container(
      height: height,
      width: width,
      decoration: BoxDecoration(
        shape: boxShape,
        color: bgColor,
      ),
      child: GestureDetector(
        onTap: () {
          routeTo != null ? routeTo! as void Function() : null;
        },
        child: Icon(
          icon,
          color: iconColor,
          size: iconSize,
        ),
      ),
    );
  }
}
