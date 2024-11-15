import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:get/get.dart';
import 'package:acs_community/controllers/announcement_controller.dart';

class AnnouncementAppBar extends StatelessWidget
    implements PreferredSizeWidget {
  final int currentIndex;
  final Function(int) onTabChanged;

  AnnouncementAppBar({
    required this.currentIndex,
    required this.onTabChanged,
  });

  @override
  Size get preferredSize => const Size.fromHeight(100); //56.0 For Android or 44.0 For IOS
  final AnnouncementController _announcementController = Get.put(AnnouncementController());

  @override
  Widget build(BuildContext context) {
    return AppBar(
      elevation: 0,
      backgroundColor: AppColors.whiteColor,
      iconTheme: const IconThemeData(
        color: AppColors.darkGreyColor,
      ),
      centerTitle: true,
      title: BigText(text: "ประกาศ", size: Dimensions.font20),
      actions: [
        IconButton(
          icon: const Icon(Icons.search),
          onPressed: () {},
        ),
        IconButton(
          icon: const Icon(Icons.more_vert),
          onPressed: () {},
        ),
      ],
      bottom: TabBar(
        labelColor: AppColors.blackColor,
        indicatorColor: AppColors.mainColor,
        tabs: [
          for (final announcementType in _announcementController.announcementTypesTH)
            Tab(child: BigText(text: announcementType, size: Dimensions.font18)),
        ],
      ),
    );
  }
}
