import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:stamp_park/utils/app_constants.dart';
import 'package:stamp_park/models/stamp_model.dart';

class StampService {
  Future<Map<String, dynamic>> saveStampInfo(Stamp stamp) async {
    try {
      final response = await http.post(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.saveStampInfo}'),
        body: {
          'visitor_code': stamp.visitorCode,
          'stamp_code': stamp.stampCode,
          'num_stamp': stamp.numStamp.toString(),
          'stamp_count': stamp.stampCount.toString(),
          'recorder_name': stamp.recorderName,
          'stamp_datetime': stamp.stampDatetime,
        },
      );

      Map<String, dynamic> responseData = json.decode(response.body);

      if (response.statusCode == 201 || response.statusCode == 201) {
        return {
          'success': true,
          'statusCode': response.statusCode,
          'message': responseData['message']
        };
      } else {
        return {
          'success': false,
          'statusCode': response.statusCode,
          'message': responseData['message']
        };
      }
    } catch (error) {
      return {'success': false, 'message': 'Error: $error'};
    }
  }

  Future<List<Stamp>> getStampHistory(String? username) async {
  try {
    final response = await http.get(Uri.parse(
        '${AppConstants.baseUrl}${AppConstants.stampHistory}/$username'));

    if (response.statusCode == 200) {
      final dynamic jsonData = jsonDecode(response.body);

      // Check if jsonData is a list
      if (jsonData is List) {
        return jsonData.map((data) => Stamp.fromJson(data)).toList();
      }
      // Check if jsonData is a map with a 'data' key
      else if (jsonData is Map && jsonData.containsKey('data')) {
        final List<dynamic> stampData = jsonData['data'];
        return stampData.map((data) => Stamp.fromJson(data)).toList();
      } else {
        throw Exception('Invalid stamp history data');
      }
    } else {
      throw Exception('Failed to load stamp history');
    }
  } catch (error) {
    print("Error getting stamp history: $error");
    throw error; // Rethrow the error to be handled in the caller
  }
}

}
