import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/pages/repair/components/no_repair.dart';

class RepairPage extends StatefulWidget {
  const RepairPage({Key? key}) : super(key: key);

  @override
  State<RepairPage> createState() => _RepairPageState();
}

class _RepairPageState extends State<RepairPage> {
  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
        length: 2,
        child: Scaffold(
          appBar: AppBar(
            elevation: 0,
            backgroundColor: AppColors.whiteColor,
            iconTheme: const IconThemeData(color: AppColors.darkGreyColor),
            centerTitle: true,
            title: BigText(text: "แจ้งซ่อม", size: Dimensions.font20),
            bottom: TabBar(
                labelColor: AppColors.blackColor,
                indicatorColor: AppColors.mainColor,
                tabs: [
                  Tab(
                      child:
                          BigText(text: "ปัจจุบัน", size: Dimensions.font18)),
                  Tab(
                      child:
                          BigText(text: "ประวัติ", size: Dimensions.font18)), //
                ]),
          ),
          backgroundColor: AppColors.menuColor,
          body: const NoRepair(
              textTab1: "ไม่มีรายการแจ้งซ่อมปัจจุบัน",
              textTab2: "ไม่มีประวัติการแจ้งซ่อม"),
        ));
  }
}
