import 'package:get/get.dart';
import 'package:acs_community/models/phone_book_model.dart';
import 'package:acs_community/services/api_service.dart';
import 'package:logger/logger.dart';

class PhoneBookController extends GetxController {
  final ApiService _apiService = ApiService();

  final RxList<PhoneBook> phoneBookLists = <PhoneBook>[].obs;
  final Logger logger = Logger();

  // List<String> contactTypes = ["myProperty", "emergency", "others"]; //Used for local database 
  List<String> contactTypes = ["1", "2", "3"];
  List<String> contactTypesTH = ["โครงการ", "ฉุกเฉิน", "อื่นๆ"];

  @override
  void onInit() {
    super.onInit();
    fetchPhoneBooks();
  }

  Future<void> fetchPhoneBooks() async {
    try {
      final List<PhoneBook> phoneBooks = await _apiService.getPhoneBooks();
      phoneBookLists.assignAll(phoneBooks);
    } catch (e) {
      logger.e('Error fetching announcements: $e');
    }
  }

  int getContactCount(String contactType) {
    return phoneBookLists
        .where((contact) => contact.contactType == contactType)
        .length;
  }

  PhoneBook getContactByType(String contactType, int index) {
    return phoneBookLists
        .where((contact) => contact.contactType == contactType)
        .elementAt(index);
  }
}
