import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';

class EditRoleContent extends StatefulWidget {
  final Function(String?) onRoleChanged;
  final String? selectedRole;

  const EditRoleContent({
    required this.onRoleChanged,
    required this.selectedRole,
    Key? key,
  }) : super(key: key);

  @override
  _EditRoleContentState createState() => _EditRoleContentState();
}

class _EditRoleContentState extends State<EditRoleContent> {
  String? _selectedRole;

  @override
  void initState() {
    super.initState();
    _selectedRole = widget.selectedRole;
  }

  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          width: 400,
          height: 230,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(Dimensions.radius15),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                height: 45,
                decoration: BoxDecoration(
                  border: Border.all(
                    color: Colors.grey,
                    width: 1.0,
                  ),
                  borderRadius: BorderRadius.circular(5.0),
                ),
                padding: const EdgeInsets.all(10.0),
                child: Row(
                  children: [
                    Radio(
                      value: "เจ้าของกรรมสิทธิ",
                      groupValue: _selectedRole,
                      onChanged: (value) {
                        setState(() {
                          _selectedRole = value.toString();
                        });
                        widget.onRoleChanged(_selectedRole);
                      },
                      activeColor: AppColors.mainColor,
                    ),
                    SmallText(
                      text: "เจ้าของกรรมสิทธิ",
                      color: _selectedRole == "เจ้าของกรรมสิทธิ"
                          ? AppColors.mainColor
                          : AppColors.darkGreyColor,
                    )
                  ],
                ),
              ),
              SizedBox(height: Dimensions.height10),
              Container(
                height: 45,
                decoration: BoxDecoration(
                  border: Border.all(
                    color: Colors.grey,
                    width: 1.0,
                  ),
                  borderRadius: BorderRadius.circular(5.0),
                ),
                padding: const EdgeInsets.all(10.0),
                child: Row(
                  children: [
                    Radio(
                      value: "ผู้ร่วมอยู่อาศัย",
                      groupValue: _selectedRole,
                      onChanged: (value) {
                        setState(() {
                          _selectedRole = value.toString();
                        });
                        widget.onRoleChanged(_selectedRole);
                      },
                      activeColor: AppColors.mainColor,
                    ),
                    SmallText(
                      text: "ผู้ร่วมอยู่อาศัย",
                      color: _selectedRole == "ผู้ร่วมอยู่อาศัย"
                          ? AppColors.mainColor
                          : AppColors.darkGreyColor,
                    )
                  ],
                ),
              ),
              SizedBox(height: Dimensions.height10),
              Container(
                height: 45,
                decoration: BoxDecoration(
                  border: Border.all(
                    color: Colors.grey,
                    width: 1.0,
                  ),
                  borderRadius: BorderRadius.circular(5.0),
                ),
                padding: const EdgeInsets.all(10.0),
                child: Row(
                  children: [
                    Radio(
                      value: "ผู้เช่า",
                      groupValue: _selectedRole,
                      onChanged: (value) {
                        setState(() {
                          _selectedRole = value.toString();
                        });
                        widget.onRoleChanged(_selectedRole);
                      },
                      activeColor: AppColors.mainColor,
                    ),
                    SmallText(
                      text: "ผู้เช่า",
                      color: _selectedRole == "ผู้เช่า"
                          ? AppColors.mainColor
                          : AppColors.darkGreyColor,
                    )
                  ],
                ),
              ),
              SizedBox(height: Dimensions.height20),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  SizedBox(
                    height: 45,
                    width: 120,
                    child: MaterialButton(
                      height: Dimensions.height50,
                      color: AppColors.whiteColor,
                      onPressed: () {
                        Navigator.pop(context);
                      },
                      shape: RoundedRectangleBorder(
                        borderRadius:
                            BorderRadius.circular(Dimensions.radius10),
                        side: BorderSide(
                          color: AppColors.greyColor,
                          width: Dimensions.width2,
                        ),
                      ),
                      child: BigText(
                        text: "ยกเลิก",
                        size: Dimensions.font18,
                        color: AppColors.blackColor
                      )
                    )
                  ),
                  SizedBox(
                    height: 45,
                    width: 120,
                    child: MaterialButton(
                      height: Dimensions.height50,
                      color: AppColors.mainColor,
                      onPressed: () {},
                      shape: RoundedRectangleBorder(
                        borderRadius:
                            BorderRadius.circular(Dimensions.radius10),
                        side: BorderSide(
                          color: AppColors.mainColor,
                          width: Dimensions.width2,
                        ),
                      ),
                      child: BigText(
                        text: "ยืนยัน",
                        size: Dimensions.font18,
                        color: AppColors.whiteColor
                      )
                    )
                  )
                ],
              )
            ],
          ),
        )
      ],
    );
  }
}
