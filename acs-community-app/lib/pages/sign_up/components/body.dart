import 'package:flutter/material.dart';

class Body extends StatelessWidget {
  const Body({super.key});

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(
            "Sign Up Page",
            style: TextStyle(
              fontSize: 25,
              color: Colors.black
            ),
          ),
        ]
      ),
    );
  }
}