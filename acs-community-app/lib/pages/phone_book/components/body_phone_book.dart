import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/phone_book_controller.dart';

class BodyPhoneBook extends StatelessWidget {
  final PhoneBookController _phoneBookController = Get.find();

  @override
  Widget build(BuildContext context) {
    _phoneBookController.fetchPhoneBooks();
    print(_phoneBookController.phoneBookLists);

    return Obx(() {
      if (_phoneBookController.phoneBookLists.isEmpty) {
        return const Center(
          child: CircularProgressIndicator(),
        );
      } else {
        return TabBarView(children: [
          for (final contactType in _phoneBookController.contactTypes)
            ListView.builder(
              itemCount: _phoneBookController.getContactCount(contactType),
              itemBuilder: (context, index) {
                final contact =
                    _phoneBookController.getContactByType(contactType, index);
                return Column(
                  children: [
                    Container(
                      color: AppColors.whiteColor,
                      child: Column(
                        children: [
                          SizedBox(height: Dimensions.height10),
                          Padding(
                            padding: EdgeInsets.symmetric(
                                horizontal: Dimensions.width15),
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Column(
                                  mainAxisAlignment: MainAxisAlignment.start,
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    BigText(
                                      text: contact.contactName,
                                      size: Dimensions.font16,
                                      color: AppColors.darkGreyColor,
                                    ),
                                    SizedBox(height: Dimensions.height5),
                                    SmallText(
                                      text: contact.contactNumber,
                                      size: Dimensions.font16,
                                      color: AppColors.mainColor,
                                    )
                                  ],
                                ),
                                const Icon(
                                  Icons.phone,
                                  color: AppColors.mainColor,
                                ),
                              ],
                            ),
                          ),
                          SizedBox(height: Dimensions.height10),
                          const BottomLine(),
                        ],
                      ),
                    ),
                  ],
                );
              },
            ),
        ]);
      }
    });
  }
}
