import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:acs_check/models/issue_topic_model.dart';
import 'package:acs_check/utils/app_constants.dart';

class IssueTopicService {
  Future<List<IssueTopic>?> fetchIssueTopics() async {
    try {
      final response = await http.get(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.issueTopic}'),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final List<dynamic> responseData = json.decode(response.body);

        return responseData.map((data) => IssueTopic.fromJson(data)).toList();
      } else {
        print('Failed to load issue topics');
      }
    } catch (e) {
      print('Error during API call: $e');
    }
    return null;
  }

}
