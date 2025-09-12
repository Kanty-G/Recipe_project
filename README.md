# Recipe Project

A **web-based recipe management application** built with PHP, MySQL, and Apache.  

---

## 1. Prerequisites  
- MAMP (Apache & MySQL)  
- Copy the `recipe_project` folder into the `htdocs` directory of MAMP.  

---

## 2. Database Setup  
1. Start Apache and MySQL via MAMP.  
2. Open [phpMyAdmin](http://localhost/phpmyadmin).  
3. Create a database named **`projet2`** and import the SQL files in `recipe_project/sql/`:  
   - **`recettes.sql`** → stores recipes (15 preloaded recipes, modifiable only by admin).  
   - **`accounts.sql`** → stores user accounts.  
   - **`sessions.sql`** → stores active sessions.  

---

## 3. Launching the Application  
- Open: [http://localhost/TP2/pages/landing.php](http://localhost/recipe_project/pages/landing.php)  
- **Admin login**:  
  - Email: `admin@admin.com`  
  - Password: `ift3225`  

---

## 4. REST API Routes  
**Base URL**: `http://localhost/recipe_project/api/`  

- **GET** `recette/recherche.php` → Search recipes (supports keyword, category, user filter, pagination).  
- **POST** `recette/add.php` → Add a new recipe.  
- **UPDATE** `recette/modify.php` → Edit a recipe (owner or admin only).  
- **DELETE** `recette/del_one.php` → Delete a recipe.  
- **GET** `recette/last_updated.php` → Get the last update timestamp of recipes.  

---
