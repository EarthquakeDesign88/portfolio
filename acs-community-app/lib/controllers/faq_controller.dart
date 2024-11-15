import 'package:get/get.dart';
import 'package:acs_community/models/faq_model.dart';
import 'package:acs_community/services/api_service.dart';
import 'package:logger/logger.dart';

class FaqController extends GetxController {
  final ApiService _apiService = ApiService();
  final RxList<Faq> faqLists = <Faq>[].obs;
  final Logger logger = Logger();

  @override
  void onInit() {
    super.onInit();
    fetchFaq();
  }

  Future<void> fetchFaq() async {
    try {
      final List<Faq> faqs = await _apiService.getFaq();
      faqLists.assignAll(faqs);
    } catch (e) {
      logger.e('Error fetching faqs: $e');
    }
  }

  int getFaqCount() {
    return faqLists.length;
  }

  Faq? fetchFaqAnswer(int faqId) {
    return faqLists.firstWhere((faq) => faq.id == faqId);
  }
}
