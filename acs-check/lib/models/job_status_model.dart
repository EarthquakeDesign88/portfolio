class JobStatus {
  final int jobStatusId;
  final String jobStatusDescription;

  JobStatus({
    required this.jobStatusId,
    required this.jobStatusDescription,
  });

  factory JobStatus.fromJson(Map<String, dynamic> json) {
    return JobStatus(
      jobStatusId: json['job_status_id'] ?? 0,
      jobStatusDescription: json['job_status_description'] ?? '',
    );
  }
}
