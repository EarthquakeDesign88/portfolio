import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';

class NoParcel extends StatelessWidget {
  const NoParcel({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(
            Icons.picture_in_picture,
            color: AppColors.greyColor,
            size: 150,
          ),
          SizedBox(height: Dimensions.height20), 
          BigText(text: "ยังไม่มีรายการพัสดุ", size: Dimensions.font22),
          SizedBox(height: Dimensions.height10),
          const SmallText(text: "รายการประวัติพัสดุจะแสดงในหน้านี้"),
        ],
      ),
    );
  }
}
