import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'package:flutter/services.dart';

class BodyInvitationCode extends StatefulWidget {
  const BodyInvitationCode({Key? key}) : super(key: key);

  @override
  State<BodyInvitationCode> createState() => _BodyInvitationCodeState();
}

class _BodyInvitationCodeState extends State<BodyInvitationCode> {
  String inviteCode = 'LD@EA89P';
  bool isCopied = false;

  void _copyTextToClipboard(String text) {
    Clipboard.setData(ClipboardData(text: text));

    setState(() {
      isCopied = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        Center(
          child: SizedBox(
            width: MediaQuery.of(context).size.width - 100,
            child: Column(children: [
              SizedBox(height: Dimensions.height30),
              BigText(text: "บ้านเลขที่ 3300/25", size: Dimensions.font26),
              SizedBox(height: Dimensions.height15),
              BigText(
                text: "Elephant Tower",
                size: Dimensions.font18,
                color: AppColors.blackColor
              ),
              SizedBox(
                width: 300,
                height: 300,
                child: Center(
                  child: QrImageView(
                    backgroundColor: AppColors.whiteColor,
                    data: '123456789',
                    size: 250,
                  ),
                ),
              ),
              SizedBox(height: Dimensions.height15),
              Container(
                decoration: BoxDecoration(
                  color: const Color.fromARGB(255, 209, 245, 233),
                  borderRadius: BorderRadius.circular(Dimensions.radius10),
                ),
                child: Padding(
                  padding:
                      EdgeInsets.symmetric(vertical: Dimensions.height10),
                  child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Row(
                          children: inviteCode.split('').map((char) {
                            return Padding(
                              padding: EdgeInsets.symmetric(
                                  horizontal: Dimensions.width5),
                              child: Text(
                                char,
                                style: const TextStyle(
                                    fontSize: 30,
                                    color: AppColors.mainColor,
                                    fontWeight: FontWeight.bold),
                              ),
                            );
                          }).toList(),
                        ),
                      ]),
                )
              ),
              SizedBox(height: Dimensions.height15),
              RichText(
                text: TextSpan(
                  children: [
                    TextSpan(
                      text: 'รหัสใช้ได้ถึงวันที่ ',
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: Dimensions.font16,
                      ),
                    ),
                    TextSpan(
                      text: '27 กรกฎาคม 2566',
                      style: TextStyle(
                        color: Colors.orange,
                        fontSize: Dimensions.font16,
                        fontWeight: FontWeight.bold
                      ),
                    ),
                  ],
                ),
              ),
              SizedBox(height: Dimensions.height30),
              SizedBox(
                height: Dimensions.height50,
                width: MediaQuery.of(context).size.width - 220,
                child: MaterialButton(
                    color: isCopied ? AppColors.greyColor : AppColors.mainColor,
                    onPressed: () {
                      _copyTextToClipboard(inviteCode);
                    },
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(Dimensions.radius10),
                      side: BorderSide(
                        color: isCopied
                            ? AppColors.greyColor
                            : AppColors.mainColor,
                        width: Dimensions.width2,
                      ),
                    ),
                    child: BigText(
                      text: isCopied ? "คัดลอกแล้ว" : "คัดลอก",
                      size: Dimensions.font18,
                      color: isCopied
                          ? AppColors.darkGreyColor
                          : AppColors.whiteColor
                    )
                ),
              )
            ]),
          ),
        )
      ],
    );
  }
}
