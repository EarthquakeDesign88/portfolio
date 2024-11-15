import 'package:flutter/material.dart';
import 'package:acs_check/utils/constants.dart';
import 'package:acs_check/widgets/bottom_navbar.dart';
import 'package:acs_check/widgets/big_text.dart';
import 'package:acs_check/widgets/small_text.dart';
import 'package:acs_check/services/auth_service.dart';
import 'package:get/get.dart';
import 'package:acs_check/pages/job_schedule_page.dart';
import 'package:acs_check/models/work_shift_model.dart';
import 'package:acs_check/services/work_shift_service.dart';
import 'package:acs_check/services/job_schedule_service.dart';
import 'package:intl/intl.dart';
import 'package:acs_check/widgets/custom_drawer.dart';

import 'dart:async';
import 'dart:developer' as developer;

import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter/services.dart';

class WorkShiftPage extends StatefulWidget {
  const WorkShiftPage({Key? key}) : super(key: key);

  @override
  _WorkShiftPageState createState() => _WorkShiftPageState();
}

class _WorkShiftPageState extends State<WorkShiftPage> {
  final AuthService authService = AuthService();
  final WorkShiftService workShiftService = WorkShiftService();
  final JobScheduleService jobScheduleService = JobScheduleService();

  List<ConnectivityResult> _connectionStatus = [ConnectivityResult.none];
  final Connectivity _connectivity = Connectivity();
  late StreamSubscription<List<ConnectivityResult>> _connectivitySubscription;

  int _currentIndex = 0;

  int? userId;
  String? firstName;
  String? lastName;

  bool isLoading = true;
  int countCompletedSchedules = 0;
  int completedSchedules = 0;

  List<WorkShift>? workShifts;
  String? jobScheduleDate;

  @override
  void initState() {
    super.initState();
    initConnectivity();
    _connectivitySubscription =
        _connectivity.onConnectivityChanged.listen(_updateConnectionStatus);

    _loadUserData();
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

      // print('Connection Failed');
    }

    // print('Connectivity changed: $_connectionStatus');
  }

  void _showNoConnectionDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: BigText(text: "ไม่สามารถเชื่อมต่ออินเตอร์เน็ต", size: Dimensions.font16),
          content: SmallText(text: "กรุณาตรวจสอบอินเตอร์เน็ตของคุณและทำรายการใหม่อีกครั้ง", size: Dimensions.font14),
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

  void _loadUserData() async {
    final storedUserId = await authService.getUserId();
    final storedFirstName = await authService.getFirstName();
    final storedLastName = await authService.getLastName();

    setState(() {
      userId = storedUserId;
      firstName = storedFirstName;
      lastName = storedLastName;
    });

    _fetchWorkShifts();
  }

  void _fetchWorkShifts() async {
    if (userId != null) {
      final currentDate = DateTime.now();
      final formattedDate = currentDate.toIso8601String().split('T')[0];
      final shifts =
          await workShiftService.fetchWorkShiftsByUser(userId!, formattedDate);

      setState(() {
        workShifts = shifts;
        jobScheduleDate = formattedDate;
        isLoading = false;
      });

      final completedData = await jobScheduleService.fetchCompletedSchedules(
          userId!, formattedDate);

      if (completedData != null) {
        for (var item in completedData) {
          int totalJobSchedules =
              int.parse(item['total_job_schedules'].toString());
          int completedJobSchedules =
              int.parse(item['completed_job_schedules'].toString());

          if (totalJobSchedules == completedJobSchedules) {
            countCompletedSchedules++;
          }
        }

        setState(() {
          completedSchedules = countCompletedSchedules;
        });
      }
    }
  }

  void _navigateToJobSchedulePage(BuildContext context, int shiftId) {
    Get.to(
      () => JobSchedulePage(),
      arguments: {
        'userId': userId,
        'jobScheduleDate': jobScheduleDate,
        'jobScheduleShiftId': shiftId,
      },
      preventDuplicates: false,
    );
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
      ),
      drawer: CustomDrawer(
        firstName: firstName,
        lastName: lastName,
        authService: authService,
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : Column(
              children: [
                if (workShifts != null && workShifts!.isNotEmpty) ...[
                  SizedBox(height: Dimensions.height20),
                  BigText(text: "ตารางงานวันนี้", size: Dimensions.font30),
                  SizedBox(height: Dimensions.height20),
                  BigText(
                      text: "มีทั้งหมด ${workShifts?.length ?? 0} รอบ",
                      size: Dimensions.font24),
                  SizedBox(height: Dimensions.height20),
                  isLoading
                      ? CircularProgressIndicator()
                      : completedSchedules == workShifts?.length
                          ? Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(
                                  Icons.check_circle,
                                  color: AppColors.successColor,
                                  size: Dimensions.font22,
                                ),
                                SizedBox(width: 8),
                                BigText(
                                  text: "ตรวจครบแล้ว",
                                  size: Dimensions.font22,
                                  color: AppColors.successColor,
                                ),
                              ],
                            )
                          : Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                SmallText(
                                    text:
                                        "ตรวจไปแล้ว ($completedSchedules/${workShifts?.length ?? 0})",
                                    size: Dimensions.font20),
                              ],
                            ),
                  SizedBox(height: Dimensions.height20),
                  Expanded(
                    child: ListView.builder(
                      itemCount: workShifts?.length ?? 0,
                      itemBuilder: (context, index) {
                        final workShift = workShifts![index];
                        return _buildTimeSlotTile(context,
                            workShift.shiftTimeSlot, workShift.workShiftId);
                      },
                    ),
                  ),
                ],
                if (workShifts == null || workShifts!.isEmpty)
                  Center(
                    child: Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.0),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Image.asset(
                            'assets/images/no_schedule.png',
                            height: 200.0,
                            width: 200.0,
                          ),
                          SizedBox(height: Dimensions.height20),
                          BigText(
                            text: "ไม่มีตารางงานวันนี้",
                            size: Dimensions.font30,
                          ),
                          SizedBox(height: Dimensions.height10),
                          SmallText(
                            text: "คุณไม่มีตารางงานสำหรับวันนี้",
                            size: Dimensions.font20,
                          ),
                          SizedBox(height: Dimensions.height10),
                          SmallText(
                            text: "โปรดตรวจสอบอีกครั้งภายหลัง",
                            size: Dimensions.font20,
                          ),
                        ],
                      ),
                    ),
                  ),
              ],
            ),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }

  Widget _buildTimeSlotTile(
      BuildContext context, String timeSlot, int shiftId) {
    final timeRange = timeSlot.split('-');
    final startTime = timeRange[0].trim();
    final endTime = timeRange[1].trim();

    final currentDate = DateTime.now();
    final DateFormat formatter = DateFormat('HH:mm');
    final startDateTime = DateFormat('HH:mm').parse(startTime);
    final endDateTime = DateFormat('HH:mm').parse(endTime);

    final bool isCurrentTimeInRange =
        formatter.format(currentDate).compareTo(startTime) >= 0 &&
            formatter.format(currentDate).compareTo(endTime) <= 0;

    return Container(
        margin: EdgeInsets.symmetric(vertical: 8.0, horizontal: 16.0),
        decoration: BoxDecoration(
          color: isCurrentTimeInRange
              ? AppColors.mainColor
              : AppColors.disableColor,
          borderRadius: BorderRadius.circular(8.0),
        ),
        child: ListTile(
            contentPadding: EdgeInsets.all(16.0),
            title: Center(
              child: Text(
                timeSlot,
                style:
                    TextStyle(fontSize: Dimensions.font18, color: Colors.white),
              ),
            ),
            onTap: isCurrentTimeInRange
                ? () => _navigateToJobSchedulePage(context, shiftId)
                : null));
  }
}
