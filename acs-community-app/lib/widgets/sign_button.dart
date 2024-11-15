import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';

class SignButton extends StatelessWidget {
  final Color? bgColor;
  final Function? routeTo;
  final Color? borderColor;
  final Color? textColor;
  final String? text;

  const SignButton({
    Key? key,
    this.bgColor,
    this.routeTo,
    this.borderColor,
    required this.text,
    this.textColor
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      color: bgColor,
      child: MaterialButton(
        minWidth: 320,
        height: Dimensions.height50,
        onPressed: routeTo != null ? routeTo! as void Function() : null,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(Dimensions.radius10),
          side: BorderSide(
            color: borderColor!,
            width: 2.0,
          ),
        ),
        child: BigText(text: text!, size: Dimensions.font18, color: textColor)),
    );
  }
}
