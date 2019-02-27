# Help Desk Part 3

Repository to share the code/database for the Part 3 Deliverable of Wordpress website.
# Setup
- Install either [Docker-CE](https://docs.docker.com/docker-for-windows/install/) or failing that, try [Docker Toolbox](https://docs.docker.com/toolbox/toolbox_install_windows/).
- Install [git](https://git-scm.com/downloads) for your system, by default it comes with a GUI and command line version.


# Installation
- Clone this repository onto your computer where you want to do development using the git client (`git clone https://github.com/Goregius/CMS-Team14` for terminal).
- On Windows: Open Docker terminal and wait for it to install, pay attention to the line that looks similar to below as the IP is where you need to go to view the site.

- ![image](https://i.imgur.com/AZNnZZA.png)

- `cd` to where you just cloned the repository, if you're in the right place typing `ls` will give you a list of everything that's in this repository.
- On Mac: 

# Execution
- From the docker terminal run `docker-compose up -d` to start a server on your computer.
- Going to `IP:8080` you should see a wordpress site, and `IP:8181` phpMyAdmin.
- Go to phpMyAdmin and log in (wordpress, wordpress).
- Import the `wordpress.sql` included to get the same database as current.

# Information
- To create your own account log in as (root, root) and add a new user for yourself, or ask another person to make one for you.
- Changes in `html/` should be reflected on the server from here.
- When making changes to phpMyAdmin database, make sure your local database is up to date by deleting it and importing from the repository's `wordpress.sql`; then make any changes, export it into `wordpress.sql` and re-upload that. This works well enough, but I'm not sure this is the best way for us to manage the database. In reality it might be easier to change the server's database and then download that to everyone's computer.
- No port forwarding or installation other than the above should be necessary!
- After making a change be sure to to stage the changes (`git add`), commit them locally (`git commit`), and push them to Github (`git push`)! Ideally we would have a different branch for each developer so that changes are not made directly to the main branch without a approved pull request, which also allows things to be merged easier.

## Directories
- html/: code served to website.
- database/: database entries and information.

## Links
- [Local Server](http://192.168.99.100:8080)
- [Local phpMyAdmin](http://192.168.99.100:8181)

## Git Info
- Through the docker terminal you can run git commands without the GUI.
- If you create a new branch using Github:
  - `git fetch` to get the up to date version of the repository.
  - `git checkout -b <new name of local branch> origin/<name of branch on Github>` to create a new local branch from the remote branch.
  - From here, you can make changes and push them to Github without affecting the main branch. To merge changes with the main website, you need to do a pull request from Github.
  - `git checkout master` takes you back to the original branch.
  - `git rebase master` updates your branch to match `master`'s current state,
