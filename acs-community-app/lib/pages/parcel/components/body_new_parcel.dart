import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:acs_community/widgets/fullscreen_image.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/parcel_controller.dart';
import 'package:acs_community/models/parcel_model.dart';
import 'package:acs_community/functions/format_datetime.dart'; 

class BodyNewParcel extends StatefulWidget {
  final int parcelId;

  const BodyNewParcel({Key? key, required this.parcelId}) : super(key: key);

  @override
  State<BodyNewParcel> createState() => _BodyNewParcelState();
}

class _BodyNewParcelState extends State<BodyNewParcel> {
  @override
  Widget build(BuildContext context) {
    final ParcelController _parcelController = Get.find();
    final Parcel? parcel = _parcelController.getParcelById(widget.parcelId);

    return Stack(
      children: [
        Column(
          children: [
            Container(
              color: AppColors.mainColor,
              width: MediaQuery.of(context).size.width,
              height: MediaQuery.of(context).size.height * 0.5,
              child: Center(
                child: Column(
                  children: [
                    SizedBox(height: Dimensions.height20), // Set the desired top margin
                    GestureDetector(
                      onTap: () {
                        showDialog(
                          context: context,
                          builder: (context) {
                            return AlertDialog(
                              content: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Align(
                                    alignment: Alignment.centerRight,
                                    child: GestureDetector(
                                      onTap: () {
                                        Navigator.of(context).pop();
                                      },
                                      child: Icon(
                                        Icons.close,
                                        color: AppColors.darkGreyColor,
                                        size: Dimensions.iconSize30,
                                      ),
                                    ),
                                  ),
                                  Container(
                                    width: 270,
                                    height: 270,
                                    decoration: BoxDecoration(
                                      borderRadius: BorderRadius.circular(Dimensions.radius20),
                                    ),
                                    child: Center(
                                      child: QrImageView(
                                        backgroundColor: AppColors.whiteColor,
                                        data: parcel?.qrData ?? '',
                                        size: 250,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            );
                          },
                        );
                      },
                      child: Container(
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(Dimensions.radius20)
                        ),
                        child: QrImageView(
                          padding: const EdgeInsets.all(15.0),
                          backgroundColor: AppColors.whiteColor,
                          data: parcel?.qrData ?? '',
                          size: 170
                        )
                      ),
                    ),
                    SizedBox(height: Dimensions.height15),
                    const SmallText(
                      text: "นำ QR code ให้เจ้าหน้าที่สแกน เพื่อรับพัสดุ",
                      color: AppColors.whiteColor
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
        Positioned(
          top: MediaQuery.of(context).size.height * 0.31,
          left: Dimensions.width20,
          right: Dimensions.width20,
          child: Container(
          width: MediaQuery.of(context).size.width - 40,
          height: MediaQuery.of(context).size.height * 0.52,
          decoration: BoxDecoration(
            color: AppColors.whiteColor,
            borderRadius: BorderRadius.circular(
                Dimensions.radius20), // Set the desired border radius
          ),
          child: Column(children: [
              Padding(
                padding: const EdgeInsets.all(10),
                child: GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => FullScreenImage(
                          imageUrl: parcel?.fileDocument ??
                          'assets/images/s1.jpg'
                        ),
                      ),
                    );
                  },
                  child: Container(
                    height: 130,
                    width: MediaQuery.of(context).size.width - 55,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(Dimensions.radius20),
                      image: DecorationImage(
                        image: AssetImage(
                          parcel?.fileDocument ??
                          'assets/images/s1.jpg'
                        ),
                        fit: BoxFit.cover,
                      ),
                    ),
                  ),
                ),
              ),
              SizedBox(height: Dimensions.height10),
              BigText(text: "ข้อมูลพัสดุ", size: Dimensions.font20),
              SizedBox(height: Dimensions.height10),
              Column(children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(
                            text: "บ้านเลขที่", size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: BigText(
                            text: parcel?.unitNo ?? 'ไม่พบข้อมูล',
                            size: Dimensions.font16,
                            color: AppColors.blackColor
                          )
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(
                            text: "ชื่อเจ้าของพัสดุ", size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: BigText(
                            text: parcel?.owner ?? 'ไม่พบข้อมูล',
                            size: Dimensions.font16,
                            color: AppColors.blackColor
                          )
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(
                            text: "หมายเลขติดตามพัสดุ",
                            size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: BigText(
                            text: parcel?.trackingNo ?? 'ไม่พบข้อมูล',
                            size: Dimensions.font16,
                            color: AppColors.blackColor
                          )
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(text: 'บริการขนส่ง', size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: BigText(
                            text: parcel?.deliveryService ?? 'ไม่พบข้อมูล',
                            size: Dimensions.font16,
                            color: AppColors.blackColor
                          )
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(
                            text: "ลักษณะพัสดุ", size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                            alignment: Alignment.centerRight,
                            child: BigText(
                                text: parcel?.type ?? 'ไม่พบข้อมูล',
                                size: Dimensions.font16,
                                color: AppColors.blackColor)),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(
                            text: "เข้าระบบเมื่อ", size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: BigText(
                            text: formatDateTime(parcel?.collectedDateTime),
                            size: Dimensions.font14,
                            color: AppColors.blackColor
                          )
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: Dimensions.width20),
                        child: SmallText(
                            text: "นำเข้าระบบโดย", size: Dimensions.font16),
                      ),
                    ),
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(right: Dimensions.width20),
                        child: Align(
                          alignment: Alignment.centerRight,
                          child: BigText(
                            text: parcel?.addedBy ?? 'ไม่พบข้อมูล',
                            size: Dimensions.font16,
                            color: AppColors.blackColor
                          )
                        ),
                      ),
                    ),
                  ],
                ),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
              ])
            ]),
          ),
        ),
      ],
    );
  }
}
