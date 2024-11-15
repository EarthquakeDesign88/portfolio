class User {
  final int id;
  final String username;
  final String? password;
  final String email;
  final String stampCode;
  final String stampCondition;
  final String companyName;

  User({
    required this.id,
    required this.username,
    required this.password,
    required this.email,
    required this.stampCode,
    required this.stampCondition,
    required this.companyName,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      username: json['username'],
      password: json['password'],
      email: json['email'],
      stampCode: json['stamp_code'],
      stampCondition: json['stamp_condition'],
      companyName: json['company_name'],
    );
  }
}
