# 📝 Todo MileMarker - Full-Stack Todo Application

A modern, feature-rich Todo application built with **Vue.js 3** and **Laravel 11**, showcasing full-stack development best practices with clean architecture, responsive design, and comprehensive functionality.

## 🚀 Features

### Core Functionality
- ✅ **Complete CRUD Operations** - Create, Read, Update, and Delete todos
- ✅ **User Authentication** - Secure registration and login system using Laravel Sanctum
- ✅ **Todo Management**
  - Mark todos as complete/incomplete
  - Add detailed descriptions
  - Set due dates with visual indicators
  - Assign todos to categories
  - Edit todos inline
- ✅ **Category System**
  - Create custom categories with color coding
  - Filter todos by category
  - Track todo count per category
- ✅ **Advanced Filtering & Sorting**
  - Search todos by title or description
  - Filter by status (complete, incomplete, overdue)
  - Filter by category
  - Sort by creation date, due date, or title
  - Ascending/descending order options
- ✅ **Responsive UI Design**
  - Clean, modern interface
  - Dark mode support
  - Mobile-friendly layout
  - Smooth transitions and animations
- ✅ **Real-time Visual Feedback**
  - Overdue todo highlighting
  - Completion status indicators
  - Loading states
  - Error handling with user feedback

## 🛠 Tech Stack

### Frontend
- **Vue.js 3** - Progressive JavaScript framework with Composition API
- **Tailwind CSS** - Utility-first CSS framework for styling
- **Axios** - Promise-based HTTP client
- **Vite** - Next-generation frontend build tool

### Backend
- **Laravel 11** - Modern PHP framework (LTS version)
- **SQLite** - Lightweight, file-based database
- **Laravel Sanctum** - API authentication system
- **Eloquent ORM** - Database abstraction layer

## 📦 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite3

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd todo-milemarker
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```
   
   Or for development with hot reload:
   ```bash
   npm run dev
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

## 🏗 Project Architecture

### Backend Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── AuthController.php      # Authentication endpoints
│           ├── TodoController.php      # Todo CRUD operations
│           └── CategoryController.php  # Category management
├── Models/
│   ├── User.php                        # User model with relationships
│   ├── Todo.php                        # Todo model with scopes
│   └── Category.php                    # Category model
database/
└── migrations/
    ├── *_create_users_table.php
    ├── *_create_categories_table.php
    └── *_create_todos_table.php
```

### Frontend Structure

```
resources/
├── js/
│   ├── components/
│   │   ├── TodoApp.vue          # Main application component
│   │   ├── TodoList.vue         # Todo list container
│   │   ├── TodoItem.vue         # Individual todo item
│   │   ├── TodoForm.vue         # Todo creation form
│   │   ├── FilterBar.vue        # Filtering and sorting controls
│   │   └── CategoryManager.vue  # Category CRUD interface
│   ├── composables/
│   │   ├── useAuth.js           # Authentication logic
│   │   ├── useTodos.js          # Todo state management
│   │   └── useCategories.js     # Category state management
│   └── app.js                   # Application entry point
└── css/
    └── app.css                  # Tailwind CSS imports
```

## 🔌 API Endpoints

For detailed API documentation with examples and request/response formats, see **[API.md](./API.md)**.

### Quick Reference

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Authenticate user
- `GET /api/user` - Get authenticated user
- `POST /api/logout` - Logout user

### Todos
- `GET /api/todos` - List all todos (with filtering)
- `POST /api/todos` - Create a new todo
- `GET /api/todos/{id}` - Get a specific todo
- `PUT /api/todos/{id}` - Update a todo
- `DELETE /api/todos/{id}` - Delete a todo
- `POST /api/todos/{id}/toggle` - Toggle completion status

### Categories
- `GET /api/categories` - List all categories
- `POST /api/categories` - Create a new category
- `PUT /api/categories/{id}` - Update a category
- `DELETE /api/categories/{id}` - Delete a category

## 💡 Technical Decisions & Best Practices

### Architecture Choices

1. **RESTful API Design**
   - Clear, predictable endpoint structure
   - Proper HTTP verbs and status codes
   - Consistent JSON response format

2. **Separation of Concerns**
   - Controllers handle HTTP logic
   - Models contain business logic and relationships
   - Composables manage frontend state
   - Components focus on presentation

3. **Authentication Strategy**
   - Laravel Sanctum for SPA authentication
   - Token-based API authentication
   - Secure password hashing

4. **Database Design**
   - Normalized schema with proper relationships
   - Foreign key constraints
   - Indexed columns for performance

### Code Quality

- **DRY Principle** - Reusable composables and components
- **Single Responsibility** - Each component/class has one job
- **Input Validation** - Both frontend and backend validation
- **Error Handling** - Graceful error handling with user feedback
- **Security** - CSRF protection, SQL injection prevention, XSS protection

### Frontend Patterns

- **Composition API** - Modern Vue.js approach for better code organization
- **Reactive State Management** - Using Vue's ref and reactive
- **Component Communication** - Props down, events up pattern
- **Async/Await** - Clean asynchronous code handling

### Backend Patterns

- **Eloquent ORM** - Query builder and relationships
- **Query Scopes** - Reusable query logic
- **Resource Controllers** - Standard CRUD operations
- **Middleware** - Authentication and request handling

## 🎨 Design Features

- **Dark Mode Support** - Automatic theme detection
- **Responsive Layout** - Mobile-first design approach
- **Visual Feedback** - Loading states, transitions, hover effects
- **Color Coding** - Category colors for visual organization
- **Intuitive UX** - Clear CTAs, inline editing, confirmation dialogs

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 🔒 Security Features

- Password hashing with bcrypt
- CSRF protection
- SQL injection prevention via Eloquent ORM
- XSS protection through Vue.js
- API authentication with Sanctum tokens
- Input validation and sanitization

## 🚀 Future Enhancements

Potential features for future development:
- [ ] Todo sharing between users
- [ ] Recurring todos
- [ ] File attachments
- [ ] Email notifications
- [ ] Todo templates
- [ ] Analytics dashboard
- [ ] Mobile apps (iOS/Android)
- [ ] PWA support
- [ ] Collaboration features
- [ ] Export/import functionality

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

Built as a demonstration of full-stack development capabilities using modern web technologies and best practices.

---

**Note**: This is a portfolio project demonstrating proficiency in Vue.js, Laravel, RESTful API design, and modern web development practices.

