class IssueTopic {
  final int issueId;
  final String issueDescription;

  IssueTopic({
    required this.issueId,
    required this.issueDescription,
  });

  factory IssueTopic.fromJson(Map<String, dynamic> json) {
    return IssueTopic(
      issueId: json['issue_id'] ?? 0,
      issueDescription: json['issue_description'] ?? '',
    );
  }
}
