# CSV Import to WordPress Posts

This project demonstrates how to import data from a CSV file into WordPress posts. It uses the Hestia theme with a custom child theme to manage the import process and display the imported posts.

## **Overview**

The project includes a WordPress site with a custom child theme for importing data from a CSV file into WordPress posts. The main functionalities include importing CSV data, using a custom page template for displaying posts, and applying custom styles.

## **Features**

- **CSV Import**: Import data from a CSV file into WordPress posts.
- **Custom Page Template**: A page template for displaying the imported posts.
- **Custom Styles**: Additional CSS styles for customizing the appearance of the imported data.
- **Responsive Design**: The page template is designed to be responsive and mobile-friendly.

## **Screenshots**

### CSV Import Process

![CSV Import Process](DB/CSV.PNG)

### Desktop View

![Desktop View](DB/Desktop%20View.png)

### Mobile View

![Mobile View](DB/Mobile%20View.png)

## **Folder Structure**

- **`wp-content/themes/hestia-child/`**: The child theme folder for the Hestia theme.
  - **`functions.php`**: Contains the main code for importing CSV data to WordPress posts.
  - **`custom-css/`**: Folder for custom CSS files.
    - **`custom-style.css`**: Custom stylesheet for additional styles.

- **`post.csv`**: CSV file located in the `wp-content/themes/hestia-child` folder. It contains the data to be imported into WordPress posts.

- **`template/`**: Contains custom page templates.
  - **`template-news.php`**: Custom page template used for displaying the imported posts.

- **`DB/`**: Folder containing images related to the CSV import process.
  - **`CSV.PNG`**: Screenshot of the CSV file data.
  - **`Desktop View.png`**: Screenshot of the desktop view of the imported data.
  - **`Mobile View.png`**: Screenshot of the mobile view of the imported data.

- **`csvpost.sql`**: SQL file for setting up the database schema for the CSV import process.

## **How It Works**

1. **CSV Import**:
   - The `functions.php` file contains code to read data from the `post.csv` file and create WordPress posts from the CSV data.

2. **Custom Page Template**:
   - `template-news.php` is a custom page template for displaying all imported posts.
   - You can select this template when creating or editing a page in WordPress.

3. **Custom Styles**:
   - `custom-style.css` contains additional styles for the page template.

4. **Database Schema**:
   - Use the `csvpost.sql` file to create the necessary database tables and structure for the CSV import process.

## **Getting Started**

To set up the CSV Import to WordPress Posts:

1. **Clone the Repository**:
   ```sh
   git clone https://github.com/yourusername/csv-import-to-wordpress-posts.git
