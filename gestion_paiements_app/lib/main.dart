// lib/main.dart
import 'package:flutter/material.dart';
import 'package:gestion_paiements_app/providers/AuthProvider.dart';
import 'package:gestion_paiements_app/screens/LoginScreen.dart';
import 'package:gestion_paiements_app/screens/RegisterScreen.dart';
import 'package:gestion_paiements_app/screens/home_screen.dart';
import 'package:provider/provider.dart';
void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
      ],
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Laravel Auth',
      theme: ThemeData(primarySwatch: Colors.blue),
      initialRoute: "/login",
      routes: {
        "/login": (context) => const LoginScreen(),
        "/register": (context) => const RegisterScreen(),
        "/home": (context) => const HomeScreen(), // à créer ensuite
      },
    );
  }
}