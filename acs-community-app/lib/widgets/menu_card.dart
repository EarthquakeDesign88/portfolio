import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class MenuCard extends StatelessWidget {
  final String? text;
  final IconData? icon;
  final Function? onPressed;

  const MenuCard({Key? key, this.text, this.icon, this.onPressed})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onPressed as void Function()?,
      child: SizedBox(
        height: Dimensions.height60,
        width: double.infinity, 
        child: Card(
          child: Center(
            child: ListTile(
              leading: Icon(
                icon!,
                color: AppColors.mainColor,
              ),
              title: Text(
                text!,
                style: TextStyle(
                  fontSize: Dimensions.font16,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ),
      )
    );
  }
}
