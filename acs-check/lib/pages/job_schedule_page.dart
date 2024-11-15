import 'dart:io';
import 'package:acs_check/pages/location_details_page.dart';
import 'package:flutter/material.dart';
import 'package:acs_check/utils/constants.dart';
import 'package:acs_check/widgets/bottom_navbar.dart';
import 'package:acs_check/widgets/big_text.dart';
import 'package:acs_check/widgets/small_text.dart';
import 'package:acs_check/widgets/custom_drawer.dart';
import 'package:acs_check/widgets/qr_scanner.dart';
import 'package:acs_check/services/auth_service.dart';
import 'package:get/get.dart';
import 'package:acs_check/models/job_schedule_model.dart';
import 'package:acs_check/services/job_schedule_service.dart';
import 'package:image_picker/image_picker.dart';
import 'package:acs_check/models/location_model.dart';
import 'package:acs_check/services/location_service.dart';
import 'package:acs_check/models/job_status_model.dart';
import 'package:acs_check/services/job_status_service.dart';
import 'package:acs_check/services/zone_service.dart';
import 'package:acs_check/models/zone_model.dart';
import 'package:acs_check/services/issue_topic_service.dart';
import 'package:acs_check/models/issue_topic_model.dart';

import 'dart:async';
import 'dart:developer' as developer;

import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter/services.dart';

class JobSchedulePage extends StatefulWidget {
  const JobSchedulePage({Key? key}) : super(key: key);

  @override
  _JobSchedulePageState createState() => _JobSchedulePageState();
}

class _JobSchedulePageState extends State<JobSchedulePage> {
  final AuthService authService = AuthService();
  final JobScheduleService jobScheduleService = JobScheduleService();
  final LocationService locationService = LocationService();
  final JobStatusService jobStatusService = JobStatusService();
  final ImagePicker _picker = ImagePicker();
  final ZoneService zoneService = ZoneService();
  final IssueTopicService issueTopicService = IssueTopicService();

  List<ConnectivityResult> _connectionStatus = [ConnectivityResult.none];
  final Connectivity _connectivity = Connectivity();
  late StreamSubscription<List<ConnectivityResult>> _connectivitySubscription;

  int _currentIndex = 0;

  String scannedCode = '';
  String zoneDescription = '';
  String locationDescription = '';
  String locationQR = '';

  int? userId;
  String? firstName;
  String? lastName;

  bool isLoading = false;
  bool isJobSchedulesLoading = false;
  bool isSubmitting = false;
  bool isZoneLoading = false;
  bool isIssueTopicLoading = false;

  List<JobSchedule> jobSchedules = [];
  List<JobStatus> jobStatuses = [];
  List<Zone> zones = [];
  List<IssueTopic> issueTopics = [];

  int totalCheckpoint = 0;
  int countCheckedPoints = 0;

  int? selectedJobStatusId;
  int selectedZoneId = 0;
  int selectedIssueId = 0;
  String selectedZoneDescription = "พื้นที่ทั้งหมด";

  List<XFile>? _images = [];

  @override
  void initState() {
    super.initState();

    initConnectivity();
    _connectivitySubscription =
        _connectivity.onConnectivityChanged.listen(_updateConnectionStatus);

    _loadUserData();
    _loadJobSchedules();
    _loadJobStatuses();
    _loadZones();
    _loadIssueTopics();
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

  void _loadUserData() async {
    final storedFirstName = await authService.getFirstName();
    final storedLastName = await authService.getLastName();

    setState(() {
      firstName = storedFirstName;
      lastName = storedLastName;
    });
  }

  Future<void> _loadZones() async {
    final arguments = Get.arguments as Map<String, dynamic>;
    final int userId = arguments['userId'];
    final String jobScheduleDate = arguments['jobScheduleDate'];
    final int jobScheduleShiftId = arguments['jobScheduleShiftId'];

    setState(() {
      isZoneLoading = true;
    });

    final fetchZones = await zoneService.fetchZonesByUser(
        jobScheduleDate, userId, jobScheduleShiftId);

    if (fetchZones != null) {
      setState(() {
        zones = fetchZones;
        isZoneLoading = false;
      });
    }
  }

  Future<void> _loadJobStatuses() async {
    final fetchedStatuses = await jobStatusService.fetchJobStatuses();
    if (fetchedStatuses != null) {
      setState(() {
        jobStatuses = fetchedStatuses
            .where(
                (status) => status.jobStatusId == 1 || status.jobStatusId == 2)
            .toList();
      });
    }
  }

  Future<void> _loadIssueTopics() async {
    setState(() {
      isIssueTopicLoading = true;
    });

    final fetchedIssueTopics = await issueTopicService.fetchIssueTopics();
    if (fetchedIssueTopics != null) {
      setState(() {
        issueTopics = fetchedIssueTopics;
        isIssueTopicLoading = false;
      });
    }
  }

  Future<void> _loadJobSchedules() async {
    setState(() {
      isJobSchedulesLoading = true;
    });

    final arguments = Get.arguments as Map<String, dynamic>;
    final int userId = arguments['userId'];
    final String jobScheduleDate = arguments['jobScheduleDate'];
    final int jobScheduleShiftId = arguments['jobScheduleShiftId'];

    final fetchedSchedules = await jobScheduleService.fetchJobSchedule(
        userId, jobScheduleDate, jobScheduleShiftId, selectedZoneId);

    final fetchedCountCheckedPoints =
        await jobScheduleService.countCheckedPoints(
            userId, jobScheduleDate, jobScheduleShiftId, selectedZoneId);

    setState(() {
      jobSchedules = fetchedSchedules ?? [];
      totalCheckpoint = jobSchedules.length;
      countCheckedPoints = fetchedCountCheckedPoints ?? 0;
      isJobSchedulesLoading = false;
    });
  }

  Future<void> _pickImages() async {
    // Allow user to pick up to 3 images
    final List<XFile>? pickedImages =
        await _picker.pickMultiImage(imageQuality: 100);

    if (pickedImages != null && pickedImages.isNotEmpty) {
      setState(() {
        if ((_images?.length ?? 0) + pickedImages.length > 3) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('ไม่สามารถอัปโหลดรูปภาพได้เกิน 3 รูป'),
              backgroundColor: AppColors.errorColor,
            ),
          );
          _images = (_images ?? []).sublist(0, 3);
        } else {
          _images = (_images ?? []) + pickedImages;
        }
      });
    }
  }

  Future<void> _takePicture() async {
    if ((_images?.length ?? 0) >= 3) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('ไม่สามารถถ่ายรูปได้เกิน 3 รูป'),
          backgroundColor: AppColors.errorColor,
        ),
      );
      return;
    }

    final image =
        await _picker.pickImage(source: ImageSource.camera, imageQuality: 100);
    if (image != null) {
      setState(() {
        _images?.add(image);
      });
    }
  }

  void _removeImage(int index) {
    setState(() {
      _images!.removeAt(index);
    });
  }

  void _showFullImage(BuildContext context, XFile imageFile) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        child: Stack(
          children: [
            Image.file(File(imageFile.path), fit: BoxFit.contain),
            Positioned(
              top: 0,
              right: 0,
              child: IconButton(
                icon: const Icon(Icons.close, color: AppColors.greyColor),
                onPressed: () => Navigator.of(context).pop(),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildImagePreview(XFile imageFile, int index) {
    return GestureDetector(
      onTap: () => _showFullImage(context, imageFile),
      child: Stack(
        children: [
          Container(
            margin: const EdgeInsets.all(8.0),
            width: 100,
            height: 100,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(8.0),
              image: DecorationImage(
                image: FileImage(File(imageFile.path)),
                fit: BoxFit.cover,
              ),
            ),
          ),
          Positioned(
            top: 0,
            right: 0,
            child: IconButton(
              icon: Container(
                decoration: const BoxDecoration(
                  color: AppColors.errorColor,
                  shape: BoxShape.circle,
                ),
                padding: EdgeInsets.all(4),
                child: const Icon(
                  Icons.close,
                  color: AppColors.whiteColor,
                  size: 20,
                ),
              ),
              onPressed: () {
                _removeImage(index);
              },
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _confirmInspectionResult() async {
    final arguments = Get.arguments as Map<String, dynamic>;
    final int userId = arguments['userId'];
    final String jobScheduleDate = arguments['jobScheduleDate'];
    final int jobScheduleShiftId = arguments['jobScheduleShiftId'];

    if (_connectionStatus.contains(ConnectivityResult.none)) {
      _showNoConnectionDialog();
      return;
    }

    if (selectedJobStatusId == 2 && selectedIssueId == 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('กรุณาเลือกหัวข้อปัญหา'),
          backgroundColor: AppColors.errorColor,
        ),
      );
      setState(() {
        isSubmitting = false;
      });
      return;
    }

    if (selectedJobStatusId == 2 && (_images == null || _images!.isEmpty)) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('กรุณาถ่ายหรืออัพโหลดรูปภาพปัญหา'),
          backgroundColor: AppColors.errorColor,
        ),
      );
      setState(() {
        isSubmitting = false;
      });
      return;
    }

    if (_images != null && _images!.length > 3) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('ไม่สามารถอัปโหลดรูปภาพได้เกิน 3 รูป'),
          backgroundColor: AppColors.errorColor,
        ),
      );
      setState(() {
        isSubmitting = false;
      });
      return;
    }

    try {
      final response = await jobScheduleService.saveInspectionResult(
          userId: userId,
          jobScheduleDate: jobScheduleDate,
          jobScheduleShiftId: jobScheduleShiftId,
          jobScheduleStatusId: selectedJobStatusId!,
          locationQR: scannedCode,
          inspectionCompletedAt: DateTime.now(),
          images: _images ?? [],
          issueTopicId: selectedIssueId);

      if (response['success']) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
              content: Text(response['message']),
              backgroundColor: AppColors.successColor),
        );

        setState(() {
          scannedCode = '';
          selectedJobStatusId = null;
          _images = [];
        });

        _loadJobSchedules();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
              content: Text(response['message']),
              backgroundColor: AppColors.errorColor),
        );
        setState(() {
          isSubmitting = false;
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(
          content: Text('พบข้อผิดพลาด โปรดลองใหม่อีกครั้งภายหลัง'),
          backgroundColor: AppColors.errorColor));
      setState(() {
        isSubmitting = false;
      });
    } finally {
      setState(() {
        isSubmitting = false;
      });
    }
  }

  Future<void> _loadLocationDescription(String scannedCode) async {
    setState(() {
      isLoading = true;
    });

    try {
      final location = await locationService.checkLocation(scannedCode);

      if (location != null) {
        setState(() {
          locationDescription = location.locationDescription;
          zoneDescription = location.zoneDescription;
          locationQR = location.locationQR;
        });
      } else {
        setState(() {
          locationDescription = 'ไม่พบข้อมูล';
          zoneDescription = 'ไม่พบข้อมูล';
          locationQR = 'ไม่พบข้อมูล';
        });
      }
    } catch (e) {
      setState(() {
        locationDescription = 'ไม่พบข้อมูล';
        zoneDescription = 'ไม่พบข้อมูล';
        locationQR = 'ไม่พบข้อมูล';
      });
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  void _navigateToLocationDetailsPage(BuildContext context, int jobAuthorityId,
      String jobScheduleDate, int jobScheduleShiftId) {
    Get.to(
      () => LocationDetailsPage(),
      arguments: {
        'jobAuthorityId': jobAuthorityId,
        'jobScheduleDate': jobScheduleDate,
        'jobScheduleShiftId': jobScheduleShiftId,
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
      body: isJobSchedulesLoading
          ? Center(child: CircularProgressIndicator())
          : CustomScrollView(
              slivers: [
                SliverToBoxAdapter(
                  child: Column(
                    children: [
                      SizedBox(height: Dimensions.height20),
                      Column(
                        children: [
                          BigText(
                            text: jobSchedules[0].workShiftDescription,
                            size: Dimensions.font34,
                          ),
                          SizedBox(height: Dimensions.height20),
                          SmallText(
                            text: "ช่วงเวลาตั้งแต่ " +
                                jobSchedules[0].shiftTimeSlot,
                            size: Dimensions.font28,
                          ),
                        ],
                      ),
                      SizedBox(height: Dimensions.height20),
                      Visibility(
                        visible: countCheckedPoints != totalCheckpoint,
                        child: ElevatedButton(
                          onPressed: () async {
                            var result = await Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => const QRScanner(),
                                ));

                            if (result is String && result.isNotEmpty) {
                              setState(() {
                                scannedCode = result;
                              });

                              await _loadLocationDescription(scannedCode);
                            }
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
                              text: scannedCode.isEmpty
                                  ? "สแกนจุดตรวจ"
                                  : "สแกนใหม่",
                              color: AppColors.whiteColor),
                        ),
                      ),
                      SizedBox(height: Dimensions.height20),
                      Visibility(
                        visible: scannedCode.isNotEmpty,
                        child: Column(
                          children: [
                            SmallText(
                              text: locationDescription.isNotEmpty
                                  ? "จุดตรวจ: ${zoneDescription}_${locationDescription}"
                                  : 'ไม่พบข้อมูล',
                              size: Dimensions.font20,
                              color: AppColors.mainColor,
                            ),
                            SizedBox(height: Dimensions.height15),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: jobStatuses.map((jobStatus) {
                                bool isActive = selectedJobStatusId ==
                                    jobStatus.jobStatusId;
                                bool isNoProblem =
                                    jobStatus.jobStatusDescription ==
                                        'ไม่พบปัญหา';
                                return Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 8.0),
                                  child: ElevatedButton(
                                    onPressed: () {
                                      setState(() {
                                        selectedJobStatusId =
                                            jobStatus.jobStatusId;
                                        if (selectedJobStatusId == 1) {
                                          _images = [];
                                        }
                                      });
                                    },
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: isActive
                                          ? (isNoProblem
                                              ? AppColors.successColor
                                              : AppColors.errorColor)
                                          : AppColors.greyColor,
                                      elevation: 3,
                                      padding: const EdgeInsets.all(16.0),
                                      shape: RoundedRectangleBorder(
                                        borderRadius:
                                            BorderRadius.circular(8.0),
                                      ),
                                    ),
                                    child: SmallText(
                                        text: jobStatus.jobStatusDescription,
                                        size: Dimensions.font20,
                                        color: AppColors.whiteColor),
                                  ),
                                );
                              }).toList(),
                            ),
                            SizedBox(height: Dimensions.height10),
                            Visibility(
                              visible: selectedJobStatusId == 2,
                              child: Column(
                                children: [
                                  Padding(
                                    padding: EdgeInsets.symmetric(
                                        vertical: Dimensions.height10),
                                    child: Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.center,
                                      children: [
                                        ElevatedButton(
                                          onPressed: _takePicture,
                                          child: Text("ถ่ายรูป"),
                                        ),
                                        SizedBox(width: Dimensions.width10),
                                        ElevatedButton(
                                          onPressed: _pickImages,
                                          child: Text("อัปโหลดรูปภาพ"),
                                        ),
                                      ],
                                    ),
                                  ),
                                  Padding(
                                    padding: EdgeInsets.symmetric(
                                        horizontal: Dimensions.width20,
                                        vertical: Dimensions.height15),
                                    child: DropdownButtonFormField<int>(
                                      decoration: const InputDecoration(
                                        labelText: "เลือกหัวข้อปัญหา",
                                        border: OutlineInputBorder(),
                                      ),
                                      items: issueTopics.map((issueTopic) {
                                        return DropdownMenuItem<int>(
                                          value: issueTopic.issueId,
                                          child:
                                              Text(issueTopic.issueDescription),
                                        );
                                      }).toList(),
                                      onChanged: (int? newValue) {
                                        setState(() {
                                          selectedIssueId = newValue ?? 0;
                                        });
                                      },
                                      value: selectedIssueId == 0
                                          ? null
                                          : selectedIssueId,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            Visibility(
                                visible: _images!.isNotEmpty,
                                child: Column(
                                  children: [
                                    SizedBox(height: Dimensions.height10),
                                    Padding(
                                      padding: EdgeInsets.symmetric(
                                          horizontal: Dimensions.width20),
                                      child: SizedBox(
                                        height: 100,
                                        child: GridView.builder(
                                          gridDelegate:
                                              const SliverGridDelegateWithFixedCrossAxisCount(
                                            crossAxisCount: 3,
                                            crossAxisSpacing: 10.0,
                                            mainAxisSpacing: 10.0,
                                          ),
                                          itemCount: _images!.length,
                                          itemBuilder: (context, index) {
                                            return _buildImagePreview(
                                                _images![index], index);
                                          },
                                          shrinkWrap: true,
                                          physics:
                                              const NeverScrollableScrollPhysics(),
                                        ),
                                      ),
                                    ),
                                  ],
                                )),
                            SizedBox(height: Dimensions.height20),
                            Visibility(
                              visible: selectedJobStatusId != null,
                              child: isSubmitting
                                  ? const CircularProgressIndicator()
                                  : ElevatedButton(
                                      onPressed: isSubmitting
                                          ? null
                                          : () async {
                                              if (isSubmitting) return;

                                              setState(() {
                                                isSubmitting = true;
                                              });

                                              await _confirmInspectionResult();
                                            },
                                      style: ElevatedButton.styleFrom(
                                          backgroundColor: AppColors.mainColor,
                                          elevation: 3,
                                          padding: const EdgeInsets.all(16.0),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(8.0),
                                          )),
                                      child: SmallText(
                                        text: "ยืนยันการตรวจสอบ",
                                        size: Dimensions.font20,
                                        color: AppColors.whiteColor,
                                      ),
                                    ),
                            ),
                          ],
                        ),
                      ),
                      SizedBox(height: Dimensions.height10),
                      isJobSchedulesLoading
                          ? CircularProgressIndicator()
                          : jobSchedules.isNotEmpty
                              ? countCheckedPoints == totalCheckpoint
                                  ? Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.center,
                                      children: [
                                        Icon(
                                          Icons.check_circle,
                                          color: AppColors.successColor,
                                          size: Dimensions.font26,
                                        ),
                                        SizedBox(width: Dimensions.width10),
                                        BigText(
                                          text: "ตรวจครบแล้ว",
                                          size: Dimensions.font26,
                                          color: AppColors.successColor,
                                        ),
                                      ],
                                    )
                                  : Column(
                                      children: [
                                        BigText(
                                          text: selectedZoneDescription ==
                                                  "พื้นที่ทั้งหมด"
                                              ? selectedZoneDescription
                                              : "พื้นที่ ${selectedZoneDescription}",
                                          size: Dimensions.font24,
                                        ),
                                        SizedBox(height: Dimensions.height10),
                                        Row(
                                            mainAxisAlignment:
                                                MainAxisAlignment.center,
                                            children: [
                                              Icon(
                                                Icons.check_circle,
                                                color: AppColors.successColor,
                                                size: Dimensions.font22,
                                              ),
                                              SizedBox(
                                                  width: Dimensions.width10),
                                              SmallText(
                                                text:
                                                    "ตรวจไปแล้ว (${countCheckedPoints}/${totalCheckpoint})",
                                                size: Dimensions.font20,
                                              ),
                                            ]),
                                      ],
                                    )
                              : Center(
                                  child: BigText(
                                      text: "ไม่พบข้อมูล",
                                      size: Dimensions.font20,
                                      color: AppColors.greyColor)),
                      SizedBox(height: Dimensions.height10),
                      PopupMenuButton<int>(
                        onSelected: (newValue) {
                          setState(() {
                            selectedZoneId = newValue;
                            if (newValue == 0) {
                              selectedZoneDescription = "พื้นที่ทั้งหมด";
                            } else {
                              final selectedZone = zones.firstWhere(
                                  (zone) => zone.zoneId == newValue);
                              selectedZoneDescription =
                                  selectedZone.zoneDescription;
                            }
                            _loadJobSchedules();
                          });
                        },
                        itemBuilder: (context) {
                          return [
                            PopupMenuItem<int>(
                              value: 0,
                              child: Text('พื้นที่ทั้งหมด',
                                  style: TextStyle(
                                    color: selectedZoneId == 0
                                        ? AppColors.mainColor
                                        : Colors.black,
                                  )),
                            ),
                            ...zones.map((zone) {
                              return PopupMenuItem<int>(
                                value: zone.zoneId,
                                child: Text(zone.zoneDescription,
                                    style: TextStyle(
                                      color: selectedZoneId == zone.zoneId
                                          ? AppColors.mainColor
                                          : Colors.black,
                                    )),
                              );
                            }).toList(),
                          ];
                        },
                        icon: Icon(Icons.filter_list),
                      ),
                      SizedBox(height: Dimensions.height10),
                    ],
                  ),
                ),
                if (jobSchedules.isNotEmpty && totalCheckpoint > 0)
                  SliverPadding(
                    padding: const EdgeInsets.symmetric(horizontal: 5.0),
                    sliver: SliverGrid(
                      delegate: SliverChildBuilderDelegate(
                        (context, index) {
                          JobSchedule jobSchedule = jobSchedules[index];
                          Color checkpointColor;
                          Color hoverColor;
                          Color? statusColor;

                          if (jobSchedule.jobScheduleStatusId == 3) {
                            checkpointColor =
                                AppColors.mainColor.withOpacity(0.1);
                            hoverColor = AppColors.mainColor.withOpacity(0.2);
                            statusColor = AppColors.darkGreyColor;
                          } else if (jobSchedule.jobScheduleStatusId == 1) {
                            checkpointColor =
                                AppColors.successColor.withOpacity(0.6);
                            hoverColor =
                                AppColors.successColor.withOpacity(0.8);
                            statusColor = AppColors.successColor;
                          } else if (jobSchedule.jobScheduleStatusId == 2) {
                            checkpointColor =
                                AppColors.errorColor.withOpacity(0.6);
                            hoverColor = AppColors.errorColor.withOpacity(0.8);
                            statusColor = AppColors.errorColor;
                          } else {
                            checkpointColor = Colors.grey.withOpacity(0.1);
                            hoverColor = Colors.grey.withOpacity(0.2);
                          }

                          return MouseRegion(
                              onEnter: (_) => setState(() {
                                    checkpointColor = hoverColor;
                                  }),
                              onExit: (_) => setState(() {
                                    checkpointColor =
                                        jobSchedule.jobScheduleStatusId == 3
                                            ? AppColors.mainColor
                                                .withOpacity(0.1)
                                            : AppColors.successColor
                                                .withOpacity(0.6);
                                  }),
                              child: GestureDetector(
                                onTap: () {
                                  _navigateToLocationDetailsPage(
                                    context,
                                    jobSchedule.jobAuthorityId,
                                    jobSchedule.jobScheduleDate,
                                    jobSchedule.jobScheduleShiftId,
                                  );
                                },
                                onLongPress: () {
                                  showDialog(
                                    context: context,
                                    builder: (BuildContext context) {
                                      return AlertDialog(
                                        title: Text('รายละเอียด'),
                                        content: Column(
                                          mainAxisSize: MainAxisSize.min,
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            SmallText(
                                              text:
                                                  'พื้นที่: ${jobSchedule.zoneDescription}',
                                              color: AppColors.greyColor,
                                              size: Dimensions.font16,
                                            ),
                                            SmallText(
                                              text:
                                                  'จุดตรวจ: ${jobSchedule.zoneDescription}_${jobSchedule.locationDescription}',
                                              color: AppColors.greyColor,
                                              size: Dimensions.font16,
                                            ),
                                            SmallText(
                                              text:
                                                  'สถานะ: ${jobSchedule.jobStatusDescription}',
                                              color: statusColor,
                                              size: Dimensions.font16,
                                            )
                                          ],
                                        ),
                                        actions: <Widget>[
                                          TextButton(
                                            child: Text('ปิด'),
                                            onPressed: () {
                                              Navigator.of(context).pop();
                                            },
                                          ),
                                        ],
                                      );
                                    },
                                  );
                                },
                                child: Container(
                                  decoration: BoxDecoration(
                                    color: checkpointColor,
                                    borderRadius: BorderRadius.circular(8.0),
                                    border: Border.all(
                                      color: checkpointColor,
                                      width: 2.0,
                                    ),
                                  ),
                                  child: Center(
                                    child: BigText(
                                      text: '${index + 1}',
                                      size: Dimensions.font18,
                                      color: AppColors.blackColor,
                                    ),
                                  ),
                                ),
                              ));
                        },
                        childCount: totalCheckpoint,
                      ),
                      gridDelegate:
                          const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 5,
                        crossAxisSpacing: 8.0,
                        mainAxisSpacing: 8.0,
                      ),
                    ),
                  )
              ],
            ),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }
}
