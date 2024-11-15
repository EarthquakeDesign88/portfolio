class Parcel {
  final int id;
  final String number;
  final String status; //new or history
  final String owner;
  final String unitNo; //Change to id if relational with other database
  final String collectedBy;
  final DateTime collectedDateTime;
  final String releasedBy;
  final String trackingNo;
  final String deliveryService;
  final String type;
  final String addedBy;
  final DateTime addedDateTime;
  final String fileDocument;
  final String qrData;

  Parcel({
    required this.id,
    required this.number,
    required this.status,
    required this.owner,
    required this.unitNo,
    required this.collectedBy,
    required this.collectedDateTime,
    required this.releasedBy,
    required this.trackingNo,
    required this.deliveryService,
    required this.type,
    required this.addedBy,
    required this.addedDateTime,
    required this.fileDocument,
    required this.qrData,
  });
}
