import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/AuthProvider.dart';
import 'RegisterScreen.dart';


class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _loading = false;

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return Scaffold(
      appBar: AppBar(title: const Text("Connexion")),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextField(
              controller: _emailController,
              decoration: const InputDecoration(labelText: "Email"),
            ),
            const SizedBox(height: 10),
            TextField(
              controller: _passwordController,
              decoration: const InputDecoration(labelText: "Mot de passe"),
              obscureText: true,
            ),
            const SizedBox(height: 20),
            _loading
                ? const Center(child: CircularProgressIndicator())
                : ElevatedButton(
              onPressed: () async {
                setState(() => _loading = true);
                try {
                  await authProvider.login(
                    _emailController.text,
                    _passwordController.text,
                  );

                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text("Connexion réussie")),
                  );

                  Navigator.pushReplacementNamed(context, "/home");
                } catch (e, stackTrace) {
                  // Affichage complet des erreurs dans le terminal
                  print("Erreur lors de la connexion: $e");
                  print(stackTrace);

                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text("Erreur: $e")),
                  );
                } finally {
                  setState(() => _loading = false);
                }
              },
              child: const Text("Se connecter"),
            ),
            const SizedBox(height: 10),
            Center(
              child: TextButton(
                onPressed: () {
                  Navigator.pushReplacement(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const RegisterScreen()),
                  );
                },
                child: const Text("Pas de compte ? Créez un compte"),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
