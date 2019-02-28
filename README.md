# Help Desk Part 3

Repository to share the code/database for the Part 3 Deliverable of Wordpress website.
# Setup
- Install [Docker Toolbox](https://docs.docker.com/toolbox/toolbox_install_windows/).
- Install [git](https://git-scm.com/downloads) for your system, by default it comes with a GUI and command line version.


# Installation
- Clone this repository onto your computer where you want to do development using the git client (`git clone https://github.com/Goregius/CMS-Team14` for terminal).
- On Windows:
  - Open Docker terminal and wait for it to install, pay attention to the line that looks similar to below as the IP is 
where you need to go to view the site.

  - ![image](https://i.imgur.com/AZNnZZA.png)

  - `cd` to where you just cloned the repository in the docker shell (you run the site from here), if you're in the right 
place typing `ls` will give you a list of everything that's in this repository.
  - Open command line as administrator.
  - `cd` to where the repository was cloned
  - If the IP from earlier was `192.168.99.100`:
    - Run the following command `mklink /H html/wp-config.php html/wp-config-192.php`.
  - If the IP from earlier was `localhost`:
    - Run the following command `mklink /H html/wp-config.php html/wp-config-local.php`.
  - This creates a 'symbolic link' for Wordpress to use, so your site is hosted correctly.
- On Mac: 
  - I don't have a Mac to test on, but if you're struggling let Reece know and maybe you can meet up and try to install it 
in person.
  - The same symbolic link will need to be created using the Mac version.

# Execution
- From the docker terminal run `docker-compose up -d` to start a server on your computer (must be in the directory where you have `docker-compose.yml`).
- Going to `IP:8080` you should see a wordpress site, and `IP:8181` phpMyAdmin.
- Go to phpMyAdmin and log in (wordpress, wordpress).
- Import the `wordpress.sql` included to get the same database as current.
- From docker terminal run `docker-compose down` to stop the server when you're not working on it.

# Information
- To create your own account log in as (root, root) and add a new user for yourself, or ask another person to make one for you.
- Changes in `html/` should be reflected on the server from here.
- The best practice for editing the database should be to wait for it to be on the GCP server, and edit it there so multiple people are not running on different databases, changes to code files shouldn't affect the database anyway.
- After making a change be sure to to stage the changes (`git add`), commit them locally (`git commit`), and push them to Github (`git push`)! Ideally we would have a different branch for each developer so that changes are not made directly to the main branch without a approved pull request, which also allows things to be merged easier.
- Themes and logins are stored in the database.

## Directories
- html/: code served to website.
- database/: database entries and information.

## Links
- [Local Server](http://localhost:8080)/[192. Server](http:192.168.99.100:8080)
- [Local phpMyAdmin](http://localhost:8181)/[192. phpMyAdmin](http:192.168.99.100:8181)

## Git Info
- Through the docker terminal you can run git commands without the GUI.
- If you create a new branch using Github:
  - `git fetch` to get the up to date version of the repository.
  - `git checkout -b <new name of local branch> origin/<name of branch on Github>` to create a new local branch from the remote branch.
  - From here, you can make changes and push them to Github without affecting the main branch. To merge changes with the main website, you need to do a pull request from Github.
  - `git checkout master` takes you back to the original branch.
  - `git rebase master` updates your branch to match `master`'s current state,
