import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';

class NoPaymentReminder extends StatelessWidget {
  const NoPaymentReminder({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          SizedBox(height: Dimensions.height60),
          const Icon(
            Icons.payment_outlined,
            color: AppColors.greyColor,
            size: 150,
          ),
          BigText(text: "ยังไม่มีรายการแจ้งเตือนค่าใช้จ่าย", size: Dimensions.font20),
          SizedBox(height: Dimensions.height20),
          const Expanded(
            child: SmallText(
                text: "รายการแจ้งเตือนค่าใช้จ่ายจะแสดงในหน้านี้ หากทางนิติฯ ของท่านทำข้อมูลรายการค้างจ่ายผ่านระบบ ACS Community"),
          )
        ],
        
      ),
    );
  }
}
