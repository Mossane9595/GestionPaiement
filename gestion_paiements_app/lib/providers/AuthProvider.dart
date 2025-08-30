import 'package:flutter/material.dart';
import '../services/ApiService.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  String? _token;
  Map<String, dynamic>? _user;

  String? get token => _token;
  Map<String, dynamic>? get user => _user;
  bool get isAuthenticated => _token != null;

  /// Connexion
  Future<void> login(String email, String password) async {
    try {
      final response = await _apiService.postRequest("connexion", {
        "email": email,
        "mot_de_passe": password, // correspond au backend
      });

      if (response.containsKey("utilisateur")) {
        _user = response["utilisateur"];
      }

      notifyListeners();
    } catch (e, stackTrace) {
      print("Erreur lors de la connexion: $e");
      print(stackTrace);
      rethrow;
    }
  }

  /// Inscription avec confirmation du mot de passe
  Future<void> register(
      String nom, String email, String password, String confirmation) async {
    try {
      final response = await _apiService.postRequest("inscription", {
        "nom": nom,
        "email": email,
        "mot_de_passe": password,
        "mot_de_passe_confirmation": confirmation,
      });

      // Vérifie si la réponse contient l'utilisateur
      if (response.containsKey("utilisateur") && response["utilisateur"] != null) {
        _user = response["utilisateur"];
        notifyListeners();
      } else {
        throw Exception("Impossible de récupérer les informations utilisateur");
      }
    } catch (e, stackTrace) {
      print("Erreur lors de l'inscription: $e");
      print(stackTrace);
      rethrow;
    }
  }


  /// Déconnexion
  void logout() {
    _token = null;
    _user = null;
    notifyListeners();
  }
}
