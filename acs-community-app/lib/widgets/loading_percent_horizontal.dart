import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class LoadingPercentHorizontal extends StatelessWidget {
  final double percent;
  final double width;
  final double height;
  final Color backgroundColor;
  final Color progressColor;

  const LoadingPercentHorizontal({
    required this.percent,
    this.width = 200,
    this.height = 20,
    this.backgroundColor = AppColors.whiteColor,
    this.progressColor = AppColors.mainColor,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: width,
      height: height,
      decoration: BoxDecoration(
        color: backgroundColor,
        borderRadius: BorderRadius.circular(height / 2),
      ),
      child: Stack(
        children: [
          Container(
            width: percent * width / 100,
            height: height,
            decoration: BoxDecoration(
              color: progressColor,
              borderRadius: BorderRadius.circular(height / 2),
            ),
          ),
          // Align(
          //   alignment: Alignment.centerRight,
          //   child: Padding(
          //     padding: const EdgeInsets.symmetric(horizontal: 8.0),
          //     child: Text(
          //       '${percent.toStringAsFixed(0)}%',
          //       style: TextStyle(
          //         color: Colors.white,
          //         fontWeight: FontWeight.bold,
          //       ),
          //     ),
          //   ),
          // ),
        ],
      ),
    );
  }
}