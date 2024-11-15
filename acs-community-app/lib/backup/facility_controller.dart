// import 'package:get/get.dart';
// import 'package:acs_community/models/facility_model.dart';
// import 'package:acs_community/services/api_service.dart';

// class FacilityController extends GetxController {
//   final List<Facility> facilityLists = [
//     Facility(
//       id: 1,
//       imagePath:
//           "https://images.unsplash.com/photo-1600431521340-491eca880813?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80",
//       title: "ห้องสมุด",
//       subtitle: "ชั้น 26 สวีท",
//       rule:
//           "1.ห้ามนำเครื่องดื่ม, อาหาร เข้ามารับประทานในห้องสมุด\n2.งดใช้เสียง",
//     ),
//     Facility(
//       id: 2,
//       imagePath:
//           "https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "สระว่ายน้ำ",
//       subtitle: "อาคาร สวีท",
//       rule: "",
//     ),
//     Facility(
//       id: 3,
//       imagePath:
//           "https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "สระว่ายน้ำ",
//       subtitle: "ชั้น 7 อาคาร ซี",
//       rule: "",
//     ),
//     Facility(
//       id: 4,
//       imagePath:
//           "https://images.unsplash.com/photo-1542766788-a2f588f447ee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1176&q=80",
//       title: "ห้องฟิตเนส",
//       subtitle: "ชั้น 7 อาคาร ซี",
//       rule: "",
//     ),
//     Facility(
//       id: 5,
//       imagePath:
//           "https://plus.unsplash.com/premium_photo-1661928260943-4aa36c5e1acc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1288&q=80",
//       title: "ห้องรับรอง",
//       subtitle: "ชั้น 7 อาคาร ซี",
//       rule: "",
//     ),
//     Facility(
//       id: 6,
//       imagePath:
//           "https://images.unsplash.com/photo-1631889993959-41b4e9c6e3c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
//       title: "อ่างจากุชชี่",
//       subtitle: "อาคาร สวีท",
//       rule: "",
//     ),
//     Facility(
//       id: 7,
//       imagePath:
//           "https://images.unsplash.com/photo-1514914197726-5a7c4ab2d6ea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80",
//       title: "ห้องสนุกเกอร์",
//       subtitle: "ชั้น 26 สวีท",
//       rule: "",
//     ),
//     Facility(
//       id: 8,
//       imagePath:
//           "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ห้องสควอช",
//       subtitle: "ชั้น 28",
//       rule: "",
//     ),
//     Facility(
//       id: 9,
//       imagePath:
//           "https://images.unsplash.com/photo-1630703178161-1e2f9beddbf8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ห้องฟิตเนส",
//       subtitle: "ชั้น 7 M",
//       rule: "",
//     ),
//     Facility(
//       id: 10,
//       imagePath:
//           "https://plus.unsplash.com/premium_photo-1675615667993-1ad49a0a1720?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ห้องดูหนัง",
//       subtitle: "ชั้น 7 M อาคาร ซี",
//       rule: "",
//     ),
//     Facility(
//       id: 11,
//       imagePath:
//           "https://plus.unsplash.com/premium_photo-1661903136240-a97367001a64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ห้องรับรอง",
//       subtitle: "ชั้น 7 M",
//       rule: "",
//     ),
//     Facility(
//       id: 12,
//       imagePath:
//           "https://images.unsplash.com/photo-1583416750470-965b2707b355?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ห้องซาวน่า",
//       subtitle: "ชั้นดาดฟ้า",
//       rule: "",
//     ),
//   ];

//   Facility? bookingById(int facilityId) {
//     return facilityLists.firstWhere((facility) => facility.id == facilityId);
//   }
// }
