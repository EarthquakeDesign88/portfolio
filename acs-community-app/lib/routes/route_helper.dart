import 'package:get/get.dart';
import 'package:acs_community/pages/welcome/welcome_page.dart';
import 'package:acs_community/pages/sign_in/sign_in_page.dart';
import 'package:acs_community/pages/sign_in/comfirm_signin_page.dart';
import 'package:acs_community/pages/sign_in/community_confirm_page.dart';
import 'package:acs_community/pages/sign_in/inprogress_signin_page.dart';
import 'package:acs_community/pages/sign_in/verify_otp_page.dart';
import 'package:acs_community/pages/sign_up/sign_up_page.dart';
import 'package:acs_community/pages/home/home_page.dart';
import 'package:acs_community/pages/social/social_page.dart';
import 'package:acs_community/pages/announcement/announcement_page.dart';
import 'package:acs_community/pages/announcement/announcement_detail_page.dart';
import 'package:acs_community/pages/setting/setting_page.dart';
import 'package:acs_community/pages/setting/personal_info_page.dart';
import 'package:acs_community/pages/setting/personal/name_page.dart';
import 'package:acs_community/pages/setting/personal/gender_page.dart';
import 'package:acs_community/pages/setting/personal/birth_date_page.dart';
import 'package:acs_community/pages/setting/personal/phone_number_page.dart';
import 'package:acs_community/pages/setting/personal/email_password_page.dart';
import 'package:acs_community/pages/setting/personal/email_page.dart';
import 'package:acs_community/pages/setting/personal/delete_account_page.dart';
import 'package:acs_community/pages/setting/notifications_page.dart';
import 'package:acs_community/pages/setting/app_version_page.dart';
import 'package:acs_community/pages/setting/faq_page.dart';
import 'package:acs_community/pages/setting/faq/faq_detail_page.dart';
import 'package:acs_community/pages/setting/privacy_policy_page.dart';
import 'package:acs_community/pages/setting/term_service_page.dart';
import 'package:acs_community/pages/setting/setting_suggestion_page.dart';
import 'package:acs_community/pages/auth_access/auth_access_page.dart';
import 'package:acs_community/pages/chat/chat_page.dart';
import 'package:acs_community/pages/parcel/parcel_page.dart';
import 'package:acs_community/pages/parcel/new_parcel_page.dart';
import 'package:acs_community/pages/parcel/history_parcel_page.dart';
import 'package:acs_community/pages/facility/facility_page.dart';
import 'package:acs_community/pages/facility/facility_booking_page.dart';
import 'package:acs_community/pages/my_unit/my_unit_page.dart';
import 'package:acs_community/pages/my_unit/invitation_code_page.dart';
import 'package:acs_community/pages/my_unit/add_unit_page.dart';
import 'package:acs_community/pages/repair/repair_page.dart';
import 'package:acs_community/pages/repair/request_repair_page.dart';
import 'package:acs_community/pages/phone_book/phone_book_page.dart';
import 'package:acs_community/pages/property_management/property_management_page.dart';
import 'package:acs_community/pages/suggestion/suggestion_page.dart';
import 'package:acs_community/pages/community_rule/community_rule_page.dart';
import 'package:acs_community/pages/payment_reminder/payment_reminder_page.dart';
import 'package:acs_community/pages/payment_reminder/payment_reminder_detail_page.dart';
import 'package:acs_community/pages/payment_reminder/attach_proof_payment_page.dart';

class RouteHelper {
  static String initial = "/";
  static String welcome = "/welcome";
  static String signIn = "/sign_in";
  static String confirmSignIn = "/confirm_signin";
  static String verifyOtp = "/verify_otp";
  static String inprogressSignIn = "/inprogress_signin";
  static String communityConfirm = "/community_confirm";
  static String signUp = "/sign_up";
  static String home = "/home";
  static String social = "/social";
  static String announcement = "/announcement";
  static String announcementDetail = "/announcement/detail";
  static String setting = "/setting";
  static String personalInfo = "/setting/personal_info";
  static String namePersonalInfo = "/setting/personal_info/name";
  static String genderPersonalInfo = "/setting/personal_info/gender";
  static String birthDatePersonalInfo = "/setting/personal_info/birthdate";
  static String phoneNumberPersonalInfo = "/setting/personal_info/phone_number";
  static String emailPasswordPersonalInfo = "/setting/personal_info/email_password";
  static String emailPersonalInfo = "/setting/personal_info/email";
  static String deleteAccountPersonalInfo = "/setting/personal_info/delete_account";
  static String notifications = "/setting/notifications";
  static String appVersion = "/setting/app_version";
  static String faq = "/setting/faq";
  static String faqAnswer = "/setting/faq/answer";
  static String privacyPolicy = "/setting/privacy_policy";
  static String termService = "/setting/term_service";
  static String settingSuggestion = "/setting/suggestion";
  static String authAccess = "/auth_access";
  static String chat = "/chat";
  static String parcel = "/parcel";
  static String newParcel = "/parcel/new_parcel";
  static String historyParcel = "/parcel/history_parcel";
  static String facility = "/facility";
  static String facilityBooking = "/facility_booking";
  static String myUnit = "/my_unit";
  static String invitationCode = "/invitation_code";
  static String addUnit = "/add_unit";
  static String repair = "/repair";
  static String requestRepair = "/request_repair";
  static String phoneBook = "/phone_book";
  static String propertyManagement = '/property_management';
  static String suggestion = '/suggestion';
  static String communityRule = '/community_rule';
  static String paymentReminder = '/payment_reminder';
  static String paymentReminderDetail = '/payment_reminder/detail';
  static String attachProofPayment = '/payment_reminder/attach_proof_payment';

  static String getAnswer(int faqId) =>
      '$faqAnswer?faqId=$faqId';
  static String getAnnouncementDetail(int detailId) =>
      '$announcementDetail?detailId=$detailId';
  static String getFacilityBooking(int facilityId) =>
      '$facilityBooking?facilityId=$facilityId';
  static String getNewParcel(int parcelId) => '$newParcel?parcelId=$parcelId';
  static String getHistoryParcel(int parcelId) =>
      '$historyParcel?parcelId=$parcelId';

  static List<GetPage> routes = [
    GetPage(name: welcome, page: () => const WelcomePage()),
    GetPage(name: signIn, page: () => const SignInPage()),
    GetPage(name: confirmSignIn, page: () => const ConfirmSigninPage()),
    GetPage(name: verifyOtp, page: () => const VerifyOtpPage()),
    GetPage(name: inprogressSignIn, page: () => const InprogressSignInPage()),
    GetPage(name: communityConfirm, page: () => const CommunityConfirmPage()),
    GetPage(name: signUp, page: () => const SignUpPage()),
    GetPage(name: home, page: () => const HomePage()),
    GetPage(name: social, page: () => const SocialPage()),
    GetPage(name: announcement, page: () => const AnnouncementPage()),
    GetPage(
      name: announcementDetail,
      page: () {
        var detailId = Get.parameters['detailId'];
        return AnnouncementDetailPage(detailId: int.parse(detailId!));
      },
      transition: Transition.fadeIn
    ),
    GetPage(name: setting, page: () => const SettingPage()),
    GetPage(name: personalInfo, page: () => const PersonalInfoPage()),
    GetPage(name: namePersonalInfo, page: () => const NamePersonalInfoPage()),
    GetPage(name: genderPersonalInfo, page: () => const GenderPersonalInfoPage()),
    GetPage(name: birthDatePersonalInfo, page: () => const BirthDatePersonalInfoPage()),
    GetPage(name: phoneNumberPersonalInfo, page: () => const PhoneNumberPersonalInfoPage()),
    GetPage(name: emailPasswordPersonalInfo, page: () => const EmailPasswordPersonalInfoPage()),
    GetPage(name: emailPersonalInfo, page: () => const EmailPersonalInfoPage()),
    GetPage(name: deleteAccountPersonalInfo, page: () => const DeleteAccountPersonalInfoPage()),
    GetPage(name: notifications, page: () => const NotificationsPage()),
    GetPage(name: appVersion, page: () => const AppVersionPage()),
    GetPage(name: faq, page: () => const FaqPage()),
    GetPage(
      name: faqAnswer,
      page: () {
        var faqId = Get.parameters['faqId'];
        return FaqDetailPage(
          faqId: int.parse(faqId!)
        );
      },
      transition: Transition.fadeIn,
    ),
    GetPage(name: privacyPolicy, page: () => const PrivacyPolicyPage()),
    GetPage(name: termService, page: () => const TermServicePage()),
    GetPage(name: settingSuggestion, page: () => const SettingSuggestionPage()),
    GetPage(name: authAccess, page: () => const AuthAccessPage()),
    GetPage(name: chat, page: () => const ChatPage()),
    GetPage(name: parcel, page: () => const ParcelPage()),
    GetPage(
      name: newParcel,
      page: () {
        var parcelId = Get.parameters['parcelId'];
        return NewParcelPage(parcelId: int.parse(parcelId!));
      },
      transition: Transition.fadeIn,
    ),
    GetPage(
      name: historyParcel,
      page: () {
        var parcelId = Get.parameters['parcelId'];
        return HistoryParcelPage(parcelId: int.parse(parcelId!));
      },
      transition: Transition.fadeIn,
    ),
    GetPage(name: facility, page: () => const FacilityPage()),
    GetPage(
      name: facilityBooking,
      page: () {
        var facilityId = Get.parameters['facilityId'];
        return FacilityBookingPage(facilityId: int.parse(facilityId!));
      },
      transition: Transition.fadeIn
    ),
    GetPage(name: myUnit, page: () => const MyUnitPage()),
    GetPage(name: invitationCode, page: () => const InvitationCodePage()),
    GetPage(name: addUnit, page: () => const AddUnitPage()),
    GetPage(name: repair, page: () => const RepairPage()),
    GetPage(name: requestRepair, page: () => const RequestRepairPage()),
    GetPage(name: phoneBook, page: () => const PhoneBookPage()),
    GetPage(name: propertyManagement, page: () => const PropertyManagementPage()),
    GetPage(name: suggestion, page: () => const SuggestionPage()),
    GetPage(name: communityRule, page: () => const CommunityRulePage()),
    GetPage(name: paymentReminder, page: () => const PaymentReminderPage()),
    GetPage(name: paymentReminderDetail, page: () => const PaymentReminderDetailPage()),
    GetPage(name: attachProofPayment, page: () => const AttachProofPaymentPage())
  ];
}
