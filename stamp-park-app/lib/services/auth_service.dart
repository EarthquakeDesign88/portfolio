import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:stamp_park/utils/app_constants.dart';
import 'package:stamp_park/models/user_model.dart';

class AuthService {
  Future<bool> login(String username, String password) async {
    try {
      final response = await http.post(
        Uri.parse('${AppConstants.baseUrl}${AppConstants.login}'),
        body: {
          'username': username,
          'password': password,
        },
      );
      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = json.decode(response.body);

        if (responseData.containsKey('user')) {
          final Map<String, dynamic> userJson = responseData['user'];
          final user = User.fromJson(userJson);

          await _updateLoginStatus(user.username, user.stampCode, user.stampCondition, user.companyName);

          // print('Login success: ${response.statusCode} ${response.body}');

          return true;
        } else {
          print('Login failed with status code: ${response.statusCode}');
        }
      } 
      else if (response.statusCode == 401) {
        print('Login failed with status code: ${response.statusCode}');
      }
    } catch (e) {
      print('Error during login: $e');
    }

    return false;
  }

  Future<void> _updateLoginStatus(String username, String stampCode, String stampCondition, String companyName) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('isLoggedIn', true);
    await prefs.setString('username', username);
    await prefs.setString('stampCode', stampCode);
    await prefs.setString('stampCondition', stampCondition);
    await prefs.setString('companyName', companyName);
  }

  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getBool('isLoggedIn') ?? false;
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('isLoggedIn', false);
    await prefs.remove('username');
    await prefs.remove('stampCode');
    await prefs.remove('stampCondition');
    await prefs.remove('companyName');
  }

  Future<String?> getUsername() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('username');
  }

  Future<String?> getStampCode() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('stampCode');
  }

  Future<String?> getStampCondition() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('stampCondition');
  }

  Future<String?> getCompanyName() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('companyName');
  }
}
