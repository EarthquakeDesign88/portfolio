// import 'package:get/get.dart';
// import 'package:acs_community/models/phone_book_model.dart';

// class PhoneBookController extends GetxController {
//   final List<PhoneBook> phoneBookLists = [
//     PhoneBook(
//         id: 1,
//         contactName: "ชั้น 7 C สระว่ายน้ำ",
//         contactNumber: "6006",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 2,
//         contactName: "รปภ ลานจอด P5B",
//         contactNumber: "6004",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 3,
//         contactName: "รปภ ลานจอด P7A",
//         contactNumber: "6005",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 4,
//         contactName: "ห้องช่าง",
//         contactNumber: "5005",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 5,
//         contactName: "ห้องช่าง",
//         contactNumber: "029374960",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 6,
//         contactName: "เคาน์เตอร์ชั้น 1 A",
//         contactNumber: "6000",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 7,
//         contactName: "เคาน์เตอร์ชั้น 1 B",
//         contactNumber: "6001",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 8,
//         contactName: "เคาน์เตอร์ชั้น 1 C",
//         contactNumber: "6002",
//         contactType: "myProperty"),
//     PhoneBook(
//         id: 9,
//         contactName: "Operator โทรจากสายภายในให้โชว์เบอร์",
//         contactNumber: "9",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 10,
//         contactName: "กองปราบปราม",
//         contactNumber: "1195",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 11,
//         contactName: "ศูนย์ดับเพลิงศรีอยุธยา",
//         contactNumber: "199",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 12,
//         contactName: "ศูนย์นเรนทร (รับแจ้งเจ็บป่วยฉุกเฉิน)",
//         contactNumber: "1669",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 13,
//         contactName: "สถานีดับเพลิงสุทธิสาร",
//         contactNumber: "022773688.9",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 14,
//         contactName: "สถานีตำรวจนครบาลพหลโยธิน",
//         contactNumber: "025122447.9",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 15,
//         contactName: "เหตุด่วนเหตุร้าย",
//         contactNumber: "191",
//         contactType: "emergency"),
//     PhoneBook(
//         id: 16,
//         contactName: "เคาน์เตอร์โรงแรม",
//         contactNumber: "029374111",
//         contactType: "others"),
//     PhoneBook(
//       id: 17,
//       contactName: "เคาน์เตอร์โรงแรม",
//       contactNumber: "7777",
//       contactType: "others"
//     ),
//   ];

//   List<String> contactTypes = ["myProperty", "emergency", "others"];
//   List<String> contactTypesTH = ["โครงการ", "ฉุกเฉิน", "อื่นๆ"];

//   int getContactCount(String contactType) {
//     return phoneBookLists
//         .where((contact) => contact.contactType == contactType)
//         .length;
//   }

//   PhoneBook getContactByType(String contactType, int index) {
//     return phoneBookLists
//         .where((contact) => contact.contactType == contactType)
//         .elementAt(index);
//   }
// }
