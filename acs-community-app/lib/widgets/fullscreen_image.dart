import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class FullScreenImage extends StatelessWidget {
  final String imageUrl;

  const FullScreenImage({Key? key, required this.imageUrl}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.blackColor,
      body: Stack(children: [
        GestureDetector(
          child: Center(
            child: Image.asset(
              imageUrl,
              fit: BoxFit.contain,
            ),
          ),
        ),
        Positioned(
            top: 75,
            left: 25,
            child: GestureDetector(
              onTap: () {
                Navigator.pop(context);
              },
              child: Container(
                width: 40,
                height: 40,
                decoration: const BoxDecoration(
                  shape: BoxShape.circle,
                  color: AppColors.greyColor,
                ),
                child: const Icon(
                  Icons.close,
                  color: AppColors.darkGreyColor,
                ),
              ),
            ))
      ]),
    );
  }
}
