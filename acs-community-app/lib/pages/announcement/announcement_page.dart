import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/bottom_navbar.dart';
import 'package:acs_community/pages/announcement/components/announcement_appbar.dart';
import 'package:acs_community/pages/announcement/components/body_announcement.dart';

class AnnouncementPage extends StatefulWidget {
  const AnnouncementPage({Key? key}) : super(key: key);

  @override
  State<AnnouncementPage> createState() => _AnnouncementPageState();
}

class _AnnouncementPageState extends State<AnnouncementPage> {
  int _currentIndex = 0;
  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AnnouncementAppBar(
          currentIndex: _currentIndex,
          onTabChanged: _onTabChanged
        ),
        backgroundColor: AppColors.menuColor,
        body: BodyAnnouncement(),
        bottomNavigationBar: BottomNavbar(
          currentIndex: _currentIndex,
          onTabChanged: _onTabChanged,
        ),
      ),
    );
  }
}
