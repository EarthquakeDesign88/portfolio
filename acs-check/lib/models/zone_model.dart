class Zone {
  final int zoneId;
  final String zoneDescription;

  Zone({
    required this.zoneId,
    required this.zoneDescription,
  });

  factory Zone.fromJson(Map<String, dynamic> json) {
    return Zone(
      zoneId: json['zone_id'] ?? 0,
      zoneDescription: json['zone_description'] ?? '',
    );
  }
}
