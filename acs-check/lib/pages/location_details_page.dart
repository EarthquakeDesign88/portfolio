import 'package:acs_check/utils/app_constants.dart';
import 'package:flutter/material.dart';
import 'package:acs_check/utils/constants.dart';
import 'package:acs_check/widgets/bottom_navbar.dart';
import 'package:acs_check/widgets/big_text.dart';
import 'package:acs_check/widgets/small_text.dart';
import 'package:acs_check/services/auth_service.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:acs_check/services/location_service.dart';
import 'package:acs_check/models/location_model.dart';
import 'package:acs_check/services/job_schedule_service.dart';
import 'package:acs_check/widgets/custom_drawer.dart';

import 'dart:async';
import 'dart:developer' as developer;

import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter/services.dart';

class LocationDetailsPage extends StatefulWidget {
  const LocationDetailsPage({Key? key}) : super(key: key);

  @override
  State<LocationDetailsPage> createState() => _LocationDetailsPageState();
}

class _LocationDetailsPageState extends State<LocationDetailsPage> {
  final AuthService authService = AuthService();
  final LocationService locationService = LocationService();
  final JobScheduleService jobImageService = JobScheduleService();

  List<ConnectivityResult> _connectionStatus = [ConnectivityResult.none];
  final Connectivity _connectivity = Connectivity();
  late StreamSubscription<List<ConnectivityResult>> _connectivitySubscription;

  int _currentIndex = 0;

  int? userId;
  String? firstName;
  String? lastName;

  bool isLoading = false;
  bool showImages = false;

  List<Location> locationDetails = [];
  List<Map<String, dynamic>> images = [];

  Widget _buildLoading() {
    return CircularProgressIndicator();
  }

  @override
  void initState() {
    super.initState();

    initConnectivity();
    _connectivitySubscription =
        _connectivity.onConnectivityChanged.listen(_updateConnectionStatus);

    _loadUserData();
    _loadLocationDetails();
  }

  @override
  void dispose() {
    _connectivitySubscription.cancel();
    super.dispose();
  }

  Future<void> initConnectivity() async {
    late List<ConnectivityResult> result;
    try {
      result = await _connectivity.checkConnectivity();
    } on PlatformException catch (e) {
      developer.log('Couldn\'t check connectivity status', error: e);
      return;
    }

    if (!mounted) {
      return Future.value(null);
    }

    return _updateConnectionStatus(result);
  }

  Future<void> _updateConnectionStatus(List<ConnectivityResult> result) async {
    setState(() {
      _connectionStatus = result;
    });

    if (_connectionStatus.contains(ConnectivityResult.none)) {
      _showNoConnectionDialog();
    }
  }

  void _showNoConnectionDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: BigText(
              text: "ไม่สามารถเชื่อมต่ออินเตอร์เน็ต", size: Dimensions.font16),
          content: SmallText(
              text: "กรุณาตรวจสอบอินเตอร์เน็ตของคุณและทำรายการใหม่อีกครั้ง",
              size: Dimensions.font14),
          actions: <Widget>[
            TextButton(
              child: SmallText(text: "ลองอีกครั้ง", size: Dimensions.font14),
              onPressed: () {
                Navigator.of(context).pop();
                initConnectivity();
              },
            ),
          ],
        );
      },
    );
  }

  Future<void> _loadUserData() async {
    try {
      final storedUserId = await authService.getUserId();
      final storedFirstName = await authService.getFirstName();
      final storedLastName = await authService.getLastName();

      setState(() {
        userId = storedUserId;
        firstName = storedFirstName;
        lastName = storedLastName;
      });
    } catch (e) {
      print('Error loading user data: $e');
    }
  }

  Future<void> _loadLocationDetails() async {
    try {
      final arguments = Get.arguments as Map<String, dynamic>;
      final jobAuthorityId = arguments['jobAuthorityId'];
      final jobScheduleDate = arguments['jobScheduleDate'];
      final jobScheduleShiftId = arguments['jobScheduleShiftId'];

      final fetchedLocationDetails = await locationService.fetchLocationDetails(
          jobAuthorityId, jobScheduleDate, jobScheduleShiftId);

      setState(() {
        locationDetails = fetchedLocationDetails ?? [];
        isLoading = false;
      });
  
    } catch (e) {
      print('Error loading location details: $e');

      setState(() {
        isLoading = false;
      });
    }
  }

  Future<void> _fetchImagesAndShowDialog(int jobScheduleId) async {
    try {
      final fetchedImages = await jobImageService.fetchImagesJob(jobScheduleId);
      setState(() {
        images = List<Map<String, dynamic>>.from(fetchedImages ?? []);
      });
    } catch (e) {
      // Handle errors or show a message
      print('Error fetching images: $e');
    }
  }

  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          "ACS Check",
          style: TextStyle(color: AppColors.whiteColor),
        ),
        backgroundColor: AppColors.mainColor,
        iconTheme: IconThemeData(color: AppColors.whiteColor),
        actions: [
          IconButton(
            icon: Icon(Icons.arrow_back, color: AppColors.whiteColor),
            onPressed: () => Navigator.of(context).pop(),
          ),
        ],
      ),
      drawer: CustomDrawer(
        firstName: firstName,
        lastName: lastName,
        authService: authService,
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: EdgeInsets.all(16.0),
              itemCount: locationDetails.length,
              itemBuilder: (context, index) {
                final location = locationDetails[index];
                final jobStatusId = location.jobStatusId ?? 0;

                Color? statusColor;

                if (jobStatusId == 3) {
                  statusColor = AppColors.darkGreyColor;
                } else if (jobStatusId == 1) {
                  statusColor = AppColors.successColor;
                } else if (jobStatusId == 2) {
                  statusColor = AppColors.errorColor;
                } else {
                  statusColor = AppColors.greyColor;
                }
                return _buildLocationCard(location, statusColor);
              },
            ),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }

  Widget _buildLocationCard(Location location, Color statusColor) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: Dimensions.height10),
      padding: EdgeInsets.all(16.0),
      decoration: BoxDecoration(
        color: AppColors.whiteColor,
        borderRadius: BorderRadius.circular(8.0),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.3),
            blurRadius: 5.0,
            offset: Offset(0, 3),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildDetailRow(
            'พื้นที่:',
            location.zoneDescription,
            AppColors.greyColor,
          ),
          _buildDetailRow(
            'จุดตรวจ:',
            location.zoneDescription + '_' + location.locationDescription,
            AppColors.greyColor,
          ),
          _buildDetailRow(
            'สถานะ:',
            location.jobStatusDescription,
            statusColor,
          ),
          if (location.jobStatusId != 3) ...[
            _buildDetailRow(
              'ตรวจสอบเวลา:',
              location.inspectionCompletedAt,
              AppColors.greyColor,
            ),
          ],
          if (location.jobStatusId == 2) ...[
            SizedBox(height: Dimensions.height10),
            _buildDetailRow(
              'หัวข้อปัญหา:',
              location.issueDescription,
              AppColors.greyColor,
            ),
            SizedBox(height: Dimensions.height10),
            ElevatedButton(
              onPressed: () async {
                await _fetchImagesAndShowDialog(location.jobScheduleId);
                setState(() {
                  showImages = !showImages;
                });
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.mainColor,
                elevation: 3,
                padding: const EdgeInsets.all(16.0),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8.0),
                ),
              ),
              child: SmallText(
                text: showImages ? "ปิด" : "ดูรูปภาพปัญหา",
                size: Dimensions.font18,
                color: AppColors.whiteColor,
              ),
            ),
            SizedBox(height: Dimensions.height10),
          ],
          Visibility(
            visible: showImages,
            child: Column(
              children: [
                if (images.isNotEmpty)
                  ...images.map((image) {
                    String imagePath = image['image_path'];

                    imagePath = '${AppConstants.baseUrl}/storage/$imagePath';

                    return Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8.0),
                      child: Image.network(
                        imagePath,
                        loadingBuilder: (context, child, loadingProgress) {
                          if (loadingProgress == null) {
                            return child;
                          } else {
                            return Center(
                              child: CircularProgressIndicator(
                                value: loadingProgress.expectedTotalBytes !=
                                        null
                                    ? loadingProgress.cumulativeBytesLoaded /
                                        (loadingProgress.expectedTotalBytes ??
                                            1)
                                    : null,
                              ),
                            );
                          }
                        },
                        errorBuilder: (context, error, stackTrace) {
                          return Center(child: Text('ไม่สามารถโหลดรูปภาพได้'));
                        },
                      ),
                    );
                  }).toList()
                else
                  SmallText(
                    text: "ไม่มีรูปภาพ",
                    size: Dimensions.font16,
                    color: AppColors.greyColor,
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDetailRow(String title, String? value, Color? color) {
    return Padding(
      padding: EdgeInsets.only(bottom: Dimensions.height10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
            child: Text(
              title,
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: AppColors.blackColor,
                fontSize: Dimensions.font16,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value ?? 'ไม่พบข้อมูล',
              style: TextStyle(
                color: color,
                fontSize: Dimensions.font16,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
