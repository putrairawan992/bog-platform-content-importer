# Nex Digital / Goodcommerce

https://github.com/user-attachments/assets/2e3c3f84-0cd9-40c8-934d-ea3335d4a296

## Fullstack Developer Test

### Blog Platform with Content Importer

---

## **Objective**

Build a simple blog platform that can import content from external APIs.  
Show us your approach to API integration and data transformation.

## **Time Estimate**

This task should not take more than 4 hours.

---

## **Requirements**

### **Blog Features**

- Blog posts with: `title`, `content`, `status` (draft/published), `source`, `external_id`
- Admin panel to manage posts
- Public page to view published posts

### **Import Functionality**

- Import from **JSONPlaceholder API** — random blog post
- Import from **FakeStore API** — random product (transform to blog post)
- Single item import per execution
- Duplicate prevention
- Imported posts saved as drafts

### **APIs**

- **JSONPlaceholder**: `https://jsonplaceholder.typicode.com/posts/{randomId}`
- **FakeStore API**: `https://fakestoreapi.com/products/{randomId}`

---

## **The Challenge**

Transform two different data structures into consistent blog posts.

### **JSONPlaceholder Post Example**

```json
{
  "id": 1,
  "title": "blog post title", 
  "body": "blog post content"
}
```

### **FakeStore Product Example**

```json
{
  "id": 1,
  "title": "Product Name",
  "description": "Product description",
  "price": 109.95,
  "category": "product category"
}
```

Both should be transformed into blog posts in your system.

---

## **Setup**

```bash
git clone [repository]
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed  # Create default admin user
php artisan serve
```

### **Default Admin Credentials**
- Email: `admin@gmail.com`
- Password: `123`

---

## **Submission**

## Submission Instructions

1. Use this repository template
2. Build your solution
3. Commit with meaningful messages
4. Submit your repository URL for us to review
5. Include in your submission:
    - Total time spent
    - Explain your approach
    - Explain how you would add a new API Source
    - Propose improvement to your own code

---

## **Evaluation Focus**

- Problem-solving approach
- Code quality and structure
- User experience
- Commit history

Show us how you think.

---

## **Closing Note**

We look forward to reviewing your solution and seeing how you approach real-world API integration challenges.

---

## **Solution Documentation**

### **Total Time Spent**

Approximately 3 hours

### **My Approach**

I approached this project by breaking it down into clear, manageable steps:

1. **Database Design**: Created a flexible schema with source tracking, image support, and duplicate prevention through unique constraints
2. **Authentication System**: Implemented login/logout functionality with session-based auth to secure admin panel
3. **Service Layer Architecture**: Implemented `ImportService` to handle all API integrations, keeping controllers thin
4. **Data Transformation**: Built reusable transformation methods to convert different API responses into consistent blog post format
5. **Admin Interface**: Created a clean, user-friendly admin panel for managing posts and importing content
6. **User Experience**: Implemented clear feedback messages, duplicate prevention, and draft-first workflow

**Key Design Decisions**:
- Separated public and admin areas with authentication middleware
- Used service classes to separate business logic from controllers
- Implemented unique constraint on `source + external_id` for automatic duplicate prevention
- All imports default to draft status for review before publishing
- Preserved source information for traceability
- Session-based authentication for simplicity

### **How to Add a New API Source**

There are two ways to add content from a new API source:

#### **Option 1: Manual Import (No Code Required)**

The easiest way to import from any API is using the Manual Import feature:

1. Login to admin panel
2. Go to Import page
3. Click "Manual Import" tab
4. Enter:
   - **API URL**: Full URL to the API endpoint (e.g., `https://api.example.com/posts/1`)
   - **Source Name**: A unique identifier (e.g., `my-api`)
5. Click "Import from URL"

The system will automatically:
- Detect common field names (title, body, description, content, etc.)
- Extract image URLs if available
- Create the post as draft
- Prevent duplicates based on source + ID

**Supported Response Formats:**
```json
// Format 1
{ "id": 1, "title": "...", "body": "..." }

// Format 2
{ "id": 1, "title": "...", "description": "..." }

// Format 3
{ "id": 1, "title": "...", "content": "...", "image": "..." }
```

#### **Option 2: Programmatic Import (For Recurring Imports)**

Adding a new API source programmatically follows a consistent pattern:

#### **Step 1: Add Import Method to ImportService**

```php
// In app/Services/ImportService.php

public function importFromNewAPI(): array
{
    try {
        // 1. Generate random ID or fetch data
        $randomId = rand(1, 100);

        // 2. Make API request
        $response = Http::get("https://api.example.com/resource/{$randomId}");

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'Failed to fetch data from New API'
            ];
        }

        $data = $response->json();

        // 3. Check for duplicates
        $exists = Post::where('source', 'newapi')
            ->where('external_id', $data['id'])
            ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => 'This item has already been imported'
            ];
        }

        // 4. Transform data to blog post format
        $title = $data['title'];
        $content = $this->transformNewAPIToContent($data);
        $image = $data['image'] ?? null; // Extract image URL if available

        // 5. Create post
        $post = Post::create([
            'title' => $title,
            'content' => $content,
            'image' => $image,
            'status' => 'draft',
            'source' => 'newapi',
            'external_id' => $data['id'],
        ]);

        return [
            'success' => true,
            'message' => 'Content imported successfully from New API',
            'post' => $post
        ];

    } catch (\Exception $e) {
        Log::error('New API import failed: ' . $e->getMessage());

        return [
            'success' => false,
            'message' => 'Error importing from New API: ' . $e->getMessage()
        ];
    }
}

// Add transformation method
private function transformNewAPIToContent(array $data): string
{
    // Transform your data structure to blog post content
    return $data['description'] . "\n\n" . $data['additional_info'];
}
```

#### **Step 2: Add Controller Method**

```php
// In app/Http/Controllers/ImportController.php

public function importNewAPI()
{
    $result = $this->importService->importFromNewAPI();

    if ($result['success']) {
        return redirect()->route('admin.import.index')
            ->with('success', $result['message']);
    }

    return redirect()->route('admin.import.index')
        ->with('error', $result['message']);
}
```

#### **Step 3: Add Route**

```php
// In routes/web.php
Route::post('import/newapi', [ImportController::class, 'importNewAPI'])->name('import.newapi');
```

#### **Step 4: Add UI Card**

Add a new card in `resources/views/admin/import/index.blade.php` following the existing pattern.

### **Proposed Improvements**

1. **Async/Queue Processing**
   - Move API imports to queue jobs for better performance
   - Prevent timeout issues on slow API responses
   - Allow batch imports in background

2. **Enhanced Error Handling**
   - Implement retry mechanism with exponential backoff
   - Add logging for failed imports with detailed error tracking
   - Create admin dashboard to view import history and failures

3. **API Source Management**
   - Create database table for API source configurations
   - Allow adding new sources through admin panel without code changes
   - Store API credentials securely using Laravel's encryption

4. **Advanced Features**
   - Schedule automatic imports using Laravel Task Scheduler
   - Add webhooks support for real-time content sync
   - Implement content versioning for imported posts
   - Add API rate limiting protection

5. **Testing**
   - Add Feature tests for import functionality
   - Add Unit tests for data transformation methods
   - Mock API responses for reliable testing

6. **Content Enhancement**
   - Add support for importing images/media
   - Implement rich text editor for content editing
   - Add categories and tags support
   - Enable SEO metadata management

7. **Security & Validation**
   - Add authentication/authorization for admin panel
   - Implement CSRF protection verification
   - Add input sanitization for imported content
   - Rate limit import actions per user

8. **User Experience**
   - Add AJAX-based imports to avoid page reloads
   - Show import progress with loading indicators
   - Add preview before confirming import
   - Implement bulk actions for managing posts

---

## **Database Schema**

### Posts Table
```
- id (bigint, primary key)
- title (string)
- content (text)
- image (string, nullable) - URL to image
- status (enum: 'draft', 'published')
- source (string, nullable) - 'jsonplaceholder', 'fakestore', 'manual'
- external_id (string, nullable) - ID from external API
- created_at (timestamp)
- updated_at (timestamp)
- unique constraint on (source, external_id) - Prevents duplicates
```

### Users Table
```
- id (bigint, primary key)
- name (string)
- email (string, unique)
- password (hashed)
- remember_token (string)
- created_at (timestamp)
- updated_at (timestamp)
```

---

## **Available Routes**

### Public Routes
- `GET /` - Homepage with published posts
- `GET /post/{post}` - Single post view

### Authentication Routes
- `GET /login` - Login page
- `POST /login` - Handle login
- `POST /logout` - Handle logout

### Admin Routes (Protected - Requires Authentication)
- `GET /admin/posts` - Posts management dashboard
- `GET /admin/posts/create` - Create new post
- `GET /admin/posts/{post}/edit` - Edit post
- `POST /admin/posts` - Store new post
- `PUT /admin/posts/{post}` - Update post
- `DELETE /admin/posts/{post}` - Delete post

### Import Routes (Protected - Requires Authentication)
- `GET /admin/import` - Import interface with tabs (Manual & Automatic)
- `POST /admin/import/manual` - Import from custom URL
- `POST /admin/import/jsonplaceholder` - Import from JSONPlaceholder
- `POST /admin/import/fakestore` - Import from FakeStore

---

## **Features Implemented**

### ✅ Core Requirements
- [x] Blog posts with title, content, image, status, source, external_id
- [x] Admin panel to manage posts (CRUD operations)
- [x] Public page to view published posts
- [x] Import from JSONPlaceholder API (random blog post)
- [x] Import from FakeStore API (random product with image transformed to blog post)
- [x] Single item import per execution
- [x] Duplicate prevention (unique constraint on source + external_id)
- [x] Imported posts saved as drafts
- [x] Data transformation for different API structures
- [x] Image support from FakeStore API products

### ✅ Additional Features
- [x] Authentication system for admin panel
- [x] Login/Logout functionality
- [x] Default admin user seeder
- [x] Protected admin routes with middleware
- [x] Responsive design with Tailwind CSS
- [x] Flash messages for user feedback
- [x] Clean, intuitive UI/UX
- [x] Sticky footer layout
- [x] Source tracking and display
- [x] Image support with URL field (auto-imported from FakeStore API)
- [x] Image preview in admin panel
- [x] Responsive image display on homepage and post detail
- [x] Manual import via custom API URL (with auto-detection of response format)
- [x] Tab-based import interface (Manual vs Automatic)
- [x] 2-column grid layout for blog posts