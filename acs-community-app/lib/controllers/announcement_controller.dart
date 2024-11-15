import 'package:get/get.dart';
import 'package:acs_community/models/announcement_model.dart';
import 'package:acs_community/services/api_service.dart';
import 'package:logger/logger.dart';


class AnnouncementController extends GetxController {
  final ApiService _apiService = ApiService();

  final RxList<Announcement> announcementLists = <Announcement>[].obs;
  final List<String> announcementTypes = ["important", "general"];
  final List<String> announcementTypesTH = ["ประกาศสำคัญ", "ข่าวสารทั่วไป"];

  final Logger logger = Logger();

  @override
  void onInit() {
    super.onInit();
    fetchAnnouncements();
  }

  Future<void> fetchAnnouncements() async {
    try {
      final List<Announcement> announcements = await _apiService.getAnnouncements();
      announcementLists.assignAll(announcements);
    } catch (e) {
      logger.e('Error fetching announcements: $e'); 
    }
  }

  int getAnnouncementCount(String type) {
    return announcementLists.where((announcement) => announcement.type == type).length;
  }

  Announcement getAnnouncementByType(String type, int index) {
    return announcementLists.where((announcement) => announcement.type == type).elementAt(index);
  }

  Announcement? getAnnouncementById(int detailId) {
    return announcementLists.firstWhere((announcement) => announcement.id == detailId);
  }
}