import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/payment_reminder/components/body_payment_reminder.dart';

class PaymentReminderPage extends StatelessWidget {
  const PaymentReminderPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(
          color: AppColors.darkGreyColor,
        ),
        centerTitle: true,
        title: BigText(text: "แจ้งเตือนค่าใช้จ่าย", size: Dimensions.font20)
      ),
      body: const BodyPaymentReminder()
    );
  }
}
