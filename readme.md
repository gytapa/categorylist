<h1>Category viewer</h1>
Web application that views category of trees in two ways : iterationaly and recursively.

How to run application:
1. Download the project
2. Create database with dummy data from database dump inside /mysqldump folder
3. Change database settings in project's .env file to match your database username,password,and database address
4. If changing the credentials in .env file does not work, please try to change them inside config\database.php file.

In the homepage you have two options: View list recursively or iterationaly. Both lists view the same data, but in different order (due to stack and it being LIFO). To add new category click "Add new category button" near each category. Which button you click matters, because the created category will be the "child" category of the button you pressed. If you want to create category without any parent - simply click the first Add Category button (It has a message saying about it)
