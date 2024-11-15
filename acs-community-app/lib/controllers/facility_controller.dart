import 'package:get/get.dart';
import 'package:acs_community/models/facility_model.dart';
import 'package:acs_community/services/api_service.dart';
import 'package:logger/logger.dart';

class FacilityController extends GetxController {
  final ApiService _apiService = ApiService();
  final RxList<Facility> facilityLists = <Facility>[].obs;
  final Logger logger = Logger();

  @override
  void onInit() {
    super.onInit();
    fetchFacilities();
  }

  Future<void> fetchFacilities() async {
    try {
      final List<Facility> facilities = await _apiService.getFacilities();
      facilityLists.assignAll(facilities);
    } catch (e) {
      logger.e('Error fetching facilities: $e');
    }
  }

  Facility? bookingById(int facilityId) {
    return facilityLists.firstWhere((facility) => facility.id == facilityId);
  }
}
