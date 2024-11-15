import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';

class CodeTextField extends StatefulWidget {
  final int length;
  final bool isButtonEnabled;
  final void Function(bool isEnabled) updateButtonState;

  const CodeTextField({
    Key? key,
    this.length = 8,
    required this.isButtonEnabled,
    required this.updateButtonState,
  }) : super(key: key);

  @override
  _CodeTextFieldState createState() => _CodeTextFieldState();
}

class _CodeTextFieldState extends State<CodeTextField> {
  late List<FocusNode> focusNodes;
  late List<TextEditingController> controllers;
  bool isButtonEnabled = false;

  @override
  void initState() {
    super.initState();
    focusNodes =
        List<FocusNode>.generate(widget.length, (index) => FocusNode());
    controllers = List<TextEditingController>.generate(
      widget.length,
      (index) => TextEditingController(),
    );

    for (int i = 0; i < widget.length - 1; i++) {
      controllers[i].addListener(() {
        if (controllers[i].text.isNotEmpty) {
          focusNodes[i + 1].requestFocus();
        }
      });
    }

    controllers[widget.length - 1].addListener(() {
      widget.updateButtonState(controllers[widget.length - 1].text.isNotEmpty);
    });
  }

  @override
  void dispose() {
    for (int i = 0; i < widget.length; i++) {
      focusNodes[i].dispose();
      controllers[i].dispose();
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    Dimensions.init(context);
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: List.generate(widget.length, (index) {
        return Container(
          width: Dimensions.width30,
          height: Dimensions.height30,
          margin: const EdgeInsets.symmetric(horizontal: 4),
          child: TextField(
            controller: controllers[index],
            focusNode: focusNodes[index],
            keyboardType: TextInputType.number,
            maxLength: 1,
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: Dimensions.font18),
            decoration: InputDecoration(
              contentPadding: EdgeInsets.zero,
              counterText: '',
              filled: true,
              fillColor: AppColors.whiteColor,
              enabledBorder: OutlineInputBorder(
                borderSide:
                    const BorderSide(color: AppColors.greyColor, width: 1.0),
                borderRadius: BorderRadius.circular(8.0),
              ),
              focusedBorder: OutlineInputBorder(
                borderSide:
                    const BorderSide(color: AppColors.mainColor, width: 1.0),
                borderRadius: BorderRadius.circular(8.0),
              ),
            ),
          ),
        );
      }),
    );
  }
}
