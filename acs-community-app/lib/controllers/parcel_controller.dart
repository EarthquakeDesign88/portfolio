import 'package:get/get.dart';
import 'package:acs_community/models/parcel_model.dart';

class ParcelController extends GetxController {
  final List<Parcel> parcelLists = [
    Parcel(
      id: 1,
      number: "2307-01",
      status: "new",
      owner: "ACS",
      unitNo: "3300/25",
      collectedBy: "คุณสายชล",
      collectedDateTime: DateTime(2023, 6, 19, 11, 18),
      releasedBy: "Pornphimon",
      trackingNo: "RLT26371008TH",
      deliveryService: "Thailand Post",
      type: "ซองจดหมาย",
      addedBy: "นิติบุคคลตึกช้าง",
      addedDateTime: DateTime(2023, 6, 1, 13, 32),
      fileDocument: "assets/images/s1.jpg",
      qrData: "1111111111",
    ),
    Parcel(
      id: 2,
      number: "2307-02",
      status: "new",
      owner: "ACS",
      unitNo: "3300/25",
      collectedBy: "คุณสายชล",
      collectedDateTime: DateTime(2023, 6, 22, 12, 17),
      releasedBy: "Pornphimon",
      trackingNo: "RLT41771002TH",
      deliveryService: "Thailand Post",
      type: "ซองจดหมาย",
      addedBy: "นิติบุคคลตึกช้าง",
      addedDateTime: DateTime(2023, 6, 1, 17, 12),
      fileDocument: "assets/images/s1.jpg",
      qrData: "2222222222",
    ),
    Parcel(
      id: 3,
      number: "2307-03",
      status: "history",
      owner: "ACS",
      unitNo: "3300/25",
      collectedBy: "คุณสายชล",
      collectedDateTime: DateTime(2023, 7, 23, 14, 18),
      releasedBy: "Pornphimon",
      trackingNo: "RLT24371008TH",
      deliveryService: "Thailand Post",
      type: "ซองจดหมาย",
      addedBy: "นิติบุคคลตึกช้าง",
      addedDateTime: DateTime(2023, 7, 1, 15, 32),
      fileDocument: "assets/images/s1.jpg",
      qrData: "33333333333",
    ),
    Parcel(
      id: 4,
      number: "2307-04",
      status: "history",
      owner: "ACS",
      unitNo: "3300/25",
      collectedBy: "คุณสายชล",
      collectedDateTime: DateTime(2023, 7, 23, 11, 17),
      releasedBy: "Pornphimon",
      trackingNo: "RLT31771002TH",
      deliveryService: "Thailand Post",
      type: "ซองจดหมาย",
      addedBy: "นิติบุคคลตึกช้าง",
      addedDateTime: DateTime(2023, 7, 1, 18, 22),
      fileDocument: "assets/images/s1.jpg",
      qrData: "44444444444",
    ),
    Parcel(
      id: 5,
      number: "2307-05",
      status: "history",
      owner: "ACS",
      unitNo: "3300/25",
      collectedBy: "คุณสายชล",
      collectedDateTime: DateTime(2023, 7, 25, 11, 17),
      releasedBy: "Pornphimon",
      trackingNo: "RLT11771002TH",
      deliveryService: "Thailand Post",
      type: "ซองจดหมาย",
      addedBy: "นิติบุคคลตึกช้าง",
      addedDateTime: DateTime(2023, 6, 1, 18, 22),
      fileDocument: "assets/images/s1.jpg",
      qrData: "55555555555",
    ),
  ];

  List<String> parcelStatus = ["new", "history"];
  List<String> parcelStatusTH = ["พัสดุใหม่", "ประวัติพัสดุ"];

  int getParcelCount(String status) {
    return parcelLists
        .where((parcel) => parcel.status == status)
        .length;
  }

  Parcel getParcelByStatus(String status, int index) {
    return parcelLists
        .where((parcel) => parcel.status == status)
        .elementAt(index);
  }

  Parcel? getParcelById(int parcelId) {
    return parcelLists.firstWhere((parcel) => parcel.id == parcelId);
  }
}
