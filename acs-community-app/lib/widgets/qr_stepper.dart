import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/code_textfield.dart';
import 'package:acs_community/widgets/sign_button.dart';
import 'package:acs_community/widgets/qrcode_scanner.dart';
import 'package:qr_code_scanner/qr_code_scanner.dart';

class QRStepper extends StatefulWidget {
  const QRStepper({Key? key}) : super(key: key);

  @override
  State<QRStepper> createState() => _QRStepperState();
}

class _QRStepperState extends State<QRStepper> {
  //Use for pass qrKey to QRCodeScanner widget
  GlobalKey qrKey = GlobalKey(debugLabel: 'QR');
  QRViewController? controller;

  //Use for callback code_textfield widget
  bool isButtonEnabled = false;
  void updateButtonState(bool isEnabled) {
    setState(() {
      isButtonEnabled = isEnabled;
    });
  }

  //Use fot set stepper
  int currentStep = 0;
  continueStep() {
    if (currentStep < 2) {
      setState(() {
        currentStep = currentStep + 1;
      });
    }
  }

  cancelStep() {
    if (currentStep > 0) {
      setState(() {
        currentStep = currentStep - 1;
      });
    }
  }

  onStepTapped(int value) {
    setState(() {
      currentStep = value;
    });
  }

  Widget controlBuilders(context, details) {
    // return Padding(
    //   padding: const EdgeInsets.all(8.0),
    //   child: Row(
    //     children: [
    //       ElevatedButton(
    //         onPressed: details.onStepContinue,
    //         child: const Text('Next'),
    //       ),
    //       const SizedBox(width: 10),
    //       OutlinedButton(
    //         onPressed: details.onStepCancel,
    //         child: const Text('Back'),
    //       ),
    //     ],
    //   ),
    // );
    // Hide button
    return Container();
  }

  @override
  Widget build(BuildContext context) {
    return Center(
      child: SizedBox(
          width: 480,
          child: Theme(
            data: Theme.of(context).copyWith(
              colorScheme: const ColorScheme.light().copyWith(
                primary: AppColors.mainColor, // Color of selected step
                // onPrimary: Colors.white, // Text color of selected step
                // background: AppColors.whiteColor, // Background color of step
                // surface: Colors.grey, // Color of line connecting steps
                // onSurface: Colors.grey, // Text color of inactive step
              ),
            ),
            child: Stepper(
              elevation: 0, //Horizontal Impact
              controlsBuilder: controlBuilders,
              type: StepperType.horizontal,
              physics: const ScrollPhysics(),
              onStepTapped: onStepTapped,
              // onStepContinue: continueStep,
              onStepCancel: cancelStep,
              currentStep: currentStep, //0, 1, 2
              steps: [
                Step(
                    title: const Text(''),
                    content: Column(
                      children: [
                        SizedBox(height: Dimensions.height20),
                        SignButton(
                          text: "สแกนรหัสเชิญจาก QR code",
                          textColor: AppColors.mainColor,
                          bgColor: AppColors.whiteColor,
                          borderColor: AppColors.mainColor,
                          routeTo: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) =>
                                    QRCodeScanner(qrKey: qrKey),
                              ),
                            );
                          },
                        ),
                        SizedBox(height: Dimensions.height15),
                        const SmallText(text: "หรือ"),
                        SizedBox(height: Dimensions.height20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(
                              Icons.security,
                              color: AppColors.greyColor,
                            ),
                            SizedBox(width: Dimensions.width5),
                            BigText(
                                text: "กรอกรหัสเชิญ 8 หลัก",
                                size: Dimensions.font26),
                            SizedBox(width: Dimensions.width5),
                            const Icon(
                              Icons.warning,
                              color: AppColors.greyColor,
                            ),
                          ],
                        ),
                        SizedBox(height: Dimensions.height15),
                        SmallText(
                            text: "รับรหัสเชิญได้จากนิติของท่าน",
                            size: Dimensions.font14),
                        SizedBox(height: Dimensions.height20),
                        CodeTextField(
                            length: 8,
                            isButtonEnabled: isButtonEnabled,
                            updateButtonState: updateButtonState),
                        SizedBox(height: Dimensions.height30),
                        MaterialButton(
                            minWidth: 350,
                            height: Dimensions.height50,
                            color: isButtonEnabled
                                ? AppColors.mainColor
                                : AppColors.greyColor,
                            onPressed: () {
                              continueStep();
                            },
                            shape: RoundedRectangleBorder(
                              borderRadius:
                                  BorderRadius.circular(Dimensions.radius10),
                              side: BorderSide(
                                color: isButtonEnabled
                                    ? AppColors.mainColor
                                    : AppColors.greyColor,
                                width: 2.0,
                              ),
                            ),
                            child: BigText(
                                text: "ยืนยัน",
                                size: Dimensions.font18,
                                color: isButtonEnabled
                                    ? AppColors.whiteColor
                                    : AppColors.secondaryTextColor)),
                      ],
                    ),
                    isActive: currentStep >= 0,
                    state: currentStep >= 0
                        ? StepState.complete
                        : StepState.disabled),
                Step(
                  title: const Text(''),
                  content: const Text('This is the Second step.'),
                  isActive: currentStep >= 0,
                  state: currentStep >= 1
                      ? StepState.complete
                      : StepState.disabled,
                ),
                Step(
                  title: const Text(''),
                  content: const Text('This is the Third step.'),
                  isActive: currentStep >= 0,
                  state: currentStep >= 2
                      ? StepState.complete
                      : StepState.disabled,
                ),
              ],
            ),
          )),
    );
  }
}
