import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/bottom_line.dart';
import 'package:acs_community/widgets/fullscreen_image.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/parcel_controller.dart';
import 'package:acs_community/models/parcel_model.dart';
import 'package:acs_community/functions/format_datetime.dart'; 

class BodyHistoryParcel extends StatefulWidget {
  final int parcelId;

  const BodyHistoryParcel({Key? key, required this.parcelId}) : super(key: key);

  @override
  State<BodyHistoryParcel> createState() => _BodyHistoryParcelState();
}

class _BodyHistoryParcelState extends State<BodyHistoryParcel> {
  @override
  Widget build(BuildContext context) {
    final ParcelController parcelController = Get.find();
    final Parcel? parcel = parcelController.getParcelById(widget.parcelId);

    return Stack(
      children: [
        Column(
          children: [
            Container(
              color: AppColors.mainColor,
              width: MediaQuery.of(context).size.width,
              height: MediaQuery.of(context).size.height * 0.3,
            ),
          ],
        ),
        Positioned(
          top: Dimensions.height20,
          left: Dimensions.width20,
          right: Dimensions.width20,
          child: Container(
              width: MediaQuery.of(context).size.width - 40,
              height: MediaQuery.of(context).size.height - 170,
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
                      height: 150,
                      width: MediaQuery.of(context).size.width - 95,
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
                      child: Align(
                        alignment: Alignment.bottomCenter,
                        child: Container(
                          height: Dimensions.height50,
                          width: MediaQuery.of(context).size.width - 95,
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.only(
                              bottomLeft: Radius.circular(Dimensions.radius20),
                              bottomRight: Radius.circular(Dimensions.radius20),
                            ),
                            color: Colors.black.withOpacity(
                                0.5), // Adjust the opacity as needed
                          ), // Adjust the color as needed
                          child: const Center(
                            child: SmallText(
                              text: "พัสดุรับแล้ว",
                              color: AppColors.whiteColor
                            )
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
                SizedBox(height: Dimensions.height10),
                BigText(text: "รายละเอียดการรับพัสดุ", size: Dimensions.font20),
                SizedBox(height: Dimensions.height10),
                Column(
                  children: [
                    Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Expanded(
                            child: Padding(
                              padding: EdgeInsets.only(left: Dimensions.width20),
                              child: SmallText(
                                text: "รับพัสดุเมื่อ",
                                size: Dimensions.font16
                              ),
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
                        ]),
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
                                text: "ชื่อผู้มารับพัสดุ",
                                size: Dimensions.font16
                              ),
                            ),
                          ),
                          Expanded(
                            child: Padding(
                              padding: EdgeInsets.only(right: Dimensions.width20),
                              child: Align(
                                alignment: Alignment.centerRight,
                                child: BigText(
                                  text: parcel?.collectedBy ?? 'ไม่พบข้อมูล',
                                  size: Dimensions.font16,
                                  color: AppColors.blackColor
                                )
                              ),
                            ),
                          ),
                        ]),
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
                                text: "จ่ายพัสดุออกโดย",
                                size: Dimensions.font16
                              ),
                            ),
                          ),
                          Expanded(
                            child: Padding(
                              padding: EdgeInsets.only(right: Dimensions.width20),
                              child: Align(
                                alignment: Alignment.centerRight,
                                child: BigText(
                                  text: parcel?.releasedBy ?? 'ไม่พบข้อมูล',
                                  size: Dimensions.font16,
                                  color: AppColors.blackColor
                                )
                              ),
                            ),
                          ),
                        ]),
                    SizedBox(height: Dimensions.height2),
                    const BottomLine(),
                    SizedBox(height: Dimensions.height2),
                  ],
                ),
                SizedBox(height: Dimensions.height30),
                const SizedBox(width: 300, child: BottomLine(width: 0.3)),
                SizedBox(height: Dimensions.height20),
                BigText(text: "ข้อมูลพัสดุ", size: Dimensions.font20),
                SizedBox(height: Dimensions.height10),
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
                    ]),
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
                            text: "ชื่อเจ้าของพัสดุ",
                            size: Dimensions.font16
                          ),
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
                    ]),
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
                                  color: AppColors.blackColor)),
                        ),
                      ),
                    ]),
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
                              text: "บริการขนส่ง", size: Dimensions.font16),
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
                                  color: AppColors.blackColor)),
                        ),
                      ),
                    ]),
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
                              color: AppColors.blackColor
                            )
                          ),
                      ),
                      ),
                    ]),
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
                                  text: formatDateTime(parcel?.addedDateTime),
                                  size: Dimensions.font14,
                                  color: AppColors.blackColor)),
                        ),
                      ),
                    ]),
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
                                  color: AppColors.blackColor)),
                        ),
                      ),
                    ]),
                SizedBox(height: Dimensions.height2),
                const BottomLine(),
                SizedBox(height: Dimensions.height2),
              ])),
        ),
      ],
    );
  }
}
