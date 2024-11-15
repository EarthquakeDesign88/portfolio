import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:stamp_park/utils/constants.dart';
import 'package:stamp_park/widgets/bottom_navbar.dart';
import 'package:stamp_park/widgets/big_text.dart';
import 'package:stamp_park/widgets/small_text.dart';
import 'package:stamp_park/widgets/bottom_line.dart';
import 'package:stamp_park/controllers/stamp_controller.dart';
import 'package:stamp_park/services/auth_service.dart';
import 'package:get/get.dart';
import 'package:stamp_park/routes/route_helper.dart';
import 'package:intl/date_symbol_data_local.dart';

class StampHistoryPage extends StatefulWidget {
  const StampHistoryPage({Key? key}) : super(key: key);

  @override
  State<StampHistoryPage> createState() => _StampHistoryPageState();
}

class _StampHistoryPageState extends State<StampHistoryPage> {
  int _currentIndex = 3;

  final AuthService authService = AuthService();
  final StampController _stampController = Get.find();
  String? username;
  late TextEditingController searchController;

  bool isLoading = true;

  Widget _buildLoading() {
    return CircularProgressIndicator();
  }

  @override
  void initState() {
    super.initState();
    initializeDateFormatting('th', null);
    _loadUserData();
    searchController = TextEditingController();
  }

  void _loadUserData() async {
    final storedUsername = await authService.getUsername();

    setState(() {
      username = storedUsername;
    });

    if (username != null && username!.isNotEmpty) {
      _stampController.fetchStampHistory(username).then((_) {
        setState(() {
          isLoading = false; 
        });
      });
    }
  }

  void _onTabChanged(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.mainColor,
      appBar: AppBar(
        title: const Text(
          "ประวัติสแตมป์",
          style: TextStyle(color: AppColors.whiteColor),
        ),
        backgroundColor: AppColors.mainColor,
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {
              showSearch(
                context: context,
                delegate: _StampSearchDelegate(_stampController),
              );
            },
          ),
        ],
        iconTheme: IconThemeData(color: AppColors.whiteColor),
      ),
      drawer: Drawer(
        child: ListView(
          children: [
            DrawerHeader(
              decoration: const BoxDecoration(
                color: AppColors.mainColor,
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Image.asset(
                    'assets/images/logo.png', 
                    width: Dimensions.width80,
                    height: Dimensions.height80,
                  ),
                  SizedBox(height: Dimensions.height10),
                  const Text(
                    "Stamp Park",
                    style: TextStyle(
                      fontSize: 20,
                      color: Colors.white,
                    ),
                  ),
                ],
              ),
            ),
            ListTile(
              leading: Icon(Icons.account_circle),
              title: SmallText(text: "$username", size: Dimensions.font18),
            ),
            ListTile(
              title: SmallText(text: "ประทับตรา", size: Dimensions.font18),
              onTap: () {
                Get.toNamed(RouteHelper.home);
              },
            ),
            ListTile(
              title: SmallText(text: "ประวัติสแตมป์", size: Dimensions.font18),
              onTap: () {
                Get.toNamed(RouteHelper.stampHistory);
              },
            ),
            ListTile(
              title: SmallText(text: "ออกจากระบบ", size: Dimensions.font18),
              onTap: () async {
                await authService.logout();
                Future.delayed(Duration(milliseconds: 100), () {
                  Get.offAllNamed(RouteHelper.login);
                });
              },
            ),
          ],
        ),
      ),
      body: Container(
        color: AppColors.whiteColor,
        child: isLoading
            ? Center(child: _buildLoading())
            : Obx(() {
          if (_stampController.stampHistoryLists.isEmpty) {
            return Center(
              child: Container(
                alignment: Alignment.center,
                child: const SmallText(
                  text: 'ไม่พบประวัติสแตมป์',
                  color: AppColors.blackColor,
                ),
              ),
            );
          } else {
            return ListView.builder(
              itemCount: _stampController.stampHistoryLists.length,
              itemBuilder: (context, index) {
                final stamp = _stampController.stampHistoryLists[index];
                final stampDateTime = DateFormat.yMMMd('th')
                    .add_Hm()
                    .format(DateTime.parse(stamp.stampDatetime));

                return Container(
                  color: AppColors.whiteColor,
                  child: Column(
                    children: [
                      SizedBox(height: Dimensions.height10),
                      Padding(
                        padding: EdgeInsets.symmetric(
                            horizontal: Dimensions.width15),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Expanded(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.start,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  BigText(
                                    text: stampDateTime,
                                    size: Dimensions.font16,
                                    color: AppColors.darkGreyColor,
                                  ),
                                  SizedBox(height: Dimensions.height5),
                                  SmallText(
                                    text: stamp.visitorCode,
                                    size: Dimensions.font16,
                                    color: AppColors.mainColor,
                                  )
                                ],
                              ),
                            ),
                            Row(
                              children: [
                                const Icon(
                                  Icons.star,
                                  color: AppColors.mainColor,
                                ),
                                SizedBox(width: Dimensions.width5),
                                SmallText(
                                  text: stamp.numStamp.toString(),
                                  size: Dimensions.font16,
                                  color: AppColors.darkGreyColor,
                                )
                              ],
                            ),
                          ],
                        ),
                      ),
                      SizedBox(height: Dimensions.height10),
                      const BottomLine(),
                    ],
                  ),
                );
              },
            );
          }
        }),
      ),
      bottomNavigationBar: BottomNavbar(
        currentIndex: _currentIndex,
        onTabChanged: _onTabChanged,
      ),
    );
  }
}

class _StampSearchDelegate extends SearchDelegate<String> {
  final StampController stampController;
  _StampSearchDelegate(this.stampController);

  bool isLoading = false;

  // Method to update isLoading
  void setLoading(bool value) {
    isLoading = value;
  }

  @override
  List<Widget> buildActions(BuildContext context) {
    return [
      IconButton(
        icon: Icon(Icons.clear),
        onPressed: () {
          query = '';
        },
      ),
    ];
  }

  @override
  Widget buildLeading(BuildContext context) {
    return IconButton(
      icon: Icon(Icons.arrow_back),
      onPressed: () {
        close(context, '');
      },
    );
  }

  @override
  Widget buildResults(BuildContext context) {
    setLoading(true); // Set loading to true when showing results

    final results = stampController.stampHistoryLists.where((stamp) =>
        stamp.visitorCode.toLowerCase().contains(query.toLowerCase()));
    if (results.isEmpty) {
      // No results found
      return Center(
        child: Container(
          alignment: Alignment.center,
          child: const SmallText(
            text: 'ไม่พบเลขที่บัตรจอดรถ',
            color: AppColors.blackColor,
          ),
        ),
      );
    } else {
      // Results found
      return ListView.builder(
        itemCount: results.length,
        itemBuilder: (context, index) {
          final stamp = results.elementAt(index);
          final stampDateTime = DateFormat.yMMMd('th').add_Hm().format(DateTime.parse(stamp.stampDatetime));
          return Column(
            children: [
              SizedBox(height: Dimensions.height10),
              Padding(
                padding: EdgeInsets.symmetric(horizontal: Dimensions.width15),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          BigText(
                            text: stampDateTime,
                            size: Dimensions.font16,
                            color: AppColors.darkGreyColor,
                          ),
                          SizedBox(height: Dimensions.height5),
                          SmallText(
                            text: stamp.visitorCode,
                            size: Dimensions.font16,
                            color: AppColors.mainColor,
                          )
                        ],
                      ),
                    ),
                    Row(
                      children: [
                        const Icon(
                          Icons.star,
                          color: AppColors.mainColor,
                        ),
                        SizedBox(width: Dimensions.width5),
                        SmallText(
                          text: stamp.numStamp.toString(),
                          size: Dimensions.font16,
                          color: AppColors.darkGreyColor,
                        )
                      ],
                    ),
                  ],
                ),
              ),
              SizedBox(height: Dimensions.height10),
              const BottomLine(),
            ],
          );
        },
      );
    }
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    return Center(
      child: Container(
        alignment: Alignment.center,
        child: const SmallText(
          text: 'กรอกเลขที่บัตรจอดรถ',
          color: AppColors.blackColor,
        ),
      ),
    );
  }
  
  @override
  void showResults(BuildContext context) {
    super.showResults(context);
  }

  @override
  void close(BuildContext context, String result) {
    setLoading(false); // Set loading to false when closing
    super.close(context, result);
  }
}
