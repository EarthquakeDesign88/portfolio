import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class TextUnderline extends StatelessWidget {
  final Color? textColor;
  final String text;
  final double sizeText;
  final Color borderColor;
  final double borderWidth;
  final bool isBold; 

  const TextUnderline({
    Key? key,
    this.textColor = AppColors.darkGreyColor,
    required this.text,
    this.sizeText = 16,
    this.borderColor = AppColors.darkGreyColor,
    this.borderWidth = 1.0,
    this.isBold = true,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        border: Border(
          bottom: BorderSide(color: borderColor, width: borderWidth),
        ),
      ),
      child: Text(
        text,
        style: TextStyle(
          fontSize: sizeText, 
          fontWeight: isBold ? FontWeight.bold : FontWeight.normal, 
          color: textColor
        ),
      ),
    );
  }
}
