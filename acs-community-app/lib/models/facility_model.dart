class Facility {
  final int id;
  final String imagePath;
  final String title;
  final String subtitle;
  final String rule;

  Facility({
    required this.id,
    required this.imagePath,
    required this.title,
    required this.subtitle,
    required this.rule,
  });


  factory Facility.fromJson(Map<String, dynamic> json) {
    return Facility(
      id: json['id'],
      imagePath: json['imagePath'],
      title: json['title'],
      subtitle: json['subtitle'],
      rule: json['rule'],
    );
  }
}
