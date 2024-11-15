class Stamp {
  final String visitorCode;
  final String stampCode;
  final int numStamp;
  final int stampCount;
  final String recorderName;
  final String stampDatetime;

  Stamp({
    required this.visitorCode,
    required this.stampCode,
    required this.numStamp,
    required this.stampCount,
    required this.recorderName,
    required this.stampDatetime,
  });

  factory Stamp.fromJson(Map<String, dynamic> json) {
    return Stamp(
      visitorCode: json['visitor_code'],
      stampCode: json['stamp_code'],
      numStamp: int.parse(json['num_stamp']),
      stampCount: int.parse(json['stamp_count']), 
      recorderName: json['recorder_name'],
      stampDatetime: json['stamp_datetime'],
    );
  }
}
