// lib/screens/home_screen.dart
import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import 'package:file_picker/file_picker.dart';
import '../providers/AuthProvider.dart';
import 'NewPaymentScreen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  double? solde;
  List paiements = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final user = authProvider.user;
    final token = authProvider.token;
    if (user == null || token == null) return;

    try {
      final soldeResponse = await http.get(
        Uri.parse("http://10.0.2.2:8000/api/comptes/${user['id']}/solde"),
        headers: {"Authorization": "Bearer $token"},
      );
      if (soldeResponse.statusCode == 200) {
        final data = jsonDecode(soldeResponse.body);
        solde = double.tryParse(data['solde'].toString());
      }

      final paiementResponse = await http.get(
        Uri.parse("http://10.0.2.2:8000/api/paiements"),
        headers: {"Authorization": "Bearer $token"},
      );
      if (paiementResponse.statusCode == 200) {
        paiements = jsonDecode(paiementResponse.body);
      }
    } catch (e) {
      debugPrint("Erreur API: $e");
    }

    setState(() {
      isLoading = false;
    });
  }

  Future<void> _showRechargeDialog() async {
    final _montantController = TextEditingController();
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final user = authProvider.user;
    final token = authProvider.token;
    if (user == null || token == null) return;

    await showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Recharger le compte"),
        content: TextField(
          controller: _montantController,
          keyboardType: TextInputType.number,
          decoration: const InputDecoration(labelText: "Montant"),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text("Annuler")),
          ElevatedButton(
            onPressed: () async {
              final montant = double.tryParse(_montantController.text);
              if (montant == null || montant <= 0) return;
              Navigator.pop(context);

              try {
                final uri = Uri.parse("http://10.0.2.2:8000/api/comptes/${user['id']}/recharger");
                final response = await http.post(uri, headers: {"Authorization": "Bearer $token"}, body: {"montant": montant.toString()});
                if (response.statusCode == 200) {
                  final data = jsonDecode(response.body);
                  setState(() => solde = data['solde']);
                  ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Compte rechargé avec succès")));
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Erreur lors du rechargement")));
                }
              } catch (e) {
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Erreur: $e")));
              }
            },
            child: const Text("Valider"),
          ),
        ],
      ),
    );
  }

  Future<void> _addAttachment(int paiementId) async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf', 'jpg', 'jpeg', 'png'],
    );
    if (result == null || result.files.single.path == null) return;

    final file = File(result.files.single.path!);
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final token = authProvider.token;
    if (token == null) return;

    final uri = Uri.parse("http://10.0.2.2:8000/api/paiements/$paiementId/pieces");
    final request = http.MultipartRequest('POST', uri);
    request.headers['Authorization'] = 'Bearer $token';
    request.files.add(await http.MultipartFile.fromPath('fichier', file.path));

    try {
      final response = await request.send();
      final respStr = await response.stream.bytesToString();
      final data = jsonDecode(respStr);
      if (response.statusCode == 200 || response.statusCode == 201) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Pièce jointe ajoutée avec succès")));
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Erreur: ${data['message'] ?? 'Impossible'}")));
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Erreur: $e")));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text("Tableau de bord"),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: fetchData)],
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
        onRefresh: fetchData,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            // Solde
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(color: Colors.blue[400], borderRadius: BorderRadius.circular(16)),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text("Solde du compte", style: TextStyle(color: Colors.white70, fontSize: 16)),
                  const SizedBox(height: 8),
                  Text("${solde ?? 0.0} CFA", style: const TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.bold)),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Actions
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                ElevatedButton.icon(
                  onPressed: () async {
                    final result = await Navigator.push(
                      context,
                      MaterialPageRoute(builder: (context) => const NewPaymentScreen()),
                    );
                    if (result == true) fetchData();
                  },
                  icon: const Icon(Icons.payment),
                  label: const Text("Paiement"),
                ),
                ElevatedButton.icon(
                  onPressed: _showRechargeDialog,
                  icon: const Icon(Icons.account_balance_wallet),
                  label: const Text("Recharger"),
                ),
              ],
            ),
            const SizedBox(height: 20),

            // Historique des paiements
            const Text("Historique des paiements", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),
            paiements.isEmpty
                ? const Text("Aucun paiement trouvé")
                : Column(
              children: paiements.map((paiement) {
                return Card(
                  margin: const EdgeInsets.symmetric(vertical: 6),
                  child: ListTile(
                    leading: const Icon(Icons.receipt_long),
                    title: Text(paiement['description'] ?? "Paiement"),
                    subtitle: Text("Montant: ${paiement['montant']} CFA\nStatus: ${paiement['statut']}"),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(paiement['created_at'].toString().substring(0, 10)),
                        IconButton(
                          icon: const Icon(Icons.attach_file),
                          onPressed: () => _addAttachment(paiement['id']),
                        ),
                      ],
                    ),
                  ),
                );
              }).toList(),
            ),
          ],
        ),
      ),
    );
  }
}
