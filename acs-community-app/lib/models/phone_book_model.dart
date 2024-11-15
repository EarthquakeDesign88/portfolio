class PhoneBook {
  final int id;
  final String contactName;
  final String contactNumber;
  final String contactType; //Have 3 types

  PhoneBook({
    required this.id,
    required this.contactName,
    required this.contactNumber,
    required this.contactType
  });


  factory PhoneBook.fromJson(Map<String, dynamic> json) {
    return PhoneBook(
      id: json['contact_id'],
      contactName: json['contact_name'],
      contactNumber: json['contact_number'],
      contactType: json['contact_cate'],
    );
  }
}
