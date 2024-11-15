import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';

class NoBooking extends StatelessWidget {
  const NoBooking({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 100),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(
            Icons.library_books_outlined,
            color: AppColors.greyColor,
            size: 150,
          ),
          BigText(text: "ยังไม่มีการจองของฉัน", size: Dimensions.font22)
        ],
      ),
    );
  }
}
