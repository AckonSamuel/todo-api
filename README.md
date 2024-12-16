# Todo API

The Todo API is a RESTful service for managing tasks, enabling users to create, read, update, and delete todos. It includes endpoints for filtering, sorting, and searching todos, as well as clear error responses for invalid requests or server issues.

---

## Features

- **CRUD Operations**: Create, Read, Update, and Delete todos.
- **Filter & Sort**: Query todos by `status` and sort results by `title` or other fields.
- **Error Handling**: Provides detailed error messages for validation, not found, or server errors.
- **Scalability**: Supports extensibility for future features.

---

## Requirements

- PHP 8.1 or newer
- Laravel Framework 10.x
- Composer
- Database (MySQL/PostgreSQL/SQLite)
- Postman or any API testing tool for testing APIs

---

## Installation

1. **Install dependencies**:
   ```bash
   composer install
2. **Set up environment variables**:
   ```bash
   cp .env.example .env
3. **Generate application key**:
   ```bash
   php artisan key:generate   
   cp .env.example .env
4. **Run database migrations**:
   ```bash
   php artisan migrate
5. **Run seed data**:
   ```bash
   php artisan db:seed
6. **Run tests**:
   ```bash
   php artisan test
---

## Deployment
 [Api Documentation Link](https://pengion-todo-a4f1355a880e.herokuapp.com/docs)
## Author

👤 **Ackon Samuel**
- GitHub: [@AckonSamuel](https://github.com/AckonSamuel/)
- Twitter: [@AckonSamuel2](https://twitter.com/dude_ackon)
- LinkedIn: [LinkedIn](https://www.linkedin.com/in/dev-ackon/)

👤
## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

Feel free to check the [issues page](../../issues/).

## Show your support

Give a ⭐️ if you like this project!

