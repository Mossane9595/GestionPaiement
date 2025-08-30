// lib/services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  final String baseUrl = "http://192.168.1.58:8000/api"; // adapte ton URL

  Future<dynamic> postRequest(String endpoint, Map<String, dynamic> data) async {
    final url = Uri.parse("$baseUrl/$endpoint");
    try {
      final response = await http.post(
        url,
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
        },
        body: jsonEncode(data),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        return jsonDecode(response.body);
      } else {
        throw Exception("Erreur API: ${response.body}");
      }
    } catch (e) {
      throw Exception("Erreur de connexion: $e");
    }
  }

  Future<dynamic> getRequest(String endpoint) async {
    final url = Uri.parse("$baseUrl/$endpoint");
    try {
      final response = await http.get(url, headers: {
        "Accept": "application/json",
      });

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        throw Exception("Erreur API: ${response.body}");
      }
    } catch (e) {
      throw Exception("Erreur de connexion: $e");
    }
  }
}
