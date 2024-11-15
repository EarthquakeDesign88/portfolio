import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/parcel/components/body_new_parcel.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/parcel_controller.dart';
import 'package:acs_community/models/parcel_model.dart';

class NewParcelPage extends StatefulWidget {
  final int parcelId;
  
  const NewParcelPage({Key? key, required this.parcelId}) : super(key: key);

  @override
  State<NewParcelPage> createState() => _NewParcelPageState();
}

class _NewParcelPageState extends State<NewParcelPage> {
  late ParcelController _parcelController;
  Parcel? parcel;

  @override
  void initState() {
    super.initState();
    _parcelController = Get.find();
    parcel = _parcelController.getParcelById(widget.parcelId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: AppColors.whiteColor,
        iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
        centerTitle: true,
        title: BigText(text: "พัสดุ ${parcel?.number ?? "ไม่มีเลขพัสดุ"}", size: Dimensions.font20)
      ),
      backgroundColor: AppColors.menuColor,
      body: BodyNewParcel(parcelId: widget.parcelId),
    );
  }
}
