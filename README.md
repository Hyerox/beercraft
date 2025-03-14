# 🍺 BeerCraft

BeerCraft is a vibrant community-driven platform where beer enthusiasts can discover, rate, and discuss their favorite beers. Built with modern web technologies, it offers a seamless experience for both casual users and dedicated beer connoisseurs.

![BeerCraft Preview](/www/images/accueil.png)

## 📋 Overview

BeerCraft allows users to:

- Browse an extensive catalog of beers from around the world
- View detailed information about each beer
- Share thoughts and ratings through a commenting system
- Filter beers by origin
- Share favorite beers on social media
- Manage their user profile

## 🌟 Key Features

### For Visitors

- **Browse Beers**: Explore our comprehensive beer catalog
- **Search & Filter**: Find beers by origin or type
- **View Details**: Access detailed information about each beer
- **Responsive Design**: Enjoy a seamless experience on any device

### For Members

- **Personal Account**: Create and manage your profile
- **Interactive Features**: Rate and comment on beers
- **Social Sharing**: Share favorite beers with friends

### For Administrators

- **Content Management**: Add, edit, or remove beers
- **User Management**: Manage user accounts and roles
- **Comment Moderation**: Monitor and moderate user comments
- **Analytics Dashboard**: Track user engagement and popular beers

## 🛠 Technical Details

### Technology Stack

- **Frontend**:

  - HTML5
  - TailwindCSS for modern, responsive styling
  - JavaScript for interactive features
  - Mobile-first approach

- **Backend**:

  - PHP 8.2
  - MySQL 8.0 for data persistence
  - PDO for secure database operations
  - Session-based authentication

- **Development Environment**:
  - Docker & Docker Compose
  - PHPMyAdmin for database management
  - Git for version control

### Security Features

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
- Rate beers
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

Made with ❤️ by BeerCraft Team
