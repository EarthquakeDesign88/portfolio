import 'package:get/get.dart';
import 'package:stamp_park/models/stamp_model.dart';
import 'package:stamp_park/services/stamp_service.dart';

class StampController extends GetxController {
  final StampService _apiStamp = StampService();
  final RxList<Stamp> stampHistoryLists = <Stamp>[].obs;

  @override
  void onInit() {
    super.onInit();
  }

  Future<void> fetchStampHistory(String? username) async {
    try {
      if (username == null || username.isEmpty) {
        throw Exception('Username is null or empty');
      }

      final List<Stamp> stamps = await _apiStamp.getStampHistory(username);
      stampHistoryLists.assignAll(stamps);
    } catch (error) {
      print("Error fetching stamp history: $error");
    }
  }
}
