# Team Task Manager – WordPress Plugin

## 📌 Overview
This WordPress plugin powers the backend of the **Team Task Manager** system, which supports a React-based frontend via custom REST APIs and secure JWT authentication.

It enables management of **teams** and **tasks**, along with role-based access control using WordPress user roles (Administrator and Team Member).

---

## 🎯 Purpose
- Expose secure REST API endpoints for team and task management
- Authenticate users using JWT tokens
- Support role-based access to control API visibility

---

## 🧱 Plugin Structure
```
team-task-manager/
├── team-task-manager.php        # Main plugin entry point
├── api/
│   ├── auth-routes.php          # JWT login/logout routes
│   └── rest-routes.php          # Custom task/team REST APIs
├── admin/
│   └── admin-pages.php          # (Optional) Admin interface (future)
├── includes/
│   └── uninstall.php            # Table cleanup on uninstall
```

---

## 🔐 Authentication Endpoints (`auth-routes.php`)
| Method | Endpoint                          | Description                                         |
|--------|-----------------------------------|-----------------------------------------------------|
| POST   | `/wp-json/jwt-auth/v1/token`      | Login with username & password → returns JWT token |
| GET    | `/wp-json/wp/v2/users/me`         | Get current logged-in user info (requires token)   |

✅ Includes a `rest_prepare_user` filter to expose roles:
```php
add_filter('rest_prepare_user', function ($response, $user, $request) {
  if (get_current_user_id() === $user->ID) {
    $response->data['roles'] = $user->roles;
  }
  return $response;
}, 10, 3);
```

---

## 📁 Team Endpoints (`rest-routes.php`)
Namespace: `/wp-json/teamtask/v1/`

### 🧩 Team Routes
| Method | Endpoint           | Description                |
|--------|--------------------|----------------------------|
| GET    | `/teams`           | Fetch all teams            |
| POST   | `/teams`           | Create a new team          |
| PUT    | `/teams/:id`       | Update team (if added)     |
| DELETE | `/teams/:id`       | Delete team (if added)     |

### 📋 Task Routes
| Method | Endpoint           | Description                |
|--------|--------------------|----------------------------|
| GET    | `/tasks`           | Fetch all tasks            |
| POST   | `/tasks`           | Create a new task          |
| PUT    | `/tasks/:id`       | Update task (if added)     |
| DELETE | `/tasks/:id`       | Delete task (if added)     |

---

## 🔒 Role-Based API Protection (Optional)
Restrict routes to only admins like this:
```php
'permission_callback' => function () {
  $user = wp_get_current_user();
  return in_array('administrator', $user->roles);
}
```
Example usage on a route:
```php
register_rest_route('teamtask/v1', '/teams', [
  'methods' => 'GET',
  'callback' => 'get_teams',
  'permission_callback' => function () {
    return current_user_can('edit_users');
  }
]);
```

---

## ✅ Summary of All Key Endpoints
| Category         | Example Endpoint                    |
|------------------|--------------------------------------|
| 🔐 Login          | `/wp-json/jwt-auth/v1/token`         |
| 🔍 Current User   | `/wp-json/wp/v2/users/me`            |
| 📁 Teams          | `/wp-json/teamtask/v1/teams`         |
| 📝 Tasks          | `/wp-json/teamtask/v1/tasks`         |

---

## 🚀 Setup Instructions
1. Place the `team-task-manager` folder inside `wp-content/plugins/`
2. Activate the plugin from WP Admin → Plugins
3. Make sure CORS and JWT headers are added in `.htaccess` or `init` hook
4. Define the following in `wp-config.php`:
```php
define('JWT_AUTH_SECRET_KEY', 'your-strong-secret');
define('JWT_AUTH_CORS_ENABLE', true);
```
5. ✅ Add this to your `.htaccess` file above `# BEGIN WordPress` to ensure JWT and REST requests route correctly:
```apache
# Fix REST API for WP JSON + JWT
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^wp-json/?$ index.php?rest_route=/ [L,QSA]
RewriteRule ^wp-json/(.*)? index.php?rest_route=/$1 [L,QSA]
</IfModule>
```

---

## 📫 Support
For developer or HR review, please refer to the paired React app `README.md` and test the REST APIs using Postman.

Contact: `mayankpadhi91@gmail.com`

---

_This plugin is part of the Team Task Manager full-stack assignment._
