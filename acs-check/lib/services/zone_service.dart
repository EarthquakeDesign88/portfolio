import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:acs_check/models/zone_model.dart';
import 'package:acs_check/utils/app_constants.dart';

class ZoneService {
  Future<List<Zone>?> fetchZones() async {
    try {
      final response = await http.get(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.zone}'),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final List<dynamic> responseData = json.decode(response.body);

        return responseData.map((data) => Zone.fromJson(data)).toList();
      } else {
        print('Failed to load zones');
      }
    } catch (e) {
      print('Error during API call: $e');
    }
    return null;
  }

  Future<List<Zone>?> fetchZonesByUser(String currentDate, int userId, int jobScheduleShiftId) async {
    try {
      final response = await http.post(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.zoneByUser}'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'job_schedule_date': currentDate,
          'user_id': userId,
          'job_schedule_shift_id': jobScheduleShiftId
        })
      );

      if (response.statusCode == 200) {
        final List<dynamic> responseData = json.decode(response.body);

        return responseData.map((data) => Zone.fromJson(data)).toList();
      } else {
        print('Failed to load zones');
      }
    } catch (e) {
      print('Error during API call: $e');
    }
    return null;
  }

}
