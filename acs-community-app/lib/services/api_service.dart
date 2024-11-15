import 'dart:convert';

import 'package:http/http.dart' as http;
import 'dart:io';
import 'package:acs_community/utils/app_constants.dart';
import 'package:acs_community/models/announcement_model.dart';
import 'package:acs_community/models/phone_book_model.dart';
import 'package:acs_community/models/property_management_model.dart';
import 'package:acs_community/models/facility_model.dart';
import 'package:acs_community/models/faq_model.dart';

class ApiService {
  Future<List<Announcement>> getAnnouncements() async {
    final response = await http.get(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.fetchAnnouncement}'));
 
    if (response.statusCode == 200) {
      final List<dynamic> jsonData = jsonDecode(response.body);
      return jsonData.map((data) => Announcement.fromJson(data)).toList();
    } else {
      throw Exception('Failed to load data');
    }
  }

  Future<List<PhoneBook>> getPhoneBooks() async {
    final response = await http.get(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.fetchPhonebook}'));


    if (response.statusCode == 200) {
      final List<dynamic> jsonData = jsonDecode(response.body);
      return jsonData.map((data) => PhoneBook.fromJson(data)).toList();
    } else {
      throw Exception('Failed to load data');
    }
  }

  Future<List<Facility>> getFacilities() async {
    final response = await http
        .get(Uri.parse('${AppConstants.baseUrl}${AppConstants.fetchFacility}'));

    if (response.statusCode == 200) {
      final List<dynamic> jsonData = jsonDecode(response.body);
      return jsonData.map((data) => Facility.fromJson(data)).toList();
    } else {
      throw Exception('Failed to load data');
    }
  }

  Future<List<PropertyManagement>> getPropertyManagement() async {
    final response = await http.get(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.fetchJuristicInfo}'));

    if (response.statusCode == 200) {
      final List<dynamic> jsonData = jsonDecode(response.body);
      return jsonData.map((data) => PropertyManagement.fromJson(data)).toList();
    } else {
      throw Exception('Failed to load data');
    }
  }

  Future<List<Faq>> getFaq() async {
    final response = await http
        .get(Uri.parse('${AppConstants.baseUrl}${AppConstants.fetchFaq}'));

    if (response.statusCode == 200) {
      final List<dynamic> jsonData = jsonDecode(response.body);
      return jsonData.map((data) => Faq.fromJson(data)).toList();
    } else {
      throw Exception('Failed to load data');
    }
  }

  Future<http.Response> sendSuggestionData(
      Map<String, dynamic> suggestionData, List<File> images) async {
    const String apiUrl =
        '${AppConstants.baseUrl}${AppConstants.createSuggestion}';

    try {
      var request = http.MultipartRequest('POST', Uri.parse(apiUrl));

      // Add suggestion data as fields in the request
      suggestionData.forEach((key, value) {
        request.fields[key] = value.toString();
      });

      // Add images to the request
      for (var i = 0; i < images.length; i++) {
        var stream = http.ByteStream(Stream.castFrom(images[i].openRead()));
        var length = await images[i].length();
        var multipartFile = http.MultipartFile('images', stream, length,
            filename: 'image$i.jpg');
        request.files.add(multipartFile);
      }

      var streamedResponse = await request.send();
      var response = await http.Response.fromStream(streamedResponse);
      return response;
    } catch (e) {
      print('Error sending suggestion data: $e');
      throw e;
    }
  }
}
