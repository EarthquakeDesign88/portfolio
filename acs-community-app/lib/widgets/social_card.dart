import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class SocialCard extends StatelessWidget {
  final String? text;
  final IconData? icon;
  final Color? color;
  final Function? routeTo;

  const SocialCard({Key? key, this.text, this.icon, this.color, this.routeTo})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: routeTo as void Function()?,
      child: SizedBox(
          height: 70,
          width: 320,
          child: Card(
            color: AppColors.whiteColor,
            margin: const EdgeInsets.symmetric(vertical: 10, horizontal: 25),
            child: ListTile(
              leading: Icon(
                icon!,
                color: color!,
              ),
              title: Text(
                text!,
                style: TextStyle(
                  color: color!,
                  fontSize: Dimensions.font16,
                ),
              ),
            ),
          )
        )
      );
  }
}
