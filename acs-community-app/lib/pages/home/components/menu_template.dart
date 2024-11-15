import 'package:flutter/material.dart';
import 'package:acs_community/utils/constants.dart';
import 'package:acs_community/widgets/big_text.dart';
import 'package:acs_community/widgets/small_text.dart';
import 'package:acs_community/widgets/menu_card.dart';
import 'package:acs_community/routes/route_helper.dart';
import 'package:get/get.dart';
import 'package:acs_community/models/menu_item_model.dart';

class MenuTemplate extends StatelessWidget {
  const MenuTemplate({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    List<MenuItem> menuItems = [
      MenuItem(
        icon: Icons.access_alarm,
        text: "แจ้งเตือนค่าใช้จ่าย",
        route: RouteHelper.paymentReminder,
      ),
      MenuItem(
        icon: Icons.picture_in_picture,
        text: "พัสดุ",
        route: RouteHelper.parcel,
      ),
      MenuItem(
        icon: Icons.spoke,
        text: "จองส่วนกลาง",
        route: RouteHelper.facility,
      ),
      MenuItem(
        icon: Icons.home_filled,
        text: "ห้องของฉัน",
        route: RouteHelper.myUnit,
      ),
      MenuItem(
        icon: Icons.construction_outlined,
        text: "แจ้งซ่อม",
        route: RouteHelper.repair,
      ),
      MenuItem(
        icon: Icons.call,
        text: "สมุดโทรศัพท์",
        route: RouteHelper.phoneBook,
      ),
      MenuItem(
        icon: Icons.list_alt_outlined,
        text: "ข้อเสนอแนะ",
        route: RouteHelper.suggestion,
      ),
      MenuItem(
        icon: Icons.menu_book,
        text: "ระเบียบชุมชน",
        route: RouteHelper.communityRule,
      ),
      MenuItem(
        icon: Icons.co_present,
        text: "ข้อมูลนิติ",
        route: RouteHelper.propertyManagement,
      ),
      MenuItem(
        icon: Icons.elevator_rounded,
        text: "เข้าใช้งานลิฟท์",
        route: RouteHelper.authAccess,
      ),
      MenuItem(
        icon: Icons.chat_outlined,
        text: "แชทกับนิติ",
        route: RouteHelper.chat,
      ),
    ];

    return Column(
      children: [
        Stack(
          children: [
            Container(
              width: MediaQuery.of(context).size.width,
              height: 200,
              decoration: const BoxDecoration(
                image: DecorationImage(
                  image: AssetImage('assets/images/tuk-chang.jpeg'),
                  fit: BoxFit.cover,
                ),
              ),
            ),
            Positioned(
              top: 60,
              right: 30,
              child: MaterialButton(
                minWidth: Dimensions.width50,
                height: Dimensions.height15,
                color: Colors.transparent,
                onPressed: () {},
                shape: RoundedRectangleBorder(
                  side: const BorderSide(
                    color: Colors.transparent,
                  ),
                  borderRadius: BorderRadius.circular(Dimensions.radius50),
                ),
                child: const SmallText(text: "เปลี่ยนชุมชน", color: AppColors.whiteColor)
              )
            ),
            Positioned(
              top: 85,
              left: 30,
              child: BigText(
                text: "3300/25",
                size: Dimensions.font26,
                color: AppColors.whiteColor
              ),
            ),
            Positioned(
              top: 115,
              left: 30,
              child: SmallText(
                text: "ตึกช้าง",
                size: Dimensions.font18,
                color: AppColors.whiteColor
              ),
            ),
            Positioned(
              top: 140,
              left: 30,
              child: Row(
                children: [
                  SmallText(
                    text: "36°C",
                    size: Dimensions.font18,
                    color: AppColors.whiteColor
                  ),
                  SizedBox(width: Dimensions.width10),
                  ElevatedButton(
                    onPressed: () {},
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.mainColor,
                    ),
                    child: const SmallText(
                        text: "AQl 50", color: AppColors.whiteColor),
                  ),
                ],
              ),
            ),
          ],
        ),
        SizedBox(height: Dimensions.height10),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: Dimensions.width5),
          child: Column(children: [
            for (int i = 0; i < menuItems.length; i += 2)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: MenuCard(
                      icon: menuItems[i].icon,
                      text: menuItems[i].text,
                      onPressed: () {
                        Get.toNamed(menuItems[i].route);
                      },
                    ),
                  ),
                  if (i + 1 < menuItems.length)
                    Expanded(
                      child: MenuCard(
                        icon: menuItems[i + 1].icon,
                        text: menuItems[i + 1].text,
                        onPressed: () {
                          Get.toNamed(menuItems[i + 1].route);
                        },
                      ),
                    )
                  else
                    Expanded(child: Container())
                ],
              ),
          ]),
        )
      ],
    );
  }
}
