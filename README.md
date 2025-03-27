# Beercraft 🍺

## Description

Beercraft est une application web permettant de gérer et découvrir différentes bières artisanales. Elle offre une interface pour consulter, ajouter et gérer une collection de bières.

## Fonctionnalités

- Catalogue de bières artisanales
- Système d'authentification utilisateur
- Gestion des bières (CRUD)
- Interface responsive
- Système de notation et commentaires

## Technologies utilisées

- PHP
- MySQL
- HTML5/CSS3
- JavaScript
- Bootstrap

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache)

## Installation

1. Clonez le repository

```bash
git clone https://github.com/yourusername/beercraft.git
cd beercraft
```

2. Installez les dépendances

```bash
composer install
```

3. Configurez l'environnement
   Copiez le fichier `.env.example` en `.env` et modifiez les paramètres de configuration nécessaires.

4. Générez la clé de l'application

```bash
php artisan key:generate
```

5. Exécutez les migrations de base de données

```bash
php artisan migrate
```

6. Démarrez le serveur de développement

```bash
php artisan serve
```

## Utilisation

Accédez à l'application via `http://localhost:8000` et commencez à explorer les différentes fonctionnalités de Beercraft.

## Contribuer

Les contributions sont les bienvenues ! Veuillez suivre les étapes ci-dessous pour contribuer :

1. Forkez le repository
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Commitez vos modifications (`git commit -m 'Add some AmazingFeature'`)

- Password hashing
- Input sanitization
- CSRF protection
- Secure session management
- Role-based access control

## 🚀 Getting Started

### Prerequisites

- Docker and Docker Compose
- Git
- Web browser

### Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/beercraft.git
cd beercraft
```

2. Start the Docker environment:

```bash
docker-compose up -d
```

3. Import the database schema:

```bash
docker exec -i mysql_container mysql -uroot -proot mydb < table.sql
```

4. Access the application:

- Website: http://localhost:8080
- PHPMyAdmin: http://localhost:8081

## 👥 User Roles

### Visitor

- Browse beers
- View details and comments
- Register for an account

### Member

- All visitor features
- Comment on beers
- Share beers

### Administrator

- All member features
- Manage beer catalog
- Moderate comments
- Manage user accounts

## 🎨 Design Philosophy

BeerCraft's interface is built with these principles:

- Clean and intuitive navigation
- Responsive design for all devices
- Consistent visual language
- Accessibility-first approach
- Performance optimization

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Contact & Support

- Issue Tracker: GitHub Issues
- Email: beercraft@outlook.com
- Website: [www.beercraft.com](https://www.beercraft.com)

## 🙏 Acknowledgments

- TailwindCSS team for the amazing CSS framework
- Docker team for containerization tools
- Open-source community for inspiration and support

---

Made by Hyerox
