# Help Desk Part 3

Repository to share the code/database for the Part 3 Deliverable of Wordpress website.
# Setup
- Install either [Docker-CE](https://docs.docker.com/docker-for-windows/install/) or failing that, try [Docker Toolbox](https://docs.docker.com/toolbox/toolbox_install_windows/).
- Install [git](https://git-scm.com/downloads) for your system, by default it comes with a GUI and command line version.


# Installation
- Clone this repository onto your computer where you want to do development using the git client (`git clone https://github.com/Goregius/CMS-Team14` for terminal).
- On Windows:
  - Open Docker terminal and wait for it to install, pay attention to the line that looks similar to below as the IP is 
where you need to go to view the site.

  - ![image](https://i.imgur.com/AZNnZZA.png)

  - `cd` to where you just cloned the repository in the docker shell (you run the site from here), if you're in the right 
place typing `ls` will give you a list of everything that's in this repository.
 - On Mac: 
  - I don't have a Mac to test on, but if you're struggling let Reece know and maybe you can meet up and try to install it 
in person.

# Execution
- In reference to [this issue](https://github.com/Goregius/CMS-Team14/issues/14), a link needs to be created for the wp-config.php file.
  - Open CMD/PowerShell with admin privileges.
  - `cd` to the repository (the same place the docker terminal is).
  - Run either:
    - `mklink /H wp-config.php wp-config-192.php` if your IP is `192.168.99.100`.
    - `mklink /H wp-config.php wp-config-localhost.php` if your IP is `localhost`.
  - This should create a 'fake' wp-config which points to the one you specified, and won't affect anyone else when you make changes to the repository.
  - If this encounters and error, make sure there isn't already a file called wp-config in your folder.
- From the docker terminal run `docker-compose up -d` to start a server on your computer.
- Going to `IP` you should see a wordpress site, and `IP:443` phpMyAdmin.
- Go to phpMyAdmin and log in (wordpress, wordpress).
- If you want your WordPress database to be the same as the real website, go to phpMyAdmin on the real website, export the wordpress table, and import that sql file to your phpMyAdmin.

# Information
 - To create your own account on the real server, log in as (root, root) and add a new user for yourself, or ask another person to make one for you.
- No port forwarding or installation other than the above should be necessary!
- After making a change be sure to to stage the changes (`git add`), commit them locally (`git commit`), and push them to Github (`git push`)! Ideally we would have a different branch for each developer so that changes are not made directly to the main branch without a approved pull request, which also allows things to be merged easier.
- Themes are stored in the database, so if you change a theme it won't change for everyone until you export the database.

## Links
- [Local Server](http://localhost)/[192. Server](http:192.168.99.100)
- [Local phpMyAdmin](http://localhost:443)/[192. phpMyAdmin](http:192.168.99.100:443)
- [Actual Server IP](http://35.189.120.246)/[Actual Server Domain](http://makeitall.ml/)
- [Actual Server phpMyAdmin](http://35.189.120.246:443)

## Git Info
- Through the docker terminal you can run git commands without the GUI.
- If you create a new branch using Github:
  - `git fetch` to get the up to date version of the repository.
  - `git checkout -b <new name of local branch> origin/<name of branch on Github>` to create a new local branch from the remote branch.
  - From here, you can make changes and push them to Github without affecting the main branch. To merge changes with the main website, you need to do a pull request from Github.
  - `git checkout master` takes you back to the original branch.
  - `git rebase master` updates your branch to match `master`'s current state.
