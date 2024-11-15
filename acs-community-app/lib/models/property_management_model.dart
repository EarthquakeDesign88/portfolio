class PropertyManagement {
  final String contactLine;
  final String contactNumber;
  final String contactEmail;

  PropertyManagement({
    required this.contactLine,
    required this.contactNumber,
    required this.contactEmail,
  });

  factory PropertyManagement.fromJson(Map<String, dynamic> json) {
    return PropertyManagement(
      contactLine: json['contactLine'],
      contactNumber: json['contactNumber'],
      contactEmail: json['contactEmail'],
    );
  }
}
