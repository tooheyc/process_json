# process_json

INSTALLATION INSTRUCTIONS

This is a simple project that should work on most versions of PHP, but it has been tested on PHP Version 7.1.8 and PHP Version 7.2.2, so please use one of those. Either clone or copy the zip file to your server's root directory from github:
https://github.com/tooheyc/process_json

The only library it uses is jQuery and the zip file has a preconfigured .htaccess file, so there should be no need to configure anything. Simply type the URL of the site into a browser and read the instructions for sorting and choosing data sets. Note that Javascript is required, so please check that your browser has it on.

The data sources are located (with the exception of the first one at https://x-24.io/DevTestData.json ) in the tests directory.

USE

When the page first loads, it will display a single table of the 24 Data JSON file sorted by last name, in descending order. There will be two buttons "Ascending” and "Descending” for changing the sort order, and selecting one of the table's three column headers will change the sort to that column. The current sort direction and column are in dark blue with white text.

Changing from one data source to another (there are 3 additional test cases provided) will retain the current sort, except for “Test case 2”, which has malformed json, so it will not sort. You can “fix” “Test case 2” by adding an opening ( [ ) bracket. That test case and “Test case 3” have names starting with UTF8 characters above ascii, demonstrating that the sort functions correctly with multibyte characters. Example, in “Test case 3”, “ÞThomas” correctly appears before “ßMax” in an ascending sort.

The added test cases each have some invalid rows, and those are displayed in a second, sortable “error” table located below the first.

Should you switch between the other test cases, the current sort for both tables will always be the column and direction used on the last sort, whichever table that was done on. For example, if you sort Test case 3 by Age, in ascending order, on one of the tables, and then switch to Test case 1, the sort will be Age, in ascending order, for the desired table and the error table. Changing the sort direction is applied to the most recently sorted table, so you can change a sort direction without altering the other table.

COMMENTS

Production environment means different things in different places, and so I've tried to make this as complete as possible without generating enormous amounts of code for a small project. There's always something more you can add, but you have to stop somewhere. Often, straight PHP code is not considered production code because without a framework the security risk is high. 

What I've written simulates an MVC framework but uses a mix of OOP and procedural code. The controllers, for example, are not objects, but they instantiate objects. If this were a larger project and still going to remain straight php, I'd spend more time building out my framework, adding models and enhancing view code. I'd probably end up converting my controllers to objects too, but it seems like overkill for just sorting a table.

- - - - - - - - - - - - - - - - - - - - -

GENERAL INFORMATION/ASSUMPTIONS

END USER

In this case, the end users are Developers, so some items are geared for that. Examples: Capitalization is left as is to show sorting is case insensitive. Additional test cases are available in the drop-down menu. An error table is created to show bad data and identify problems in a way useful to developers.

DATA - FIRST AND LAST NAMES

Accepts any printable multibyte characters as valid (example: C-3PO and Æ are good).
Capitalization is left as entered to show that sorting is case insensitive.
Row is rejected if the first or last name has more than 100 characters. (Data sent to error table, see below.)

DATA - AGE 

Assume entries are for humans and so there is a limit to possible ages.
Age must be numeric.
Age range:  0 <= age < 130

Decimals allowed. Output will round to two decimal places, removing unnecessary zeroes.
(Explanation: Allows for age to be expressed as part of year, which could be important for young children or for medical data.)

ERROR TABLE

Problem entries are posted to an error table. 
Any rows with missing or blank fields (for first name, last name, or age) are rejected.
Rows with first or last names of more than 100 characters are rejected.
Rows with age that is nonnumeric or < 0 or >= 130 are rejected.

TESTING

Additional test data sources created and accessed in pulldown menu.
Browsers tested: Chrome 65.0.3325.181, Firefox 59.0.2 (64-bit), Safari 11.0.3, Opera 52.0, and Safari for iPhone.
