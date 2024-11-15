class AppConstants {
  static const String appName = "ACS Check";
  static const String appVersion = "1.0.0.1";

  //Local App
  static const String baseUrl = "http://10.0.2.2:2096";

  //PPE
  // static const String baseUrl = "http://10.10.10.202:2096";

  // PROD
  // static const String baseUrl = "https://www.elephant-tower.com:2096";

  static const String login = "/api/login";

  static const String workShift = "/api/work-shift";
  static const String workShiftByUser = "/api/work-shift-byuser";
  static const String zone = "/api/zone";
  static const String zoneByUser = "/api/zone-byuser";
  static const String issueTopic = "/api/issue-topic";
  static const String jobSchedule = "/api/job-schedule";
  static const String countCheckedPoints = "/api/count-checked-points";
  static const String jobStatus = "/api/job-status";
  static const String saveInspectionResult = "/api/save-inspection-result";
  static const String countCompletedSchedules = "/api/count-completed-schedules";
  static const String fetchLocationDetails = "/api/location-details";
  static const String fetchImagesJob = "/api/images-job";
  static const String fetchJobScheduleHistory = "/api/job-history";
  static const String checkLocation = "/api/check-location";
}

