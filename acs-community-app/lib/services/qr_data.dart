import 'package:acs_community/utils/app_constants.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:logger/logger.dart';

Future<void> sendQRData(String qrData) async {
  final Logger logger = Logger();

  try {
    final generateTime = DateTime.now();
    final expirationTime = generateTime.add(const Duration(minutes: 2));

    var data = {
      "qrdata": qrData,
      "status": "Identity Authentication",
      "generate_time": generateTime.toString(),
      "expiration_time": expirationTime.toString()
    };

    logger.e('QR Data: $data');

    final res = await http.post(
      Uri.parse('${AppConstants.baseUrl}${AppConstants.generateQrCodeUri}'), 
      headers: {
        'Content-Type': 'application/json', 
      },
      body: jsonEncode(data), 
    );

    if (res.statusCode == 200) {
      logger.e('QR Data sent successfully');
    } else {
      logger.e('Failed to send QR Data. Status code: ${res.statusCode}');
    }
  } catch (err) {
    logger.e('Error sending QR Data: $err');
  }
}
