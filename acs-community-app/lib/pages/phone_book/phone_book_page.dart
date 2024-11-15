import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/phone_book/components/body_phone_book.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/phone_book_controller.dart';
class PhoneBookPage extends StatefulWidget {
  const PhoneBookPage({Key? key}) : super(key: key);

  @override
  State<PhoneBookPage> createState() => _PhoneBookPageState();
}

class _PhoneBookPageState extends State<PhoneBookPage> {
  final PhoneBookController _phoneBookController = Get.put(PhoneBookController());
  
  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: _phoneBookController.contactTypesTH.length,
      child: Scaffold(
        appBar: AppBar(
          elevation: 0,
          backgroundColor: AppColors.whiteColor,
          iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
          centerTitle: true,
          title: BigText(text: "สมุดโทรศัพท์", size: Dimensions.font20),
          bottom: TabBar(
            labelColor: AppColors.blackColor,
            indicatorColor: AppColors.mainColor,
            tabs: [
             for (final contactType in _phoneBookController.contactTypesTH)
              Tab(child: BigText(text: contactType, size: Dimensions.font18)),
            ]
          ),
        ),
        backgroundColor: AppColors.menuColor,
        body: BodyPhoneBook())
    );
  }
}
