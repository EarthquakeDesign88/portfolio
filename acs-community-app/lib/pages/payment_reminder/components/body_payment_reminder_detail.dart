import 'package:acs_community/widgets/main_button.dart';
import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/custom_icon.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';

class BodyPaymentReminderDetail extends StatelessWidget {
  const BodyPaymentReminderDetail({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width30),
          child: Column(
            children: [
              SizedBox(height: Dimensions.height20),
              BigText(text: "ยอดรวมที่ต้องชำระ (บาท)", size: Dimensions.font20),
              SizedBox(height: Dimensions.height10),
              BigText(
                text: "12,000.50",
                size: Dimensions.font22,
                color: AppColors.mainColor
              ),
              SizedBox(height: Dimensions.height10),
              const BottomLine(),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        Center(child: BigText(text: "รายละเอียด", size: Dimensions.font20)),
        SizedBox(height: Dimensions.height20),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                  text: "ชุมชน",
                  size: Dimensions.font16,
                  color: AppColors.darkGreyColor),
              BigText(
                  text: "ACS Community",
                  size: Dimensions.font16,
                  color: AppColors.blackColor),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                text: "บ้านเลขที่",
                size: Dimensions.font16,
                color: AppColors.darkGreyColor
              ),
              BigText(
                text: "3300/25",
                size: Dimensions.font16,
                color: AppColors.blackColor
              ),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                  text: "รายการ",
                  size: Dimensions.font16,
                  color: AppColors.darkGreyColor),
              BigText(
                  text: "ค่าส่วนกลาง",
                  size: Dimensions.font16,
                  color: AppColors.blackColor),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                  text: "เจ้าของกรรมสิทธิ์",
                  size: Dimensions.font16,
                  color: AppColors.darkGreyColor),
              BigText(
                  text: "นายทดสอบ ระบบ",
                  size: Dimensions.font16,
                  color: AppColors.blackColor),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                  text: "กำหนดชำระ",
                  size: Dimensions.font16,
                  color: AppColors.darkGreyColor),
              BigText(
                  text: "30 ก.ค. 66",
                  size: Dimensions.font16,
                  color: AppColors.blackColor),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height20),
        Center(child: BigText(text: "ข้อมูลการชำระเงิน", size: Dimensions.font20)),
        SizedBox(height: Dimensions.height20),
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            InkWell(
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    fullscreenDialog: true,
                    builder: (BuildContext context) {
                      return Scaffold(
                        appBar: AppBar(
                          elevation: 0,
                          backgroundColor: AppColors.whiteColor,
                          iconTheme: const IconThemeData(
                            color: AppColors.darkGreyColor,
                          ),
                        ),
                        body: Center(
                          child: Container(
                            child: Column(
                              children: [
                                SizedBox(height: Dimensions.height30),
                                BigText(
                                    text: "ACS Community",
                                    size: Dimensions.font20),
                                SizedBox(height: Dimensions.height5),
                                BigText(
                                    text: "บ้านเลขที่ 3300/25",
                                    size: Dimensions.font20),
                                SizedBox(height: Dimensions.height5),
                                BigText(
                                    text: "ค่าส่วนกลาง",
                                    size: Dimensions.font20),
                                SizedBox(height: Dimensions.height30),
                                BigText(
                                    text: "ยอดรวมที่ต้องชำระ (บาท)",
                                    size: Dimensions.font16),
                                SizedBox(height: Dimensions.height10),
                                BigText(
                                    text: "12,000.50",
                                    size: Dimensions.font22,
                                    color: AppColors.mainColor),
                                Container(
                                  decoration: BoxDecoration(
                                    borderRadius: BorderRadius.circular(Dimensions.radius20)
                                  ),
                                  child: QrImageView(
                                    padding: const EdgeInsets.all(15.0),
                                    backgroundColor: AppColors.whiteColor,
                                    data: '123456789',
                                    size: 190
                                  )
                                ),
                                SizedBox(height: Dimensions.height10),
                                BigText(
                                  text: "ใช้สำหรับการชำระเงินผ่าน Mobile Banking",
                                  size: Dimensions.font16
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                );
              },
              child: Container(
                  height: 60,
                  width: 170,
                  decoration: BoxDecoration(
                    color: AppColors.whiteColor,
                    border: Border.all(
                      color: AppColors.mainColor,
                      width: 2,
                    ),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(
                        Icons.qr_code_rounded,
                        color: AppColors.mainColor,
                        size: 50,
                      ),
                      SizedBox(width: Dimensions.width10),
                      BigText(
                        text: "QR Code",
                        size: Dimensions.font18,
                        color: AppColors.mainColor
                      )
                    ],
                  )),
            ),
            SizedBox(width: Dimensions.width20),
            InkWell(
              onTap: () {},
              child: Container(
                  height: 60,
                  width: 170,
                  decoration: BoxDecoration(
                    color: AppColors.whiteColor,
                    border: Border.all(
                      color: AppColors.mainColor,
                      width: 2,
                    ),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(
                        Icons.qr_code_rounded,
                        color: AppColors.mainColor,
                        size: 50,
                      ),
                      SizedBox(width: Dimensions.width10),
                      BigText(
                          text: "Barcode",
                          size: Dimensions.font18,
                          color: AppColors.mainColor)
                    ],
                  )),
            )
          ],
        ),
        SizedBox(height: Dimensions.height20),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                  text: "รายละเอียดบัญชี",
                  size: Dimensions.font16,
                  color: AppColors.blackColor),
              const CustomIcon(
                  icon: Icons.arrow_drop_down,
                  bgColor: AppColors.secondaryColor,
                  iconColor: AppColors.mainColor,
                  iconSize: 40),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              BigText(
                  text: "วิธีการชำระเงิน Mobile Banking",
                  size: Dimensions.font16,
                  color: AppColors.blackColor),
              const CustomIcon(
                  icon: Icons.arrow_drop_down,
                  bgColor: AppColors.secondaryColor,
                  iconColor: AppColors.mainColor,
                  iconSize: 40),
            ],
          ),
        ),
        SizedBox(height: Dimensions.height10),
        const BottomLine(),
        SizedBox(height: Dimensions.height10),
        Center(
          child: BigText(
            text: "*อย่าลืมแนบหลักฐานการชำระเงินนะคะ",
            size: Dimensions.font16,
            color: Colors.redAccent
          ),
        ),
        SizedBox(height: Dimensions.height10),
        MainButton(
          text: "แนบหลักฐานการชำระเงิน", 
          icon: Icons.payment_outlined, 
          routeTo: (){
            Get.toNamed(RouteHelper.attachProofPayment);
          }
        )
      ],
    );
  }
}
