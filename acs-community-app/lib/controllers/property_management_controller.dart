import 'package:get/get.dart';
import 'package:acs_community/models/property_management_model.dart';
import 'package:acs_community/services/api_service.dart';
import 'package:logger/logger.dart';

class PropertyManagementController extends GetxController {
  final ApiService _apiService = ApiService();

  final RxList<PropertyManagement> propertyManagementLists =
      <PropertyManagement>[].obs;

  final Logger logger = Logger();

  @override
  void onInit() {
    super.onInit();
    fetchJuristicInfo();
  }

  Future<void> fetchJuristicInfo() async {
    try {
      final List<PropertyManagement> juristicInfo = await _apiService.getPropertyManagement();
      propertyManagementLists.assignAll(juristicInfo);
    } catch (e) {
      logger.e('Error fetching announcements: $e');
    }
  }

}
