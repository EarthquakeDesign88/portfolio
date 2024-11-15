import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class BodyChat extends StatelessWidget {
  const BodyChat({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Expanded(
          child: ListView.builder(
            itemCount: 10, 
            itemBuilder: (context, index) {
              final isSender = index % 2 == 0; 

              return Padding(
                padding: const EdgeInsets.all(8.0),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (isSender)
                      Expanded(
                        flex: 1,
                        child: SizedBox(
                          width: Dimensions.width40,
                          height: Dimensions.height40,
                          child: const CircleAvatar(
                            backgroundColor: AppColors.mainColor,
                            foregroundColor: Colors.white,
                            child: Text('S'),
                          ),
                        ),
                      ),
                    Expanded(
                      flex: 5,
                      child: Container(
                        padding: const EdgeInsets.all(8.0),
                        decoration: BoxDecoration(
                          color: isSender ? Colors.blue[100] : Colors.grey[200],
                          borderRadius: BorderRadius.circular(8.0),
                        ),
                        child: Column(
                          crossAxisAlignment: isSender
                              ? CrossAxisAlignment.end
                              : CrossAxisAlignment.start,
                          children: [
                            Text(
                              isSender ? 'แจ้งปัญหา $index' : 'ตอบกลับ $index',
                              style: const TextStyle(fontWeight: FontWeight.bold),
                            ),
                            SizedBox(height: Dimensions.height5),
                            isSender
                                ? Text('รายละเอียด $index')
                                : Text('กำลังดำเนินการแก้ไขอยู่ $index'),
                            if (index % 2 == 0)
                              Container(
                                margin: const EdgeInsets.symmetric(vertical: 8.0),
                                width: double.infinity,
                                height: 150,
                                color: Colors.grey[300],
                                child: Image.network(
                                  'https://images.unsplash.com/photo-1444419988131-046ed4e5ffd6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
                                  fit: BoxFit.fill
                                )
                              ),
                          ],
                        ),
                      ),
                    ),
                    if (!isSender)
                      Expanded(
                        flex: 1,
                        child: SizedBox(
                          width: Dimensions.width40,
                          height: Dimensions.height40,
                          child: const CircleAvatar(
                            backgroundColor: Colors.grey,
                            foregroundColor: Colors.white,
                            child: Text('นิติ'),
                          ),
                        ),
                      ),
                  ],
                ),
              );
            },
          ),
        ),
        Padding(
          padding: const EdgeInsets.all(8.0),
          child: Row(
            children: [
              IconButton(
                icon: const Icon(Icons.camera_alt),
                color: AppColors.mainColor,
                onPressed: () {
           
                },
              ),
              IconButton(
                icon: const Icon(Icons.image),
                color: AppColors.mainColor,
                onPressed: () {
  
                },
              ),
              const Expanded(
                child: TextField(
                  decoration: InputDecoration(
                    hintText: 'พิมพ์ข้อความ',
                  ),
                ),
              ),
              IconButton(
                icon: const Icon(Icons.send),
                onPressed: () {

                },
              ),
            ],
          ),
        ),
      ],
    );
  }
}
