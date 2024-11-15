import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:acs_check/models/job_status_model.dart';
import 'package:acs_check/utils/app_constants.dart';

class JobStatusService {
  Future<List<JobStatus>?> fetchJobStatuses() async {
    try {
      final response = await http.get(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.jobStatus}'),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final List<dynamic> responseData = json.decode(response.body);

        return responseData.map((data) => JobStatus.fromJson(data)).toList();
      } else {
        print('Failed to load job statuses');
      }
    } catch (e) {
      print('Error during API call: $e');
    }
    return null;
  }

}
