// import 'package:get/get.dart';
// import 'package:acs_community/models/announcement_model.dart';

// class AnnouncementController extends GetxController {
//   final List<Announcement> announcementLists = [
//     Announcement(
//       id: 1,
//       imagePath:
//           "https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
//       title: "ประกาศสำคัญ 1",
//       subtitle: "รายละเอียด 1",
//       type: "important",
//       date: "วันที่ 11 ม.ค. 2566, 14:00 น.",
//       totalThank: "1",
//       totalView: "86",
//     ),
//     Announcement(
//       id: 2,
//       imagePath:
//           "https://images.unsplash.com/photo-1546074177-ffdda98d214f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
//       title: "ประกาศสำคัญ 2",
//       subtitle: "รายละเอียด 2",
//       type: "important",
//       date: "วันที่ 12 ม.ค. 2566, 14:00 น.",
//       totalThank: "12",
//       totalView: "45",
//     ),
//     Announcement(
//       id: 3,
//       imagePath:
//           "https://images.unsplash.com/photo-1559526324-c1f275fbfa32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ประกาศสำคัญ 3",
//       subtitle: "รายละเอียด 3",
//       type: "important",
//       date: "วันที่ 17 ม.ค. 2566, 14:00 น.",
//       totalThank: "10",
//       totalView: "876",
//     ),
//     Announcement(
//       id: 4,
//       imagePath:
//           "https://images.unsplash.com/photo-1601893211509-81b6d03e46a0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ข่าวสารทั่วไป 1",
//       subtitle: "รายละเอียด 1",
//       type: "general",
//       date: "วันที่ 11 ม.ค. 2566, 14:00 น.",
//       totalThank: "2",
//       totalView: "74",
//     ),
//     Announcement(
//       id: 5,
//       imagePath:
//           "https://images.unsplash.com/photo-1634577004337-1df1fd52f914?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ข่าวสารทั่วไป 2",
//       subtitle: "รายละเอียด 2",
//       type: "general",
//       date: "วันที่ 14 ม.ค. 2566, 14:00 น.",
//       totalThank: "20",
//       totalView: "63",
//     ),
//     Announcement(
//       id: 6,
//       imagePath:
//           "https://images.unsplash.com/photo-1628349407899-46565857ab55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ข่าวสารทั่วไป 3",
//       subtitle: "รายละเอียด 3",
//       type: "general",
//       date: "วันที่ 15 ม.ค. 2566, 14:00 น.",
//       totalThank: "12",
//       totalView: "44",
//     ),
//     Announcement(
//       id: 7,
//       imagePath:
//           "https://images.unsplash.com/photo-1559526324-c1f275fbfa32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80",
//       title: "ประกาศสำคัญ 4",
//       subtitle: "รายละเอียด 4",
//       type: "important",
//       date: "วันที่ 18 ม.ค. 2566, 15:07 น.",
//       totalThank: "14",
//       totalView: "74",
//     ),
//     Announcement(
//       id: 8,
//       imagePath:
//           "https://images.unsplash.com/photo-1563430862227-8fe668221256?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
//       title: "ข่าวสารทั่วไป 4",
//       subtitle: "รายละเอียด 4",
//       type: "general",
//       date: "วันที่ 10 ม.ค. 2566, 10:00 น.",
//       totalThank: "2",
//       totalView: "15",
//     ),
//   ];

//   List<String> announcementTypes = ["important", "general"];
//   List<String> announcementTypesTH = ["ประกาศสำคัญ", "ข่าวสารทั่วไป"];

//   int getAnnouncementCount(String type) {
//     return announcementLists
//         .where((announcement) => announcement.type == type)
//         .length;
//   }

//   Announcement getAnnouncementByType(String type, int index) {
//     return announcementLists
//         .where((announcement) => announcement.type == type)
//         .elementAt(index);
//   }

//   Announcement? getAnnouncementById(int detailId) {
//     return announcementLists
//         .firstWhere((announcement) => announcement.id == detailId);
//   }
// }
