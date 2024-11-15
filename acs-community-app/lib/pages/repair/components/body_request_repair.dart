import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/main_button.dart';
// import 'package:acs_community/widgets/image_input_box.dart';

class BodyRequestRepair extends StatefulWidget {
  const BodyRequestRepair({Key? key}) : super(key: key);

  @override
  State<BodyRequestRepair> createState() => _BodyRequestRepairState();
}

class _BodyRequestRepairState extends State<BodyRequestRepair> {
  bool hasEnteredUnitNo = true;
  bool hasEnteredBuilding = false;
  bool hasEnteredRoom = false;
  bool hasEnteredTopic = false;
  bool hasEnteredDetail = false;
  bool hasEnteredPhoneNumber = false;

  String? selectedType;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(15, 0, 15, 0),
      child: SingleChildScrollView(
        child: Column(
          children: [
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "บ้านเลขที่",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
                BigText(text: "*", color: Colors.red, size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              color: hasEnteredUnitNo
                  ? AppColors.hoverInputText
                  : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  decoration: const InputDecoration(
                    hintText: '3300/25',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredUnitNo = value.isNotEmpty;
                    });
                  },
                ),
              ),
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "อาคาร",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              color: hasEnteredBuilding
                  ? AppColors.hoverInputText
                  : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  decoration: const InputDecoration(
                    hintText: 'ระบุอาคาร',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredBuilding = value.isNotEmpty;
                    });
                  },
                ),
              ),
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "ชั้น/เลขที่",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              color:
                  hasEnteredRoom ? AppColors.hoverInputText : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  decoration: const InputDecoration(
                    hintText: 'ระบุชั้น/เลขห้อง',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredRoom = value.isNotEmpty;
                    });
                  },
                ),
              ),
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "ประเภทงานซ่อม",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
                BigText(text: "*", color: Colors.red, size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  height: 40,
                  width: MediaQuery.of(context).size.width / 2.3,
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: Colors.grey,
                      width: 1.0,
                    ),
                    borderRadius: BorderRadius.circular(5.0),
                  ),
                  padding: const EdgeInsets.all(5.0),
                  child: Row(
                    children: [
                      Radio(
                        value: "ไฟฟ้า",
                        groupValue: selectedType,
                        onChanged: (value) {
                          setState(() {
                            selectedType = value.toString();
                          });
                        },
                        activeColor: AppColors.mainColor,
                      ),
                      SmallText(
                        text: "ไฟฟ้า",
                        color: selectedType == "ไฟฟ้า"
                            ? AppColors.mainColor
                            : AppColors.darkGreyColor,
                      )
                    ],
                  ),
                ),
                Container(
                  height: 40,
                  width: MediaQuery.of(context).size.width / 2.3,
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: Colors.grey,
                      width: 1.0,
                    ),
                    borderRadius: BorderRadius.circular(5.0),
                  ),
                  padding: const EdgeInsets.all(5.0),
                  child: Row(
                    children: [
                      Radio(
                        value: "ประปา",
                        groupValue: selectedType,
                        onChanged: (value) {
                          setState(() {
                            selectedType = value.toString();
                          });
                        },
                        activeColor: AppColors.mainColor,
                      ),
                      SmallText(
                        text: "ประปา",
                        color: selectedType == "ประปา"
                            ? AppColors.mainColor
                            : AppColors.darkGreyColor,
                      )
                    ],
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  height: 40,
                  width: MediaQuery.of(context).size.width / 2.3,
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: Colors.grey,
                      width: 1.0,
                    ),
                    borderRadius: BorderRadius.circular(5.0),
                  ),
                  padding: const EdgeInsets.all(5.0),
                  child: Row(
                    children: [
                      Radio(
                        value: "แอร์",
                        groupValue: selectedType,
                        onChanged: (value) {
                          setState(() {
                            selectedType = value.toString();
                          });
                        },
                        activeColor: AppColors.mainColor,
                      ),
                      SmallText(
                        text: "แอร์",
                        color: selectedType == "แอร์"
                            ? AppColors.mainColor
                            : AppColors.darkGreyColor,
                      )
                    ],
                  ),
                ),
                Container(
                  height: 40,
                  width: MediaQuery.of(context).size.width / 2.3,
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: Colors.grey,
                      width: 1.0,
                    ),
                    borderRadius: BorderRadius.circular(5.0),
                  ),
                  padding: const EdgeInsets.all(5.0),
                  child: Row(
                    children: [
                      Radio(
                        value: "อื่นๆ",
                        groupValue: selectedType,
                        onChanged: (value) {
                          setState(() {
                            selectedType = value.toString();
                          });
                        },
                        activeColor: AppColors.mainColor,
                      ),
                      SmallText(
                        text: "อื่นๆ",
                        color: selectedType == "อื่นๆ"
                            ? AppColors.mainColor
                            : AppColors.darkGreyColor,
                      )
                    ],
                  ),
                ),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "หัวข้อการแจ้งซ่อม (0/100)",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
                BigText(text: "*", color: Colors.red, size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              color:
                  hasEnteredTopic ? AppColors.hoverInputText : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  decoration: const InputDecoration(
                    hintText: 'พิมพ์หัวข้อการแจ้งซ่อม',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredTopic = value.isNotEmpty;
                    });
                  },
                ),
              ),
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "รายละเอียด",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
                BigText(text: "*", color: Colors.red, size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              height: 80,
              color: hasEnteredDetail
                  ? AppColors.hoverInputText
                  : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  decoration: const InputDecoration(
                    hintText: 'พิมพ์รายละเอียดข้อเสนอแนะ',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredDetail = value.isNotEmpty;
                    });
                  },
                  maxLines: null, // Allow multiple lines of text
                  textInputAction: TextInputAction
                      .newline, // Change keyboard action to new line
                  onEditingComplete: () {
                    WidgetsBinding.instance.addPostFrameCallback((_) {
                      final currentFocus = FocusScope.of(context);
                      if (!currentFocus.hasPrimaryFocus &&
                          currentFocus.hasFocus) {
                        currentFocus.nextFocus();
                      }
                    });
                    // final currentFocus = FocusScope.of(context);
                    // currentFocus.nextFocus();
                  },
                ),
              ),
            ),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "เพิ่มรูป (ไม่เกิน 3 รูป)",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            // ImageInputBox(),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "เพิ่มวิดีโอ (ไม่เกิน 10 MB)",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            // ImageInputBox(),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "เบอร์โทรศัพท์สำหรับติดต่อกลับ",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
                BigText(text: "*", color: Colors.red, size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              color: hasEnteredPhoneNumber
                  ? AppColors.hoverInputText
                  : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  decoration: const InputDecoration(
                    hintText: 'พิมพ์เบอร์โทรศัพท์',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredPhoneNumber = value.isNotEmpty;
                    });
                  },
                ),
              ),
            ),
            SizedBox(height: Dimensions.height20),
            const MainButton(
              text: "ยืนยัน",
              bgColor: AppColors.greyColor,
              borderColor: AppColors.greyColor,
              textColor: AppColors.darkGreyColor,
            )
          ],
        ),
      ),
    );
  }
}
