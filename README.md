# Payments App — Frontend Flutter (Web & Mobile)

Application Flutter **complète** pour gérer des paiements et piloter un **tableau de bord** connecté à un backend Laravel (ou à une API **mock** intégrée pour tester sans serveur).

> **Points clés**
> - Dashboard **web & mobile** : **solde disponible**, **paiements réguliers** (Internet, Eau, Électricité…), **historique récent**.
> - **Paiements** : description, montant, justificatif (PDF/image), statut (pending/validated/failed), upload multipart.
> - **Historique & filtres** : par **jour**, **mois (YYYY-MM)** et **année**.
> - **Auth** : inscription, connexion, déconnexion (Bearer token, Laravel Sanctum).
> - **API simulée (mock)** activable/désactivable en 1 ligne pour une démo immédiate.

---

## 🧩 Fonctionnalités

### 1) Tableau de bord (web & mobile)
- **Solde disponible** (fictif par défaut, peut venir d’une API).
- **Paiements réguliers** : Internet, Eau, Électricité… bouton **Payer** qui **pré-remplit** la description et le montant.
- **Historique récent** (5 derniers paiements) avec statut coloré et accès au détail.
- **Accès rapide** vers l’historique complet et création d’un nouveau paiement.

### 2) Paiements
- Créer un paiement avec :
  - **Description** (ex. *Internet mois d’août 2025*),
  - **Montant**,
  - **Justificatif** PDF/JPG/PNG (optionnel).
- Le paiement transite soit par l’API Laravel **réelle**, soit par l’API **mock** (simulation 80% “validated”, 20% “failed”).  
- Après validation, il apparaît **immédiatement** dans l’historique.

### 3) Historique & filtres
- Liste paginée et filtrable par **jour**, **mois**, **année**.
- Compatible avec l’API Laravel donnée (query params `day`, `month` = `YYYY-MM`, `year`).

### 4) Authentification
- **Register**, **Login**, **Logout** avec **token** stocké en **secure storage**.
- En-tête `Authorization: Bearer <token>` géré automatiquement.

---

## 🏗️ Architecture du code (extrait)

```
lib/
├─ main.dart
├─ core/
│  ├─ constants.dart          # BASE_URL, USE_MOCK_API, etc.
│  └─ utils.dart              # formatage dates & monnaie
├─ models/
│  ├─ user.dart
│  ├─ payment.dart
│  └─ recurring_payment.dart
├─ services/
│  └─ api_service.dart        # Dio + API mock intégrée
├─ providers/
│  ├─ auth_provider.dart      # état auth (Riverpod)
│  └─ payment_provider.dart   # état paiements + filtres
├─ screens/
│  ├─ splash_screen.dart
│  ├─ auth/
│  │  ├─ login_screen.dart
│  │  └─ register_screen.dart
│  ├─ dashboard_screen.dart   # Dashboard web & mobile
│  └─ payments/
│     ├─ payments_list_screen.dart
│     ├─ payment_form_screen.dart
│     └─ payment_detail_screen.dart
└─ widgets/
   └─ loading_widget.dart
```

**Techniques** : Flutter 3+, Riverpod, Dio, Secure Storage, File Picker, Intl, Url Launcher.

---

## ⚙️ Prérequis

- Flutter **3+** (`flutter --version`)  
- (Optionnel) Android SDK / Xcode selon plateforme
- Backend Laravel (si vous désactivez le mock) **ou** rien (mock = **ON** par défaut)

---

## 🚀 Démarrage rapide

1. **Installer les dépendances**
   ```bash
   flutter pub get
   ```

2. **Configurer la cible API**  
   Ouvrir `lib/core/constants.dart` :
   ```dart
   class Constants {
     static const BASE_URL = 'http://localhost:8000'; // ou http://10.0.2.2:8000 pour Android emulator
     static const API_BASE = '$BASE_URL/api';

     // API simulée (mock) : true = sans backend ; false = Laravel réel
     static const bool USE_MOCK_API = true;
     static const int MOCK_DELAY_MS = 900;
   }
   ```
   - **Mock ON** (`true`) : vous pouvez tester immédiatement sans serveur.
   - **Mock OFF** (`false`) : pointez `BASE_URL` vers votre Laravel.

3. **Lancer l’app**
   - **Mobile** : `flutter run`
   - **Web (Chrome)** : `flutter run -d chrome`  
     (Option : `--web-renderer canvaskit` pour un meilleur rendu).

---

## 🧪 Scénarios de test (démo)

- **Dashboard** → vérifiez le **solde**, la **liste des paiements réguliers** et l’**historique récent**.  
- **Payer un service régulier** : depuis “Paiements réguliers” → **Payer** → formulaire **pré-rempli** → **Envoyer**.  
- **Historique** → ouvrez la page **Historique** → testez les **filtres** (jour, mois, année).  
- **Justificatif** → attachez un **PDF** ou **image** (max ~5 Mo côté backend).  
- **Auth** → créez un compte, connectez-vous, déconnectez-vous.

---

## 🔌 Intégration avec Laravel (si `USE_MOCK_API=false`)

### Endpoints attendus
- `POST /api/register` → body: `name`, `email`, `password`, `password_confirmation` → **retourne** `{ user, token }`
- `POST /api/login` → body: `email`, `password` → **retourne** `{ user, token }`
- `POST /api/logout` (auth) → révoque le **token courant**
- `GET /api/payments` (auth) → supporte `day`, `month (YYYY-MM)`, `year`, `page`
- `POST /api/payments` (auth) → multipart: `description`, `amount`, `receipt?` (pdf/jpg/png)
- `GET /api/payments/{id}` (auth)
- `DELETE /api/payments/{id}` (auth)
- `GET /api/payments/{id}/receipt` (auth) → `{ url: "..." }`

### Tips Laravel
- **Tokens** : Sanctum / personal access tokens (`createToken('api-token')`).
- **CORS** : autoriser l’origine (surtout pour **web**).
- **Fichiers** : `php artisan storage:link` puis servir `public/storage`.  
  Vérifier `FILESYSTEM_DISK=public` et droits d’accès.  
- **Uploads** : ajuster `upload_max_filesize` et `post_max_size` si besoin.
- **Android emulator** : utilisez `http://10.0.2.2:8000` au lieu de `http://localhost:8000`.

---

## 🧱 Design & responsive

- **Responsive** : layout en **double colonne** sur grands écrans, **une colonne** sur mobile.
- **Statuts** : `validated` (vert), `failed` (rouge), `pending` (orange).
- **Accessibilité** : taille de police lisible, contraste suffisant, labels de formulaire explicites.

---

## 🛠️ Scripts utiles

```bash
# Lancer en web
flutter run -d chrome --web-renderer canvaskit

# Build Web (dossier build/web)
flutter build web

# Build APK Android
flutter build apk --split-per-abi

# Build AppBundle (Play Store)
flutter build appbundle
```

---

## 🧰 Personnalisation

- **Couleurs / thème** : dans `main.dart` → `ThemeData(...)`.
- **Locale FR** : `Intl` est déjà utilisé (`fr_FR`) pour dates/monnaie.
- **Désactiver le mock** : `USE_MOCK_API=false` (fichier `constants.dart`).

---

## ❓Dépannage (FAQ)

- **CORS en Web** : activer CORS dans Laravel ; vérifier l’URL exacte (HTTP/HTTPS).  
- **Android ne se connecte pas à Laravel local** : utilisez `10.0.2.2` (pas `localhost`).  
- **Justificatif inaccessible** : exécuter `php artisan storage:link` et vérifier `asset('storage/...')`.  
- **Erreur 413 (Payload Too Large)** : augmenter `upload_max_filesize` & `post_max_size`.  
- **Mixed content HTTPS** : si votre site est en HTTPS, servez l’API et les fichiers aussi en HTTPS.

---

## 🧪 Stack technique

- **Flutter** (UI), **Riverpod** (state), **Dio** (HTTP), **Secure Storage** (token), **File Picker** (justificatifs), **Intl** (locales), **Url Launcher** (ouverture reçu).

---

## 📄 Licence

MIT – Utilisation libre dans un cadre pédagogique / démo technique.
