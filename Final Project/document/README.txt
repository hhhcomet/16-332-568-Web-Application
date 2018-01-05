********STOCKFORECASTER*******
Developed By:
Rong Zhang
Jingxuan Chen
Xiuqi Ye
Feng Rong
Xiaoyi Tang
Zhe Chang

--------------------------------------


Our work is based on mySQL and PHP, and all the tests are based on XAMPP. To run our code, please install XAMPP first.

Here is the website to download your XAMPP: https://www.apachefriends.org/index.html

For all parts running, there are two steps necessary.

1. Install XAMPP and run it. Start module "Apache" and  "MySQL".(If you can't start "Apache", please check the problem and stop the process which uses the port)

2. Copy our code files (all the code folder) to folder "htdocs" under your XAMPP installation folder.(i.e. C:\xampp\htdocs)

---------------------------------------

For database part, here are the steps to run our code.

1.Open an explorer(our test on chrome), type address "http://localhost/code/data/page1.php".

2.There are four links in that page. Please click them in order.

3.Click the first link to create the database and there will be a successful notification.

4.Click the second link to update current price. Because this php file can work in background to update current data every minute,and will last as long as you want (until you stop the services),you may find the page will never load successfully. Just ignore it(you can close the page or do something else), the code will run background.

5.Click the third link to update historical price. This page will load a little slowly, please wait patiently until the page load completely. Ignore the notices in the result page.

6.Click the fourth link to start Email notification service. This php file is also work in background, so just close this page.

7.You can click "Admin" of "mySQL" in XAMPP to reach the homepage of phpmyAdmin or type the address "hittp://localhost/phpmyadmin" to read this site. In the left part of the page, there is a database called "stockdatabase" and all the data are stored in it. You can use this site to search oruse the database, and export the database in .sql or .csv format.

Note: If you want to export on kind of stock data, you should search the symbol(i.e. GOOG) first then export the data you want.

-----------------------------------------

For the site part, here are the steps to access and use our stock forecaster website. NOTE: Please get access to our website after you create the database!

1.Type the address "http://localhost/code/run/homepage.html".

2.Click "sign up" or "join now!" to create your account.

3.After sign up, log in to the website.

4.Now you can check the contents of our website. In most parts, you can click the symbol name to check the specific contents of it.

5.As our special feature, you can click the Email page to add a stock symbol to your account and a price to send you the notification.

------------------------------------------

We use neural network to predict the long term price and use Beyesian curve fitting and SVM to predict the short term price.

