import 'package:flutter/material.dart';
import 'package:acs_check/utils/constants.dart';
import 'package:acs_check/widgets/bottom_navbar.dart';
import 'package:acs_check/widgets/big_text.dart';
import 'package:acs_check/widgets/small_text.dart';
import 'package:acs_check/services/auth_service.dart';
import 'package:acs_check/models/job_schedule_model.dart';
import 'package:acs_check/services/job_schedule_service.dart';
import 'package:acs_check/services/work_shift_service.dart';
import 'package:acs_check/models/work_shift_model.dart';
import 'package:acs_check/services/zone_service.dart';
import 'package:acs_check/models/zone_model.dart';
import 'package:acs_check/services/job_status_service.dart';
import 'package:acs_check/models/job_status_model.dart';
import 'package:intl/intl.dart';
import 'package:acs_check/utils/app_constants.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:acs_check/widgets/custom_drawer.dart';

import 'dart:async';
import 'dart:developer' as developer;

import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter/services.dart';

class HistoryJobPage extends StatefulWidget {
  const HistoryJobPage({Key? key}) : super(key: key);

  @override
  State<HistoryJobPage> createState() => _HistoryJobPageState();
}

class _HistoryJobPageState extends State<HistoryJobPage> {
  final AuthService authService = AuthService();
  final JobScheduleService jobScheduleService = JobScheduleService();
  final WorkShiftService workShiftService = WorkShiftService();
  final JobStatusService jobStatusService = JobStatusService();
  final ZoneService zoneService = ZoneService();

  List<ConnectivityResult> _connectionStatus = [ConnectivityResult.none];
  final Connectivity _connectivity = Connectivity();
  late StreamSubscription<List<ConnectivityResult>> _connectivitySubscription;

  int _currentIndex = 1;

  int? userId;
  String? firstName;
  String? lastName;

  bool isLoading = false;
  bool showImages = false;
  bool searchPerformed = false;

  List<JobSchedule> jobSchedules = [];
  List<WorkShift> workShifts = [];
  List<JobStatus> jobStatuses = [];
  List<Zone> zones = [];

  Map<int, List<Map<String, dynamic>>> imagesMap = {};

  DateTime? _selectedDate;
  String? _selectedShift;
  String? _selectedStatus;
  String? _selectedZone;

  int _searchResultsCount = 0;

  @override
  void initState() {
    super.initState();

    initConnectivity();
    _connectivitySubscription =
        _connectivity.onConnectivityChanged.listen(_updateConnectionStatus);

    _loadUserData();
    _loadWorkShifts();
    _loadJobStatuses();
    _loadZones();
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
    final storedUserId = await authService.getUserId();
    final storedFirstName = await authService.getFirstName();
    final storedLastName = await authService.getLastName();

    setState(() {
      userId = storedUserId;
      firstName = storedFirstName;
      lastName = storedLastName;
    });
  }

  Future<void> _loadWorkShifts() async {
    final fetchedWorkShifts = await workShiftService.fetchWorkShifts();
    if (fetchedWorkShifts != null) {
      setState(() {
        workShifts = fetchedWorkShifts;
      });
    }
  }

  Future<void> _loadJobStatuses() async {
    final fetchedStatuses = await jobStatusService.fetchJobStatuses();
    if (fetchedStatuses != null) {
      setState(() {
        jobStatuses = fetchedStatuses;
      });
    }
  }

  Future<void> _loadZones() async {
    final fetchZones = await zoneService.fetchZones();

    if (fetchZones != null) {
      setState(() {
        zones = fetchZones;
      });
    }
  }

  void _searchJobSchedules() async {
    if (_connectionStatus.contains(ConnectivityResult.none)) {
      _showNoConnectionDialog();
      return;
    }

    if (_selectedDate == null) {
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text("ข้อผิดพลาด"),
            content: Text("กรุณาเลือกวันที่ตรวจสอบก่อนทำการค้นหา"),
            actions: [
              TextButton(
                child: const Text("ตกลง"),
                onPressed: () {
                  Navigator.of(context).pop();
                },
              ),
            ],
          );
        },
      );
      return;
    }

    setState(() {
      isLoading = true;
    });

    try {
      var formattedDate = DateFormat('yyyy-MM-dd').format(_selectedDate!);
      int? shiftId =
          _selectedShift != null ? int.tryParse(_selectedShift!) : null;
      int? statusId =
          _selectedStatus != null ? int.tryParse(_selectedStatus!) : null;

      int? zoneId = _selectedZone != null ? int.tryParse(_selectedZone!) : null;

      if (userId != null && _selectedDate != null) {
        var fetchedJobSchedulesHistory =
            await jobScheduleService.fetchJobSchedulesHistory(
          userId!,
          formattedDate,
          shiftId,
          statusId,
          zoneId,
        );

        if (fetchedJobSchedulesHistory != null &&
            fetchedJobSchedulesHistory.isNotEmpty) {
          setState(() {
            jobSchedules = fetchedJobSchedulesHistory;
            _searchResultsCount = jobSchedules.length;
            searchPerformed = true;
          });
        } else {
          setState(() {
            jobSchedules = [];
            _searchResultsCount = 0;
          });
        }
      }
    } catch (error) {
      print('Error fetching job schedules: $error');
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  Future<void> _fetchImagesAndShowDialog(int jobScheduleId) async {
    final fetchedImages =
        await jobScheduleService.fetchImagesJob(jobScheduleId);

    setState(() {
      imagesMap[jobScheduleId] =
          List<Map<String, dynamic>>.from(fetchedImages ?? []);
    });

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: BigText(text: "รูปภาพปัญหา", size: Dimensions.font24),
          content: SingleChildScrollView(
            child: Column(
              children: [
                if (imagesMap[jobScheduleId]!.isNotEmpty)
                  ...imagesMap[jobScheduleId]!.map((image) {
                    String imagePath = image['image_path'];

                    imagePath = '${AppConstants.baseUrl}/storage/$imagePath';

                    return Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8.0),
                      child: CachedNetworkImage(
                        imageUrl: imagePath,
                        placeholder: (context, url) =>
                            CircularProgressIndicator(),
                        errorWidget: (context, url, error) {
                          return Icon(Icons.error);
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
          actions: [
            TextButton(
              child: SmallText(text: "ปิด", color: AppColors.mainColor),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
          ],
        );
      },
    );
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
          iconTheme: IconThemeData(color: AppColors.whiteColor)),
      drawer: CustomDrawer(
        firstName: firstName,
        lastName: lastName,
        authService: authService,
      ),
      body: Stack(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              children: [
                _buildSearchFilters(context),
                SizedBox(height: Dimensions.height15),
                if (searchPerformed)
                  BigText(
                    text: "ค้นหาได้: $_searchResultsCount รายการ",
                    color: AppColors.darkGreyColor,
                    size: Dimensions.font16,
                  ),
                SizedBox(height: Dimensions.height15),
                Expanded(
                    child: isLoading
                        ? Center(child: CircularProgressIndicator())
                        : jobSchedules.isEmpty
                            ? Center(
                                child: BigText(
                                    text: "ไม่พบข้อมูล",
                                    size: Dimensions.font20,
                                    color: AppColors.greyColor))
                            : _buildJobSchedulesList()),
              ],
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

  Widget _buildSearchFilters(BuildContext context) {
    return Column(
      children: [
        Row(
          children: [
            Expanded(
              child: TextFormField(
                decoration: const InputDecoration(
                  labelText: "เลือกวันที่ตรวจสอบ",
                  border: OutlineInputBorder(),
                ),
                readOnly: true,
                onTap: () async {
                  DateTime? pickedDate = await showDatePicker(
                    context: context,
                    initialDate: DateTime.now(),
                    firstDate: DateTime(2000),
                    lastDate: DateTime(2101),
                    locale: const Locale('th', 'TH'),
                  );

                  setState(() {
                    _selectedDate = pickedDate;
                  });
                                },
                controller: TextEditingController(
                  text: _selectedDate == null
                      ? ''
                      : '${_selectedDate!.day}/${_selectedDate!.month}/${_selectedDate!.year}',
                ),
              ),
            ),
            SizedBox(width: Dimensions.width15),
            Expanded(
              child: DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: "เลือกช่วงเวลา",
                  border: OutlineInputBorder(),
                ),
                items: [
                  DropdownMenuItem<String>(
                    value: '',
                    child: Text('ทุกช่วงเวลา'),
                  ),
                  ...workShifts.map((WorkShift shift) {
                    return DropdownMenuItem<String>(
                      value: shift.workShiftId.toString(),
                      child: Text(shift.shiftTimeSlot),
                    );
                  }).toList(),
                ],
                onChanged: (newValue) {
                  setState(() {
                    _selectedShift = newValue ?? '';
                  });
                },
                value: _selectedShift?.isEmpty ?? true ? '' : _selectedShift,
              ),
            ),
          ],
        ),
        SizedBox(height: Dimensions.height15),
        Row(
          children: [
            Expanded(
              child: DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: "เลือกสถานะ",
                  border: OutlineInputBorder(),
                ),
                items: [
                  DropdownMenuItem<String>(
                    value: '',
                    child: Text('สถานะทั้งหมด'),
                  ),
                  ...jobStatuses.map((jobStatus) {
                    return DropdownMenuItem<String>(
                      value: jobStatus.jobStatusId.toString(),
                      child: Text(jobStatus.jobStatusDescription),
                    );
                  }).toList(),
                ],
                onChanged: (newValue) {
                  setState(() {
                    _selectedStatus = newValue ?? '';
                  });
                },
                value: _selectedStatus?.isEmpty ?? true ? '' : _selectedStatus,
              ),
            ),
            SizedBox(width: Dimensions.width15),
            Expanded(
              child: DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: "เลือกพื้นที่",
                  border: OutlineInputBorder(),
                ),
                items: [
                  DropdownMenuItem<String>(
                    value: '',
                    child: Text('พื้นที่ทั้งหมด'),
                  ),
                  ...zones.map((zone) {
                    return DropdownMenuItem<String>(
                      value: zone.zoneId.toString(),
                      child: Text(zone.zoneDescription),
                    );
                  }).toList(),
                ],
                onChanged: (newValue) {
                  setState(() {
                    _selectedZone = newValue ?? '';
                  });
                },
                value: _selectedZone?.isEmpty ?? true ? '' : _selectedZone,
              ),
            ),
          ],
        ),
        SizedBox(height: Dimensions.height15),
        ElevatedButton(
          onPressed: _searchJobSchedules,
          style: ElevatedButton.styleFrom(
            backgroundColor: AppColors.mainColor,
            elevation: 3,
            padding: const EdgeInsets.all(16.0),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8.0),
            ),
          ),
          child: SmallText(text: "ค้นหา", color: AppColors.whiteColor),
        ),
        SizedBox(height: Dimensions.height15),
      ],
    );
  }

  Widget _buildJobSchedulesList() {
    if (isLoading) {
      return Center(child: CircularProgressIndicator());
    } else if (jobSchedules.isEmpty) {
      return Center(
        child: BigText(
          text: "ไม่พบข้อมูล",
          size: Dimensions.font20,
          color: AppColors.greyColor,
        ),
      );
    } else {
      return ListView.builder(
        itemCount: jobSchedules.length,
        itemBuilder: (context, index) {
          final jobSchedule = jobSchedules[index];
          Color? statusColor;

          if (jobSchedule.jobScheduleStatusId == 3) {
            statusColor = AppColors.darkGreyColor;
          } else if (jobSchedule.jobScheduleStatusId == 1) {
            statusColor = AppColors.successColor;
          } else {
            statusColor = AppColors.errorColor;
          }

          return _buildJobScheduleItem(jobSchedule, statusColor);
        },
      );
    }
  }

  Widget _buildJobScheduleItem(JobSchedule jobSchedule, Color statusColor) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8),
      elevation: 5,
      child: ListTile(
        title: BigText(
          text:
              "จุดตรวจ: ${jobSchedule.zoneDescription}_${jobSchedule.locationDescription}",
          size: Dimensions.font18,
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SmallText(
              text: "พื้นที่: ${jobSchedule.zoneDescription}",
              color: AppColors.greyColor,
              size: Dimensions.font14,
            ),
            SmallText(
              text: "ช่วงเวลา: ${jobSchedule.shiftTimeSlot}",
              color: AppColors.greyColor,
              size: Dimensions.font14,
            ),
            SmallText(
              text: "สถานะ: ${jobSchedule.jobStatusDescription}",
              color: statusColor,
              size: Dimensions.font14,
            ),
            if(jobSchedule.jobScheduleStatusId == 2)
              SmallText(
                text: "หัวข้อปัญหา: ${jobSchedule.issueDescription}",
                color: statusColor,
                size: Dimensions.font14,
              ),
            SizedBox(height: Dimensions.height5),
            if (jobSchedule.jobScheduleStatusId != 3)
              SmallText(
                  text: "ตรวจสอบเวลา: ${jobSchedule.inspectionCompletedAt}",
                  color: AppColors.greyColor,
                  size: Dimensions.font14)
          ],
        ),
        trailing: jobSchedule.jobScheduleStatusId == 2
            ? IconButton(
                icon: Icon(Icons.image),
                onPressed: () async {
                  await _fetchImagesAndShowDialog(jobSchedule.jobScheduleId);
                },
              )
            : null,
      ),
    );
  }
}
