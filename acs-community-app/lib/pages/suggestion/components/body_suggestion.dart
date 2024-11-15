import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/image_input_box.dart';
import 'package:acs_community/widgets/main_button.dart';

import 'dart:io';
import 'package:acs_community/services/api_service.dart';

class BodySuggestion extends StatefulWidget {
  const BodySuggestion({Key? key}) : super(key: key);

  @override
  State<BodySuggestion> createState() => _BodySuggestionState();
}

class _BodySuggestionState extends State<BodySuggestion> {
  bool hasEnteredTopic = false;
  bool hasEnteredDetail = false;
  bool hasEnteredPhoneNumber = false;
  bool hasEnteredEmail = false;

  final TextEditingController topicController = TextEditingController();
  final TextEditingController detailController = TextEditingController();
  final TextEditingController phoneNumberController = TextEditingController();
  final TextEditingController emailController = TextEditingController();

  late ImageInputBox imageInputBox;

  List<File> _selectedImages = [];

  @override
  void initState() {
    super.initState();
    imageInputBox = ImageInputBox(
      // Initialize it here
      onImagesChanged: (List<File> images) {
        setState(() {
          _selectedImages = images;
        });
      },
    );
  }

  Future<void> _submitSuggestionData() async {
    // Gather user inputs
    String topic = topicController.text;
    String detail = detailController.text;
    String phoneNumber = phoneNumberController.text;
    String email = emailController.text;
    List<File> images = _selectedImages;

    Map<String, dynamic> suggestionData = {
      'topic': topic,
      'detail': detail,
      'phoneNumber': phoneNumber,
      'email': email,
      'created_at': DateTime.now().toIso8601String(), 
      'user_created': 1, 
    };

    void showAlert(String title, String content) {
      showDialog(
        context: context,
        builder: (context) => AlertDialog(
          title: Text(title),
          content: Text(content),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text('OK'),
            ),
          ],
        ),
      );
    }

    try {
      final response =
          await ApiService().sendSuggestionData(suggestionData, images);
      if (response.statusCode == 200) {
        // API call successful, do something
        print('API response: ${response.body}');

        showAlert('Success', 'ส่งข้อเสนอแนะเสร็จเรียบร้อยแล้ว');
      } else {
        showAlert('Error', 'พบข้อผิดพลาด โปรดลองใหม่');
      }
    } catch (e) {
      // Handle other exceptions
      print('Error sending suggestion data: $e');
      showAlert('Error', 'An error occurred while sending suggestion data.');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(15, 0, 15, 0),
      child: SingleChildScrollView(
        child: Column(
          children: [
            SizedBox(height: Dimensions.height10),
            const Row(children: [
              SmallText(
                  text: "ข้อเสนอแนะสำหรับทีมบริหาร (นิติบุคคล)",
                  color: Colors.red),
            ]),
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "หัวข้อข้อเสนอแนะ",
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
                  controller: topicController,
                  decoration: const InputDecoration(
                    hintText: 'พิมพ์หัวข้อข้อเสนอแนะ',
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
                  controller: detailController,
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
            imageInputBox,
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
                  controller: phoneNumberController,
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
            SizedBox(height: Dimensions.height10),
            Row(
              children: [
                BigText(
                    text: "ส่งสำเนาไปยังอีเมลของคุณ",
                    color: AppColors.blackColor,
                    size: Dimensions.font16),
                BigText(text: "*", color: Colors.red, size: Dimensions.font16),
              ],
            ),
            SizedBox(height: Dimensions.height10),
            Container(
              color:
                  hasEnteredEmail ? AppColors.hoverInputText : Colors.grey[200],
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: TextFormField(
                  controller: emailController,
                  decoration: const InputDecoration(
                    hintText: 'พิมพ์อีเมลของคุณ',
                    hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                    border: InputBorder.none,
                  ),
                  onChanged: (value) {
                    setState(() {
                      hasEnteredEmail = value.isNotEmpty;
                    });
                  },
                ),
              ),
            ),
            Align(
              alignment: Alignment.bottomCenter,
              child: Padding(
                padding: EdgeInsets.only(top: Dimensions.height40),
                child: GestureDetector(
                  onTap: () async {
                    await _submitSuggestionData();
                  },
                  child: const Align(
                    alignment: Alignment.bottomCenter,
                    child: MainButton(
                      text: "ยืนยัน",
                      bgColor: AppColors.greyColor,
                      borderColor: AppColors.greyColor,
                      textColor: AppColors.darkGreyColor,
                    ),
                  ),
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
